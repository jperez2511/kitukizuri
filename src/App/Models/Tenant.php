<?php

namespace Icebearsoft\Kitukizuri\App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $connection = 'tenants';
    protected $table      = 'tenants';
    protected $primaryKey = 'tenant_id';
}
