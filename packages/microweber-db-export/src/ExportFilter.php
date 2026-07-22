<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport;

/**
 * Holds all filtering options for database export/copy operations.
 *
 * Filters are applied at query-build time (before data is fetched), so large
 * tables are never loaded into memory only to discard rows afterwards.
 *
 * Supports:
 *  - skip-tables:       omit entire tables
 *  - skip-fields:       omit specific columns from specific tables (table.column)
 *  - only-ids:          restrict a table to specific row IDs (table.id1,id2,…)
 *  - where-conditions:  arbitrary column filters per table (table.column OP value)
 */
class ExportFilter
{
    /** @var list<string> Tables to skip entirely. */
    private array $skipTables = [];

    /**
     * Columns to skip per table.
     * @var array<string, list<string>>  table => [column, …]
     */
    private array $skipFields = [];

    /**
     * Only include rows with these IDs per table.
     * @var array<string, list<int>>  table => [id, …]
     */
    private array $onlyIds = [];

    /**
     * Arbitrary where-conditions per table.
     * @var array<string, list<array{column: string, operator: string, value: mixed}>>
     */
    private array $whereConditions = [];

    // ──────────────────────────────────────────────────────────────────────
    //  Skip tables
    // ──────────────────────────────────────────────────────────────────────

    /**
     * @param  list<string> $tables
     */
    public function setSkipTables(array $tables): static
    {
        $this->skipTables = $tables;

        return $this;
    }

    public function addSkipTable(string $table): static
    {
        if (! in_array($table, $this->skipTables, true)) {
            $this->skipTables[] = $table;
        }

        return $this;
    }

    /**
     * @return list<string>
     */
    public function getSkipTables(): array
    {
        return $this->skipTables;
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Skip fields
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Parse "table.column" pairs from a flat list.
     *
     * Input:  ["content.title", "cart.session_id"]
     * Result: ["content" => ["title"], "cart" => ["session_id"]]
     *
     * @param  list<string> $pairs
     */
    public function setSkipFields(array $pairs): static
    {
        $this->skipFields = [];

        foreach ($pairs as $pair) {
            $parts = explode('.', $pair, 2);
            if (count($parts) === 2) {
                $this->skipFields[$parts[0]][] = $parts[1];
            }
        }

        return $this;
    }

    public function addSkipField(string $table, string $column): static
    {
        $this->skipFields[$table][] = $column;

        return $this;
    }

    /**
     * @return list<string>
     */
    public function getSkipFieldsForTable(string $table): array
    {
        return $this->skipFields[$table] ?? [];
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Only-IDs
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Parse "table.id1,id2,id3" pairs from a flat list.
     *
     * Input:  ["content.1,2,3", "users.2,4,5"]
     * Result: ["content" => [1,2,3], "users" => [2,4,5]]
     *
     * @param  list<string> $pairs
     */
    public function setOnlyIds(array $pairs): static
    {
        $this->onlyIds = [];

        foreach ($pairs as $pair) {
            $dotPos = strpos($pair, '.');
            if ($dotPos === false) {
                continue;
            }
            $table = substr($pair, 0, $dotPos);
            $ids   = array_map('intval', explode(',', substr($pair, $dotPos + 1)));
            $this->onlyIds[$table] = $ids;
        }

        return $this;
    }

    /**
     * @param  list<int> $ids
     */
    public function addOnlyIds(string $table, array $ids): static
    {
        $this->onlyIds[$table] = array_map('intval', $ids);

        return $this;
    }

    /**
     * @return list<int>
     */
    public function getOnlyIdsForTable(string $table): array
    {
        return $this->onlyIds[$table] ?? [];
    }

    // ──────────────────────────────────────────────────────────────────────
    //  Where-conditions
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Add a where-condition for a specific table.
     * These are applied at query-build time.
     */
    public function addWhere(string $table, string $column, string $operator, mixed $value): static
    {
        $this->whereConditions[$table][] = [
            'column'   => $column,
            'operator' => $operator,
            'value'    => $value,
        ];

        return $this;
    }

    /**
     * @return list<array{column: string, operator: string, value: mixed}>
     */
    public function getWhereConditionsForTable(string $table): array
    {
        return $this->whereConditions[$table] ?? [];
    }

    /**
     * Check if any filters are configured.
     */
    public function hasFilters(): bool
    {
        return ! empty($this->skipTables)
            || ! empty($this->skipFields)
            || ! empty($this->onlyIds)
            || ! empty($this->whereConditions);
    }

    /**
     * Reset all filters.
     */
    public function reset(): static
    {
        $this->skipTables = [];
        $this->skipFields = [];
        $this->onlyIds = [];
        $this->whereConditions = [];

        return $this;
    }
}