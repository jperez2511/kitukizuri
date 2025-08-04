<?php

namespace Icebearsoft\Kitukizuri\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Events\ConnectionCreated;
use Icebearsoft\Kitukizuri\App\Database\Grammar\{
    CustomPostgresGrammar
};

class PostgresGrammarProvider extends ServiceProvider
{
    public function register()
    {
        $this->app['events']->listen(ConnectionCreated::class, function($event){
            if ($event->connection->getDriverName() === 'pgsql') {
                $event->connection->setQueryGrammar(new CustomPostgresGrammar($event->connection));
            }
        });
    }
}