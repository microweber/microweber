<?php
namespace Microweber\Database;

use Illuminate\Database\Connection as BaseConnection;
use \Cache;
use \Config;


class Connection extends BaseConnection
{
    public function select($query, $bindings = array())
    {
        return parent::select($query, $bindings);

        $cacheTables = array_keys(\BaseModel::$cacheTables);
        $table = false;
        preg_match('/from `\w*('. implode('|', $cacheTables) .')`/', $query, $table);

        if(empty($cacheTables) or !$table)
        {
            return parent::select($query, $bindings);
        }

        $key = crc32($query . implode($bindings));

        $data = Cache::get($key, function() use ($key, $query, $bindings) {
            $result = parent::select($query, $bindings);
    		Cache::put($key, $result, 10);
    		//var_dump('Cached: '. $query);
    		return $result;
        });

        return $data;
    }
    
    public function selectOne($query, $bindings = array())
    {
        $records = $this->select($query, $bindings);
        
        if (count($records) > 0)
        {
            return reset($records);
        }
        
        return null;
    }
}
