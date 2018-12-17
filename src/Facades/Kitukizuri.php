<?php namespace Icebearsoft\Kitukizuri\Facades;

use Illuminate\Support\Facades\Facade;

class Kitukizuri extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return 'kitukizuri';
    }
}
