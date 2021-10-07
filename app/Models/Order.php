<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        }
    }

    public static function updateOrder($request)
    {
        foreach ($request->orders as $order) {

            if (isset($order['arrive_status'])) {
                Order::where('id', $order['id'])->update([
                    'memo' => $order['memo'],
                    'arrive_status' => 1,
                ]);
            } else {
                Order::where('id', $order['id'])->update([
                    'memo' => $order['memo'],
                    'arrive_status' => 0,
                ]);
            }
        }
    }

    public function deleteOrder($request)
    {
        $construction_id = $request->id;
        $construction = Construction::findOrFail($construction_id);

        if ($construction->status == 4) {
            return redirect()->route('dashboard');
        }

        Order::where('id', $request->orderId)->delete();

        // 注文書の到着状況を取得
        list($const_arrive_status, $status) = $this->getArriveStatusOfOrders($construction_id);

        // 工事情報を更新
        Construction::where('id', $construction_id)->update([
            'arrive_status' => $const_arrive_status,
            'status' => $status,
        ]);

        return $construction_id;

    }
}
