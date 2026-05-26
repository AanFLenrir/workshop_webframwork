<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PapanController extends Controller
{
    public function index()
    {
        return view('papan.index');
    }
}
