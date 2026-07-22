<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport;

use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;

/**
 * Creates tables on a target connection, copies rows in chunked transactions,
 * fixes auto-increment sequences, and re-creates indexes.
 */
class TableCopier
{
    public function __construct(
        private readonly int $chunkSize = 500,
    ) {}

    // ──────────────────────────────────────────────────────────────────────
    //  Create table
    // ──────────────────────────────────────────────────────────────────────

    public function createTable(Connection $conn, string $table, TableMeta $meta): void
    {
        $schema = $conn->getSchemaBuilder();

        if ($schema->hasTable($table)) {
            $schema->drop($table);
        }

        $schema->create($table, function (Blueprint $blueprint) use ($meta) {
            foreach ($meta->columns as $col) {
                if ($col->name === $meta->autoIncrementColumn) {
                    // Auto-increment primary key
                    $this->addAutoIncrementColumn($blueprint, $col);
                    continue;
                }

                $column = $this->addColumn($blueprint, $col);

                if ($col->nullable) {
                    $column->nullable();
                }

                $default = $this->cleanDefault($col->default, $col->type);
                if ($default !== null) {
                    $column->default($default);
                }
            }
        });
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Copy rows
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Copy all rows from source to target in chunked transactions.
     * Works with tables that have an 'id' column and those that don't.
     *
     * @return int Number of rows copied
     */
    public function copyRows(
        Connection $source,
        Connection $target,
        string $table,
        TableMeta $meta,
    ): int {
        $hasId = $meta->hasColumn('id');
        $total = 0;

        if ($hasId) {
            $source->table($table)
                ->orderBy('id')
                ->chunk($this->chunkSize, function ($rows) use ($target, $table, &$total) {
                    $batch = [];
                    foreach ($rows as $row) {
                        $batch[] = (array) $row;
                    }
                    if (! empty($batch)) {
                        $target->transaction(function () use ($target, $table, $batch) {
                            // Insert in sub-chunks to avoid parameter limits
                            foreach (array_chunk($batch, 100) as $subChunk) {
                                $target->table($table)->insert($subChunk);
                            }
                        });
                        $total += count($batch);
                    }
                });
        } else {
            // Tables without id — fetch all, insert in chunks
            $allRows = $source->table($table)->get();
            $batch   = [];

            foreach ($allRows as $row) {
                $batch[] = (array) $row;

                if (count($batch) >= $this->chunkSize) {
                    $target->transaction(function () use ($target, $table, $batch) {
                        foreach (array_chunk($batch, 100) as $subChunk) {
                            $target->table($table)->insert($subChunk);
                        }
                    });
                    $total += count($batch);
                    $batch = [];
                }
            }

            // Remaining rows
            if (! empty($batch)) {
                $target->transaction(function () use ($target, $table, $batch) {
                    foreach (array_chunk($batch, 100) as $subChunk) {
                        $target->table($table)->insert($subChunk);
                    }
                });
                $total += count($batch);
            }
        }

        return $total;
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Fix auto-increment
    // ──────────────────────────────────────────────────────────────────────

    /**
     * After copying rows, set the auto-increment sequence to the correct
     * next value so new inserts don't collide with existing IDs.
     */
    public function fixAutoIncrement(Connection $conn, string $table, TableMeta $meta): void
    {
        if (! $meta->hasAutoIncrement()) {
            return;
        }

        $aiCol = $meta->autoIncrementColumn;
        $maxId = (int) $conn->table($table)->max($aiCol);

        if ($maxId === 0) {
            return;
        }

        $driver = $conn->getDriverName();
        $nextId = $maxId + 1;

        match ($driver) {
            'mysql'  => $conn->statement("ALTER TABLE `{$table}` AUTO_INCREMENT = {$nextId}"),
            'pgsql'  => $conn->statement("SELECT setval(pg_get_serial_sequence('{$table}', '{$aiCol}'), {$maxId})"),
            'sqlite' => $this->fixAutoIncrementSqlite($conn, $table, $maxId),
            default  => null,
        };
    }

    private function fixAutoIncrementSqlite(Connection $conn, string $table, int $maxId): void
    {
        // SQLite tracks auto-increment in sqlite_sequence
        $exists = $conn->select(
            "SELECT name FROM sqlite_master WHERE type='table' AND name='sqlite_sequence'"
        );

        if (empty($exists)) {
            return;
        }

        $row = $conn->selectOne(
            "SELECT seq FROM sqlite_sequence WHERE name = ?",
            [$table],
        );

        if ($row) {
            $conn->update(
                "UPDATE sqlite_sequence SET seq = ? WHERE name = ?",
                [$maxId, $table],
            );
        } else {
            $conn->insert(
                "INSERT INTO sqlite_sequence (name, seq) VALUES (?, ?)",
                [$table, $maxId],
            );
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Create indexes
    // ──────────────────────────────────────────────────────────────────────

    public function createIndexes(Connection $conn, string $table, TableMeta $meta): void
    {
        if (empty($meta->indexes)) {
            return;
        }

        $schema = $conn->getSchemaBuilder();

        $schema->table($table, function (Blueprint $blueprint) use ($meta) {
            foreach ($meta->indexes as $idx) {
                // Generate a safe index name for the target database
                $indexName = $meta->name . '_' . implode('_', $idx->columns) . '_idx';

                if (strlen($indexName) > 64) {
                    $indexName = substr(md5($indexName), 0, 16) . '_idx';
                }

                if ($idx->unique) {
                    $blueprint->unique($idx->columns, $indexName);
                } else {
                    $blueprint->index($idx->columns, $indexName);
                }
            }
        });
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Column helpers
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Clean default values coming from the schema inspector.
     * SQLite/MySQL may wrap numeric defaults in quotes ('1', '0.5').
     */
    private function cleanDefault(mixed $default, string $type): mixed
    {
        if ($default === null) {
            return null;
        }

        $val = (string) $default;

        // Strip surrounding single quotes
        if (strlen($val) >= 2 && $val[0] === "'" && $val[strlen($val) - 1] === "'") {
            $val = substr($val, 1, -1);
        }

        // Handle NULL string
        if (strtoupper($val) === 'NULL') {
            return null;
        }

        // Cast to appropriate PHP type for numeric column types
        $numericTypes = ['integer', 'bigInteger', 'smallInteger', 'tinyInteger', 'mediumInteger', 'boolean'];
        if (in_array($type, $numericTypes, true)) {
            return (int) $val;
        }

        $floatTypes = ['float', 'double', 'decimal'];
        if (in_array($type, $floatTypes, true)) {
            return (float) $val;
        }

        return $val;
    }

    private function addAutoIncrementColumn(Blueprint $blueprint, ColumnMeta $col): void
    {
        $type = strtolower($col->type);

        if (in_array($type, ['bigInteger', 'bigint'], true)) {
            $blueprint->id($col->name);
        } else {
            $blueprint->increments($col->name);
        }
    }

    /**
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    private function addColumn(Blueprint $blueprint, ColumnMeta $col): mixed
    {
        $type = $col->type;

        return match ($type) {
            'increments', 'bigIncrements' => $blueprint->unsignedBigInteger($col->name),
            'integer'      => $blueprint->integer($col->name),
            'bigInteger'   => $blueprint->bigInteger($col->name),
            'smallInteger' => $blueprint->smallInteger($col->name),
            'tinyInteger'  => $blueprint->tinyInteger($col->name),
            'mediumInteger'=> $blueprint->mediumInteger($col->name),
            'float'        => $blueprint->float($col->name),
            'double'       => $blueprint->double($col->name),
            'decimal'      => $blueprint->decimal($col->name),
            'boolean'      => $blueprint->boolean($col->name),
            'string'       => $blueprint->string($col->name, 255),
            'char'         => $blueprint->char($col->name),
            'text'         => $blueprint->text($col->name),
            'mediumText'   => $blueprint->mediumText($col->name),
            'longText'     => $blueprint->longText($col->name),
            'binary'       => $blueprint->binary($col->name),
            'date'         => $blueprint->date($col->name),
            'dateTime'     => $blueprint->dateTime($col->name),
            'dateTimeTz'   => $blueprint->dateTimeTz($col->name),
            'time'         => $blueprint->time($col->name),
            'timeTz'       => $blueprint->timeTz($col->name),
            'json'         => $blueprint->json($col->name),
            'uuid'         => $blueprint->uuid($col->name),
            default        => $blueprint->text($col->name),
        };
    }
}