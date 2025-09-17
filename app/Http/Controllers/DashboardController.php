<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //meto do index
    public function index()
    {
        return view('dashboard');
    }
}
