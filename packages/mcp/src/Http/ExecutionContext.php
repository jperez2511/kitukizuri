<?php

namespace Icebearsoft\Kitukizuri\Mcp\Http;

use Icebearsoft\Kitukizuri\App\Models\Tenant;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExecutionContext
{
    public function assertUser(?Authenticatable $user): void
    {
        if (!$user instanceof Model || !$user->exists) {
            throw new AuthorizationException('Access denied.');
        }
        $this->assertConnection($user);
        if (config('kitukizuri.multiTenants') === true) {
            $tenant = Tenant::where('dominio', request()->getHttpHost())->where('activo', 1)->first();
            $connection = DB::connection();
            // Tenant middleware must already have resolved this exact host before auth.
            if (!$tenant || DB::getDefaultConnection() !== 'mysql'
                || (string) $connection->getDatabaseName() !== (string) $tenant->db
                || (string) $connection->getConfig('host') !== (string) $tenant->db_host
                || (string) $connection->getConfig('username') !== (string) $tenant->db_username
                || (string) $connection->getConfig('tenantid') !== (string) $tenant->tenant_id) {
                throw new AuthorizationException('Tenant context unavailable.');
            }
        }
    }

    public function assertConnection(Model $model): void
    {
        if ($model->getConnection() !== DB::connection()) {
            throw new AuthorizationException('Cross-connection resources are not available.');
        }
    }

    public function tenant(): string
    {
        return (string) (config('kitukizuri.multiTenants') === true
            ? DB::connection()->getConfig('tenantid') : 'single');
    }

    public function rateKey(?Authenticatable $user, string $tool = ''): string
    {
        return hash('sha256', implode('|', [request()->getHttpHost(), $this->tenant(),
            $user?->getAuthIdentifier(), hash('sha256', request()->bearerToken() ?? ''), $tool]));
    }
}
