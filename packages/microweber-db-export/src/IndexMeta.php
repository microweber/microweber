<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport;

/**
 * Describes a single index on a database table.
 */
class IndexMeta
{
    /**
     * @param  string       $name
     * @param  list<string> $columns
     * @param  bool         $unique
     */
    public function __construct(
        public readonly string $name,
        public readonly array  $columns,
        public readonly bool   $unique = false,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name'    => $this->name,
            'columns' => $this->columns,
            'unique'  => $this->unique,
        ];
    }
}