<?php

namespace MicroweberPackages\Database\Traits;

use DB;
use MicroweberPackages\Database\Filter;
use Config;
use MicroweberPackages\Repository\Repositories\AbstractRepository;

trait QueryFilter
{
    public $table_cache_ttl = 3600;

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

        $strict_categories = false;
        if (isset($params['strict_categories'])) {
            $strict_categories = $params['strict_categories'];
        }


        $params_orig = $params;
        $is_fields = false;
        if (isset($params['fields']) and $params['fields'] != false) {
            $is_fields = $params['fields'];
            if (!is_array($is_fields)) {
                $is_fields = explode(',', $is_fields);
            }

            $is_fields_q = [];
            if ($is_fields) {
                foreach ($is_fields as $is_field) {
                    if (is_string($is_field)) {
                        $is_field = trim($is_field);
                        if ($is_field != '') {
                            $is_fields_q[] = $table . '.' . $is_field;
                        }
                    }
                }
            }
            if ($is_fields_q) {
                $query = $query->select($is_fields_q);
            }

        } else {
            $query = $query->select($table . '.*');
        }

        if (isset($params['require_table_to_have_any_of_columns']) and $params['require_table_to_have_any_of_columns'] != false) {
            $require_any_cols = array();
            if (is_array($params['require_table_to_have_any_of_columns'])) {
                $a_merge = $params['require_table_to_have_any_of_columns'];
            } else {
                $a_merge = explode(',', $params['require_table_to_have_any_of_columns']);

            }
            if($a_merge){
                $require_any_cols = array_merge($require_any_cols, $a_merge);
            }
            if ($require_any_cols) {
                $require_any_cols = array_flip($require_any_cols);
                $are_fields_found = $this->map_array_to_table($table, $require_any_cols);

                if (!$are_fields_found) {
                    return;
                }
            }

        }

        $exclude_shorthand = false;
        if (isset($params['exclude_shorthand']) && $params['exclude_shorthand'] ) {
            $exclude_shorthand = $params['exclude_shorthand'];
            if(!is_array($exclude_shorthand) and is_string($exclude_shorthand)){
                $exclude_shorthand = explode(',',$exclude_shorthand);
                $exclude_shorthand = array_map('trim', $exclude_shorthand);
            } else {
                $exclude_shorthand = true;
            }
        }

        $exclude_ids = [];
        if(isset($params['exclude_ids']) and is_string($params['exclude_ids'])){
            $exclude_ids_merge = explode(',',$params['exclude_ids']);
            if($exclude_ids_merge){
                $exclude_ids = array_merge($exclude_ids,$exclude_ids_merge);
            }
        } else if(isset($params['exclude_ids']) and is_array($params['exclude_ids'])) {
            $exclude_ids = array_merge($exclude_ids,$params['exclude_ids']);
        }




        foreach ($params as $filter => $value) {

            $compare_sign = false;
            $compare_value = false;

            if (is_string($value)) {

                $use_shorthand = true;
                if ($exclude_shorthand  and is_array($exclude_shorthand) and in_array($filter,$exclude_shorthand)) {
                    $use_shorthand = false;
                } else   if ($exclude_shorthand ) {
                    $use_shorthand = false;
                }

                if ($use_shorthand) {
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

                case 'search_in_tags':

                    $to_search_keyword = false;
                    if (isset($params['search_in_tags_keyword'])) {
                        $params['search_in_tags_keyword'] = urldecode($params['search_in_tags_keyword']);
                        $to_search_keyword = $params['search_in_tags_keyword'];
                    }

                    if ($to_search_keyword) {
                        $to_search_keywords = explode(',', $to_search_keyword);
                        if (!empty($to_search_keywords)) {
                            if ($this->supports($table, 'tag')) {
                                $query = $query->join('tagging_tagged', 'tagging_tagged.taggable_id', '=', $table . '.id')->distinct();
                                foreach ($to_search_keywords as $to_search_keyword) {
                                    $to_search_keyword = trim($to_search_keyword);
                                    if ($to_search_keyword != false) {
                                        if ($dbDriver == 'pgsql') {
                                            $query = $query->orWhere('tagging_tagged.tag_name', 'ILIKE', '%' . $to_search_keyword . '%');
                                        } else {
                                            $query = $query->orWhere('tagging_tagged.tag_name', 'LIKE', '%' . $to_search_keyword . '%');
                                        }
                                    }
                                }
                            }
                        }
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

                                    $antiXss = new \MicroweberPackages\Helper\HTMLClean();
                                    $to_search_keyword = $antiXss->clean($to_search_keyword);

                                    if ($to_search_keyword != false) {
                                        if (is_string($to_search_in_fields)) {
                                            $to_search_in_fields = explode(',', $to_search_in_fields);
                                        }
                                        $to_search_keyword = preg_replace("/(^\s+)|(\s+$)/us", '', $to_search_keyword);
                                        $to_search_keyword = strip_tags($to_search_keyword);
                                        $to_search_keyword = str_replace('\\', '', $to_search_keyword);
                                        $to_search_keyword = str_replace('*', '', $to_search_keyword);
                                        $to_search_keyword = str_replace('[', '', $to_search_keyword);
                                        $to_search_keyword = str_replace(']', '', $to_search_keyword);

                                        //check if keyword is breaking the regex and remove ( and ) from it
                                        try{
                                            $check_match = preg_match('~^[A-Za-z][A-Za-z0-9]*(\[(?P<array>"(?:.*(?:(?<!\\)(?>\\\\)*\").*|.*)+(?:(?<!\\)(?>\\\\)*"))\]|\[\]|)$~',trim($to_search_keyword,"\r\t"),$matches);
                                        } catch(\Exception $e){
                                            $to_search_keyword = str_replace('(', '', $to_search_keyword);
                                            $to_search_keyword = str_replace(')', '', $to_search_keyword);
                                        }

                                        $to_search_keyword = str_replace('(', '', $to_search_keyword);
                                        $to_search_keyword = str_replace(')', '', $to_search_keyword);


                                        $to_search_keyword = str_replace(';', '', $to_search_keyword);
                                        $to_search_keyword = str_replace('%', '', $to_search_keyword);

                                        if(mb_strlen($to_search_keyword) > 100){
                                            $to_search_keyword = mb_substr($to_search_keyword, 0, 100);
                                        }


                                        if ($to_search_keyword != '') {

                                            /*                                            // Search in tags
                                                                                       if ($this->supports($table, 'tag') and isset($filter['search_in_tags']) && $filter['search_in_tags']) {
                                                                                             $query = $query->join('tagging_tagged', 'tagging_tagged.taggable_id', '=', $table . '.id')->distinct();
                                                                                            if ($dbDriver == 'pgsql') {
                                                                                                $query = $query->orWhere('tagging_tagged.tag_name', 'ILIKE', '%'.$to_search_keyword.'%');
                                                                                            } else {
                                                                                                $query = $query->orWhere('tagging_tagged.tag_name', 'LIKE', '%'.$to_search_keyword.'%');
                                                                                            }
                                                                                        }*/

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

                                                //$query->{$where_or_where}(function ($query) use ($to_search_in_fields, $to_search_keyword, $params, $table, $dbDriver,$where_or_where) {
                                                $query =  $query->$where_or_where(function ($query) use ($to_search_in_fields, $to_search_keyword, $params, $table, $dbDriver,$where_or_where) {


                                                    foreach ($to_search_in_fields as $to_search_in_field) {
                                                        $to_search_keyword = str_replace('.', ' ', $to_search_keyword);
                                                        if ($dbDriver == 'pgsql') {
                                                            $query = $query->$where_or_where($to_search_in_field, 'ILIKE', '%' . $to_search_keyword . '%');
                                                        } else {
                                                            $query = $query->orWhere($to_search_in_field, 'LIKE', '%' . $to_search_keyword . '%');
                                                            $query = $query->orWhere($to_search_in_field, 'REGEXP', $to_search_keyword);
                                                            $query = $query->orWhere($to_search_in_field, 'LIKE', '%' . $to_search_keyword);
                                                            $query = $query->orWhere($to_search_in_field, 'LIKE', '.' . $to_search_keyword);
                                                            $query = $query->orWhere($to_search_in_field, 'LIKE', $to_search_keyword . '%');
                                                            if($where_or_where == 'orWhere'){
                                                                $query = $query->orWhereRaw('LOWER(`' . $to_search_in_field . '`) LIKE ? ', ['%' . trim(strtolower($to_search_keyword)) . '%']);
                                                            } else {
                                                                $query = $query->orWhereRaw('LOWER(`' . $to_search_in_field . '`) LIKE ? ', ['%' . trim(strtolower($to_search_keyword)) . '%']);

                                                                // $query = $query->whereRaw('LOWER(`' . $to_search_in_field . '`) LIKE ? ', ['%' . trim(strtolower($to_search_keyword)) . '%']);
                                                                // $query = $query->orWhereRaw('LOWER(`' . $to_search_in_field . '`) LIKE ? ', ['%' . trim(strtolower($to_search_keyword)) . '%']);

                                                            }
                                                        }
                                                    }




                                                });


                                                $query = $query->distinct();




                                            }
                                        }
                                    }
                                    $kw_number_counter++;
                                }

                                if (isset($params['is_active'])) {
                                    $query->where('is_active', $params['is_active']);
                                }
                                if (isset($params['is_deleted'])) {
                                    $query->where('is_deleted', $params['is_deleted']);
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
                case 'tag_names':

                    $ids = $value;

                    if (is_string($ids)) {
                        $ids = explode(',', $ids);
                    } elseif (!is_array($ids)) {
                        $ids = array($ids);
                    }

                    if (is_array($ids) and !empty($ids)) {
                        $ids = array_values($ids);
                        if ($this->supports($table, 'tag')) {
                            if ($filter == 'tag' or $filter == 'tags') {
                                $query = $query->withAnyTag($ids);
                            } else if ($filter == 'all_tags' or $filter == 'all_tag' or $filter == 'tag_names') {
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
                        $ids = array_filter($ids);


                        if (!empty($ids)) {
//                            $query = $query->join('categories_items as categories_items_joined_table', 'categories_items_joined_table.rel_id', '=', $table . '.id')
//                             ->where('categories_items_joined_table.rel_type', $table)
//                             ->whereIn('categories_items_joined_table.parent_id', $ids)->distinct();


//                            if (!isset($search_joined_tables_check['categories_items'])) {
//                                $query = $query->join('categories_items as categories_items_joined_table', 'categories_items_joined_table.rel_id', '=', $table . '.id');
//                                $query = $query->where('categories_items_joined_table.rel_id', '=', $table . '.id');
//                                $query = $query->where('categories_items_joined_table.rel_type', $table);
//
//                                $search_joined_tables_check['categories_items'] = true;
//
//                            }
//                            $query = $query->whereIn('categories_items_joined_table.parent_id', $ids)->distinct();
//

                            if ($strict_categories) {
                                $cat_ids_strict = '';
                            }

                            if (!$strict_categories) {
                                if($ids){

                                    //   $get_subcats = $this->table('categories')->where('data_type','category')->whereIn('parent_id',$ids)->get();
                                }
                            }

                            if (!isset($search_joined_tables_check['categories_items'])) {
                                $search_joined_tables_check['categories_items'] = true;

                                $query = $query->join('categories_items', function ($join) use ($table, $ids, $exclude_ids) {

                                    $join->on('categories_items.rel_id', '=', $table . '.id')
                                        ->where('categories_items.rel_type', '=', $table);


                                    if($exclude_ids){
                                        $join->whereNotIn('categories_items.rel_id', $exclude_ids);

                                    }

                                    $join->whereIn('categories_items.parent_id', $ids)->distinct();



                                });

                                // $query->whereIn('categories_items.parent_id', $ids);
                                //    $query = $query->join('categories_items as categories_items_joined_table', 'categories_items_joined_table.rel_id', '=', $table . '.id')->where('categories_items_joined_table.rel_type', $table);
                            }

                            $query = $query->distinct();



//
//                            if (!isset($search_joined_tables_check['categories_items'])) {
//                                $search_joined_tables_check['categories_items'] = true;
//
//                                $query = $query->join('categories_items as categories_items_joined_table', 'categories_items_joined_table.rel_id', '=', $table . '.id')->where('categories_items_joined_table.rel_type', $table);
//                            }
//                            $query->where('categories_items_joined_table.rel_type', $table);
//

                            //  $query->whereIn('categories_items_joined_table.parent_id', $ids)->distinct();

//                            foreach ($ids as $cat_id) {
//                               $query->where('categories_items_joined_table.parent_id', $cat_id);
//                            }

                            //    $query = $query->distinct();


//                        $query = $query->join('categories_items as categories_items_joined_table', 'categories_items_joined_table.rel_id', '=', $table . '.id')
//                            ->where('categories_items_joined_table.rel_type', $table)
//                            ->whereIn('categories_items_joined_table.parent_id', $ids)->distinct();

                        }


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
                        if (!isset($params['fields'])) {
                            $group_by_criteria[] = $table . '.id';
                        }
                    }

                    $query = $query->groupBy($group_by_criteria);


                    unset($params[$filter]);
                    break;
                case 'limit':
                    $criteria = intval($value);

                    $query = $query->take($criteria);

//                    $params_filter = [];
//                    $params_filter['limit'] = $value;
//                    $query = AbstractRepository::queryLimitLogic($query, $table, [], $params_filter);


                    unset($params['limit']);

                    break;

                case 'join':
                    $query->join($value, $table . '.rel_id', '=', $value . '.id')->where($table . '.rel_type', $value);
                    break;
                case 'current_page':

//                    $params_filter = [];
//                    $params_filter['current_page'] = $value;
//                    $query = AbstractRepository::queryLimitLogic($query, $table, [], $params_filter);



                    $criteria = 0;
                    //  $criteria = intval($value);
                    if ($value > 1) {
                        if ($is_limit != false) {
                            $criteria = intval($value - 1) * intval($is_limit);
                        }
                    }

                    $query = $query->skip($criteria);

                    unset($params[$filter]);
                    break;
                case 'ids':

                    $params_filter = [];
                    $params_filter['ids'] = $value;
                    $query = AbstractRepository::queryIncludeIdsLogic($query, $table, [], $params_filter);


//                    $ids = $value;
//                    if (is_string($ids)) {
//                        $ids = explode(',', $ids);
//                    } elseif (is_int($ids)) {
//                        $ids = array($ids);
//                    }
//
//                    if (isset($ids) and is_array($ids) == true) {
//                        foreach ($ids as $idk => $idv) {
//                            $ids[$idk] = intval($idv);
//                        }
//                    }
//
//                    if (is_array($ids)) {
//                        $query = $query->whereIn($table . '.id', $ids);
//                    }

                    unset($params[$filter]);
                    break;
                case 'remove_ids':
                case 'exclude_ids':
                     unset($params[$filter]);

                    $params_filter = [];
                    $params_filter['exclude_ids'] = $value;
                    $query = AbstractRepository::queryExcludeIdsLogic($query, $table, [], $params_filter);
//                    if (is_string($ids)) {
//                        $ids = explode(',', $ids);
//                    } elseif (is_int($ids)) {
//                        $ids = array($ids);
//                    }
//
//                    if (isset($ids) and is_array($ids) == true) {
//                        foreach ($ids as $idk => $idv) {
//                            $ids[$idk] = intval($idv);
//                        }
//                    }
//                    if (is_array($ids)) {
//                        $query = $query->whereNotIn($table . '.id', $ids);
//                    }
//



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
                    // $this->useCache = false;
                    break;

//                case 'is_active':
//                case 'is_deleted':
//                $query = $query->select($table . '.' . $filter);
//
//                $query = $query->where($table . '.'.$filter, $value);
//
//                break;


                case 'attributes':

                    $search_items = $value;
                    if (is_array($search_items) and !empty($search_items)) {
                        $query = $query->join('attributes', 'attributes.rel_id', '=', $table . '.id')
                            ->where('attributes.rel_type', $table);

                        foreach ($search_items as $search_item_key => $search_key_value) {
                            $query = $query->where('attributes.attribute_name', $search_item_key);
                            $query = $query->where('attributes.attribute_value', $search_key_value);
                        }

                    }
                    break;


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

                    $query = call_user_func($params[$filter], $query, $params);
                    unset($params[$filter]);

//
//                    $query = $query->where(function ($query) use (&$params, $filter) {
//                        $call = $params[$filter];
//                        unset($params[$filter]);
//
//
//                        //call_user_func_array($call, $params);
//                        $query = call_user_func($call, $query, $params);
//
//                        return $query;
//
//                    });


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

        $query_hook = $criteria_overwrite = $this->app->event_manager->response('mw.database.' . $table . '.get.query_filter', ['query'=>$query,'params'=>$params]);
        $query = $this->map_array_to_table($table, $query_hook['query']);

        return $query;
    }

    public function map_array_to_table($table, $array)
    {
        if (!is_array($array)) {
            return $array;
        }


        if (isset($this->table_fields[$table])) {
            $fields = $this->table_fields[$table];
        } else {
            $this->table_fields[$table] =  $fields = $this->get_fields($table);
        }



        $r = $fields;

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
                    if(!empty($query)) {
                        $query->whereNotNull($column);
                    }
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
