<?php

namespace Icebearsoft\Kitukizuri\Mcp\Http;

use Closure;
use Icebearsoft\Kitukizuri\Mcp\Execution\AuditRecorder;
use Illuminate\Http\Request;

/** Captures calls rejected by Laravel's registry, auth or throttling before handle(). */
class AuditMcpCall
{
    public function handle(Request $request, Closure $next)
    {
        $started = microtime(true);
        try {
            return $next($request);
        } finally {
            if ($request->input('method') === 'tools/call' && !$request->attributes->get('kitukizuri.mcp.audited')) {
                app(AuditRecorder::class)->record(
                    (string) $request->input('params.name', ''), [], 'rejected_before_execution', $started
                );
            }
        }
    }
}
