<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Alert;
use App\Models\Alert_Config;
use App\Models\Construction;
use App\Models\Order;

class IndexController extends Controller
{
    //
    public function index()
    {
        $statuses = Status::all();
        $alerts = Alert::all();
        $constructions = Construction::sortable()->paginate(15);
        return view('dashboard', compact('statuses', 'alerts', 'constructions'));
    }

    public function add()
    {
        $alert_configs = Alert_Config::all();
        return view('index.add', compact('alert_configs'));
    }

    public function create(Request $request)
    {
        if ($request->image) {
            Construction::create([
                'contract_date' => $request->contract_date,
                'construction_date' => $request->construction_date,
                'customer_name' => $request->customer_name,
                'construction_name' => $request->construction_name,
                'arrive_status' => '未',
                'alert_config' => $request->alert_config,
            ]);

            // 今作成した工事データを取り出す
            $construction = Construction::latest()->first();

            foreach ($request->image as $image) {
                Order::create([
                    'construction_id' => $construction->id,
                    'image' => $image,
                    'arrive_status' => 0,
                ]);
            }
        } else {
            Construction::create([
                'contract_date' => $request->contract_date,
                'construction_date' => $request->construction_date,
                'customer_name' => $request->customer_name,
                'construction_name' => $request->construction_name,
                'arrive_status' => '',
                'alert_config' => $request->alert_config,
            ]);
        }

        return redirect()->action('IndexController@index');
    }

    public function edit(Request $request, $id)
    {
        $construction = Construction::findOrFail($id);
        $orders = Order::where('construction_id', $id)->get();
        $alert_configs = Alert_Config::all();

        return view('index.edit', compact('construction', 'orders', 'alert_configs'));
    }
}
