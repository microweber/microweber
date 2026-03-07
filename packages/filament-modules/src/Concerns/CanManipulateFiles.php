<?php

namespace Coolsam\Modules\Concerns;

use Coolsam\Modules\Facades\FilamentModules;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Nwidart\Modules\Module;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

use function Laravel\Prompts\confirm;

trait CanManipulateFiles
{
    /**
     * @param  array<string>  $paths
     */
    protected function checkForCollision(array $paths): bool
    {
        foreach ($paths as $path) {
            if (! $this->fileExists($path)) {
                continue;
            }

            if (! confirm(basename($path) . ' already exists, do you want to overwrite it?')) {
                $this->components->error("{$path} already exists, aborting.");

                return true;
            }

            unlink($path);
        }

        return false;
    }

    /**
     * @param  array<string, string>  $replacements
     *
     * @throws FileNotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function copyStubToApp(string $stub, string $targetPath, array $replacements = []): void
    {
        $D = DIRECTORY_SEPARATOR;
        $filesystem = app(Filesystem::class);

        $stubPath = $this->getDefaultStubPath() . "{$D}{$stub}.stub";

        $stub = str($filesystem->get($stubPath));

        foreach ($replacements as $key => $replacement) {
            $stub = $stub->replace("{{ {$key} }}", $replacement);
        }

        $stub = (string) $stub;

        $this->writeFile($targetPath, $stub);
    }

    protected function fileExists(string $path): bool
    {
        $filesystem = app(Filesystem::class);

        return $filesystem->exists($path);
    }

    protected function writeFile(string $path, string $contents): void
    {
        $filesystem = app(Filesystem::class);

        $filesystem->ensureDirectoryExists(
            pathinfo($path, PATHINFO_DIRNAME),
        );

        $filesystem->put($path, $contents);
    }

    protected function getDefaultStubPath(): string
    {
        return $this->getModule()->appPath('Commands' . DIRECTORY_SEPARATOR . 'stubs');
    }

    protected function getModule(): Module
    {
        return FilamentModules::getModule($this->getModuleStudlyName());
    }

    abstract protected function getModuleStudlyName(): string;
}
