<?php

namespace Icebearsoft\Kitukizuri\App\Console\Command;

use DB;
use Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MigrateTTS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:tts
        {--all : Ejecutar migrations para todas las bases de datos de tenants}
        {--rollback : Rollback de la base de datos tenants}
        {--id=* : ejecutar migrations para una base de datos de tenants}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea las tablas para la base de datos tenants';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // validate migrate or rollback
        if ($this->option('rollback')) {
            $action = ':rollback';
            $msg = 'Rollback for';
        } else {
            $action = '';
            $msg = 'to Migrating';
        }

        // opcion migration all database or by ID
        if ($this->option('all') || !empty($this->option('id'))) {
            $dbs = $this->getDB($this->option('id'));
            foreach ($dbs as $db) {
                $this->info('Start '.$msg.': '.$db->db);
                DB::purge('mysql');
                Config::set('database.connections.mysql.database',  $db->db);
                Config::set('database.connections.mysql.username',  $db->db_username);
                Config::set('database.connections.mysql.password',  $db->db_password);
                DB::reconnect('mysql');
                Artisan::call('migrate'.$action);
                $output = Artisan::output();
                $this->info($output);
            }
        } else if(empty($this->option('all')) && empty($this->option('id'))) {
            Artisan::call('migrate'.$action, ['--database' => 'tenants', '--path' => 'database/migrations/tenants']);
        }

        return Command::SUCCESS;
    }

    private function getDB($tenantids = null)
    {
        $query = DB::connection('tenants')
          ->table('tenants')
          ->select('db', 'db_password', 'db_username')
          ->where('activo', true);

        if (!empty($tenantids)) {
          $query->whereIn('tenant_id', $tenantids);
        }

        $dbs = $query->groupBy('db', 'db_password', 'db_username')->get();

        if ($dbs->isEmpty()) {
          throw new \Exception('Data Base not exist.');
        }

        return $dbs;
    }
}
