<?php

namespace Icebearsoft\Kitukizuri\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table      = 'roles';
    protected $primaryKey = 'rolid';
    protected $guarded    = ['rolid'];
}
