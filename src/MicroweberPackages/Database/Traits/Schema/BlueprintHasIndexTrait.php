<?php

namespace MicroweberPackages\Database\Traits\Schema;

trait BlueprintHasIndexTrait
{
    protected function makeIndexName($prefix , $table , $type, array $columns)
    {
        $index = strtolower($prefix.$table.''.implode('', $columns).'_'.$type);

        return str_replace(['-', '.'], '_', $index);
    }
}
