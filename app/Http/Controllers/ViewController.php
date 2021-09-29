<?php

// 主にビューの表示のみの処理をここに書く

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alert_Config;
use App\Models\Construction;
use App\Models\Order;
use App\Models\Alert;
use App\Models\Log;
use App\Class\Common;

class ViewController extends Controller
{
    //
    public function __construct(Construction $construction, Order $order, Common $common, Alert $alert, Alert_Config $alert_config, Log $log)
    {
        $this->construction = $construction;
        $this->order = $order;
        $this->alert = $alert;
        $this->alert_config = $alert_config;
        $this->log = $log;
        $this->common = $common;
    }

    public function add()
    {
        $alert_configs = $this->alert_config->all();
        return view('index.add', compact('alert_configs'));
    }

    public function edit(Request $request, $id)
    {
        list($construction, $orders, $alert_configs, $logs) = $this->common->getInfoForDetail($id);
        list($previousUrl, $find) = $this->common->getFindWord($request);
        $view = $this->common->selectBladeForEdit($id);
        return view($view, compact('construction', 'orders', 'alert_configs', 'find', 'previousUrl', 'logs'));
    }

    public function delete(Request $request, $id)
    {
        list($construction, $orders, $alert_configs) = $this->common->getInfoForDetail($id); 
        return view('index.delete', compact('construction', 'orders', 'alert_configs'));
    }

}
