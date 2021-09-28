<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Construction;
use App\Models\Order;


class OrderController extends Controller
{
    //
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function deleteOrder(Request $request)
    {
        $construction_id = $this->order->deleteOrder($request);
        return redirect()->route('edit', ['id' => $construction_id])->with('message', '注文書を削除しました。');
    }
}
