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
        'fields'
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
        $filters = array();
        $cf = array_flip($this->default_filters);
        foreach ($cf as $k => $v) {
            $enabled = isset($params[$k]);
            if($enabled){
                $filters[$k] = $params[$k];
            }
        }

        foreach ($filters as $filter => $enabled) {
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
                case 'limit':
                    $criteria = intval($params['limit']);
                    $query = $query->take($criteria);
                    break;

                case 'id':
                    $criteria = trim($params['id']);

                    $query = $query->where('id', $criteria);
                    break;
                case 'min':
                    $criteria = trim($params['min']);

                    $query = $query->min($criteria);
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

        $params = $this->map_array_to_table($table, $params);

        if ($params !== false) {
            $query = $query->where($params);
        }
        $data = $query->get();
        $empty = $data->isEmpty();

        if ($empty == true) {

            return null;
        }
        $new_data = array();
        if (!empty($data)) {
            $i = 0;

            foreach ($data as $item) {
                $item = $item->toArray();
                $new_data[$i] = $item;
                $i++;
            }
        }

        //$data = $data->toArray();
        $data = $new_data;

        if (isset($orig_params['single']) || isset($orig_params['one'])) {

            if (!isset($data[0])) {
                return false;
            }
            return $data[0];
        }


        return $data;
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

        if ($id) {
            unset($params['created_on']);
            $save = self::find($id)->update($params);
        } else {
            $save = self::insert($params);

        }
        return ($save);


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
        $value = Cache::remember('model.columns.' . $table, $this->table_cache_ttl, function () use ($table) {
            return DB::connection()->getSchemaBuilder()->getColumnListing($table);
        });
        return $value;

    }


}

