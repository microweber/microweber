<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\DbExport\DbExportManager;

/**
 * Import tables from a JSON file.
 *
 * Usage:
 *   microweber:db-import --path=file.json
 *   microweber:db-import --connection=mysql --path=file.json
 */
class DbImportCommand extends Command
{
    protected $signature = 'microweber:db-import
                            {--path= : JSON file to import from}
                            {--connection= : Target connection name (default = app default)}
                            {--chunk-size=500 : Number of rows per chunk}';

    protected $description = 'Import database tables from a JSON file (streaming, memory-friendly)';

    public function handle(DbExportManager $manager): int
    {
        $path = $this->option('path');

        if (! $path) {
            $this->error('You must specify --path=file.json');

            return self::FAILURE;
        }

        $connection = $this->option('connection');
        $chunkSize  = (int) $this->option('chunk-size');
        $manager->setChunkSize($chunkSize);

        $this->info("Importing from {$path}...");

        try {
            $manager->importFromJson((string) $path, $connection, function (string $table, int $rows) {
                $this->line("  ✓ {$table}: {$rows} rows");
            });

            $this->newLine();
            $this->info('Import complete.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Import failed: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}