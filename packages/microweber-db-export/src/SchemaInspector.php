<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport;

use Illuminate\Database\Connection;

/**
 * Introspects a database connection to discover tables, columns,
 * auto-increment fields, and indexes.
 *
 * Works across SQLite, MySQL, and PostgreSQL.
 */
class SchemaInspector
{
    /**
     * @return list<string> Table names without system tables.
     */
    public function listTableNames(Connection $conn): array
    {
        $tables = [];
        $skip = [
            'sqlite_sequence', 'sqlite_master',
            'information_schema', 'pg_catalog',
        ];

        foreach ($conn->getSchemaBuilder()->getTables() as $info) {
            $name = $info['name'] ?? '';
            if ($name === '' || in_array($name, $skip, true)) {
                continue;
            }
            $tables[] = $name;
        }

        return $tables;
    }

    /**
     * Full table metadata: columns, their types, nullable flags,
     * auto-increment column (if any), and indexes.
     *
     * @return TableMeta
     */
    public function inspectTable(Connection $conn, string $table): TableMeta
    {
        $driver  = $this->driverName($conn);
        $columns = $this->getColumns($conn, $table, $driver);
        $autoInc = $this->detectAutoIncrement($conn, $table, $driver, $columns);
        $indexes = $this->getIndexes($conn, $table);

        return new TableMeta(
            name: $table,
            columns: $columns,
            autoIncrementColumn: $autoInc,
            indexes: $indexes,
        );
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Columns
    // ──────────────────────────────────────────────────────────────────────

    /**
     * @return list<ColumnMeta>
     */
    private function getColumns(Connection $conn, string $table, string $driver): array
    {
        $schemaColumns = $conn->getSchemaBuilder()->getColumns($table);
        $cols = [];

        foreach ($schemaColumns as $col) {
            $cols[] = new ColumnMeta(
                name:     $col['name'],
                type:     $this->normalizeType($col['type_name'] ?? $col['type'] ?? 'string', $driver),
                nullable: (bool) ($col['nullable'] ?? true),
                default:  $col['default'] ?? null,
                autoIncrement: (bool) ($col['auto_increment'] ?? false),
            );
        }

        return $cols;
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Auto-increment detection
    // ──────────────────────────────────────────────────────────────────────

    private function detectAutoIncrement(
        Connection $conn,
        string $table,
        string $driver,
        array $columns,
    ): ?string {
        // First, check if the schema builder already told us
        foreach ($columns as $col) {
            if ($col->autoIncrement) {
                return $col->name;
            }
        }

        return match ($driver) {
            'sqlite' => $this->detectAutoIncrementSqlite($conn, $table),
            'mysql'  => $this->detectAutoIncrementMysql($conn, $table),
            'pgsql'  => $this->detectAutoIncrementPgsql($conn, $table),
            default  => null,
        };
    }

    private function detectAutoIncrementSqlite(Connection $conn, string $table): ?string
    {
        // In SQLite, INTEGER PRIMARY KEY is auto-increment (ROWID alias).
        $rows = $conn->select("PRAGMA table_info('{$table}')");

        foreach ($rows as $row) {
            $row = (array) $row;
            if (($row['pk'] ?? 0) == 1
                && stripos($row['type'] ?? '', 'int') !== false
            ) {
                return $row['name'];
            }
        }

        return null;
    }

    private function detectAutoIncrementMysql(Connection $conn, string $table): ?string
    {
        $dbName = $conn->getDatabaseName();
        $rows   = $conn->select(
            "SELECT COLUMN_NAME
             FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND EXTRA LIKE '%auto_increment%'",
            [$dbName, $table],
        );

        return count($rows) > 0 ? ((array) $rows[0])['COLUMN_NAME'] : null;
    }

    private function detectAutoIncrementPgsql(Connection $conn, string $table): ?string
    {
        $rows = $conn->select(
            "SELECT column_name, column_default
             FROM information_schema.columns
             WHERE table_name = ? AND column_default LIKE 'nextval%'",
            [$table],
        );

        return count($rows) > 0 ? ((array) $rows[0])['column_name'] : null;
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Indexes
    // ──────────────────────────────────────────────────────────────────────

    /**
     * @return list<IndexMeta>
     */
    private function getIndexes(Connection $conn, string $table): array
    {
        $indexes = [];

        try {
            $rawIndexes = $conn->getSchemaBuilder()->getIndexes($table);
        } catch (\Throwable) {
            return [];
        }

        foreach ($rawIndexes as $idx) {
            $name    = $idx['name'] ?? '';
            $columns = $idx['columns'] ?? [];
            $unique  = (bool) ($idx['unique'] ?? false);
            $primary = (bool) ($idx['primary'] ?? false);

            // Skip primary key — it's created with the column
            if ($primary) {
                continue;
            }

            if (empty($columns)) {
                continue;
            }

            $indexes[] = new IndexMeta(
                name:    $name,
                columns: $columns,
                unique:  $unique,
            );
        }

        return $indexes;
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Helpers
    // ──────────────────────────────────────────────────────────────────────

    private function driverName(Connection $conn): string
    {
        return $conn->getDriverName();
    }

    /**
     * Normalise database-specific type names to Blueprint-compatible types.
     */
    private function normalizeType(string $raw, string $driver): string
    {
        $raw = strtolower(trim($raw));

        // Map of common DB-specific types → Blueprint method names
        $map = [
            'int'              => 'integer',
            'int4'             => 'integer',
            'int8'             => 'bigInteger',
            'integer'          => 'integer',
            'bigint'           => 'bigInteger',
            'smallint'         => 'smallInteger',
            'tinyint'          => 'tinyInteger',
            'mediumint'        => 'mediumInteger',
            'serial'           => 'integer',
            'bigserial'        => 'bigInteger',
            'float'            => 'float',
            'float4'           => 'float',
            'float8'           => 'double',
            'double'           => 'double',
            'double precision' => 'double',
            'real'             => 'float',
            'decimal'          => 'decimal',
            'numeric'          => 'decimal',
            'bool'             => 'boolean',
            'boolean'          => 'boolean',
            'varchar'          => 'string',
            'character varying'=> 'string',
            'char'             => 'char',
            'character'        => 'char',
            'bpchar'           => 'char',
            'text'             => 'text',
            'mediumtext'       => 'mediumText',
            'longtext'         => 'longText',
            'tinytext'         => 'string',
            'blob'             => 'binary',
            'mediumblob'       => 'binary',
            'longblob'         => 'binary',
            'bytea'            => 'binary',
            'date'             => 'date',
            'datetime'         => 'dateTime',
            'timestamp'        => 'dateTime',
            'timestamp without time zone' => 'dateTime',
            'timestamp with time zone'    => 'dateTimeTz',
            'timestamptz'      => 'dateTimeTz',
            'time'             => 'time',
            'time without time zone' => 'time',
            'time with time zone'    => 'timeTz',
            'timetz'           => 'timeTz',
            'json'             => 'json',
            'jsonb'            => 'json',
            'uuid'             => 'uuid',
            'inet'             => 'string',
            'cidr'             => 'string',
            'macaddr'          => 'string',
            'enum'             => 'string',
            'set'              => 'string',
            'year'             => 'integer',
        ];

        return $map[$raw] ?? 'text';
    }
}