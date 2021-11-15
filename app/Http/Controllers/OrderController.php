<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Construction;
use App\Models\Order;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    //
    public function __construct(Order $order, Log $log)
    {
        $this->order = $order;
        $this->log = $log;
    }

    public function deleteOrder(Request $request)
    {
        $construction_id = $request->id;
        $this->log->createdeleteOrderLog($construction_id, $request->orderId);
        $this->order->deleteOrder($request);
        return redirect()->route('edit', ['id' => $construction_id])->with('message', '注文書を削除しました。');
    }
}
