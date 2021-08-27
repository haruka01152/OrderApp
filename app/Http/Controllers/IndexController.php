<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    //
    public function index()
    {
        return view('dashboard');
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
