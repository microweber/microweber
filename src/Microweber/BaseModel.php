<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Microweber\Traits\QueryFilter as QueryFilter;

class BaseModel extends Eloquent
{


    use QueryFilter; //trait with db functions

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


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


    protected static function boot()
    {
        static::observe(new BaseModelObserver);
        parent::boot();
    }


    public function filter($params)
    {

        if (is_string($params)) {
            $params = parse_params($params);
        }
        if (isset($params['table'])) {
            $this->setTable($params['table']);
        }
        $orig_params = $params;
        $query = parent::items($params);
        if (!is_object($query)) {
            return false;
        }

        $items = $query->get()->toArray();

         if (is_object($items)) {
            $empty = $items->isEmpty();

            if ($empty == true) {
                return false;
            }
        }
        if (empty($items)) {
            //  dd($params);
            return false;
        }
        if (isset($orig_params['single']) || isset($orig_params['one'])) {
            if (!isset($items[0])) {
                return false;
            }
            return $items[0];
        }
        return $items;

    }


    public function scopeItems($query, $params = false)
    {
        $table = $this->table;
        if (is_string($params)) {
            $params = parse_params($params);
        }
        $debug = false;
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


        if (is_array($params) and !empty($params)) {

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
            // return $query;
        }
        if (isset($orig_params['avg']) and ($orig_params['avg'])) {
            $column = $orig_params['avg'];
            $query = $query->avg($column);
            return $query;
        }

        return $query;

        ///aaaaaaaaaaaaa


        $data = $query->get();

        if ($data == false or empty($data)) {

            return false;
        }

        $empty = $data->isEmpty();

        if ($empty == true) {

            // hangs here? in phpunit on windows

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
        if (!isset($params['created_at']) == false) {
            $params['created_at'] = date("Y-m-d H:i:s");
        }
        if (!isset($params['updated_at']) == false) {
            $params['updated_at'] = date("Y-m-d H:i:s");
        }
        $id = false;
        if (isset($params['id']) and $params['id'] != 0) {
            $id = $params['id'];
        }


        $params = $this->map_array_to_table($table, $params);
        $id_to_return = false;
        $this->fireModelEvent('saving');
        if ($id) {
            unset($params['created_at']);
            $save = self::find($id)->update($params);
            $id_to_return = intval($id);
        } else {
            $save = self::insert($params);

            $id_to_return = DB::getPdo()->lastInsertId();


        }
        $id_to_return = intval($id_to_return);
        return ($id_to_return);


    }


}

