<?php

namespace Icebearsoft\Kitukizuri\App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenants extends Model
{
    protected $connection = 'tenants';
    protected $table      = "tenants";
	protected $primaryKey = "tenant_id";
	protected $guarded    = ['tenant_id'];
}
