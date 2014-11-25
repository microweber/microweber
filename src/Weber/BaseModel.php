<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/23/14
 * Time: 11:56 PM
 */


use Illuminate\Database\Eloquent\Model as Eloquent;


class BaseModel extends Eloquent
{
    private $filter_keys = ['id', 'module', 'type'];
    public $custom_filters = ['single', 'order_by'];

    public function __construct()
    {
    }

    // called once when Post is first used
    public static function boot()
    {
        // there is some logic in this method, so don't forget this!
        parent::boot();

    }

    public function scopeItems($query, $params)
    {
        $table = $this->table;
        if (is_string($params)) {
            $params = parse_params($params);
        }

        $cf = array_flip($this->custom_filters);
        foreach($cf as $k=>&$v) {
            $v = isset($params[$k]);
        }

        foreach($cf as $filter=>$enabled) {
            if(!$enabled)
                continue;

            switch($filter) {
                case 'order_by':
                    $criteria = explode(',', $params['order_by']);
                    foreach($criteria as $c) {
                        $c = explode(' ', $c);
                        if(isset($c[1])) {
                            $query = $query->orderBy($c[0], $c[1]);
                        }
                        else if(isset($c[0])) {
                            $query = $query->orderBy($c[0]);
                        }
                    }
                    break;
            }
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
        return DB::connection()->getSchemaBuilder()->getColumnListing($table);
    }

}

