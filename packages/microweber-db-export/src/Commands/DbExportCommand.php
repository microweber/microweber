<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\DbExport\DbExportManager;

/**
 * Artisan command for database export and cross-connection copy.
 *
 * Usage:
 *   Copy between connections:
 *     microweber:db-export sqlite mysql
 *     microweber:db-export mysql pgsql --tables=users,content
 *
 *   Export to JSON file:
 *     microweber:db-export --path=file.json
 *     microweber:db-export --connection=sqlite --path=file.json
 */
class DbExportCommand extends Command
{
    protected $signature = 'microweber:db-export
                            {source? : Source connection name (for copy mode)}
                            {target? : Target connection name (for copy mode)}
                            {--path= : Export to JSON file instead of copying}
                            {--connection= : Connection to export from (used with --path)}
                            {--tables= : Comma-separated list of tables to copy/export}
                            {--chunk-size=500 : Number of rows per chunk}';

    protected $description = 'Export database tables to JSON or copy between connections (SQLite, MySQL, PostgreSQL)';

    public function handle(DbExportManager $manager): int
    {
        $chunkSize = (int) $this->option('chunk-size');
        $manager->setChunkSize($chunkSize);

        $tables = $this->option('tables')
            ? array_map('trim', explode(',', (string) $this->option('tables')))
            : [];

        $path = $this->option('path');

        if ($path) {
            return $this->handleExport($manager, (string) $path, $tables);
        }

        $source = $this->argument('source');
        $target = $this->argument('target');

        if ($source && $target) {
            return $this->handleCopy($manager, (string) $source, (string) $target, $tables);
        }

        $this->error('Provide source and target connections for copy, or --path for JSON export.');
        $this->line('');
        $this->line('  Copy:   microweber:db-export sqlite mysql');
        $this->line('  Export: microweber:db-export --path=backup.json');

        return self::FAILURE;
    }

    private function handleCopy(DbExportManager $manager, string $source, string $target, array $tables): int
    {
        $this->info("Copying from [{$source}] to [{$target}]...");

        try {
            $result = $manager->copy($source, $target, $tables, function (string $table, int $rows) {
                $this->line("  ✓ {$table}: {$rows} rows");
            });

            $totalRows   = array_sum($result);
            $totalTables = count($result);

            $this->newLine();
            $this->info("Done! Copied {$totalTables} tables, {$totalRows} total rows.");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Copy failed: {$e->getMessage()}");

            return self::FAILURE;
        }
    }

    private function handleExport(DbExportManager $manager, string $path, array $tables): int
    {
        $connection = $this->option('connection');

        $this->info('Exporting to ' . $path . '...');

        try {
            $manager->exportToJson($path, $connection, $tables, function (string $table, int $rows) {
                $this->line("  ✓ {$table}: {$rows} rows");
            });

            $size = filesize($path);
            $this->newLine();
            $this->info("Done! Exported to {$path} (" . number_format($size) . " bytes)");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Export failed: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}