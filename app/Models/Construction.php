<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\DB;
use App\Class\Image;
use Carbon\Carbon;
use App\Models\Alert;

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
        'order_status',
        'remarks',
    ];

    public $sortable = ['contract_date', 'construction_date', 'customer_name', 'construction_name'];

    public function alerts()
    {
        return $this->hasOne('App\Models\Alert');
    }

    public function order_statuses()
    {
        return $this->hasOne('App\Models\OrderStatus', 'id', 'order_status');
    }

    public static function getConstruction($id)
    {
        $construction = Construction::findOrFail($id);
        return $construction;
    }

    public static function getAllConstructions()
    {
        $constructions = Construction::where('status', '!=', 3)->get();
        return $constructions;
    }

    public function findConstructions()
    {
        $nonpage_constructions = Construction::where(function ($query) {

            $status = request('status');
            $order_status = request('order_status');
            $find = request('find');

            $query->where(function ($query) use ($status) {
                if ($status == 'all') {
                    $query->where('status', '=', 1)
                        ->orWhere('status', '=', 2);
                } elseif ($status == null || $status == 1) {
                    $query->where('status', '=', 1);
                } else {
                    $query->where('status', '=', $status);
                }
            });

            $query->where(function ($query) use ($order_status) {
                if ($order_status == 'all' || $order_status == null) {
                    $query->where('order_status', '!=', 0);
                } else {
                    $query->where('order_status', $order_status);
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

    public function createData($request)
    {
        if ($request->notAlert) {
            $alert_config = null;
        } else {
            $alert_config = $request->alert_config;
        }
        $construction = new Construction;
        $form = $request->all();
        unset($form['_token']);
        $construction->fill($form);
        $construction->alert_config = $alert_config;
        $construction->arrive_status = '';
        $construction->save();
    }

    public function createConstruction($request)
    {
        $this->createData($request);
        $id = Construction::latest()->first()->id;

        if (isset($request->images) && $request->images[0] != null) {
            // 注文書登録があった場合は登録して、工事の物品到着状況を書き込む
            $imageAndPath = Image::store($request);
            Order::createOrder($imageAndPath, $id);
            $orders = Order::getOrders($id)->count();
            Construction::where('id', $id)->update(['arrive_status' => '0 / ' . $orders]);
        }
    }

    public function updateConstruction($request, $id)
    {
        // 既存注文書の情報が変わっているかチェック
        // （変わっていれば更新処理）
        $judge = Order::judgeOrderChange($id, $request->orders);
        if ($judge) {
            Order::updateOrder($request);
        }

        // 新しい注文書があれば登録
        if (isset($request->images) && $request->images[0] != null) {
            $imageAndPath = Image::store($request);
            Order::createOrder($imageAndPath, $id);
        }

        // 注文書の到着状況を取得
        list($const_arrive_status, $status) = Order::getArriveStatusOfOrders($id);

        // 案件情報を更新
        $construction = Construction::findOrFail($id);
        $form = $request->all();
        unset($form['_token']);
        $construction->fill($form);
        $construction->arrive_status = $const_arrive_status;
        if ($request->notAlert) {
            $alert_config = null;
        } else {
            $alert_config = $request->alert_config;
        }
        $construction->alert_config = $alert_config;
        $construction->status = $status;

        if ($construction->isDirty() || $judge) {
            Log::createEditLog($id, $request, $construction->getDirty());
            $construction->save();
            Alert::alertForUpdateConstruction($id, $const_arrive_status, $alert_config, $construction);
            return true;
        } else {
            return false;
        }
    }

    public function destroyConstruction($id)
    {
        Construction::where('id', $id)->update([
            'status' => 3,
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
