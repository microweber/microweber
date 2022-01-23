<?php

namespace MicroweberPackages\Install;

use Illuminate\Database\Migrations\Migrator;
use function Illuminate\Database\Migrations;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema as DbSchema;

class MicroweberMigrator extends Migrator
{


    /**
     * Run the pending migrations at a given path.
     *
     * @param array|string $paths
     * @param array $options
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
     * @param string $file
     * @param int $batch
     * @param bool $pretend
     * @return void
     */
    protected function runUp($file, $batch, $pretend)
    {

        $this->ensureMigrationsTableExists();
        $migration = $this->resolve(
            $name = $this->getMigrationName($file)
        );

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
            if (strpos($e->getMessage(), 'already exists') !== false) {
                $this->repository->log($name, $batch);
            }
        }

        $runTime = round(microtime(true) - $startTime, 2);

        $this->note("<info>Migrated:</info>  {$name} ({$runTime} seconds)");
    }

    private function ensureMigrationsTableExists()
    {
        if (!DbSchema::hasTable('migrations')) {
            try {
                DbSchema::create('migrations', function ($table) {
                    $table->increments('id');
                    $table->string('migration');
                    $table->integer('batch');
                });
            } catch (QueryException $e) {

            }
        }

    }

    public $logger = null;

    public function log($text)
    {
        if (is_object($this->logger) and method_exists($this->logger, 'log')) {
            $this->logger->log($text);
        }
    }

}
