<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Expr\Array_;

trait UtilityTrait 
{    
    protected function composerInstall($package)
    {
        $command = ['composer', 'require', $package];
        
        $process = new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']);
        $process->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

    protected function artisanCommand($action, $option = null)
    {
        $command = [$this->phpBinary(), 'artisan', $action];
        
        if (!empty($option) && is_array($option)) {
            $command = \array_merge($command, $option);
        } else if (!empty($option)){
            $command[] = $option;
        }

        $process = new Process($command, base_path());
        $process->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

    protected function phpBinary()
    {
        return (new PhpExecutableFinder())->find(false) ?: 'php';
    }

    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    protected function editConfig($path, $fields)
    {
        $code = file_get_contents(config_path($path . '.php'));
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $stmts = $parser->parse($code);

        $traverser = new NodeTraverser;
        $traverser->addVisitor(new CloningVisitor);
        $stmts = $traverser->traverse($stmts);

        foreach ($stmts as $stmt) {
            if ($stmt instanceof Return_ && $stmt->expr instanceof Array_) {
                $this->mergeArray($stmt->expr, $fields);
            }
        }

        $prettyPrinter = new PrettyPrinter\Standard;
        $code = $prettyPrinter->prettyPrintFile($stmts);
        file_put_contents(config_path($path . '.php'), $code);
    }

    protected function mergeArray(Array_ $node, array $fields)
    {
        foreach ($node->items as $item) {
            $key = $item->key->value;
            if (isset($fields[$key])) {
                if ($item->value instanceof Array_ && is_array($fields[$key])) {
                    $this->mergeArray($item->value, $fields[$key]);
                } else {
                    $item->value = new \PhpParser\Node\Scalar\String_($fields[$key]);
                }
            }
        }
    }

    protected function runCommands($commands, $path = null)
    {
        $process = Process::fromShellCommandline(implode(' && ', $commands), $path, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> '.$e->getMessage().PHP_EOL);
            }
        }

        $process->run(function ($type, $line) {
            $this->output->write('    '.$line);
        });
    }
}