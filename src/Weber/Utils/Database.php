<?php

namespace Weber\Utils;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class Database
{
    public $cache_minutes = 60;


    public function build_table($table_name, $fields_to_add)
    {

        $key = 'mw_build_table';
        $hash = $table_name . crc32(serialize($fields_to_add));
        $value = Cache::get($key);

        if (!isset($value[$hash])) {
            $val = $fields_to_add;
            $minutes = $this->cache_minutes;
            $expiresAt = Carbon::now()->addMinutes($minutes);
            $value[$hash] = 1;
            $cache = Cache::put($key, $value, $expiresAt);
            $this->_exec_table_builder($table_name, $fields_to_add);
        }
    }

    public function add_table_index()
    {
        //@todo
    }

    private function _exec_table_builder($table_name, $fields_to_add)
    {
        $table_name = $this->assoc_table_name($table_name);

        if (!Schema::hasTable($table_name)) {

            Schema::create($table_name, function ($table) {
                $table->increments('id');
            });
        }
        if (is_array($fields_to_add)) {
            foreach ($fields_to_add as $name => $type) {

                if(is_array($type)){
                    $name = array_shift($type);
                    $type = array_shift($type);
                }

                if (!Schema::hasColumn($table_name, $name)) {
                    Schema::table($table_name, function ($table) use ($name, $type) {
                        $table->$type($name);
                    });
                }
            }
        }
    }


    public function assoc_table_name($assoc_name)
    {

        if ($this->table_prefix == false) {
            $this->table_prefix = Config::get('database.connections.mysql.prefix');
        }
        $assoc_name_o = $assoc_name;
        $assoc_name = str_ireplace($this->table_prefix, '', $assoc_name);
        return $assoc_name;
    }

    public $table_prefix;

    public function real_table_name($assoc_name)
    {

        $assoc_name_new = $assoc_name;


        if ($this->table_prefix == false) {
            $this->table_prefix = Config::get('database.connections.mysql.prefix');
        }


        if ($this->table_prefix != false) {
            $assoc_name_new = str_ireplace('table_', $this->table_prefix, $assoc_name_new);
        }

        $assoc_name_new = str_ireplace('table_', $this->table_prefix, $assoc_name_new);
        $assoc_name_new = str_ireplace($this->table_prefix . $this->table_prefix, $this->table_prefix, $assoc_name_new);

        if ($this->table_prefix and $this->table_prefix != '' and stristr($assoc_name_new, $this->table_prefix) == false) {
            $assoc_name_new = $this->table_prefix . $assoc_name_new;
        }

        return $assoc_name_new;
    }
}
