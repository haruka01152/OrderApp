<?php

namespace App\Class;

use App\Models\Construction;
use App\Models\Alert_Config;
use App\Models\Order;
use App\Models\Alert;
use App\Models\Status;

class Common
{
    public function __construct(Construction $construction, Order $order, Alert $alert, Status $status)
    {
        $this->construction = $construction;
        $this->order = $order;
        $this->alert = $alert;
        $this->status = $status;
    }

    public function getInfoForDashboard()
    {
        $statuses = $this->status->all();
        $alerts = $this->alert->getAlerts();
        $nonpage_constructions = $this->construction->findConstructions();
        $constructions = $nonpage_constructions->paginate(20);
        $find_constructions = $constructions->count();

        return [$statuses, $alerts, $nonpage_constructions, $constructions, $find_constructions];
    }

    public function getInfoForDetail($id)
    {
        $construction = Construction::findOrFail($id);
        $orders = $this->order->getOrders($id);
        $alert_configs = Alert_Config::all();

        return [$construction, $orders, $alert_configs];
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

        if ($construction->status != 4) {
            $view = 'index.edit';
        } elseif ($construction->status == 4) {
            $view = 'index.deleted';
        }

        return $view;
    }
}
