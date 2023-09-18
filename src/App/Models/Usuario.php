<?php

namespace Icebearsoft\Kitukizuri\App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $guarded    = ['id'];
}
