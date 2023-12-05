<?php

namespace Icebearsoft\Kitukizuri\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Icebearsoft\Kitukizuri\App\Models\LaravelLogReader;
class LogController extends Controller
{
    public function index()
    {
        $month = date('m') - 1;

        
        $log = new LaravelLogReader(['date' => date('Y-'.$month.'-d H:i:s')]);
        $log->get(); 

        return view('kitukizuri::log', [
            'logs'   => $log,
            'titulo' => 'Logs',
            'kmenu'  => true,
            'layout' => 'krud::layout'
        ]);
    }
}