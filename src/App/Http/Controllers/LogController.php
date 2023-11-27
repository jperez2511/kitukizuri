<?php

namespace Icebearsoft\Kitukizuri\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Icebearsoft\Kitukizuri\App\Models\LaravelLogReader;
class LogController extends Controller
{
    public function index()
    {
        $log = new LaravelLogReader;
        $log->get(); 

        return view('kitukizuri::log', [
            'logs'   => $log,
            'titulo' => 'Logs',
            'kmenu'  => true,
            'layout' => 'krud::layout'
        ]);
    }
}