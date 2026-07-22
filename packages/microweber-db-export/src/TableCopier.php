<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport;

use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Schema\Blueprint;

/**
 * Creates tables on a target connection, copies rows in chunked transactions,
 * fixes auto-increment sequences, and re-creates indexes.
 *
 * All queries use the DB facade and Query Builder — never raw SQL with
 * hard-coded table names — so table prefixes configured in Laravel work
 * correctly.
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
     * Copy rows from source to target in chunked transactions.
     *
     * For tables with an 'id' column, uses chunk() which pages
     * efficiently by primary key.  For tables without an id column, uses
     * cursor() to stream rows one-at-a-time — never loading the full
     * result set into memory.
     *
     * An optional pre-built $query can be passed (already filtered by
     * DbExportManager); if null the full table is read.
     *
     * @return int Number of rows copied
     */
    public function copyRows(
        Connection $source,
        Connection $target,
        string $table,
        TableMeta $meta,
        ?Builder $query = null,
    ): int {
        $hasId = $meta->hasColumn('id');
        $total = 0;

        $baseQuery = $query ?? $source->table($table)->select('*');

        if ($hasId) {
            $baseQuery
                ->orderBy('id')
                ->chunk($this->chunkSize, function ($rows) use ($target, $table, &$total): void {
                    $batch = [];
                    foreach ($rows as $row) {
                        $batch[] = (array) $row;
                    }
                    if (! empty($batch)) {
                        $this->insertBatch($target, $table, $batch);
                        $total += count($batch);
                    }
                });
        } else {
            // Tables without id — use cursor() for memory efficiency
            $batch = [];

            foreach ($baseQuery->cursor() as $row) {
                $batch[] = (array) $row;

                if (count($batch) >= $this->chunkSize) {
                    $this->insertBatch($target, $table, $batch);
                    $total += count($batch);
                    $batch = [];
                }
            }

            // Remaining rows
            if (! empty($batch)) {
                $this->insertBatch($target, $table, $batch);
                $total += count($batch);
            }
        }

        return $total;
    }

    /**
     * Insert a batch of rows inside a transaction, splitting into sub-chunks
     * to avoid hitting the SQL parameter limit.
     *
     * @param  array<int, array<string, mixed>> $batch
     */
    private function insertBatch(Connection $conn, string $table, array $batch): void
    {
        $conn->transaction(function () use ($conn, $table, $batch): void {
            foreach (array_chunk($batch, 100) as $subChunk) {
                $conn->table($table)->insert($subChunk);
            }
        });
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Fix auto-increment
    // ──────────────────────────────────────────────────────────────────────

    /**
     * After copying rows, set the auto-increment sequence to the correct
     * next value so new inserts don't collide with existing IDs.
     *
     * All operations use the DB Query Builder or parameterised statements
     * with the connection's table prefix so prefixed setups don't break.
     */
    public function fixAutoIncrement(Connection $conn, string $table, TableMeta $meta): void
    {
        if (! $meta->hasAutoIncrement()) {
            return;
        }

        $aiCol = $meta->autoIncrementColumn ?? 'id';
        /** @var int|float|string|null $rawMax */
        $rawMax = $conn->table($table)->max($aiCol);
        $maxId = (int) $rawMax;

        if ($maxId === 0) {
            return;
        }

        $driver = $conn->getDriverName();
        $nextId = $maxId + 1;

        // The prefixed table name is needed for raw DDL statements
        $prefixedTable = $conn->getTablePrefix() . $table;

        match ($driver) {
            'mysql'  => $this->fixAutoIncrementMysql($conn, $prefixedTable, $nextId),
            'pgsql'  => $this->fixAutoIncrementPgsql($conn, $prefixedTable, $aiCol, $maxId),
            'sqlite' => $this->fixAutoIncrementSqlite($conn, $table, $maxId),
            default  => null,
        };
    }

    private function fixAutoIncrementMysql(Connection $conn, string $prefixedTable, int $nextId): void
    {
        // Use backtick-quoted identifiers for safety
        $conn->statement(
            "ALTER TABLE `{$prefixedTable}` AUTO_INCREMENT = {$nextId}"
        );
    }

    private function fixAutoIncrementPgsql(Connection $conn, string $prefixedTable, string $aiCol, int $maxId): void
    {
        // pg_get_serial_sequence accepts the full (prefixed) table name
        $conn->statement(
            "SELECT setval(pg_get_serial_sequence(?, ?), ?)",
            [$prefixedTable, $aiCol, $maxId],
        );
    }

    private function fixAutoIncrementSqlite(Connection $conn, string $table, int $maxId): void
    {
        // SQLite tracks auto-increment in sqlite_sequence.
        // The table prefix is NOT used in sqlite_sequence entries — SQLite
        // stores the name as given to CREATE TABLE, and Laravel's schema
        // builder already includes the prefix, so we use the raw $table.
        $prefixedTable = $conn->getTablePrefix() . $table;

        $exists = $conn->select(
            "SELECT name FROM sqlite_master WHERE type='table' AND name='sqlite_sequence'"
        );

        if (empty($exists)) {
            return;
        }

        $row = $conn->selectOne(
            'SELECT seq FROM sqlite_sequence WHERE name = ?',
            [$prefixedTable],
        );

        if ($row) {
            $conn->update(
                'UPDATE sqlite_sequence SET seq = ? WHERE name = ?',
                [$maxId, $prefixedTable],
            );
        } else {
            $conn->insert(
                'INSERT INTO sqlite_sequence (name, seq) VALUES (?, ?)',
                [$maxId, $prefixedTable],
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
        $usedNames = [];

        // Create each index individually — if one fails (e.g. BLOB/TEXT column
        // without key length on MySQL), the others still get created.
        foreach ($meta->indexes as $idx) {
            $indexName = $meta->name . '_' . implode('_', $idx->columns) . '_idx';

            if (strlen($indexName) > 64) {
                $indexName = substr(md5($indexName), 0, 16) . '_idx';
            }

            // Ensure uniqueness by appending a counter if needed
            $baseName = $indexName;
            $counter = 2;
            while (in_array($indexName, $usedNames, true)) {
                $indexName = $baseName . '_' . $counter;
                $counter++;
            }
            $usedNames[] = $indexName;

            try {
                $idxName = $indexName; // capture for closure
                $idxMeta = $idx;

                $schema->table($table, function (Blueprint $blueprint) use ($idxMeta, $idxName): void {
                    if ($idxMeta->unique) {
                        $blueprint->unique($idxMeta->columns, $idxName);
                    } else {
                        $blueprint->index($idxMeta->columns, $idxName);
                    }
                });
            } catch (\Throwable) {
                // Skip indexes that cannot be created — common reasons:
                //  - duplicate index names
                //  - BLOB/TEXT columns without a key length (MySQL)
                //  - column type incompatibilities across drivers
            }
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Column helpers
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Clean default values coming from the schema inspector.
     *
     * Handles multiple driver quirks:
     *  - SQLite/MySQL may wrap numeric defaults in quotes ('1', '0.5')
     *  - PostgreSQL appends type casts ('general'::character varying)
     *  - Various NULL representations
     */
    private function cleanDefault(mixed $default, string $type): mixed
    {
        if ($default === null) {
            return null;
        }

        $val = is_scalar($default) ? (string) $default : '';

        // Strip PostgreSQL type casts: 'value'::type  or  value::type
        if (str_contains($val, '::')) {
            $val = (string) preg_replace('/::[\w\s]+$/', '', $val);
        }

        // Strip surrounding single quotes
        if (strlen($val) >= 2 && $val[0] === "'" && $val[strlen($val) - 1] === "'") {
            $val = substr($val, 1, -1);
        }

        // Handle NULL string and PostgreSQL NULL::type
        if ($val === '' || strtoupper($val) === 'NULL') {
            return null;
        }

        // Strip PostgreSQL function calls like nextval(...)
        if (str_starts_with($val, 'nextval(')) {
            return null;
        }

        // Handle CURRENT_TIMESTAMP and similar SQL expressions — these are
        // not literal string defaults, they're SQL expressions that the
        // Blueprint handles via useCurrent() / useCurrentOnUpdate().
        // Returning them as string defaults would fail on MySQL.
        $sqlExpressions = ['CURRENT_TIMESTAMP', 'CURRENT_DATE', 'CURRENT_TIME', 'NOW()'];
        if (in_array(strtoupper($val), $sqlExpressions, true)) {
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

        if (in_array($type, ['biginteger', 'bigint'], true)) {
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
        return match ($col->type) {
            'increments', 'bigIncrements' => $blueprint->unsignedBigInteger($col->name),
            'integer'      => $blueprint->integer($col->name),
            'bigInteger'   => $blueprint->bigInteger($col->name),
            'smallInteger' => $blueprint->smallInteger($col->name),
            'tinyInteger'  => $blueprint->tinyInteger($col->name),
            'mediumInteger' => $blueprint->mediumInteger($col->name),
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