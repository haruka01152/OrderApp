<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Alert;
use App\Models\Alert_Config;
use App\Models\Construction;
use App\Models\Order;
use App\Http\Requests\IndexRequest;
use App\Class\Common;

class IndexController extends Controller
{
    public function __construct(Construction $construction, Order $order, Common $common, Alert $alert)
    {
        $this->construction = $construction;
        $this->order = $order;
        $this->alert = $alert;
        $this->common = $common;
    }

    public function index(Request $request)
    {
        list($statuses, $alerts, $nonpage_constructions, $constructions, $find_constructions) = $this->common->getInfoForDashboard();

        $this->construction->countHits();

        // 何も入力せず検索したらstatusを保って最初のURLにリダイレクト
        if (isset($request['find']) && $request['find'] == '') {
            return redirect()->route('dashboard', ['status' => $request['status']]);
        }

        return view('dashboard', compact('statuses', 'alerts', 'constructions', 'find_constructions'));
    }

    public function create(IndexRequest $request)
    {
        $this->construction->createConstruction($request);
        return redirect()->route('dashboard')->with('message', '案件を作成しました。');
    }

    public function update(IndexRequest $request, $id)
    {
        $this->construction->updateConstruction($request, $id);
        list($construction, $orders, $alert_configs) = $this->common->getInfoForDetail($id);

        list($previousUrl, $find) = $this->common->getFindWord($request);

        $message = '案件を更新しました。';

        return view('index.edit', compact('construction', 'orders', 'alert_configs', 'previousUrl', 'find', 'message'));
    }

    public function destroy(Request $request, $id)
    {
        $this->construction->destroyConstruction($id);
        return redirect()->route('dashboard')->with('message', '案件を削除しました。');
    }

    public function restore(Request $request, $id)
    {
        $this->construction->restoreConstruction($id);
        return redirect()->route('edit', ['id' => $id])->with('message', '案件を復元しました。');
    }
}
