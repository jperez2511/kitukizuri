<?php

namespace Icebearsoft\Kitukizuri\Http\Controllers;

use App\Http\Controllers\Controller;
use Icebearsoft\Kitukizuri\Models\LaravelLogReader;
class LogController extends Controller
{
    public function index()
    {
        $log = new LaravelLogReader;
        $log->get(); 

        return view('kitukizuri::log', [
            'log' => $log
        ]);
    }
}