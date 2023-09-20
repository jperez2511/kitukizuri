<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('app.index');
    }

    public function setLanguage($lang, Request $request)
    {
        Session::put('lang', $lang);
        return redirect()->back();
    }
}
