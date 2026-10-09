<?php
namespace Icebearsoft\Kitukizuri\Mcp\Tests\Fixtures;
class Patient extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'patients';
    protected $guarded = [];
    protected $hidden = ['private_note'];
    public $timestamps = false;
}
