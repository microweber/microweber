<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport;

use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;

/**
 * Orchestrates cross-connection database migration, JSON export, and JSON import.
 *
 * Every public method works in chunked, memory-friendly batches and wraps
 * writes in transactions so a failure mid-way leaves the target clean.
 */
class DbExportManager
{
    private int $chunkSize = 500;

    public function setChunkSize(int $size): static
    {
        $this->chunkSize = max(1, $size);

        return $this;
    }

    public function getChunkSize(): int
    {
        return $this->chunkSize;
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Connection-to-connection copy
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Copy tables from one database connection to another.
     *
     * @param  string        $from   Source connection name (e.g. "sqlite")
     * @param  string        $to     Target connection name (e.g. "mysql")
     * @param  list<string>  $tables Restrict to these tables; empty = all
     * @param  callable|null $onTable fn(string $table, int $rowsCopied)
     * @return array<string, int>    table => rows copied
     */
    public function copy(
        string $from,
        string $to,
        array $tables = [],
        ?callable $onTable = null,
    ): array {
        $inspector = new SchemaInspector();
        $copier    = new TableCopier($this->chunkSize);

        $sourceConn = DB::connection($from);
        $targetConn = DB::connection($to);

        if (empty($tables)) {
            $tables = $inspector->listTableNames($sourceConn);
        }

        $result = [];

        foreach ($tables as $table) {
            $meta = $inspector->inspectTable($sourceConn, $table);

            // Create the table on target (drop if exists)
            $copier->createTable($targetConn, $table, $meta);

            // Copy rows in chunks + transactions
            $count = $copier->copyRows($sourceConn, $targetConn, $table, $meta);

            // Fix auto-increment sequence on target
            $copier->fixAutoIncrement($targetConn, $table, $meta);

            // Re-create indexes
            $copier->createIndexes($targetConn, $table, $meta);

            $result[$table] = $count;

            if ($onTable !== null) {
                $onTable($table, $count);
            }
        }

        return $result;
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Export to JSON file
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Export tables from a connection into a streaming JSON file.
     *
     * @param  string        $path       Output file path
     * @param  string|null   $connection Connection name (null = default)
     * @param  list<string>  $tables     Restrict to these tables; empty = all
     * @param  callable|null $onTable    fn(string $table, int $rows)
     */
    public function exportToJson(
        string $path,
        ?string $connection = null,
        array $tables = [],
        ?callable $onTable = null,
    ): void {
        $exporter = new JsonExporter($this->chunkSize);
        $exporter->export($path, $connection, $tables, $onTable);
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Import from JSON file
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Import tables from a streaming JSON file into a connection.
     *
     * @param  string        $path       Input file path
     * @param  string|null   $connection Target connection name (null = default)
     * @param  callable|null $onTable    fn(string $table, int $rows)
     */
    public function importFromJson(
        string $path,
        ?string $connection = null,
        ?callable $onTable = null,
    ): void {
        $importer = new JsonImporter($this->chunkSize);
        $importer->import($path, $connection, $onTable);
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Table content helper (used by Backup module)
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Get all rows from a table, chunked and memory-friendly.
     *
     * @param  string      $table
     * @param  string|null $connection
     * @return array<int, array<string, mixed>>
     */
    public function getTableContent(string $table, ?string $connection = null): array
    {
        $conn = DB::connection($connection);

        if (! $this->tableExists($conn, $table)) {
            return [];
        }

        $query = $conn->table($table)->select('*');

        // Filter temp media just like the backup module does
        if ($table === 'media') {
            $query->where('media_type', '!=', 'media_tn_temp');
        }

        $rows = [];
        $hasId = $this->columnExists($conn, $table, 'id');

        if ($hasId) {
            $query->orderBy('id')->chunk($this->chunkSize, function ($chunk) use (&$rows) {
                foreach ($chunk as $item) {
                    $rows[] = (array) $item;
                }
            });
        } else {
            foreach ($query->get() as $item) {
                $rows[] = (array) $item;
            }
        }

        return $rows;
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Helpers
    // ──────────────────────────────────────────────────────────────────────

    private function tableExists(Connection $conn, string $table): bool
    {
        return $conn->getSchemaBuilder()->hasTable($table);
    }

    private function columnExists(Connection $conn, string $table, string $column): bool
    {
        return $conn->getSchemaBuilder()->hasColumn($table, $column);
    }
}