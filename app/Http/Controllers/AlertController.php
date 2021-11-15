<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alert;

class AlertController extends Controller
{
    //
    public function create()
    {
        Alert::createAlerts();
        return redirect()->route('dashboard')->with('message', 'アラートを作成');
    }

    public function delete()
    {
        Alert::destroyAlerts();
        return redirect()->route('dashboard')->with('message', 'アラートを削除');
    }

}
