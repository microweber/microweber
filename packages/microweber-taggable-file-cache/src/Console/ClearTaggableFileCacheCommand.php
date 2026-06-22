<?php

namespace MicroweberPackages\TaggableFileCache\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearTaggableFileCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-taggable-file {--env= : The environment to clear cache for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the taggable file cache. Supports --env flag for multi-site environments.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cachePath = config('cache.stores.file.path');

        if (!$cachePath) {
            $this->error('No file cache path configured.');
            return 1;
        }

        $env = $this->option('env') ?: app()->environment();

        $filesystem = new Filesystem();

        // The service provider scopes the cache as a nested `<env>/<locale>` directory, so
        // deleting the `<env>` directory recursively clears every locale beneath it. We also
        // sweep any legacy flat `<env>-<locale>` directories (from the previous layout) so a
        // single `--env=` clear is robust across the format change.
        $targets = [];

        $envCacheDir = $cachePath . DIRECTORY_SEPARATOR . $env;
        if (is_dir($envCacheDir)) {
            $targets[] = $envCacheDir;
        }

        $legacyFlatDirs = glob($cachePath . DIRECTORY_SEPARATOR . $env . '-*', GLOB_ONLYDIR);
        if (!empty($legacyFlatDirs)) {
            $targets = array_merge($targets, $legacyFlatDirs);
        }

        if (empty($targets)) {
            $this->info("No taggable file cache found for environment: [{$env}]");
            return 0;
        }

        foreach ($targets as $dir) {
            $filesystem->deleteDirectory($dir);
            $this->info("Cleared cache directory: " . basename($dir));
        }

        return 0;
    }
}