<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport;

use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Orchestrates cross-connection database migration, JSON export, and JSON import.
 *
 * Every public method works in chunked, memory-friendly batches and wraps
 * writes in transactions so a failure mid-way leaves the target clean.
 *
 * Filter options (skip-tables, skip-fields, only-ids, where-conditions) are
 * configured through {@see ExportFilter} and applied at query-build time —
 * before any data is fetched.
 */
class DbExportManager
{
    private int $chunkSize = 500;

    private ExportFilter $filter;

    public function __construct()
    {
        $this->filter = new ExportFilter();
    }

    public function setChunkSize(int $size): static
    {
        $this->chunkSize = max(1, $size);

        return $this;
    }

    public function getChunkSize(): int
    {
        return $this->chunkSize;
    }

    /**
     * Get the current export filter for chaining configuration.
     */
    public function filter(): ExportFilter
    {
        return $this->filter;
    }

    /**
     * Replace the export filter entirely.
     */
    public function setFilter(ExportFilter $filter): static
    {
        $this->filter = $filter;

        return $this;
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Connection-to-connection copy
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Copy tables from one database connection to another.
     *
     * @param  string        $from    Source connection name (e.g. "sqlite")
     * @param  string        $to      Target connection name (e.g. "mysql")
     * @param  list<string>  $tables  Restrict to these tables; empty = all
     * @param  callable|null $onTable fn(string $table, int $rowsCopied)
     * @return array<string, int>     table => rows copied
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

        $tables = $this->resolveTables($sourceConn, $tables, $inspector);

        $result = [];

        foreach ($tables as $table) {
            $meta = $inspector->inspectTable($sourceConn, $table);

            // Create the table on target (drop if exists)
            $copier->createTable($targetConn, $table, $meta);

            // Build a filtered query for the source data
            $query = $this->buildFilteredQuery($sourceConn, $table);

            // Copy rows in chunks + transactions
            $count = $copier->copyRows($sourceConn, $targetConn, $table, $meta, $query);

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
        $exporter = new JsonExporter($this->chunkSize, $this->filter);
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
    //  Table content helper (used by Backup / Export modules)
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Get rows from a table with all configured filters applied at query
     * time (skip-fields, only-ids, where-conditions).  Reads in chunks so
     * memory stays bounded even on large tables.
     *
     * @param  string       $table
     * @param  string|null  $connection
     * @param  list<int>    $ids        Optional list of IDs to restrict to (applied at query time)
     * @return array<int, array<string, mixed>>
     */
    public function getTableContent(string $table, ?string $connection = null, array $ids = []): array
    {
        $conn = DB::connection($connection);

        if (! $this->tableExists($conn, $table)) {
            return [];
        }

        $query = $this->buildFilteredQuery($conn, $table);

        // Apply ID filter at query time — not after fetching
        $hasId = $this->columnExists($conn, $table, 'id');
        if (! empty($ids) && $hasId) {
            $query->whereIn('id', $ids);
        }

        $rows = [];

        if ($hasId) {
            $query->orderBy('id')->chunk($this->chunkSize, function ($chunk) use (&$rows) {
                foreach ($chunk as $item) {
                    $rows[] = (array) $item;
                }
            });
        } else {
            // Tables without id — use cursor for memory efficiency
            foreach ($query->cursor() as $item) {
                $rows[] = (array) $item;
            }
        }

        return $rows;
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Internal helpers
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Resolve the final list of tables to operate on, applying skip-tables.
     *
     * @param  Connection       $conn
     * @param  list<string>     $requested
     * @param  SchemaInspector  $inspector
     * @return list<string>
     */
    private function resolveTables(Connection $conn, array $requested, SchemaInspector $inspector): array
    {
        if (empty($requested)) {
            $requested = $inspector->listTableNames($conn);
        }

        $skipTables = $this->filter->getSkipTables();

        if (empty($skipTables)) {
            return $requested;
        }

        return array_values(array_filter(
            $requested,
            fn (string $t) => ! in_array($t, $skipTables, true),
        ));
    }

    /**
     * Build a query for the given table with all configured filters applied
     * (skip-fields, where-conditions, only-ids) — before any data is read.
     */
    private function buildFilteredQuery(Connection $conn, string $table): Builder
    {
        // Select only non-skipped columns
        $skipFields = $this->filter->getSkipFieldsForTable($table);
        if (! empty($skipFields)) {
            $allColumns = $conn->getSchemaBuilder()->getColumnListing($table);
            $select = array_values(array_diff($allColumns, $skipFields));
            $query = $conn->table($table)->select($select ?: ['*']);
        } else {
            $query = $conn->table($table)->select('*');
        }

        // Apply only-ids filter at query time
        $onlyIds = $this->filter->getOnlyIdsForTable($table);
        if (! empty($onlyIds) && $this->columnExists($conn, $table, 'id')) {
            $query->whereIn('id', $onlyIds);
        }

        // Apply per-table where-conditions
        $wheres = $this->filter->getWhereConditionsForTable($table);
        foreach ($wheres as $where) {
            $query->where($where['column'], $where['operator'], $where['value']);
        }

        return $query;
    }

    private function tableExists(Connection $conn, string $table): bool
    {
        return $conn->getSchemaBuilder()->hasTable($table);
    }

    private function columnExists(Connection $conn, string $table, string $column): bool
    {
        return $conn->getSchemaBuilder()->hasColumn($table, $column);
    }
}