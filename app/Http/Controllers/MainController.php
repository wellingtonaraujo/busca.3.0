<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class MainController extends Controller
{
    //meto do index
    public function index()
    {
        return view('main');
    }
}
