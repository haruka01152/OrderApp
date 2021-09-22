<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Alert;
use App\Models\Alert_Config;
use App\Models\Construction;
use App\Models\Order;
use App\Http\Requests\IndexRequest;

class IndexController extends Controller
{
    //
    public function index(Request $request)
    {
        $statuses = Status::all();
        $alerts = Alert::orderBy('id', 'asc')->paginate(5);

        $nonpage_constructions = Construction::where(function ($query) {

            $status = request('status');
            $find = request('find');

            $query->where(function ($query) use ($status) {
                if ($status == 3) {
                    $query->where('status', '=', 1)
                        ->orWhere('status', '=', 2);
                } elseif (!$status) {
                    $query->where('status', '=', 1);
                } else {
                    $query->where('status', '=', $status);
                }
            });

            $query->where(function ($query) use ($find) {
                if (strpos($find, ' ') || strpos($find, '　')) {
                    $find = mb_convert_kana($find, 's');
                    $find = preg_split('/[\s]+/', $find, -1, PREG_SPLIT_NO_EMPTY);
                }

                if (is_array($find)) {
                    foreach ($find as $f) {
                        $query->where('customer_name', 'LIKE', "%{$f}%")
                            ->orWhere('construction_name', 'LIKE', "%{$f}%");
                    }
                } else {
                    $query->where('customer_name', 'LIKE', "%{$find}%")
                        ->orWhere('construction_name', 'LIKE', "%{$find}%");
                }
            });
        })->sortable()->orderBy('created_at', 'desc');

        $constructions = $nonpage_constructions->paginate(20);
        $find_constructions = count($constructions);

        // 検索でヒットした件数を割り出す
        if (!request('page')) {
            $all_constructions = $nonpage_constructions->count();
            session(['all_constructions' => $all_constructions]);
        }

        // 何も入力せず検索したら最初のURLにリダイレクト
        if (isset($request['find']) && $request['find'] == '') {
            return redirect()->route('dashboard', ['status' => $request['status']]);
        }

        return view('dashboard', compact('statuses', 'alerts', 'constructions', 'find_constructions'));
    }

    public function create(IndexRequest $request)
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

    public function update(IndexRequest $request, $id)
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
        $all_orders = Order::where('construction_id', $id)->get()->count();
        $arrived_orders = Order::where('construction_id', $id)->where('arrive_status', 1)->get()->count();

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

        $previousUrl = request('previousUrl');
        $find  = urldecode(str_replace('find=', '', strstr($previousUrl, 'find=')));

        return view('index.edit', compact('previousUrl', 'find'))->with('message', '案件を更新しました。');
    }

    public function destroy(Request $request, $id)
    {
        Construction::where('id', $id)->update([
            'status' => 4,
        ]);
        return redirect()->route('dashboard')->with('message', '案件を削除しました。');
    }

    public function restore(Request $request, $id)
    {
        $construction = Construction::findOrFail($id);

        if ($construction->arrive_status == '✔') {
            Construction::where('id', $id)->update([
                'status' => 2,
            ]);
        } else {
            Construction::where('id', $id)->update([
                'status' => 1,
            ]);
        }

        return redirect()->route('edit', ['id' => $id])->with('message', '案件を復元しました。');
    }
}
