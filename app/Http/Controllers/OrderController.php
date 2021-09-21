<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Construction;
use App\Models\Order;


class OrderController extends Controller
{
    //
    public function delete(Request $request)
    {
        $construction = Construction::findOrFail($request->id);

        if ($construction->status == 4) {
            return redirect()->route('dashboard');
        }

        $construction_id = $request->id;
        $order_id = $request->orderId;
        Order::where('id', $order_id)->delete();

        // 注文書の到着状況を取得
        $all_orders = Order::where('construction_id', $construction_id)->get()->count();
        $arrived_orders = Order::where('construction_id', $construction_id)->where('arrive_status', 1)->get()->count();

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
        Construction::where('id', $construction_id)->update([
            'arrive_status' => $const_arrive_status,
            'status' => $status,
        ]);

        return redirect()->route('edit', ['id' => $construction_id])->with('message', '注文書を削除しました。');
    }
}
