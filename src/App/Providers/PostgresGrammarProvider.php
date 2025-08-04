<?php

namespace Icebearsoft\Kitukizuri\App\Providers;

use Iluminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Icebearsoft\Kitukizuri\App\Database\Grammar\{
    CustomPostgresGrammar
};

class PostgresGrammarProvider extends ServiceProvider
{
    public function boot()
    {
        if(config('database.default') === 'pgsql') {
            $connection = DB::connection();
            $connection->setQueryGrammar(new CustomPostgresGrammar($connection));
        }
    }
}