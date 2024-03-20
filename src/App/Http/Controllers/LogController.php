<?php

namespace Icebearsoft\Kitukizuri\App\Http\Controllers;

use App\Http\Controllers\Controller;

use Icebearsoft\Kitukizuri\App\Models\Log;
use Icebearsoft\Kitukizuri\App\Models\LaravelLogReader;
class LogController extends Controller
{
    public function index()
    {
        $channel = env('LOG_CHANNEL');

        if($channel == 'stack')
        {
            $file = storage_path('logs/laravel.log');
            return response()->download($file, 'laravel.log', ['Content-Type' => 'text/plain']);
        } else if ($channel == 'database') {
            $log = Log::orderBy('id_log', 'desc')->get();
        }

        return view('kitukizuri::log', [
            'logs'   => $log,
            'titulo' => 'Logs',
            'kmenu'  => true,
            'layout' => 'krud::layout',
            'dash'   => true
        ]);
    }
}