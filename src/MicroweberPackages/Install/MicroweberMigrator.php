<?php
namespace MicroweberPackages\Install;

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

        $this->note("<info>Migrated:</info>  {$name} ({$runTime} seconds)");
    }

}
