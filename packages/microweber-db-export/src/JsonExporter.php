<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport;

use Illuminate\Support\Facades\DB;

/**
 * Exports database tables to a streaming JSON file.
 *
 * The JSON structure is: { "table_name": [ {row}, ... ], ... }
 * Written incrementally so the whole dataset is never in memory at once.
 */
class JsonExporter
{
    public function __construct(
        private readonly int $chunkSize = 500,
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

            // Write table key
            fwrite($fp, json_encode($table) . ": [\n");

            $hasId      = $conn->getSchemaBuilder()->hasColumn($table, 'id');
            $query      = $conn->table($table)->select('*');
            $isFirstRow = true;
            $rowCount   = 0;

            if ($hasId) {
                $query->orderBy('id')->chunk($this->chunkSize, function ($rows) use ($fp, &$isFirstRow, &$rowCount) {
                    foreach ($rows as $row) {
                        if (! $isFirstRow) {
                            fwrite($fp, ",\n");
                        }
                        $isFirstRow = false;
                        fwrite($fp, json_encode((array) $row, JSON_UNESCAPED_UNICODE));
                        $rowCount++;
                    }
                });
            } else {
                foreach ($query->get() as $row) {
                    if (! $isFirstRow) {
                        fwrite($fp, ",\n");
                    }
                    $isFirstRow = false;
                    fwrite($fp, json_encode((array) $row, JSON_UNESCAPED_UNICODE));
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
}