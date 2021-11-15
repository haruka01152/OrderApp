<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'construction_id',
        'class',
    ];

    public function constructions()
    {
        return $this->belongsTo('App\Models\Construction', 'construction_id', 'id');
    }

    public static function getAllAlerts($paginate_number)
    {
        $alerts = Alert::select('alerts.*')
            ->join('constructions', 'alerts.construction_id', '=', 'constructions.id')
            ->orderBy('constructions.construction_date', 'asc')
            ->paginate($paginate_number);
        return $alerts;
    }

    public static function getAlerts($paginate_number, $class)
    {
        if ($class != null && $class != 'all') {
            $alerts = Alert::select('alerts.*')
                ->join('constructions', 'alerts.construction_id', '=', 'constructions.id')
                ->where('class', $class)
                ->orderBy('constructions.construction_date', 'asc')
                ->paginate($paginate_number);
        } elseif ($class == null || $class == 'all') {
            $alerts = Alert::select('alerts.*')
                ->join('constructions', 'alerts.construction_id', '=', 'constructions.id')
                ->orderBy('constructions.construction_date', 'asc')
                ->paginate($paginate_number);
        }
        return $alerts;
    }

    // 物品未到着アラートを作成
    public static function createData($construction_id)
    {
        $construction = Construction::getConstruction($construction_id);
        Alert::create([
            'construction_id' => $construction_id,
            'class' => 1,
        ]);
    }

    // 指示依頼中アラートを作成
    public static function createData2($construction_id)
    {
        $construction = Construction::getConstruction($construction_id);
        Alert::create([
            'construction_id' => $construction_id,
            'class' => 2,
        ]);
    }

    // 発注指示アラートを作成
    public static function createData3($construction_id)
    {
        $construction = Construction::getConstruction($construction_id);
        Alert::create([
            'construction_id' => $construction_id,
            'class' => 3,
        ]);
    }

    // すべての工事のアラートを一斉に作成（タスクスケジューラ用）
    public static function createAlerts()
    {
        $constructions = Construction::getAllConstructions();

        foreach ($constructions as $construction) {
            $today = new Carbon('today');
            if (($construction->construction_date != null && $construction->status == 1) && ($construction->alert_config != null && $construction->alert_config <= $today)) {
                $alert1 = Alert::where('construction_id', $construction->id)->where('class', 1)->first();
                $status = Construction::where('id', $construction->id)->first()->arrive_status;
                if ($alert1 == null && $status != '✔') {
                    self::createData($construction->id);
                }
            }

            $alert2 = Alert::where('construction_id', $construction->id)->where('class', 2)->first();
            if ($alert2 == null && $construction->order_status == 2) {
                self::createData2($construction->id);
            }

            $alert3 = Alert::where('construction_id', $construction->id)->where('class', 3)->first();
            if ($alert3 == null && $construction->order_status == 3) {
                self::createData3($construction->id);
            }
        }
    }

    // アラートを案件ごとに1つずつ作成
    public static function createOneAlert($id)
    {
        $construction = Construction::getConstruction($id);
        $today = new Carbon('today');
        if (($construction->construction_date != null && $construction->status == 1) && ($construction->alert_config != null && $construction->alert_config <= $today)) {
            $alert = Alert::where('construction_id', $construction->id)->where('class', 1)->first();
            $status = Construction::where('id', $construction->id)->first()->arrive_status;
            if ($alert == null && $status != '✔') {
                self::createData($construction->id);
            }
        }
        $alert2 = Alert::where('construction_id', $construction->id)->where('class', 2)->first();
        if ($alert2 == null && $construction->order_status == 2) {
            self::createData2($construction->id);
        }

        $alert3 = Alert::where('construction_id', $construction->id)->where('class', 3)->first();
        if ($alert3 == null && $construction->order_status == 3) {
            self::createData3($construction->id);
        }
    }

    public static function alertForUpdateConstruction($id, $const_arrive_status, $alert_config, $construction)
    {
        Alert::createOneAlert($id);
        $today = new Carbon('today');
        if (($const_arrive_status == '✔' || $alert_config == null) || $today <= $alert_config) {
            Alert::deleteAlert($id, 1);
        }
        $alert = Alert::where('construction_id', $construction->id)->where('class', '!=', 1)->first();
        if ($alert != null && $construction->order_status != $alert->class) {
            Alert::deleteAlert($construction->id, $alert->class);
        }
    }

    public static function deleteAllAlert($id)
    {
        Alert::where('construction_id', $id)->delete();
    }

    public static function deleteAlert($id, $class_number)
    {
        Alert::where('construction_id', $id)->where('class', $class_number)->delete();
    }

    // 削除されるべきなのに残ってしまった物品到着用アラートを一括削除（開発用、通常使わない）
    public static function destroyAlerts()
    {
        $constructions = Construction::getAllConstructions();
        foreach ($constructions as $construction) {
            $status = $construction->status;

            if ($status == 2 || $status == 3) {
                self::deleteAlert($construction->id, 1);
            }
        }
    }
}
