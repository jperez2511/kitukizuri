<?php namespace Icebearsoft\Kitukizuri\Facades;

use Illuminate\Support\Facades\Facade;

class Kitukizuri extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'kitukizuri';
    }
}
