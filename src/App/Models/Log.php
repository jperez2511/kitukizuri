<?php

namespace Icebearsoft\Kitukizuri\App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table      = "logs";
	protected $primaryKey = "id_log";
	protected $guarded    = ['id_log'];
}
