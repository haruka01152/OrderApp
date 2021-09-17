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
    public function index(Request $request)
    {
        $statuses = Status::all();
        $alerts = Alert::all();
        $all_constructions = count(Construction::where('status', '!=', 4)->get());

        $constructions = Construction::where(function ($query) {
            if ($find = request('find')) {
                $query->where('customer_name', 'LIKE', "%{$find}%")
                    ->orWhere('construction_name', 'LIKE', "%{$find}%");
            }

            switch (request('status')) {
                case null:
                case 1:
                    $query->where('status', 1);
                    break;
                case 2:
                    $query->where('status', 2);
                    break;
                case 3:
                    $query->where('status', 1)->orWhere('status', 2);
                    break;
                case 4:
                    $query->where('status', 4);
                    break;
            }
        })->sortable()->orderBy('contract_date', 'desc')->paginate(15);

        $find_constructions = count($constructions);

        // 何も入力せず検索したら最初のURLにリダイレクト
        if (isset($request['find']) && $request['find'] == '') {
            return redirect()->route('dashboard', ['status' => $request['status']]);
        }

        return view('dashboard', compact('statuses', 'alerts', 'constructions', 'all_constructions', 'find_constructions'));
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
                'arrive_status' => '',
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

        return redirect()->route('dashboard');
    }

    public function edit(Request $request, $id)
    {
        $construction = Construction::findOrFail($id);
        $orders = Order::where('construction_id', $id)->get();
        $alert_configs = Alert_Config::all();

        if ($construction->status != 4) {
            return view('index.edit', compact('construction', 'orders', 'alert_configs'));
        } elseif ($construction->status == 4) {
            return view('index.deleted', compact('construction', 'orders', 'alert_configs'));
        }
    }

    public function update(Request $request, $id)
    {
        // 新しい注文書があれば登録
        if ($request->images[0] != null) {
            foreach ($request->images as $image) {
                Order::create([
                    'construction_id' => $id,
                    'image' => $image,
                    'arrive_status' => 0,
                ]);
            }
        }

        // 既存の注文書があれば情報を更新
        if ($request->orders) {
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
        }

        // 注文書の到着状況を取得
        $all_orders = count(Order::where('construction_id', $id)->get());
        $arrived_orders = count(Order::where('construction_id', $id)->where('arrive_status', 1)->get());

        if ($all_orders > 0) {
            if ($all_orders == $arrived_orders) {
                $const_arrive_status = '✔';
                $status = 2;
            } else {
                $const_arrive_status = $arrived_orders . ' / ' . $all_orders;
                $status = 1;
            }
        } else {
            // 注文書がひとつもない場合
            $const_arrive_status = '';
            $status = 1;
        }

        // 工事情報を更新
        Construction::where('id', $id)->update([
            'contract_date' => $request->contract_date,
            'construction_date' => $request->construction_date,
            'customer_name' => $request->customer_name,
            'construction_name' => $request->construction_name,
            'arrive_status' => $const_arrive_status,
            'alert_config' => $request->alert_config,
            'status' => $status,
        ]);

        return redirect()->route('edit', ['id' => $id])->with('message', '更新しました。');
    }

    public function delete(Request $request, $id)
    {
        $construction = Construction::findOrFail($id);
        $orders = Order::where('construction_id', $id)->get();
        $alert_configs = Alert_Config::all();
        return view('index.delete', compact('construction', 'orders', 'alert_configs'));
    }

    public function destroy(Request $request, $id)
    {
        Construction::where('id', $id)->update([
            'status' => 4,
        ]);
        return redirect()->route('dashboard');
    }

    public function deleteOrder(Request $request)
    {
        $construction = Construction::findOrFail($request->id);

        if ($construction->status == 4) {
            return redirect()->route('dashboard');
        }

        $construction_id = $request->id;
        $order_id = $request->orderId;
        Order::where('id', $order_id)->delete();

        // 注文書の到着状況を取得
        $all_orders = count(Order::where('construction_id', $construction_id)->get());
        $arrived_orders = count(Order::where('construction_id', $construction_id)->where('arrive_status', 1)->get());

        if ($all_orders > 0) {
            if ($all_orders == $arrived_orders) {
                $const_arrive_status = '✔';
                $status = 2;
            } else {
                $const_arrive_status = $arrived_orders . ' / ' . $all_orders;
                $status = 1;
            }
        } else {
            // 注文書がひとつもない場合
            $const_arrive_status = '';
            $status = 1;
        }

        // 工事情報を更新
        Construction::where('id', $construction_id)->update([
            'arrive_status' => $const_arrive_status,
            'status' => $status,
        ]);

        return redirect()->route('edit', ['id' => $construction_id]);
    }
}
