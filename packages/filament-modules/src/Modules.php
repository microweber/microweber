<?php

namespace Coolsam\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Traits\Macroable;
use Nwidart\Modules\Facades\Module;
use Symfony\Component\Process\Process;

class Modules
{
    use Macroable;

    public function getModule(string $name): \Nwidart\Modules\Module
    {
        return Module::findOrFail($name);
    }

    public function convertPathToNamespace(string $fullPath): string
    {
        $base = str(trim(config('modules.paths.modules', base_path('Modules')), '/\\'));
        $relative = str($fullPath)->afterLast($base)->replaceFirst(DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR);

        return str($relative)
            ->ltrim('/\\')
            ->prepend(DIRECTORY_SEPARATOR)
            ->prepend(config('modules.namespace', 'Modules'))
            ->replace(DIRECTORY_SEPARATOR, '\\')
            ->replace('\\\\', '\\')
            ->rtrim('.php')
            ->explode(DIRECTORY_SEPARATOR)
            ->map(fn ($piece) => str($piece)->studly()->toString())
            ->implode('\\');
    }

    public function execCommand(string $command, ?Command $artisan = null): void
    {
        $process = Process::fromShellCommandline($command);
        $process->start();
        foreach ($process as $type => $data) {
            if (! $artisan) {
                echo $data;
            } else {
                $artisan->info(trim($data));
            }
        }
    }

    public function packagePath(string $path = ''): string
    {
        // return the base path of this package
        return dirname(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR) . ($path ? DIRECTORY_SEPARATOR . trim($path, DIRECTORY_SEPARATOR) : '');
    }
}
