<?php

return [
    'enabled' => env('KITUKIZURI_MCP_ENABLED', false),
    'path' => 'mcp/kitukizuri',
    // Use a tenant-local guard. Passport auth:api is another host-app option.
    'middleware' => ['auth:sanctum'],
    'permissions' => [
        'list' => 'show', 'get' => 'show', 'create' => 'create', 'update' => 'edit', 'delete' => 'destroy',
    ],
    'per_minute' => 60,
    'per_tool_per_minute' => 30,
    'audit_channel' => 'kitukizuri-mcp',
    // Cache only the route-to-controller index, never users, builders or permissions.
    'cache_key' => 'kitukizuri-mcp:routes:v1',
];
