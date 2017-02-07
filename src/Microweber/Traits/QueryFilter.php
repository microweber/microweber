<?php

namespace Microweber\Traits;

use DB;
use Filter;
use Config;

trait QueryFilter
{
    public $table_cache_ttl = 60;

    public $filter_keys = [];

    public static $custom_filters = [];

    public static function custom_filter($name, $callback)
    {
        self::$custom_filters[$name] = $callback;
    }

    public function map_filters($query, &$params, $table)
    {
        $dbDriver = Config::get('database.default');

        if (!isset($params['count']) and !isset($params['count_paging'])) {
            if (isset($params['paging_param']) and isset($params[$params['paging_param']]) and $params['paging_param'] != 'current_page') {
                $params['current_page'] = intval($params[$params['paging_param']]);
                unset($params[$params['paging_param']]);
            }
        }
        $is_limit = false;
        if (isset($params['limit'])) {
            $is_limit = $params['limit'];
        }
        $is_id = false;
        if (isset($params['id'])) {
            $is_id = $params['id'];
        }
        $params_orig = $params;
        $is_fields = false;
        if (isset($params['fields']) and $params['fields'] != false) {
            $is_fields = $params['fields'];
            if (!is_array($is_fields)) {
                $is_fields = explode(',', $is_fields);
            }

            if (is_array($is_fields) and !empty($is_fields)) {
                foreach ($is_fields as $is_field) {
                    if (is_string($is_field)) {
                        $is_field = trim($is_field);
                        if ($is_field != '') {
                            $query = $query->select($table . '.' . $is_field);
                        }
                        $query = $query->select($table . '.*');
                    }
                }
            }
        } else {
            $query = $query->select($table . '.*');

        }

        foreach ($params as $filter => $value) {
            $compare_sign = false;
            $compare_value = false;

            if (is_string($value)) {
                if (stristr($value, '[lt]')) {
                    $compare_sign = '<';
                    $value = str_replace('[lt]', '', $value);
                } elseif (stristr($value, '[lte]')) {
                    $compare_sign = '<=';
                    $value = str_replace('[lte]', '', $value);
                } elseif (stristr($value, '[st]')) {
                    $compare_sign = '<';
                    $value = str_replace('[st]', '', $value);
                } elseif (stristr($value, '[ste]')) {
                    $compare_sign = '<=';
                    $value = str_replace('[ste]', '', $value);
                } elseif (stristr($value, '[gt]')) {
                    $compare_sign = '>';
                    $value = str_replace('[gt]', '', $value);
                } elseif (stristr($value, '[gte]')) {
                    $compare_sign = '>=';
                    $value = str_replace('[gte]', '', $value);
                } elseif (stristr($value, '[mt]')) {
                    $compare_sign = '>';
                    $value = str_replace('[mt]', '', $value);
                } elseif (stristr($value, '[md]')) {
                    $compare_sign = '>';
                    $value = str_replace('[md]', '', $value);
                } elseif (stristr($value, '[mte]')) {
                    $compare_sign = '>=';
                    $value = str_replace('[mte]', '', $value);
                } elseif (stristr($value, '[mde]')) {
                    $compare_sign = '>=';
                    $value = str_replace('[mde]', '', $value);
                } elseif (stristr($value, '[neq]')) {
                    $compare_sign = '!=';
                    $value = str_replace('[neq]', '', $value);
                } elseif (stristr($value, '[eq]')) {
                    $compare_sign = '=';
                    $value = str_replace('[eq]', '', $value);
                } elseif (stristr($value, '[int]')) {
                    $value = str_replace('[int]', '', $value);
                    $value = intval($value);
                } elseif (stristr($value, '[is]')) {
                    $compare_sign = '=';
                    $value = str_replace('[is]', '', $value);
                } elseif (stristr($value, '[like]')) {
                    $compare_sign = 'LIKE';
                    $value = str_replace('[like]', '', $value);
                    $compare_value = '%' . $value . '%';
                } elseif (stristr($value, '[not_like]')) {
                    $value = str_replace('[not_like]', '', $value);
                    $compare_sign = 'NOT LIKE';
                    $compare_value = '%' . $value . '%';
                } elseif (stristr($value, '[is_not]')) {
                    $value = str_replace('[is_not]', '', $value);
                    $compare_sign = 'NOT LIKE';
                    $compare_value = '%' . $value . '%';
                } elseif (stristr($value, '[in]')) {
                    $value = str_replace('[in]', '', $value);
                    $compare_sign = 'in';
                } elseif (stristr($value, '[not_in]')) {
                    $value = str_replace('[not_in]', '', $value);
                    $compare_sign = 'not_in';
                } elseif (strtolower($value) == '[null]') {
                    $value = str_replace('[null]', '', $value);
                    $compare_sign = 'null';
                } elseif (strtolower($value) == '[not_null]') {
                    $value = str_replace('[not_null]', '', $value);
                    $compare_sign = 'not_null';
                }
                if ($filter == 'created_at' or $filter == 'updated_at') {
                    $compare_value = date('Y-m-d H:i:s', strtotime($value));
                }
            }

            switch ($filter) {

                case 'fields':
                    $fields = $value;
                    if ($fields != false and is_string($fields)) {
                        $fields = explode(',', $fields);
                    }

                    if (is_array($fields) and !empty($fields)) {
                        $query = $query->select($fields);
                    }
                    unset($params[$filter]);
                    break;

                case 'keyword':

                    if (isset($params['search_in_fields'])) {
                        $to_search_in_fields = $params['search_in_fields'];

                        if (isset($params['keyword'])) {
                            $params['keyword'] = urldecode($params['keyword']);
                            $to_search_keyword = $params['keyword'];
                        }

                        if ($to_search_in_fields != false and $to_search_keyword != false) {
                            if ($dbDriver == 'sqlite') {
                                $pdo = DB::connection('sqlite')->getPdo();
                                $pdo->sqliteCreateFunction('regexp',
                                    function ($pattern, $data, $delimiter = '~', $modifiers = 'isuS') {
                                        if (isset($pattern, $data) === true) {
                                            return preg_match(sprintf('%1$s%2$s%1$s%3$s', $delimiter, $pattern, $modifiers), $data) > 0;
                                        }

                                        return;
                                    }
                                );
                            }


                            $to_search_keywords = explode(',', $to_search_keyword);

                            if (!empty($to_search_keywords)) {
                                $kw_number_counter = 0;
                                foreach ($to_search_keywords as $to_search_keyword) {
                                    $to_search_keyword = trim($to_search_keyword);


                                    if ($to_search_keyword != false) {
                                        if (is_string($to_search_in_fields)) {
                                            $to_search_in_fields = explode(',', $to_search_in_fields);
                                        }
                                        $to_search_keyword = preg_replace("/(^\s+)|(\s+$)/us", '', $to_search_keyword);
                                        $to_search_keyword = strip_tags($to_search_keyword);
                                        $to_search_keyword = str_replace('\\', '', $to_search_keyword);
                                        $to_search_keyword = str_replace('*', '', $to_search_keyword);
                                        $to_search_keyword = str_replace(';', '', $to_search_keyword);
                                        if ($to_search_keyword != '') {
                                            if (!empty($to_search_in_fields)) {
                                                $where_or_where = 'orWhere';
                                                if (isset($params['keywords_exact_match'])) {
                                                    $where_or_where = 'where';
                                                }


                                                //check search in joined table
                                                $search_joined_tables_check = array();
                                                foreach ($to_search_in_fields as $to_search_in_field) {
                                                    $check_if_join_is_needed = explode('.', $to_search_in_field);
                                                    if (isset($check_if_join_is_needed[1])) {
                                                        $rest = $check_if_join_is_needed[0];
                                                        if (!isset($search_joined_tables_check[$rest])) {
                                                            $query = $query->join($rest, $rest . '.rel_id', '=', $table . '.id')
                                                                ->where($rest . '.rel_type', $table)->distinct();
                                                            $search_joined_tables_check[$rest] = true;
                                                        }
                                                    }
                                                }

                                                $query->{$where_or_where}(function ($query) use ($to_search_in_fields, $to_search_keyword, $params, $table) {
                                                    foreach ($to_search_in_fields as $to_search_in_field) {
                                                        $query = $query->orWhere($to_search_in_field, 'REGEXP', $to_search_keyword);
                                                    }

                                                    if (isset($params['is_active'])) {
                                                        $query->where('is_active', $params['is_active']);
                                                    }
                                                    if (isset($params['is_deleted'])) {
                                                        $query->where('is_deleted', $params['is_deleted']);
                                                    }
                                                });
                                                //  $query->distinct();
                                            }
                                        }
                                    }
                                    $kw_number_counter++;
                                }
                            }

                        }
                    }


                    unset($params[$filter]);
                    break;

                case 'single':
                case 'one':

                    break;
                case 'tag':
                case 'tags':
                case 'all_tags':
                case 'all_tag':

                    $ids = $value;

                    if (is_string($ids)) {
                        $ids = explode(',', $ids);
                    } elseif (!is_array($ids)) {
                        $ids = array($ids);
                    }
                    if (is_array($ids) and !empty($ids)) {
                        if ($this->supports($table, 'tag')) {
                            if ($filter == 'tag' or $filter == 'tags') {
                                $query = $query->withAnyTag($ids);
                            } else if ($filter == 'all_tags' or $filter == 'all_tag') {
                                $query = $query->withAllTags($ids);
                            }
                        }
                    }
                    break;
                case 'category':
                case 'categories':

                    $ids = $value;

                    if (is_string($ids)) {
                        $ids = explode(',', $ids);
                    } elseif (is_int($ids)) {
                        $ids = array($ids);
                    }
                    if (is_array($ids)) {
                        //                        $query = $query->leftJoin('categories_items', function($join) use ($table,$ids)
//                        {
//                            $join->on('categories_items.rel_id', '=',  $table . '.id');
//                            $join->on('categories_items.rel_type', '=',  $table);
//                          //  $join->whereIn('categories_items.parent_id', $ids);
//
//                        })->whereIn('categories_items.parent_id', $ids)->groupBy('categories_items.rel_id');

                        $query = $query->leftJoin('categories_items', 'categories_items.rel_id', '=', $table . '.id')
                            ->where('categories_items.rel_type', $table)
                            ->whereIn('categories_items.parent_id', $ids)->distinct();


                    }
                    unset($params[$filter]);

                    break;
                case 'order_by':
                    $order_by_criteria = explode(',', $value);

                    foreach ($order_by_criteria as $c) {
                        $c = urldecode($c);
                        $c = explode(' ', $c);
                        if (isset($c[0]) and trim($c[0]) != '') {
                            $c[0] = trim($c[0]);
                            if (isset($c[1])) {
                                $c[1] = trim($c[1]);
                            }
                            if (isset($c[1]) and ($c[1]) != '') {
                                $query = $query->orderBy($c[0], $c[1]);
                            } elseif (isset($c[0])) {
                                $query = $query->orderBy($c[0]);
                            }
                        }
                    }
                    unset($params[$filter]);
                    break;
                case 'group_by':
                    $group_by_criteria = explode(',', $value);
                    if (!empty($group_by_criteria)) {
                        $group_by_criteria = array_map('trim', $group_by_criteria);
                    }


                    if ($dbDriver == 'pgsql') {
                        if (isset($params_orig['order_by']) and $params_orig['order_by']) {
                            $o = explode(' ', $params_orig['order_by']);
                            $group_by_criteria[] = $o[0];
                        }
                        if(!isset($params['fields'])){
                            $group_by_criteria[] = $table . '.id';
                        }
                    }

                    $query = $query->groupBy($group_by_criteria);


                    unset($params[$filter]);
                    break;
                case 'limit':
                    $criteria = intval($value);

                    $query = $query->take($criteria);
                    unset($params['limit']);

                    break;
                case 'current_page':
                    $criteria = 1;

                    if ($value > 1) {
                        if ($is_limit != false) {
                            $criteria = intval($value - 1) * intval($is_limit);
                        }
                    }
                    if ($criteria > 1) {
                        $query = $query->skip($criteria);
                    }
                    unset($params[$filter]);
                    break;
                case 'ids':
                    $ids = $value;
                    if (is_string($ids)) {
                        $ids = explode(',', $ids);
                    } elseif (is_int($ids)) {
                        $ids = array($ids);
                    }

                    if (isset($ids) and is_array($ids) == true) {
                        foreach ($ids as $idk => $idv) {
                            $ids[$idk] = intval($idv);
                        }
                    }

                    if (is_array($ids)) {
                        $query = $query->whereIn($table . '.id', $ids);
                    }

                    unset($params[$filter]);
                    break;
                case 'remove_ids':
                case 'exclude_ids':
                    unset($params[$filter]);
                    $ids = $value;
                    if (is_string($ids)) {
                        $ids = explode(',', $ids);
                    } elseif (is_int($ids)) {
                        $ids = array($ids);
                    }

                    if (isset($ids) and is_array($ids) == true) {
                        foreach ($ids as $idk => $idv) {
                            $ids[$idk] = intval($idv);
                        }
                    }
                    if (is_array($ids)) {
                        $query = $query->whereNotIn($table . '.id', $ids);
                    }

                    break;
                case 'id':
                    unset($params[$filter]);
                    $criteria = trim($value);
                    if ($compare_sign != false) {
                        if ($compare_value != false) {
                            $val = $compare_value;
                        } else {
                            $val = $value;
                        }

                        $query = $query->where($table . '.id', $compare_sign, $val);
                    } else {
                        $query = $query->where($table . '.id', $criteria);
                    }

                    break;

                case 'no_cache':
                    $this->useCache = false;
                    break;

//                case 'is_active':
//                case 'is_deleted':
//                $query = $query->select($table . '.' . $filter);
//
//                $query = $query->where($table . '.'.$filter, $value);
//
//                break;

                case 'search_params':
                    $search_items = $value;


                    if (is_array($search_items) and !empty($search_items)) {
                        foreach ($search_items as $search_item_key => $search_key_value)
                            switch ($search_item_key) {
                                case 'custom_field':
                                    if (is_array($search_key_value) and !empty($search_key_value)) {
                                        // $query =  $query->select('custom_fields.id');
                                        $query = $query->join('custom_fields', 'custom_fields.rel_id', '=', $table . '.id')
                                            ->where('custom_fields.rel_type', $table);


                                        $query = $query->join('custom_fields_values', function ($join) use ($table, $search_key_value, $query) {
                                            $join->on('custom_fields_values.custom_field_id', '=', 'custom_fields.id');
                                            foreach ($search_key_value as $search_key_value_k => $search_key_value_v) {
                                                //  $join->on('custom_fields.name_key', '=', $search_key_value_k);
                                                //   $join->on('custom_fields.name_key', '=', $search_key_value_k);
                                                $join->on('custom_fields_values.value', '=', $search_key_value_v);
                                            }

                                        });

                                        $query = $query->join('custom_fields as custom_fields_names_q', function ($join) use ($table, $search_key_value, $query) {

                                            foreach ($search_key_value as $search_key_value_k => $search_key_value_v) {
                                                $join->orOn('custom_fields_names_q.name_key', '=', $search_key_value_k);
                                                $join->orOn('custom_fields_names_q.name', '=', $search_key_value_k);
                                                $join->orOn('custom_fields_names_q.type', '=', $search_key_value_k);
                                            }

                                        });

                                        $query = $query->distinct();

                                    }

                                    break;
                            }

                    }

                    break;


                case $this->_is_closure($params[$filter]):


                    $query = $query->where(function ($query) use (&$params, $filter) {
                        $call = $params[$filter];
                        unset($params[$filter]);
                        //call_user_func_array($call, $params);
                        call_user_func($call, $query, $params);


                    });


                    break;
                default:
                    if ($compare_sign != false) {
                        unset($params[$filter]);
                        if ($compare_value != false) {
                            $query = $query->where($table . '.' . $filter, $compare_sign, $compare_value);
                        } else {
                            if ($compare_sign == 'null' || $compare_sign == 'not_null') {
                                if ($compare_sign == 'null') {
                                    $query = $query->whereNull($table . '.' . $filter);
                                }
                                if ($compare_sign == 'not_null') {
                                    $query = $query->whereNotNull($table . '.' . $filter);
                                }


                            } else if ($compare_sign == 'in' || $compare_sign == 'not_in') {
                                if (is_string($value)) {
                                    $value = explode(',', $value);
                                } elseif (is_int($value)) {
                                    $value = array($value);
                                }
                                if (is_array($value)) {
                                    if ($compare_sign == 'in') {
                                        $query = $query->whereIn($table . '.' . $filter, $value);
                                    } elseif ($compare_sign == 'not_in') {
                                        $query = $query->whereIn($table . '.' . $filter, $value);
                                    }
                                }
                            } else {
                                $query = $query->where($table . '.' . $filter, $compare_sign, $value);
                            }
                        }
                    }
                    break;
            }
        }

        foreach (self::$custom_filters as $name => $callback) {
            if (!isset($params[$name])) {
                continue;
            }
            call_user_func_array($callback, [$query, $params[$name], $table]);
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

    public function map_values_to_query($query, $params)
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

    public function __call($method, $params)
    {
        return Filter::get($method, $params, $this);
    }

    private function _is_closure($t)
    {
        return is_object($t) && ($t instanceof \Closure);
    }
}
