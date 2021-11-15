<?php

// 主にビューの表示のみの処理をここに書く

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Construction;
use App\Models\Order;
use App\Models\Alert;
use App\Models\Log;
use App\Class\Common;
use App\Class\Calender;
use App\Models\OrderStatus;

class ViewController extends Controller
{
    //
    public function __construct(Construction $construction, Order $order, Common $common, Alert $alert, Log $log, Calender $calender)
    {
        $this->construction = $construction;
        $this->order = $order;
        $this->alert = $alert;
        $this->log = $log;
        $this->common = $common;
        $this->calender = $calender;
    }

    public function add()
    {
        $order_statuses = OrderStatus::all();
        return view('index.add', compact('order_statuses'));
    }

    public function edit(Request $request, $id)
    {
        list($construction, $orders, $logs, $order_statuses) = $this->common->getInfoForDetail($id);
        $view = $this->common->selectBladeForEdit($id);
        return view($view, compact('construction', 'orders', 'logs', 'order_statuses'));
    }

    public function delete(Request $request, $id)
    {
        list($construction, $orders, $logs, $order_statuses) = $this->common->getInfoForDetail($id); 
        return view('index.delete', compact('construction', 'orders', 'logs', 'order_statuses'));
    }

    public function calender(Request $request)
    {
        list($dates, $year, $month, $previousYear, $previousMonth, $nextYear, $nextMonth) = $this->calender->getCalendarDates($request);
        $constructions = $this->construction->getAllConstructions();
        return view('index.calender', compact('dates', 'year', 'month', 'previousYear', 'previousMonth', 'nextYear', 'nextMonth', 'constructions'));
    }

    public function alerts(Request $request){
        $class = $request->class;
        $alerts = Alert::getAlerts(50,$class);
        return view('index.alert', compact('alerts'));
    }

    public function logs()
    {
        $logs = Log::paginate(50);
        return view('index.logs', compact('logs'));
    }
}
