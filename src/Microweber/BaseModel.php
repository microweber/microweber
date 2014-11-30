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

    private $useCache = true;

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

    public function __construct()
    {
    }

    public function get()
    {
        dd('fu');
        return parent::get();
    }

    public function scopeItems($query, $params = false)
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
                case 'limit':
                    $criteria = intval($params['limit']);
                    $query = $query->take($criteria);
                    break;

                case 'id':
                    $criteria = trim($params['id']);

                    $query = $query->where('id',$criteria);
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

        foreach (self::$custom_filters as $name => $callback)
        {
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


        $data = $data->toArray();

        if ($cf['single']) {
            if (!isset($data[0])) {
                return false;
            }
            return $data[0];
        }


        return $data;
    }



    public function save_item($params){
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

        $params = $this->map_array_to_table($table, $params);
        if(isset($params['id'])){
            unset($params['created_on']);

            $save = parent::where('id', $params['id'])->update($params);
        } else {
            $save = parent::insert($params);

        }
       return($save);


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

