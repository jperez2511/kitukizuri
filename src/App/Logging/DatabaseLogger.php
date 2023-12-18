<?php

namespace Icebearsoft\Kitukizuri\App\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Icebearsoft\Kitukizuri\App\Models\Log;

class DatabaseLogger extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {
        Log::create([
            'level' => $record['level_name'],
            'message' => $record['message'],
            'context' => json_encode($record['context']),
        ]);
    }
}
