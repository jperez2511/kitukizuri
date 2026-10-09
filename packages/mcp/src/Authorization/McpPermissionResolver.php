<?php

namespace Icebearsoft\Kitukizuri\Mcp\Authorization;

use Icebearsoft\Kitukizuri\Authorization\ModuleAccess;
use Illuminate\Contracts\Auth\Authenticatable;

class McpPermissionResolver
{
    public function __construct(private ModuleAccess $access) {}

    public function permission(string $operation): ?string
    {
        return config('kitukizuri-mcp.permissions', [])[$operation] ?? null;
    }

    public function grants(?Authenticatable $user): array
    {
        return $this->access->grants($user);
    }

    public function allows(array $grants, string $module, string $operation): bool
    {
        $permission = $this->permission($operation);
        return $permission !== null && in_array($permission, $grants[$module] ?? [], true);
    }

    public function can(?Authenticatable $user, string $module, string $operation): bool
    {
        // No persistent permission cache: revocation takes effect on the next call.
        return $this->allows($this->grants($user), $module, $operation);
    }
}
