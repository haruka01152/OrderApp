<?php

// 主にビューの表示のみの処理をここに書く

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alert_Config;
use App\Models\Construction;
use App\Models\Order;


class ViewController extends Controller
{
    //
    public function add()
    {
        $alert_configs = Alert_Config::all();
        return view('index.add', compact('alert_configs'));
    }

    public function edit(Request $request, $id)
    {
        $construction = Construction::findOrFail($id);
        $orders = Order::where('construction_id', $id)->get();
        $alert_configs = Alert_Config::all();

        // 検索を介していたら検索ワードを取得
        if(strpos(url()->previous(), 'find')){
            $find = urldecode(str_replace('find=', '', strstr(url()->previous(), 'find=')));
            $previousUrl = url()->previous();
        }else{
            $previousUrl = 0;
            $find = 0;
        }

        if ($construction->status != 4) {
            $view = 'index.edit';
        } elseif ($construction->status == 4) {
            $view = 'index.deleted';
        }

        return view($view, compact('construction', 'orders', 'alert_configs', 'find', 'previousUrl'));
    }

    public function delete(Request $request, $id)
    {
        $construction = Construction::findOrFail($id);
        $orders = Order::where('construction_id', $id)->get();
        $alert_configs = Alert_Config::all();
        return view('index.delete', compact('construction', 'orders', 'alert_configs'));
    }

}
