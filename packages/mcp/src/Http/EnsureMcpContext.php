<?php

namespace Icebearsoft\Kitukizuri\Mcp\Http;

use Closure;
use Illuminate\Http\Request;

class EnsureMcpContext
{
    public function handle(Request $request, Closure $next)
    {
        app(ExecutionContext::class)->assertUser($request->user());
        return $next($request);
    }
}
