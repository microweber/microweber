<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class BaseModel extends Eloquent
{
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'updated_on';

    public $default_limit = 30;



    public static $cache = true;

    public $table_cache_ttl = 60;
    private $filter_keys = ['id', 'module', 'type'];
    protected $guarded = array();

    private $useCache = true;

    public static $cacheTables = [];

    public $default_filters = [
        'id',
        'limit',
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
        'fields',


    ];

    private static $custom_filters = [];

    public static function custom_filter($name, $callback)
    {
        self::$custom_filters[$name] = $callback;
    }

    /*function __construct() {
    }*/

    protected static function boot()
    {
        parent::boot();

        // $table = app()->make(get_called_class())->table;
        //static::$cacheTables[$table] = true;

        // static::observe(new BaseModelObserver);
    }

    public function scopeItems($query, $params = false)
    {
        $table = $this->table;
        if (is_string($params)) {
            $params = parse_params($params);
        }
        $orig_params = $params;
        $items_per_page = false;
        if (isset($orig_params['count_paging']) and ($orig_params['count_paging'])) {
            if (isset($params['limit'])) {
                $items_per_page = $params['limit'];
                unset($params['limit']);
            }
            if (isset($params['page'])) {
                unset($params['page']);
            }
            $orig_params['count'] = true;
        }


        $query = $this->map_filters($query, $params);
        $params = $this->map_array_to_table($table, $params);
        $query = $this->map_values_to_query($query, $params);


        if ($params !== false) {
            $query = $query->where($params);
        }


        if (isset($orig_params['count']) and ($orig_params['count'])) {
            $query = $query->count();
            if ($items_per_page != false) {
                $query = intval(ceil($query / $items_per_page));
            }
            return $query;
        }
        if (isset($orig_params['min']) and ($orig_params['min'])) {
            $column = $orig_params['min'];
            $query = $query->min($column);
            return $query;
        }
        if (isset($orig_params['max']) and ($orig_params['max'])) {
            $column = $orig_params['max'];
            $query = $query->max($column);
            return $query;
        }if (isset($orig_params['avg']) and ($orig_params['avg'])) {
            $column = $orig_params['avg'];
            $query = $query->avg($column);
            return $query;
        }


        $data = $query->get();
        $empty = $data->isEmpty();

        if ($empty == true) {

            return false;
        }


        $data = $data->toArray();

        if (!is_array($data)) {
            return false;
        }

        if (isset($orig_params['single']) || isset($orig_params['one'])) {
            if (!isset($data[0])) {
                return false;
            }
            return $data[0];
        }


        return $data;
    }


    public function get_items($params)
    {
        return self::items($params);
    }

    public function save_item($params)
    {
        $table = $this->table;
        if (is_string($params)) {
            $params = parse_params($params);
        }
        if (!isset($params['created_on']) == false) {
            $params['created_on'] = date("Y-m-d H:i:s");
        }
        if (!isset($params['updated_on']) == false) {
            $params['updated_on'] = date("Y-m-d H:i:s");
        }
        $id = false;
        if (isset($params['id'])) {
            $id = $params['id'];
        }


        $params = $this->map_array_to_table($table, $params);
        $id_to_return = false;
        if ($id) {
            unset($params['created_on']);
            $save = self::find($id)->update($params);
            $id_to_return = intval($id);
        } else {
            $save = self::insert($params);

            $id_to_return = DB::getPdo()->lastInsertId();


        }
        return ($id_to_return);


    }


    public function map_filters($query, $params)
    {

        if(!isset($params['limit'])){
            $params['limit'] = $this->default_limit;
        }


        foreach ($params as $filter => $value) {
            switch ($filter) {
                case 'order_by':
                    $criteria = explode(',', $value);
                    foreach ($criteria as $c) {
                        $c = explode(' ', $c);
                        if (isset($c[1])) {
                            $query = $query->orderBy($c[0], $c[1]);
                        } else if (isset($c[0])) {
                            $query = $query->orderBy($c[0]);
                        }
                    }
                    break;
                case 'limit':
                    $criteria = intval($value);
                    $query = $query->take($criteria);
                    break;


                case 'current_page':
                $criteria = intval($value);
                $query = $query->skip($criteria);
                break;

                case 'id':
                    $criteria = trim($value);

                    $query = $query->where('id', $criteria);
                    break;

                case 'no_cache':
                    $this->useCache = false;
                    break;


            }


        }


        foreach (self::$custom_filters as $name => $callback) {
            if (!isset($params[$name])) {
                continue;
            }
            call_user_func_array($callback, [$query, $params[$name]]);
        }
        return $query;
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

    public function map_values_to_query($query, &$params)
    {
        foreach ($params as $column => $value) {


            switch ($value) {
                case '[not_null]':
                    $query->whereNotNull($column);
                    unset($params[$column]);
                    break;

                case '[null]':
                    $query->whereNull($column);
                    unset($params[$column]);
                    break;
            }

            if (stristr($value, '[lt]')) {
                $value = str_replace('[lt]', '', $value);
                $query = $query->where($column, '<', $value);
                unset($params[$column]);

            } else if (stristr($value, '[lte]')) {
                $two_chars = '<=';
                $value = str_replace('[lte]', '', $value);
            } else if (stristr($value, '[st]')) {
                $one_char = '<';
                $value = str_replace('[st]', '', $value);
            } else if (stristr($value, '[ste]')) {
                $two_chars = '<=';
                $value = str_replace('[ste]', '', $value);
            } else if (stristr($value, '[gt]')) {
                $one_char = '>';
                $value = str_replace('[gt]', '', $value);
            } else if (stristr($value, '[gte]')) {
                $two_chars = '>=';
                $value = str_replace('[gte]', '', $value);
            } else if (stristr($value, '[mt]')) {
                $one_char = '>';
                $value = str_replace('[mt]', '', $value);
            } else if (stristr($value, '[md]')) {
                $one_char = '>';
                $value = str_replace('[md]', '', $value);
            } else if (stristr($value, '[mte]')) {
                $two_chars = '>=';
                $value = str_replace('[mte]', '', $value);
            } else if (stristr($value, '[mde]')) {
                $two_chars = '>=';
                $value = str_replace('[mde]', '', $value);
            } else if (stristr($value, '[neq]')) {
                $two_chars = '!=';
                $value = str_replace('[neq]', '', $value);
            } else if (stristr($value, '[eq]')) {
                $one_char = '=';
                $value = str_replace('[eq]', '', $value);
            } else if (stristr($value, '[int]')) {
                $value = str_replace('[int]', '', $value);
            } else if (stristr($value, '[is]')) {
                $one_char = '=';
                $value = str_replace('[is]', '', $value);
            } else if (stristr($value, '[like]')) {
                $two_chars = '%';
                $value = str_replace('[like]', '', $value);
            } else if (stristr($value, '[null]')) {
                $value = 'is_null';
            } else if (stristr($value, '[not_null]')) {
                $value = 'is_not_null';
            } else if (stristr($value, '[is_not]')) {
                $two_chars = '!%';
                $value = str_replace('[is_not]', '', $value);
            }

        }


        return $query;
    }


    public function get_fields($table)
    {
        $value = Cache::remember('model.columns.' . $table, $this->table_cache_ttl, function () use ($table) {
            return DB::connection()->getSchemaBuilder()->getColumnListing($table);
        });
        return $value;

    }


}

