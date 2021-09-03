<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Alert;

class IndexController extends Controller
{
    //
    public function index()
    {
        $statuses = Status::all();
        $alerts = Alert::all();
        return view('dashboard', compact('statuses', 'alerts'));
    }

    public function add()
    {
        return view('index.add');
    }

    public function edit(Request $request, $id)
    {
        return view('index.edit');
    }
}
