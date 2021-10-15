<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Alert;
use App\Models\Alert_Config;
use App\Models\Construction;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use App\Notifications\InvoicePaid;

class AlertController extends Controller
{
    //
    public function alerts(){
        $alerts = Alert::paginate(50);
        return view('index.alert', compact('alerts'));
    }

    public function create()
    {
        Alert::createAlert();
        return redirect()->route('dashboard')->with('message', 'アラートを作成');
    }
}
