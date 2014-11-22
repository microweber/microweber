<?php

namespace Microweber\Utils;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Database
{
    public function build_table($table_name, $fields_to_add)
    {

        if (!Schema::hasTable($table_name)) {
            Schema::create($table_name, function ($table) {
                $table->increments('id');
            });
        }

        foreach ($fields_to_add as $name => $type) {
            if (!Schema::hasColumn($table_name, $name)) {
                Schema::table($table_name, function ($table) use ($name, $type) {
                    $table->$type($name);
                });
            }
        }
    }
}
