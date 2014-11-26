<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/23/14
 * Time: 11:56 PM
 */


use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 *
 * @property BaseModel $where(string $column, string $operator = null, mixed $value = null, string $boolean = 'and')
 *
 */
class BaseModel extends Eloquent
{
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'updated_on';

    public $table_cache_ttl = 60;
    private $filter_keys = ['id', 'module', 'type'];
    public $default_filters = [
        'single',
        'order_by',
        'min',
        'max',
        'avg',
        'exclude_ids',
        'ids',
        'current_page',
        'count_paging',
        'to_search_in_fields',
        'to_search_keyword',
        'fields'
    ];

    private static $custom_filters = [];
    public static function custom_filter($name, $callback)
    {
        self::$custom_filters[$name] = $callback;
    }

    public function __construct()
    {
    }

    public function scopeItems($query, $params)
    {
        $table = $this->table;
        if (is_string($params)) {
            $params = parse_params($params);
        }

        $cf = array_flip($this->default_filters);
        foreach ($cf as $k => &$v) {
            $v = isset($params[$k]);
        }

        foreach ($cf as $filter => $enabled) {
            if (!$enabled)
                continue;

            switch ($filter) {
                case 'order_by':
                    $criteria = explode(',', $params['order_by']);
                    foreach ($criteria as $c) {
                        $c = explode(' ', $c);
                        if (isset($c[1])) {
                            $query = $query->orderBy($c[0], $c[1]);
                        } else if (isset($c[0])) {
                            $query = $query->orderBy($c[0]);
                        }
                    }
                    break;
            }
        }
        foreach (self::$custom_filters as $name => $callback) {
            if(!isset($params[$name])) {
                continue;
            }


            call_user_func_array($callback, [$query,$params[$name]]);
        }

        $params = $this->map_array_to_table($table, $params);

        if ($params !== false) {
            $query = $query->where($params);
        }

        $data = $query->get()->toArray();

        if ($cf['single']) {
            return $data[0];
        }


        return $data;
    }

    public function map_array_to_table($table, $array)
    {

        $r = $this->get_fields($table);
        $r = array_diff($r, $this->filter_keys);

        $r = array_intersect($r, array_keys($array));
        $r = array_flip($r);
        $r = array_intersect_key($array, $r);
        return $r;
    }


    public function get_fields($table)
    {
        $value = Cache::remember('model.columns.'.$table, $this->table_cache_ttl, function() use ($table)
        {
            return DB::connection()->getSchemaBuilder()->getColumnListing($table);
        });
        return $value;

    }




}

