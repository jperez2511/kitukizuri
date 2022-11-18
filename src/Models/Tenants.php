<?php

namespace Icebearsoft\Kitukizuri\Models;

use Illuminate\Database\Eloquent\Model;

class Tenants extends Model
{
    protected $connection = 'tenants';
    protected $table      = "tenants";
	protected $primaryKey = "tenants_id";
	protected $guarded    = ['tenants_id'];
}
