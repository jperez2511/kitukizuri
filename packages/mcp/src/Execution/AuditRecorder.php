<?php

namespace Icebearsoft\Kitukizuri\Mcp\Execution;

use Icebearsoft\Kitukizuri\Mcp\Http\ExecutionContext;
use Illuminate\Support\Facades\Log;

/** Values and result payloads are deliberately never logged. Replace via the container. */
class AuditRecorder
{
    public function record(string $tool, array $arguments, string $status, float $started): void
    {
        $tool = preg_match('/^[a-z][a-z0-9_-]{0,47}\.(list|get|create|update|delete)$/D', $tool) ? $tool : '[unknown]';
        [$module, $operation] = array_pad(explode('.', $tool, 2), 2, null);
        $sanitized = [];
        foreach (array_slice(array_keys($arguments), 0, 100) as $key) {
            // Untrusted argument keys can themselves contain secrets. Store counts only.
            $sanitized[] = '[redacted]';
        }
        Log::channel(config('kitukizuri-mcp.audit_channel'))->info('kitukizuri.mcp.call', [
            'authenticated_user' => request()->user()?->getAuthIdentifier(),
            'tenant' => app(ExecutionContext::class)->tenant(),
            'tool' => $tool, 'module' => $module, 'operation' => $operation,
            'arguments' => $sanitized, 'result' => $status, 'status' => $status,
            'duration_ms' => round((microtime(true) - $started) * 1000, 2),
            'timestamp' => now()->toIso8601String(),
            // User-Agent is untrusted: do not retain its raw value.
            'client' => hash('sha256', (string) request()->userAgent()),
        ]);
        request()->attributes->set('kitukizuri.mcp.audited', true);
    }
}
