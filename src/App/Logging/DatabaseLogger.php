<?php

namespace Icebearsoft\Kitukizuri\App\Logging;

use Auth;
use Monolog\Logger;
use Monolog\LogRecord;
use Illuminate\Support\Facades\Request;
use Monolog\Handler\AbstractProcessingHandler;


use Icebearsoft\Kitukizuri\App\Models\Log;

class DatabaseLogger extends AbstractProcessingHandler
{
    public function __invoke(array $config)
    {
        return new Logger('database', [$this]);
    }

    protected function write(LogRecord $record): void
    {

        $time   = $record->datetime->format('Y-m-d H:i:s.u');
        $url    = Request::fullUrl();
        $method = Request::method();
        $agent  = Request::header('User-Agent');;
        $params = json_enconde(Request::all());
        $memory = memory_get_peak_usage(true);
        $user   = Auth::check() ? Auth::id() : null;

        Log::create([
            'id_user' => $user,
            'params'  => $params,
            'url'     => $url,
            'method'  => $method,
            'agent'   => $agent,
            'memory'  => $memory,
            'time'    => $time,
            'level'   => $record['level_name'],
            'message' => $record['message'],
            'context' => json_encode($record['context']),
        ]);
    }
}
