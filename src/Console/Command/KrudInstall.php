<?php

namespace Icebearsoft\Kitukizuri\Console\Command;

use DB;
use Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

class KrudInstall extends Command
{    
    /**
     * The name and signature of the console command. 
     *
     * @var string
     */
    protected $signature = "krud:install";
        
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Genera los datos necesarios para inicializar un proyecto basado en KRUD";
    
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

    }

    protected function composerInstall($package)
    {   
        $command = [$this->phpBinary(), $package, 'require'];
        
        $process = new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']);
        $process->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

    protected function phpBinary()
    {
        return (new PhpExecutableFinder())->find(false) ?: 'php';
    }
}