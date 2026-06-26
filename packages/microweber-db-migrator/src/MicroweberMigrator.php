<?php

namespace MicroweberPackages\DbMigrator;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema as DbSchema;

/**
 * Custom migrator that extends Laravel's Migrator.
 *
 * Key differences from the stock migrator:
 *  - Automatically creates the `migrations` table if it is missing.
 *  - Catches exceptions during individual migration runs and logs them
 *    instead of aborting the whole batch.
 */
class MicroweberMigrator extends Migrator
{
    /**
     * Run the pending migrations at a given path.
     *
     * @param  array|string  $paths
     * @param  array         $options
     * @return array
     */
    public function run($paths = [], array $options = [])
    {
        $this->ensureMigrationsTableExists();
        parent::run($paths, $options);
    }

    /**
     * Run "up" a migration instance.
     *
     * @param  string  $file
     * @param  int     $batch
     * @param  bool    $pretend
     * @return void
     */
    protected function runUp($file, $batch, $pretend)
    {
        $this->ensureMigrationsTableExists();

        $migration = $this->resolve(
            $name = $this->getMigrationName($file)
        );

        if (!$name) {
            return;
        }

        if ($pretend) {
            return $this->pretendToRun($migration, 'up');
        }

        $this->note("<comment>Migrating:</comment> {$name}");

        $startTime = microtime(true);

        try {
            $this->runMigration($migration, 'up');
            $this->repository->log($name, $batch);
            $this->log('Migrating: ' . $name);
        } catch (\Exception $e) {
            $this->note($e->getMessage());
            Log::error($e->getMessage());
        }

        $runTime = round(microtime(true) - $startTime, 2);

        $this->note("<info>Migrated:</info>  {$name} ({$runTime} seconds)");
    }

    /**
     * Resolve a migration instance from a file.
     *
     * @param  string  $file
     * @return object|null
     */
    public function resolve($file)
    {
        $class = $this->getMigrationClass($file);
        if (class_exists($class)) {
            return new $class;
        }
        return null;
    }

    protected function runMigration($migration, $method)
    {
        if (!$migration) {
            return false;
        }
        return parent::runMigration($migration, $method);
    }

    private function ensureMigrationsTableExists(): void
    {
        if (!DbSchema::hasTable('migrations')) {
            try {
                DbSchema::create('migrations', function ($table) {
                    $table->increments('id');
                    $table->string('migration');
                    $table->integer('batch');
                });
            } catch (QueryException $e) {
                // table may have been created concurrently – ignore
            }
        }
    }

    // ------------------------------------------------------------------
    //  Logging
    // ------------------------------------------------------------------

    /** @var object|null */
    public $logger = null;

    public function log($text): void
    {
        if (is_object($this->logger) && method_exists($this->logger, 'log')) {
            $this->logger->log($text);
        }
    }

    public function note($text): void
    {
        if (is_object($this->logger) && method_exists($this->logger, 'log')) {
            $this->logger->log($text);
        }
    }
}