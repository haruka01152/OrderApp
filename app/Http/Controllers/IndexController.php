<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Alert;
use App\Models\Construction;
use App\Models\Order;
use App\Models\Log;
use App\Http\Requests\IndexRequest;
use App\Class\Common;

class IndexController extends Controller
{
    public function __construct(Construction $construction, Order $order, Common $common, Alert $alert, Log $log)
    {
        $this->construction = $construction;
        $this->order = $order;
        $this->alert = $alert;
        $this->log = $log;
        $this->common = $common;
    }

    public function index(Request $request)
    {
        list($statuses, $alerts, $nonpage_constructions, $constructions, $order_statuses) = $this->common->getInfoForDashboard();
        // 何も入力せず検索したらstatusを保って最初のURLにリダイレクト
        if (isset($request['find']) && $request['find'] == '') {
            return redirect()->route('dashboard', ['status' => $request['status'], 'order_status' => $request['order_status']]);
        }
    
        return view('dashboard', compact('statuses', 'alerts', 'constructions', 'order_statuses'));
    }

    public function create(IndexRequest $request)
    {
        $this->construction->createConstruction($request);
        $id = Construction::latest()->first()->id;
        $this->alert->createOneAlert($id);
        $this->log->createAddLog($id, $request);
        return redirect()->route('dashboard')->with('message', '案件を作成しました。');
    }

    public function update(IndexRequest $request, $id)
    {
        if($this->construction->updateConstruction($request, $id) != false){
            // ログ作成はConstruction.phpにて
            return redirect()->route('edit', $id)->with('message', '案件を更新しました。');
        }else{
            return redirect()->route('edit', $id)->with('message', '※項目に変化がありません。');
        }
    }

    public function destroy(Request $request, $id)
    {
        $this->construction->destroyConstruction($id);
        $this->alert->deleteAllAlert($id);
        $this->log->createDeleteLog($id, $request);
        return redirect()->route('dashboard')->with('message', '案件を削除しました。');
    }

    public function restore($id)
    {
        $this->construction->restoreConstruction($id);
        $this->alert->createOneAlert($id);
        $this->log->createRestoreLog($id);
        return redirect()->route('edit', $id)->with('message', '案件を復元しました。');
    }
}
