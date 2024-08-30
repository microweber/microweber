<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Facades\Schema as DbSchema;

class ModuleMigrator extends Migrator
{

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
        $migration = $this->resolve(
            $name = $this->getMigrationName($file)
        );

        if (!$name) {
            return;
        }

        if ($pretend) {
            return $this->pretendToRun($migration, 'up');
        }

        try {
            $this->runMigration($migration, 'up');
            $this->repository->log($name, $batch);
            $this->logs[] = [
                'migration' => $name,
                'message' => 'Migrated',
                'success' => true,
            ];
        } catch (\Exception $e) {
            $this->logs[] = [
                'migration' => $name,
                'message' => $e->getMessage(),
                'success' => false,
            ];
        }
    }

    public $logs = [];

    public function getLogs()
    {
        return $this->logs;
    }

}

