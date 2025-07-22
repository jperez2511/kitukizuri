<?php

namespace Icebearsoft\Kitukizuri\App\Traits;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

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

    protected function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    protected function copyDirectory($from, $to)
    {
        (new Filesystem)->copyDirectory($from, $to);
    }

    protected function isPackageInstalled($package)
    {
        $packages = json_decode(file_get_contents(base_path('composer.json')), true);

        return isset($packages['require'][$package]);
    }

    protected function removeDockerBlock(string $filePath, string $blockName): bool
    {
        if (!file_exists($filePath)) {
            throw new Exception("El archivo no existe: $filePath");
        }

        $content = file_get_contents($filePath);

        $pattern = "/#start\s+$blockName(.*?)#end\s+$blockName/s";

        if (preg_match($pattern, $content)) {
            $newContent = preg_replace($pattern, '', $content);
            file_put_contents($filePath, $newContent);
            return true;
        }

        return false; // No encontró el bloque
    }

}