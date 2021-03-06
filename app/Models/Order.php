<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Class\Image;


class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'construction_id',
        'image',
        'memo',
        'arrive_status',
        'path',
    ];

    public static function getOrders($id)
    {
        $orders = Order::where('construction_id', $id)->get();
        return $orders;
    }

    public static function getArriveStatusOfOrders($id)
    {
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

        return [$const_arrive_status, $status];
    }

    public static function createOrder($imageAndPath, $id)
    {
        foreach ($imageAndPath as $image => $path) {
            Order::create([
                'construction_id' => $id,
                'image' => $image,
                'arrive_status' => 0,
                'path' => $path,
            ]);

            $newOrders[] = Order::where('path', $path)->value('id');
        }

        return $newOrders;
    }

    public static function updateOrder($request)
    {
        $orders = Order::getOrders($request->construction_id);
        foreach ($orders as $order) {

            if (isset($request->orders['order' . $order->id . '_arrive_status'])) {
                $order->update([
                    'memo' => $request->orders['order' . $order->id . '_memo'],
                    'arrive_status' => 1,
                ]);
            } else {
                $order->update([
                    'memo' => $request->orders['order' . $order->id . '_memo'],
                    'arrive_status' => 0,
                ]);
            }
        }
    }

    public function deleteOrder($request)
    {
        $construction_id = $request->id;
        $construction = Construction::findOrFail($construction_id);

        if ($construction->status == 3) {
            return redirect()->route('dashboard');
        }

        Image::delete($request, $request->orderId);
        Order::where('id', $request->orderId)->delete();

        // 注文書の到着状況を取得
        list($const_arrive_status, $status) = $this->getArriveStatusOfOrders($construction_id);

        // 案件情報を更新
        Construction::where('id', $construction_id)->update([
            'arrive_status' => $const_arrive_status,
            'status' => $status,
        ]);
    }

    public static function judgeOrderChange($construction_id, $request_orders)
    {
        // 既存注文書の情報が変わっているかどうかのチェック
        $order_list = Order::getOrders($construction_id);

        if (count($order_list) > 0) {
            foreach ($order_list as $order) {
                if ($request_orders['order' . $order->id . '_memo'] != $order->memo) {
                    $changeOrder[$order->id] = 1;
                } elseif ((isset($request_orders['order' . $order->id . '_arrive_status']) && $order->arrive_status == 0) || (!isset($request_orders['order' . $order->id . '_arrive_status']) && $order->arrive_status == 1)) {
                    $changeOrder[$order->id] = 1;
                } else {
                    $changeOrder[$order->id] = 0;
                }
            }

            if (in_array(1, $changeOrder, true)) {
                return $changeOrder;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }
}
