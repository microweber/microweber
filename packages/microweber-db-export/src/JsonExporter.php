<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport;

use Illuminate\Support\Facades\DB;

/**
 * Exports database tables to a streaming JSON file.
 *
 * The JSON structure is: { "table_name": [ {row}, ... ], ... }
 * Written incrementally so the whole dataset is never in memory at once.
 *
 * Respects the {@see ExportFilter} for skip-tables, skip-fields, only-ids,
 * and where-conditions — all applied at query-build time.
 */
class JsonExporter
{
    public function __construct(
        private readonly int $chunkSize = 500,
        private readonly ?ExportFilter $filter = null,
    ) {}

    /**
     * @param  string        $path
     * @param  string|null   $connection
     * @param  list<string>  $tables
     * @param  callable|null $onTable
     */
    public function export(
        string $path,
        ?string $connection = null,
        array $tables = [],
        ?callable $onTable = null,
    ): void {
        $conn      = DB::connection($connection);
        $inspector = new SchemaInspector();

        if (empty($tables)) {
            $tables = $inspector->listTableNames($conn);
        }

        // Apply skip-tables filter
        if ($this->filter !== null) {
            $skip = $this->filter->getSkipTables();
            if (! empty($skip)) {
                $tables = array_values(array_filter(
                    $tables,
                    fn (string $t) => ! in_array($t, $skip, true),
                ));
            }
        }

        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $fp = fopen($path, 'wb');
        if ($fp === false) {
            throw new \RuntimeException("Cannot open file for writing: {$path}");
        }

        fwrite($fp, "{\n");

        $isFirstTable = true;

        foreach ($tables as $table) {
            if (! $conn->getSchemaBuilder()->hasTable($table)) {
                continue;
            }

            if (! $isFirstTable) {
                fwrite($fp, ",\n");
            }
            $isFirstTable = false;

            fwrite($fp, json_encode($table) . ": [\n");

            $query = $this->buildFilteredQuery($conn, $table);

            $hasId      = $conn->getSchemaBuilder()->hasColumn($table, 'id');
            $isFirstRow = true;
            $rowCount   = 0;

            if ($hasId) {
                $query->orderBy('id')->chunk($this->chunkSize, function ($rows) use ($fp, &$isFirstRow, &$rowCount): void {
                    foreach ($rows as $row) {
                        if (! $isFirstRow) {
                            fwrite($fp, ",\n");
                        }
                        $isFirstRow = false;
                        fwrite($fp, (string) json_encode((array) $row, JSON_UNESCAPED_UNICODE));
                        $rowCount++;
                    }
                });
            } else {
                // Use cursor for memory efficiency instead of get()
                foreach ($query->cursor() as $row) {
                    if (! $isFirstRow) {
                        fwrite($fp, ",\n");
                    }
                    $isFirstRow = false;
                    fwrite($fp, (string) json_encode((array) $row, JSON_UNESCAPED_UNICODE));
                    $rowCount++;
                }
            }

            fwrite($fp, "\n]");

            if ($onTable !== null) {
                $onTable($table, $rowCount);
            }
        }

        fwrite($fp, "\n}\n");
        fclose($fp);
    }

    /**
     * Build a filtered query using the ExportFilter, respecting skip-fields,
     * only-ids, and where-conditions.
     */
    private function buildFilteredQuery(\Illuminate\Database\Connection $conn, string $table): \Illuminate\Database\Query\Builder
    {
        if ($this->filter !== null) {
            $skipFields = $this->filter->getSkipFieldsForTable($table);
            if (! empty($skipFields)) {
                $allColumns = $conn->getSchemaBuilder()->getColumnListing($table);
                $select = array_values(array_diff($allColumns, $skipFields));
                $query = $conn->table($table)->select($select ?: ['*']);
            } else {
                $query = $conn->table($table)->select('*');
            }

            $onlyIds = $this->filter->getOnlyIdsForTable($table);
            if (! empty($onlyIds) && $conn->getSchemaBuilder()->hasColumn($table, 'id')) {
                $query->whereIn('id', $onlyIds);
            }

            foreach ($this->filter->getWhereConditionsForTable($table) as $where) {
                $query->where($where['column'], $where['operator'], $where['value']);
            }
        } else {
            $query = $conn->table($table)->select('*');
        }

        return $query;
    }
}