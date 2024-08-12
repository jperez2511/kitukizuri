<?php

namespace Icebearsoft\Kitukizuri\App\Console\Command;

use DB;
use Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SeedTTS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:tts
        {--class=* : Ejecuta un Seeder en especifico}
        {--t|tenantid=* : Ejecuta los seeders en un tenant en especifico}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inicializa la base de datos';

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
        if ($this->option('class') === 'InicialSeeder' && empty($this->option('tenantid'))) {
            return $this->info('Especificar tenantid con -t.');
        }

        $dbs = $this->getDB($this->option('tenantid'));
        foreach ($dbs as $db) {
            $this->info('Seed DB: ' . $db->db);
            DB::purge('mysql');
            Config::set('database.connections.mysql.database',  $db->db);
            Config::set('database.connections.mysql.username',  $db->db_username);
            Config::set('database.connections.mysql.password',  $db->db_password);
            DB::reconnect('mysql');
            if ($this->option('class')) {
                foreach ($this->option('class') as $clase) {
                    Artisan::call('db:seed --class='.$clase);
                }
            } else {
                Artisan::call('db:seed');
            }
        }

    }

    private function getDB($tenantids = null) 
    {
        $query = DB::connection('tenants')
          ->table('tenants')
          ->select('db', 'db_password', 'db_username')
          ->where('activo', true)
          ->orderBy('tenant_id');
    
        if (!empty($tenantids)) {
          $query->whereIn('tenant_id', $tenantids);
        }
    
        $dbs = $query->get();
    
        if ($dbs->isEmpty()) {
          throw new \Exception('Data Base not exist.');
        }
    
        return $dbs;
    }
}