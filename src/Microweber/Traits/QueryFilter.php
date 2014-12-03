<?php

namespace Microweber\Traits;

use Illuminate\Support\Facades\Cache;

trait QueryFilter
{
    public $default_limit = 30;


    public $table_cache_ttl = 60;

    public $filter_keys = [];

    public static $custom_filters = [];

    public static function custom_filter($name, $callback)
    {
        self::$custom_filters[$name] = $callback;
    }

    public function map_filters($query, &$params)
    {

        if (!isset($params['limit'])) {
            $params['limit'] = $this->default_limit;
        }

        foreach ($params as $filter => $value) {


            $compare_sign = false;
            $compare_value = false;

            if (is_string($value)) {
                if (stristr($value, '[lt]')) {
                    $compare_sign = '<';
                    $value = str_replace('[lt]', '', $value);
                } else if (stristr($value, '[lte]')) {
                    $compare_sign = '<=';
                    $value = str_replace('[lte]', '', $value);
                } else if (stristr($value, '[st]')) {
                    $compare_sign = '<';
                    $value = str_replace('[st]', '', $value);
                } else if (stristr($value, '[ste]')) {
                    $compare_sign = '<=';
                    $value = str_replace('[ste]', '', $value);
                } else if (stristr($value, '[gt]')) {
                    $compare_sign = '>';
                    $value = str_replace('[gt]', '', $value);
                } else if (stristr($value, '[gte]')) {
                    $compare_sign = '>=';
                    $value = str_replace('[gte]', '', $value);
                } else if (stristr($value, '[mt]')) {
                    $compare_sign = '>';
                    $value = str_replace('[mt]', '', $value);
                } else if (stristr($value, '[md]')) {
                    $compare_sign = '>';
                    $value = str_replace('[md]', '', $value);
                } else if (stristr($value, '[mte]')) {
                    $compare_sign = '>=';
                    $value = str_replace('[mte]', '', $value);
                } else if (stristr($value, '[mde]')) {
                    $compare_sign = '>=';
                    $value = str_replace('[mde]', '', $value);
                } else if (stristr($value, '[neq]')) {
                    $compare_sign = '!=';
                    $value = str_replace('[neq]', '', $value);
                } else if (stristr($value, '[eq]')) {
                    $compare_sign = '=';
                    $value = str_replace('[eq]', '', $value);
                } else if (stristr($value, '[int]')) {
                    $value = str_replace('[int]', '', $value);
                    $value = intval($value);
                } else if (stristr($value, '[is]')) {
                    $compare_sign = '=';
                    $value = str_replace('[is]', '', $value);
                } else if (stristr($value, '[like]')) {
                    $compare_sign = 'LIKE';
                    $value = str_replace('[like]', '', $value);
                    $compare_value = '%' . $value . '%';
                } else if (stristr($value, '[not_like]')) {
                    $value = str_replace('[not_like]', '', $value);
                    $compare_sign = 'NOT LIKE';
                    $compare_value = '%' . $value . '%';
                } else if (stristr($value, '[is_not]')) {
                    $value = str_replace('[is_not]', '', $value);
                    $compare_sign = 'NOT LIKE';
                    $compare_value = '%' . $value . '%';
                }
            }

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
                    unset($params[$filter]);
                    break;
                case 'limit':
                    $criteria = intval($value);
                    $query = $query->take($criteria);
                    unset($params[$filter]);
                    break;
                case 'current_page':
                    $criteria = intval($value);
                    $query = $query->skip($criteria);

                    unset($params[$filter]);
                    break;
                case 'ids':
                    $ids = $value;
                    if (is_string($ids)) {
                        $ids = explode(',', $ids);
                    }


                    $query = $query->whereIn('id', $ids);
                    unset($params[$filter]);
                    break;
                case 'exclude_ids':
                    unset($params[$filter]);
                    $ids = $value;
                    if (is_string($ids)) {
                        $ids = explode(',', $ids);
                    }
                    $query = $query->whereNotIn('id', $ids);
                    break;
                case 'id':
                    unset($params[$filter]);
                    $criteria = trim($value);
                    $query = $query->where('id', $criteria);
                    break;

                case 'no_cache':
                    $this->useCache = false;
                    break;


                default:
                    if ($compare_sign != false) {
                        unset($params[$filter]);
                        if ($compare_value != false) {
                            $query = $query->where($filter, $compare_sign, $compare_value);

                        } else {
                            $query = $query->where($filter, $compare_sign, $value);

                        }
                    }

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
        if (!is_array($array)) {
            return $array;
        }

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