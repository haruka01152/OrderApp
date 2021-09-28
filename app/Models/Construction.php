<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class Construction extends Model
{
    use HasFactory;
    use Sortable;

    protected $fillable = [
        'contract_date',
        'construction_date',
        'customer_name',
        'construction_name',
        'arrive_status',
        'alert_config',
    ];

    public $sortable = ['contract_date', 'construction_date', 'customer_name', 'construction_name'];

    public function alerts()
    {
        return $this->hasMany('App\Models\Alert');
    }

    public function findConstructions()
    {
        $nonpage_constructions = Construction::where(function ($query) {

            $status = request('status');
            $find = request('find');

            $query->where(function ($query) use ($status) {
                if ($status == 3) {
                    $query->where('status', '=', 1)
                        ->orWhere('status', '=', 2);
                } elseif (!$status) {
                    $query->where('status', '=', 1);
                } else {
                    $query->where('status', '=', $status);
                }
            });

            $query->where(function ($query) use ($find) {
                if (strpos($find, ' ') || strpos($find, '　')) {
                    $find = mb_convert_kana($find, 's');
                    $find = preg_split('/[\s]+/', $find, -1, PREG_SPLIT_NO_EMPTY);
                }

                if (is_array($find)) {
                    // スペース区切りで複数キーワード検索
                    foreach ($find as $f) {
                        $query->where(DB::raw('CONCAT(customer_name, construction_name)'), 'LIKE', "%{$f}%");
                    }
                } else {
                    $query->where('customer_name', 'LIKE', "%{$find}%")
                        ->orWhere('construction_name', 'LIKE', "%{$find}%");
                }
            });
        })->sortable()->orderBy('created_at', 'desc');

        return $nonpage_constructions;
    }

    public function countHits()
    {
        // 検索でヒットした件数を割り出す
        $nonpage_constructions = $this->findConstructions();

        $all_constructions = $nonpage_constructions->count();
        session(['all_constructions' => $all_constructions]);

    }

    public function createConstruction($request)
    {
        if ($request->images[0] != null) {

            Construction::create([
                'contract_date' => $request->contract_date,
                'construction_date' => $request->construction_date,
                'customer_name' => $request->customer_name,
                'construction_name' => $request->construction_name,
                'arrive_status' => '',
                'alert_config' => $request->alert_config,
            ]);

            // 今作成した工事データを取り出す
            $id = Construction::latest()->first()->id;

            Order::createOrder($request, $id);

        } else {
            Construction::create([
                'contract_date' => $request->contract_date,
                'construction_date' => $request->construction_date,
                'customer_name' => $request->customer_name,
                'construction_name' => $request->construction_name,
                'arrive_status' => '',
                'alert_config' => $request->alert_config,
            ]);
        }
    }

    public function updateConstruction($request, $id)
    {
        // 新しい注文書があれば登録
        if ($request->images[0] != null) {
            Order::createOrder($request, $id);
        }

        // 既存の注文書があれば情報を更新
        if ($request->orders) {
            Order::updateOrder($request);
        }

        // 注文書の到着状況を取得
        $all_orders = Order::where('construction_id', $id)->get()->count();
        $arrived_orders = Order::where('construction_id', $id)->where('arrive_status', 1)->get()->count();

        if ($all_orders > 0) {
            if ($all_orders == $arrived_orders) {
                $const_arrive_status = '✔';
                $status = 2;
            } else {
                $const_arrive_status = $arrived_orders . ' / ' . $all_orders;
                $status = 1;
            }
        } else {
            // 注文書がひとつもない場合
            $const_arrive_status = '';
            $status = 1;
        }

        // 工事情報を更新
        Construction::where('id', $id)->update([
            'contract_date' => $request->contract_date,
            'construction_date' => $request->construction_date,
            'customer_name' => $request->customer_name,
            'construction_name' => $request->construction_name,
            'arrive_status' => $const_arrive_status,
            'alert_config' => $request->alert_config,
            'status' => $status,
        ]);
    }

    public function destroyConstruction($id)
    {
        Construction::where('id', $id)->update([
            'status' => 4,
        ]);
    }

    public function restoreConstruction($id)
    {
        $construction = Construction::findOrFail($id);

        if ($construction->arrive_status == '✔') {
            Construction::where('id', $id)->update([
                'status' => 2,
            ]);
        } else {
            Construction::where('id', $id)->update([
                'status' => 1,
            ]);
        }
    }
}
