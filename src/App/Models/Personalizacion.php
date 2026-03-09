<?php

namespace Icebearsoft\Kitukizuri\App\Models;

use Illuminate\Database\Eloquent\Model;

class Personalizacion extends Model
{
    protected $table = 'personalizaciones';
    protected $primaryKey = 'personalizacionid';
    protected $guarded = ['personalizacionid'];
}
