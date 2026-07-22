<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\DbExport\DbExportManager;
use MicroweberPackages\DbExport\ExportFilter;

/**
 * Artisan command for database export and cross-connection copy.
 *
 * Copy between connections:
 *   microweber:db-export sqlite mysql
 *   microweber:db-export mysql pgsql --tables=users,content
 *   microweber:db-export pgsql sqlite --skip-tables=log,sessions
 *   microweber:db-export pgsql sqlite --skip-fields=content.title,cart.session_id
 *   microweber:db-export pgsql sqlite --only-ids=content.1,2,3,users.2,4,5
 *
 * Export to JSON file:
 *   microweber:db-export --path=file.json
 *   microweber:db-export --connection=sqlite --path=file.json
 */
class DbExportCommand extends Command
{
    protected $signature = 'microweber:db-export
                            {source? : Source connection name (for copy mode)}
                            {target? : Target connection name (for copy mode)}
                            {--path= : Export to JSON file instead of copying}
                            {--connection= : Connection to export from (used with --path)}
                            {--tables= : Comma-separated list of tables to copy/export}
                            {--skip-tables= : Comma-separated list of tables to skip}
                            {--skip-fields= : Comma-separated table.column pairs to skip (e.g. content.title,cart.session_id)}
                            {--only-ids= : Restrict tables to specific IDs (e.g. content.1,2,3 users.4,5)}
                            {--chunk-size=500 : Number of rows per chunk}';

    protected $description = 'Export database tables to JSON or copy between connections (SQLite, MySQL, PostgreSQL)';

    public function handle(DbExportManager $manager): int
    {
        $chunkSize = (int) $this->option('chunk-size');
        $manager->setChunkSize($chunkSize);

        // Configure filters from CLI options
        $this->configureFilters($manager);

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

    private function configureFilters(DbExportManager $manager): void
    {
        $filter = $manager->filter();

        // --skip-tables=log,sessions,stats
        $skipTables = $this->option('skip-tables');
        if ($skipTables) {
            $filter->setSkipTables(
                array_map('trim', explode(',', (string) $skipTables))
            );
        }

        // --skip-fields=content.title,cart.session_id
        $skipFields = $this->option('skip-fields');
        if ($skipFields) {
            $filter->setSkipFields(
                array_map('trim', explode(',', (string) $skipFields))
            );
        }

        // --only-ids="content.1,2,3 users.4,5"
        // Splits on spaces to get separate table.ids groups
        $onlyIds = $this->option('only-ids');
        if ($onlyIds) {
            $groups = preg_split('/\s+/', trim((string) $onlyIds));
            if ($groups !== false) {
                $filter->setOnlyIds($groups);
            }
        }
    }

    /**
     * @param  list<string> $tables
     */
    private function handleCopy(DbExportManager $manager, string $source, string $target, array $tables): int
    {
        $this->info("Copying from [{$source}] to [{$target}]...");

        try {
            $result = $manager->copy($source, $target, $tables, function (string $table, int $rows): void {
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

    /**
     * @param  list<string> $tables
     */
    private function handleExport(DbExportManager $manager, string $path, array $tables): int
    {
        $connection = $this->option('connection');

        $this->info('Exporting to ' . $path . '...');

        try {
            $manager->exportToJson($path, $connection, $tables, function (string $table, int $rows): void {
                $this->line("  ✓ {$table}: {$rows} rows");
            });

            $size = filesize($path);
            $this->newLine();
            $this->info("Done! Exported to {$path} (" . number_format((int) $size) . " bytes)");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Export failed: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}