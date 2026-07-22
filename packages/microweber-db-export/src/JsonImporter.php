<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport;

use Illuminate\Support\Facades\DB;
use JsonMachine\Items;

/**
 * Imports database tables from a JSON file using streaming (JsonMachine)
 * so arbitrarily large files can be processed without exhausting memory.
 *
 * Expected JSON structure: { "table_name": [ {row}, ... ], ... }
 */
class JsonImporter
{
    public function __construct(
        private readonly int $chunkSize = 500,
    ) {}

    /**
     * @param  string        $path
     * @param  string|null   $connection
     * @param  callable|null $onTable
     */
    public function import(
        string $path,
        ?string $connection = null,
        ?callable $onTable = null,
    ): void {
        if (! is_file($path)) {
            throw new \RuntimeException("Import file not found: {$path}");
        }

        $conn      = DB::connection($connection);
        $inspector = new SchemaInspector();
        $copier    = new TableCopier($this->chunkSize);

        // Stream top-level keys (table names)
        $tables = Items::fromFile($path);

        foreach ($tables as $tableName => $rows) {
            if (! is_string($tableName)) {
                continue;
            }

            // Skip metadata keys
            if (str_starts_with($tableName, '__')) {
                continue;
            }

            // Determine if table exists; if not, try creating from the first row
            $tableExists = $conn->getSchemaBuilder()->hasTable($tableName);

            $batch    = [];
            $rowCount = 0;

            /** @var iterable<mixed, mixed> $rows */
            foreach ($rows as $row) {
                /** @var array<string, mixed> $rowArray */
                $rowArray = (array) $row;

                if (! $tableExists) {
                    $this->createTableFromRow($conn, $tableName, $rowArray);
                    $tableExists = true;
                }

                $batch[] = $rowArray;

                if (count($batch) >= $this->chunkSize) {
                    $this->insertBatch($conn, $tableName, $batch);
                    $rowCount += count($batch);
                    $batch = [];
                }
            }

            if (! empty($batch)) {
                $this->insertBatch($conn, $tableName, $batch);
                $rowCount += count($batch);
            }

            // Fix auto-increment after import
            if ($tableExists) {
                $this->fixAutoIncrementAfterImport($conn, $tableName, $inspector);
            }

            if ($onTable !== null) {
                $onTable($tableName, $rowCount);
            }
        }
    }

    /**
     * @param  array<int, array<string, mixed>> $batch
     */
    private function insertBatch(
        \Illuminate\Database\Connection $conn,
        string $table,
        array $batch,
    ): void {
        $conn->transaction(function () use ($conn, $table, $batch) {
            foreach (array_chunk($batch, 100) as $subChunk) {
                $conn->table($table)->insert($subChunk);
            }
        });
    }

    /**
     * Best-effort table creation from the first row of data when the table
     * doesn't already exist. Infers column types from PHP value types.
     *
     * @param  array<string, mixed> $row
     */
    private function createTableFromRow(
        \Illuminate\Database\Connection $conn,
        string $table,
        array $row,
    ): void {
        $conn->getSchemaBuilder()->create($table, function ($blueprint) use ($row) {
            foreach ($row as $col => $value) {
                if ($col === 'id') {
                    $blueprint->increments('id');
                    continue;
                }

                match (true) {
                    is_int($value)   => $blueprint->integer($col)->nullable(),
                    is_float($value) => $blueprint->double($col)->nullable(),
                    is_bool($value)  => $blueprint->boolean($col)->nullable(),
                    default          => $blueprint->longText($col)->nullable(),
                };
            }
        });
    }

    private function fixAutoIncrementAfterImport(
        \Illuminate\Database\Connection $conn,
        string $table,
        SchemaInspector $inspector,
    ): void {
        try {
            $meta = $inspector->inspectTable($conn, $table);
            (new TableCopier($this->chunkSize))->fixAutoIncrement($conn, $table, $meta);
        } catch (\Throwable) {
            // Non-critical — the import itself succeeded
        }
    }
}