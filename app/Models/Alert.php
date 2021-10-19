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
    ];

    public function constructions()
    {
        return $this->belongsTo('App\Models\Construction', 'construction_id', 'id');
    }

    public static function getAlerts($paginate_number)
    {
        $alerts = Alert::select('alerts.*')
                ->join('constructions', 'alerts.construction_id', '=', 'constructions.id')
                ->orderBy('constructions.construction_date', 'asc')
                ->paginate($paginate_number);
        return $alerts;
    }

    public static function createData($construction_id)
    {
        $construction = Construction::getConstruction($construction_id);
        Alert::create([
            'construction_id' => $construction_id,
        ]);
    }

    // すべての工事のアラートを一斉に作成
    public static function createAlerts()
    {
        $constructions = Construction::getAllConstructions();

        foreach ($constructions as $construction) {
            $today =new Carbon();
            $construction_date =new Carbon($construction->construction_date);
            if ($construction->construction_date != null && $today->modify("+{$construction->alert_config} days") >= $construction_date) {
                $alert = Alert::where('construction_id', $construction->id)->first();
                $status = Construction::where('id', $construction->id)->first()->arrive_status;
                if ($alert == null && $status != '✔') {
                    self::createData($construction->id);
                }
            }
        }
    }

    // アラートを案件ごとに1つずつ作成
    public static function createOneAlert($id)
    {
        $construction = Construction::getConstruction($id);
        $today =new Carbon();
        $construction_date =new Carbon($construction->construction_date);

        if ($construction->construction_date != null && $today->modify("+{$construction->alert_config} days") >= $construction_date) {
            $alert = Alert::where('construction_id', $construction->id)->first();
            $status = Construction::where('id', $construction->id)->first()->arrive_status;
            if ($alert == null && $status != '✔') {
                self::createData($construction->id);
            }
        }
    }

    public static function deleteAlert($id)
    {
        Alert::where('construction_id', $id)->delete();
    }
}
