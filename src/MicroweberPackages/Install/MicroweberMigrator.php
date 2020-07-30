<?php

namespace MicroweberPackages\Install;

use Illuminate\Database\Events\MigrationEnded;
use Illuminate\Database\Events\MigrationStarted;
use Illuminate\Database\Migrations\Migrator;
use function Illuminate\Database\Migrations;

class MicroweberMigrator extends Migrator {

    /**
     * Run "up" a migration instance.
     *
     * @param  string  $file
     * @param  int  $batch
     * @param  bool  $pretend
     * @return void
     */
    protected function runUp($file, $batch, $pretend)
    {
        // First we will resolve a "real" instance of the migration class from this
        // migration file name. Once we have the instances we can run the actual
        // command such as "up" or "down", or we can just simulate the action.
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

        } catch (\Exception $e) {
            if(strpos($e->getMessage(), 'already exists') !==false) {
                $this->repository->log($name, $batch);
            }
        }

        $runTime = round(microtime(true) - $startTime, 2);

        // Once we have run a migrations class, we will log that it was run in this
        // repository so that we don't try to run it next time we do a migration
        // in the application. A migration repository keeps the migrate order.

        $this->note("<info>Migrated:</info>  {$name} ({$runTime} seconds)");
    }

}
