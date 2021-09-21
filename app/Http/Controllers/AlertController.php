<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Alert;
use App\Models\Alert_Config;
use App\Models\Construction;
use App\Models\Order;
use Carbon\Carbon;

class AlertController extends Controller
{
    //
    public function alerts(){
        $alerts = Alert::paginate(50);
        return view('index.alert', compact('alerts'));
    }

    public function createAlert()
    {
        $today = new Carbon('today');

        $constructions = Construction::where('id', '!=', 'delete')->get();

        foreach($constructions as $construction){
            Alert::create([
                'construction_id' => $construction->id,
            ]);
        }
        
        return redirect()->route('dashboard');
    }
}
