<?php

namespace App\Class;

use App\Models\Construction;
use App\Models\Order;
use App\Models\Alert;
use App\Models\Status;
use App\Models\Log;
use App\Models\OrderStatus;

class Common
{
    public function __construct(Construction $construction, Order $order, Alert $alert, Status $status, Log $log)
    {
        $this->construction = $construction;
        $this->order = $order;
        $this->alert = $alert;
        $this->log = $log;
        $this->status = $status;
    }

    public function getInfoForDashboard()
    {
        $statuses = $this->status->where('id', '>', 1)->get();
        $alerts = $this->alert->getAllAlerts(5);
        $nonpage_constructions = $this->construction->findConstructions();
        $constructions = $nonpage_constructions->paginate(20);
        $order_statuses = OrderStatus::all();

        return [$statuses, $alerts, $nonpage_constructions, $constructions, $order_statuses];
    }

    public function getInfoForDetail($id)
    {
        $construction = Construction::findOrFail($id);
        $orders = $this->order->getOrders($id);
        $logs = $this->log->getLogs($id);
        $order_statuses = OrderStatus::all();

        return [$construction, $orders, $logs, $order_statuses];
    }

    public function getFindWord($request)
    {
        // 検索を介していたら検索ワードを取得
        if ($request->previousUrl) {
            $previousUrl = $request->previousUrl;
        } elseif (strpos(url()->previous(), 'find')) {
            $previousUrl = url()->previous();
        }else{
            $previousUrl = 0;
        }

        $find  = urldecode(str_replace('find=', '', strstr($previousUrl, 'find=')));

        return [$previousUrl, $find];
    }

    public function selectBladeForEdit($id)
    {
        $construction = $this->construction->findOrFail($id);

        if ($construction->status != 3) {
            $view = 'index.edit';
        } elseif ($construction->status == 3) {
            $view = 'index.deleted';
        }

        return $view;
    }

}
