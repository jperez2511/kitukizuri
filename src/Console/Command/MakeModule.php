<?php

namespace Icebearsoft\Kitukizuri\Console\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MakeModule extends Command
{    
    /**
     * The name and signature of the console command. 
     *
     * @var string
     */
    protected $signature = "make:krud";
        
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Genera un modulo utilizable con Krud";
    
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
     * Execute te console command.
     *
     * @return void
     */
    public function handle() 
    {
        $moduloNombre = $this->ask('Nombre del módulo:');
        dd($moduloNombre);
    }
}