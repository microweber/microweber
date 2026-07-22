<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport;

/**
 * Describes a single column in a database table.
 */
class ColumnMeta
{
    public function __construct(
        public readonly string  $name,
        public readonly string  $type,
        public readonly bool    $nullable = true,
        public readonly mixed   $default = null,
        public readonly bool    $autoIncrement = false,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name'           => $this->name,
            'type'           => $this->type,
            'nullable'       => $this->nullable,
            'default'        => $this->default,
            'auto_increment' => $this->autoIncrement,
        ];
    }
}