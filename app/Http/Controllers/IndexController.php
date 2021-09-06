<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Alert;
use App\Models\Construction;

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
        return view('index.add');
    }

    public function edit(Request $request, $id)
    {
        return view('index.edit');
    }
}
