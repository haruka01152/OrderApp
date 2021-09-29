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
        $construction_id = $this->order->deleteOrder($request);
        $this->log->createLog(Auth::user()->name, $construction_id, url()->current());
        return redirect()->route('edit', ['id' => $construction_id])->with('message', '注文書を削除しました。');
    }
}
