<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Alert;
use App\Models\Construction;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use App\Notifications\InvoicePaid;

class AlertController extends Controller
{
    //
    public function alerts(Request $request){
        $class = $request->class;
        $alerts = Alert::getAlerts(50,$class);
        return view('index.alert', compact('alerts'));
    }

    public function create()
    {
        Alert::createAlerts();
        return redirect()->route('dashboard')->with('message', 'アラートを作成');
    }

    public function delete()
    {
        Alert::destroyAlerts();
        return redirect()->route('dashboard')->with('message', 'アラートを削除');
    }

}
