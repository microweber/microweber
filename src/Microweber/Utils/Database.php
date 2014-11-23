<?php

namespace Microweber\Utils;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Database
{
    public $cache_minutes = 60;


    public function build_table($table_name, $fields_to_add)
    {
        $key = 'mw_build_table_' . $table_name . crc32(serialize($fields_to_add));
        $value = Cache::get($key);
        if (empty($value)) {
            $val = $fields_to_add;
            $minutes = $this->cache_minutes;
            $expiresAt = Carbon::now()->addMinutes($minutes);
            $cache = Cache::add($key, 1, $expiresAt);
            $this->_exec_table_builder($table_name, $fields_to_add);
        }
    }

    public function add_table_index(){
        //@tbd
    }
    private function _exec_table_builder($table_name, $fields_to_add)
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
