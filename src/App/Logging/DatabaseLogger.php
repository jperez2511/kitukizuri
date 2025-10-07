<?php

namespace App\Logging;

use App\Models\Log as LogEntry; // evita colisión con Facade Log
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger as MonoLogger;
use Monolog\LogRecord;
use Throwable;

class DatabaseLogger extends AbstractProcessingHandler
{
    // Para usarlo como "via" en logging.php
    public function __invoke(array $config): MonoLogger
    {
        // Nivel por defecto (debug) o el que mandes en el canal
        $level = $config['level'] ?? Level::Debug;
        // NO empujes el StreamHandler aquí; solo este handler.
        $logger = new MonoLogger('database');
        $logger->pushHandler($this->setLevel($level));
        return $logger;
    }

    protected function write(LogRecord $record): void
    {
        // Info de request (si aplica; en consola puede no haber)
        $allParams = [];
        $url   = null;
        $method= null;
        $agent = null;

        try {
            if (!app()->runningInConsole()) {
                $allParams = Request::all();
                $url    = Request::fullUrl();
                $method = Request::method();
                $agent  = Request::header('User-Agent');
            }
        } catch (Throwable $e) {
            // ignoramos problemas de Request fuera de HTTP
        }

        $params = !empty($allParams) ? json_encode($allParams) : null;
        $time   = $record->datetime->format('Y-m-d H:i:s.u');
        $memory = memory_get_peak_usage(true);
        $userId = Auth::check() ? Auth::id() : null;

        // Monolog 3: usa propiedades, no array-access
        $levelName = $record->level->getName();
        $message   = $record->message;
        $context   = $record->context ?? [];

        // Extrae excepción si viene en context
        $exception = null;
        if (isset($context['exception']) && $context['exception'] instanceof \Throwable) {
            $ex = $context['exception'];
            $exception = [
                'message' => $ex->getMessage(),
                'code'    => $ex->getCode(),
                'file'    => $ex->getFile(),
                'line'    => $ex->getLine(),
                'trace'   => $ex->getTraceAsString(),
            ];
        }

        // 1) Si BD no está lista, cae a archivo
        if (!$this->canUseDatabase()) {
            $this->fallbackToFile($record, [
                'user_id' => $userId, 'params' => $params, 'url' => $url,
                'method'  => $method, 'agent'  => $agent,  'memory'=> $memory,
                'time'    => $time,   'level'  => $levelName,
                'exception' => $exception,
            ]);
            return;
        }

        // 2) Intento BD y si falla => fallback
        try {
            LogEntry::create([
                'id_user' => $userId,
                'params'  => $params,
                'url'     => $url,
                'method'  => $method,
                'agent'   => $agent,
                'memory'  => $memory,
                'time'    => $time,
                'level'   => $levelName,
                'message' => $message,
                'context' => json_encode([
                    'original' => $context,
                    'all'      => $exception,
                ]),
            ]);
        } catch (Throwable $e) {
            $this->fallbackToFile($record, [
                'user_id' => $userId, 'params' => $params, 'url' => $url,
                'method'  => $method, 'agent'  => $agent,  'memory'=> $memory,
                'time'    => $time,   'level'  => $levelName,
                'exception' => $exception,
                'db_error'  => $e->getMessage(),
            ]);
        }
    }

    private function canUseDatabase(): bool
    {
        try {
            // 1) ¿Hay conexión por defecto configurada?
            $default = config('database.default');
            if (empty($default)) {
                return false;
            }
            // 2) ¿La conexión responde?
            DB::connection()->getPdo();

            // 3) ¿Existe la tabla del modelo?
            $table = (new LogEntry)->getTable();
            if (!Schema::hasTable($table)) {
                return false;
            }
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    private function fallbackToFile(LogRecord $record, array $extra = []): void
    {
        try {
            $path = storage_path('logs/laravel-fallback.log');

            $fallback = new MonoLogger('db-fallback');
            $fallback->pushHandler(new StreamHandler($path, Level::Debug, true));

            // Importante: no uses Facade Log aquí para evitar recursión.
            $fallback->log($record->level, $record->message, array_merge($record->context ?? [], $extra));
        } catch (Throwable $e) {
            // Última red: error_log del sistema
            @error_log('[db-fallback-failed] '.$e->getMessage().' | original='.$record->message);
        }
    }
}
