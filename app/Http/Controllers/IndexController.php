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
        if ($request->image[0] != null) {

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

    public function update(Request $request, $id)
    {
        // 登録済みの注文書情報の更新
        foreach ($request->orders as $order) {
            if (isset($order['arrive_status'])) {
                Order::where('id', $order['id'])->update([
                    'memo' => $order['memo'],
                    'arrive_status' => 1,
                ]);
            } else {
                Order::where('id', $order['id'])->update([
                    'memo' => $order['memo'],
                    'arrive_status' => 0,
                ]);
            }
        }

        // 新しい注文書があれば登録
        if ($request->image[0] != null) {

            // 工事データを取り出す
            $construction = Construction::findOrFail($id);

            foreach ($request->image as $image) {
                Order::create([
                    'construction_id' => $construction->id,
                    'image' => $image,
                    'arrive_status' => 0,
                ]);
            }
        }

        // 所属する注文書の到着状況に合わせ、工事自体の物品到着状況を更新
        $arrive_status = Order::where('construction_id', $id)->pluck('arrive_status')->toArray();

        // 未着物品の有無確認
        if (count($arrive_status) > 0 && in_array(0, $arrive_status, true)) 
        {
            // ひとつも到着していない
            if (!in_array(1, $arrive_status, true)) {
                Construction::where('id', $id)->update([
                    'arrive_status' => '未',
                ]);
            }else{
                // 到着のもの・未着のもの両方存在している
                $all_orders = count($arrive_status);
                $arrive_orders = count(Order::where(['construction_id', $id],['arrive_status', 1])->get());
                dd($arrive_orders);
            }

        } else {
            Construction::where('id', $id)->update([
                'arrive_status' => '✔',
            ]);
        }

        Construction::where('id', $id)->update([
            'contract_date' => $request->contract_date,
            'construction_date' => $request->construction_date,
            'customer_name' => $request->customer_name,
            'construction_name' => $request->construction_name,
            'alert_config' => $request->alert_config,
        ]);

        return redirect()->action('IndexController@index');
    }
}
