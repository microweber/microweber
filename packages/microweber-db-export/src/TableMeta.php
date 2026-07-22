<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport;

/**
 * Describes the schema of a single database table.
 */
class TableMeta
{
    /**
     * @param  string            $name
     * @param  list<ColumnMeta>  $columns
     * @param  string|null       $autoIncrementColumn
     * @param  list<IndexMeta>   $indexes
     */
    public function __construct(
        public readonly string  $name,
        public readonly array   $columns,
        public readonly ?string $autoIncrementColumn,
        public readonly array   $indexes,
    ) {}

    public function hasColumn(string $name): bool
    {
        foreach ($this->columns as $col) {
            if ($col->name === $name) {
                return true;
            }
        }

        return false;
    }

    public function hasAutoIncrement(): bool
    {
        return $this->autoIncrementColumn !== null;
    }

    /**
     * @return list<string>
     */
    public function columnNames(): array
    {
        return array_map(fn (ColumnMeta $c) => $c->name, $this->columns);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name'    => $this->name,
            'columns' => array_map(fn (ColumnMeta $c) => $c->toArray(), $this->columns),
            'auto_increment_column' => $this->autoIncrementColumn,
            'indexes' => array_map(fn (IndexMeta $i) => $i->toArray(), $this->indexes),
        ];
    }
}