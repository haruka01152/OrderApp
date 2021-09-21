<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Alert;
use App\Models\Alert_Config;
use App\Models\Construction;
use App\Models\Order;
use Carbon\Carbon;
use \Yasumi\Yasumi;

class AlertController extends Controller
{
    //
    public function alerts(){
        $alerts = Alert::paginate(50);
        return view('index.alert', compact('alerts'));
    }

    public function createAlert()
    {
        $construction = Construction::orderBy('construction_date', 'desc')->take(1)->get();
        dd($construction);

        $today = date('m/d/Y');
        $holidays = Yasumi::create('Japan', date('Y'), 'ja_JP');

        // foreach($constructions as $construction){
            $construction_date = $construction->construction_date;
            $holidayInBetweenDays = $holidays->between(
                \DateTime::createFromFormat('m/d/Y', $today),
                \DateTime::createFromFormat('m/d/Y', $construction_date)
            );
            dd($holidayInBetweenDays);

            // Alert::create([
            //     'construction_id' => $construction->id,
            // ]);
        // }
        
        return redirect()->route('dashboard');
    }
}
