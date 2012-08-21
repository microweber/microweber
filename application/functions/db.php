<?php

$db = c('db');
$is_sqlite = strstr($db['dsn'], 'sqlite:');

if ($is_sqlite != false) {
    define("DB_IS_SQLITE", true);
} else {
    define("DB_IS_SQLITE", false);
}

function db_q($q) {
    $db = new DB(c('db'));

    $q = $db->query($q);
    return $q;
}

function db_get_id($table, $id = 0, $is_this_field = false) {

    $id = intval($id);

    if ($id == 0) {

        return false;
    }

    if ($is_this_field == false) {
        $is_this_field = "id";
    }

    $table = db_get_table_name($table);

    $q = "SELECT * from $table where {$is_this_field}=$id limit 1";
    // d($q);
    $q = db_query($q);

    $q = $q [0];

    // /$q = intval ( $q );

    if (count($q) > 0) {

        return $q;
    } else {

        return false;
    }
}

function guess_table_name($for = false) {



    if (stristr($for, 'table_') == false) {
        switch ($for) {
            case 'user' :
            case 'users' :
                $to_table = 'table_users';
                break;

            case 'media' :
            case 'picture' :
            case 'video' :
            case 'file' :
                $to_table = 'table_media';
                break;

            case 'comment' :
            case 'comments' :

                $to_table = 'table_comments';
                break;


            case 'module' :
            case 'modules' :
            case 'table_modules' :
            case 'modul' :

                $to_table = 'table_modules';
                break;

            case 'category' :
            case 'categories' :
            case 'cat' :
            case 'taxonomy' :
            case 'tag' :
            case 'tags' :
                $to_table = 'table_taxonomy';
                break;

            case 'post' :
            case 'page' :
            case 'content' :

            default :
                $to_table = 'table_content';
                break;
        }
        return $to_table;
    } else {
        return $for;
    }
}

function db_query($q, $cache_id = false, $cache_group = 'global', $time = false) {
    if (trim($q) == '') {
        return false;
    }

    if ($cache_id != false) {
        // $results =false;
        $results = cache_get_content($cache_id, $cache_group, $time);
        if ($results != false) {
            if ($results == '---empty---') {
                return false;
            } else {
                return $results;
            }
        }
    }
    $db = new DB(c('db'));
    $q = $db->get($q);
    if (empty($q)) {
        if ($cache_id != false) {

            cache_store_data('---empty---', $cache_id, $cache_group);
        }
        return false;
    }





    // $result = $q->result_array ();

    $results = array();
    if (!empty($q)) {
        foreach ($q as $result) {

            if (isset($result ['custom_field_value'])) {
                if (strtolower($result ['custom_field_value']) == 'array') {
                    if (isset($result ['custom_field_values'])) {
                        $result ['custom_field_values'] = unserialize(base64_decode($result ['custom_field_values']));
                        $result ['custom_field_value'] = 'Array';
                        //$cfvq = "custom_field_values =\"" . $custom_field_to_save ['custom_field_values'] . "\",";
                    }
                }
            }
            $results[] = $result;
        }
    }


    $result = $results;



    if ($cache_id != false) {
        if (!empty($result)) {
            cache_store_data($result, $cache_id, $cache_group);
        } else {
            cache_store_data('---empty---', $cache_id, $cache_group);
        }
    }

    return $result;
}

/**
 * get data from the database this is the MOST important function in the
 * Microweber CMS.
 * Everything relies on it.
 *
 * @author Peter Ivanov
 *
 * @param string $table
 *        	-
 *        	the table name ex. table_content
 * @param array $criteria
 *        	The array of database fields you want to filter
 *        	ex.
 *        	$criteria =array('id' => 11); //gets the item
 *
 *
 *        	Query options:
 *        	$criteria['debug'] = 1; //print the sql
 *        	$criteria['cache_group'] = 'content' //same as the $cache_group
 *        	$criteria['no_cache'] = 1; //does not cache the query
 *        	$criteria['count'] = 1; //get only the count
 *
 *
 *
 *        	Result limit:
 *        	$criteria['limit'] = 10; //gets 10 results
 *        	$criteria['limit'] = array(30,10); //gets 10 results with offset
 *        	30
 *
 *
 *
 * @param string $cache_group
 *        	-
 *        	The cache folder to use to cache the query result
 *        	You must delete this cache group when you save data to the $table
 */
function db_get($table, $criteria, $cache_group = false) {
    return __db_get_long($table, $criteria, $limit = false, $offset = false, $orderby = false, $cache_group, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false);
}

/**
 * get data
 * Microweber CMS.
 * Everything relies on it.
 *
 * @author Peter Ivanov
 */
function __db_get_long($table = false, $criteria = false, $limit = false, $offset = false, $orderby = false, $cache_group = false, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false) {
    $cms_db_tables = c('db_tables'); // ->'table_options';
    // $this->db->query ( 'SET NAMES utf8' );
    if ($table == false) {

        return false;
    }
    if (!empty($cms_db_tables)) {

        foreach ($cms_db_tables as $k => $v) {

            // var_dump($k, $v);
            if (strtolower($table) == strtolower($v)) {

                $table_assoc_name = $k;
            }
        }
    }

    $aTable_assoc = db_get_table_name($table);

    if (!empty($criteria)) {
        if (isset($criteria ['debug'])) {
            $debug = true;
            if (($criteria ['debug'])) {
                $criteria ['debug'] = false;
            } else {
                unset($criteria ['debug']);
            }
        }
        if (isset($criteria ['cache_group'])) {
            $cache_group = $criteria ['cache_group'];
        }
        if (isset($criteria ['no_cache'])) {
            $cache_group = false;
            if (($criteria ['no_cache']) == true) {
                $criteria ['no_cache'] = false;
            } else {
                unset($criteria ['no_cache']);
            }
        }

        if (isset($criteria ['count_only']) and $criteria ['count_only'] == true) {
            $count_only = $criteria ['count_only'];

            unset($criteria ['count_only']);
        }

        if (isset($criteria ['count'])) {
            $count_only = $criteria ['count'];

            unset($criteria ['count']);
        }

        if (isset($criteria ['get_count']) and $criteria ['get_count'] == true) {
            $count_only = true;

            unset($criteria ['get_count']);
        }

        if (isset($criteria ['count']) and $criteria ['count'] == true) {
            $count_only = $criteria ['count'];

            unset($criteria ['count']);
        }

        if (isset($criteria ['with_pictures']) and $criteria ['with_pictures'] == true) {
            $with_pics = true;
        }

        if (isset($criteria ['limit']) and $criteria ['limit'] == true and $count_only == false) {
            $limit = $criteria ['limit'];
        }
        if (isset($criteria ['limit'])) {
            $limit = $criteria ['limit'];
        }

        $curent_page = isset($criteria ['curent_page']) ? $criteria ['curent_page'] : null;
        if ($curent_page == false) {
            $curent_page = isset($criteria ['page']) ? $criteria ['page'] : null;
        }

        $offset = isset($criteria ['offset']) ? $criteria ['offset'] : false;

        if ($limit == false) {
            $limit = isset($criteria ['limit']) ? $criteria ['limit'] : false;
        }
        if ($offset == false) {
            $offset = isset($criteria ['offset']) ? $criteria ['offset'] : false;
        }

        if ($count_only == false) {
            $qLimit = "";
            if ($limit == false) {

                if (!isset($items_per_page) or $items_per_page == false) {

                    $items_per_page = 30;
                }

                $items_per_page = intval($items_per_page);

                if (intval($curent_page) < 1) {

                    $curent_page = 1;
                }

                $page_start = ($curent_page - 1) * $items_per_page;

                $page_end = ($page_start) + $items_per_page;

                $temp = $page_end - $page_start;

                if (intval($temp) == 0) {

                    $temp = 1;
                }

                $qLimit .= "LIMIT {$temp} ";

                if (($offset) == false) {

                    $qLimit .= "OFFSET {$page_start} ";
                }
            }
            $limit_from_paging_q = $qLimit;
        }

        if ($debug) {
            // p($limit_from_paging_q);
            // p($limit);
        }

        if (isset($criteria ['fields'])) {
            $only_those_fields = $criteria ['fields'];
            if (is_string($criteria ['fields'])) {
                $criteria ['fields'] = false;
            } else {
                unset($criteria ['fields']);
            }
        }
    }
    if (!empty($criteria)) {
        foreach ($criteria as $fk => $fv) {
            if (strstr($fk, 'custom_field_') == true) {

                $addcf = str_ireplace('custom_field_', '', $fk);

                // $criteria ['custom_fields_criteria'] [] = array ($addcf =>
                // $fv );

                $criteria ['custom_fields_criteria'] [$addcf] = $fv;
            }
        }
    }
    if (!empty($criteria ['custom_fields_criteria'])) {

        $table_custom_fields = $cms_db_tables ['table_custom_fields'];

        $only_custom_fieldd_ids = array();

        $use_fetch_db_data = true;

        $ids_q = "";

        if (!empty($ids)) {

            $ids_i = implode(',', $ids);

            $ids_q = " and to_table_id in ($ids_i) ";
        }

        $only_custom_fieldd_ids = array();
        // p($data ['custom_fields_criteria'],1);
        foreach ($criteria ['custom_fields_criteria'] as $k => $v) {

            if (is_array($v) == false) {

                $v = addslashes($v);
                $v = html_entity_decode($v);
                $v = urldecode($v);
            }
            $is_not_null = false;
            if ($v == 'IS NOT NULL') {
                $is_not_null = true;
            }

            $k = addslashes($k);

            if (!empty($category_content_ids)) {

                $category_ids_q = implode(',', $category_content_ids);

                $category_ids_q = " and to_table_id in ($category_ids_q) ";
            } else {

                $category_ids_q = false;
            }

            $only_custom_fieldd_ids_q = false;

            if (!empty($only_custom_fieldd_ids)) {

                $only_custom_fieldd_ids_i = implode(',', $only_custom_fieldd_ids);

                $only_custom_fieldd_ids_q = " and to_table_id in ($only_custom_fieldd_ids_i) ";
            }
            if ($is_not_null == true) {
                $cfvq = "custom_field_value IS NOT NULL  ";
            } else {
                $cfvq = "custom_field_value LIKE '$v'  ";
            }
            $q = "SELECT  to_table_id from $table_custom_fields where

			to_table = '$aTable_assoc' and

			custom_field_name = '$k' and

			$cfvq

			$ids_q   $only_custom_fieldd_ids_q


			$my_limit_q

			order by field_order asc

			";

            $q2 = $q;
            // p($q);
            $q = $this->core_model->dbQuery($q, md5($q), 'custom_fields');
            //

            if (!empty($q)) {

                $ids_old = $ids;

                $ids = array();

                foreach ($q as $itm) {

                    $only_custom_fieldd_ids [] = $itm ['to_table_id'];

                    // if(in_array($itm ['to_table_id'],$category_ids)==
                    // false){

                    $includeIds [] = $itm ['to_table_id'];

                    // }
                //
				}
            } else {

                // $ids = array();

                $remove_all_ids = true;

                $includeIds = false;

                $includeIds [] = '0';

                $includeIds [] = 0;
            }
        }
    }

    $original_cache_group = $cache_group;

    if (!empty($criteria ['only_those_fields'])) {

        $only_those_fields = $criteria ['only_those_fields'];

        // unset($criteria['only_those_fields']);
        // no unset xcause f cache
    }

    if (!empty($criteria ['include_taxonomy'])) {

        $include_taxonomy = true;
    } else {

        $include_taxonomy = false;
    }

    if (!empty($criteria ['exclude_ids'])) {

        $exclude_ids = $criteria ['exclude_ids'];

        // unset($criteria['only_those_fields']);
        // no unset xcause f cache
    }

    if (!empty($criteria ['ids'])) {
        foreach ($criteria ['ids'] as $itm) {

            $includeIds [] = $itm;
        }
    }

    $to_search = false;

    if (isset($criteria ['keyword'])) {
        if (!isset($criteria ['search_by_keyword']) or $criteria ['search_by_keyword'] == false) {
            $criteria ['search_by_keyword'] = $criteria ['keyword'];
        }
    }

    if (isset($criteria ['keywords'])) {
        if (!isset($criteria ['search_by_keyword']) or $criteria ['search_by_keyword'] == false) {
            $criteria ['search_by_keyword'] = $criteria ['keywords'];
        }
    }

    if (isset($criteria ['search_keyword'])) {
        if (!isset($criteria ['search_by_keyword']) or $criteria ['search_by_keyword'] == false) {
            $criteria ['search_by_keyword'] = $criteria ['search_keyword'];
        }
    }

    if (isset($criteria ['search_in_fields'])) {
        if ($criteria ['search_by_keyword_in_fields'] == false) {
            $criteria ['search_by_keyword_in_fields'] = $criteria ['search_in_fields'];
        }
    }

    if (isset($criteria ['search_by_keyword']) and strval(trim($criteria ['search_by_keyword'])) != '') {

        $to_search = trim($criteria ['search_by_keyword']);

        // p($to_search,1);
    }

    if (isset($criteria ['search_by_keyword_in_fields']) and is_array(($criteria ['search_by_keyword_in_fields']))) {

        if (!empty($criteria ['search_by_keyword_in_fields'])) {

            $to_search_in_those_fields = $criteria ['search_by_keyword_in_fields'];
        }
    }

    // if ($count_only == false) {
    // var_dump ( $cache_group );
    if ($cache_group != false) {

        $cache_group = trim($cache_group);

        // $start_time = mktime ();

        if ($force_cache_id != false) {

            $cache_id = $force_cache_id;

            $function_cache_id = $force_cache_id;
        } else {

            $function_cache_id = false;

            $args = func_get_args();

            foreach ($args as $k => $v) {

                $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
            }

            $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

            $cache_id = $function_cache_id;
        }

        $original_cache_id = $cache_id;

        $cache_content = cache_get_content($original_cache_id, $original_cache_group);

        if ($cache_group == 'taxonomy') {

            //
        }

        if (($cache_content) != false) {

            if ($cache_content == '---empty---') {

                return false;
            }

            if ($count_only == true) {

                $ret = $cache_content [0] ['qty'];

                return $ret;
            } else {

                return $cache_content;
            }
        }
    }

    if (!empty($orderby)) {

        $order_by = " ORDER BY  {$orderby[0]}  {$orderby[1]}  ";
    } else {

        $order_by = false;
    }

    if ($qLimit == '' and ($limit != false) and $count_only == false) {
        if (is_array($limit)) {
            $offset = $limit [1] - $limit [0];
            $limit = " limit  {$limit[0]} , $offset  ";
        } else {
            $limit = " limit  0 , {$limit}  ";
        }
    } else {

        $limit = false;
    }

    $criteria = map_array_to_database_table($table, $criteria);

    if (!empty($criteria)) {

        // $query = $this->db->get_where ( $table, $criteria, $limit,
        // $offset );
    } else {

        // $query = $this->db->get ( $table, $limit, $offset );
    }

    if ($only_those_fields == false) {

        $q = "SELECT * FROM $table ";
    } else {

        if (is_array($only_those_fields)) {

            if (!empty($only_those_fields)) {

                $flds = implode(',', $only_those_fields);

                $q = "SELECT $flds FROM $table ";
            } else {

                $q = "SELECT * FROM $table ";
            }
        } else {

            $q = "SELECT * FROM $table ";
        }
    }

    if ($count_only == true) {

        $q = "SELECT count(*) as qty FROM $table ";
    }

    $where = false;

    if (is_array($ids)) {

        if (!empty($ids)) {

            $idds = false;

            foreach ($ids as $id) {

                $id = intval($id);

                $idds .= "   OR id=$id   ";
            }

            $idds = "  and ( id=0 $idds   ) ";
        } else {

            $idds = false;
        }
    }

    if (!empty($exclude_ids)) {

        $first = array_shift($exclude_ids);

        $exclude_idds = false;

        foreach ($exclude_ids as $id) {

            $id = intval($id);

            $exclude_idds .= "   AND id<>$id   ";
        }

        $exclude_idds = "  and ( id<>$first $exclude_idds   ) ";
    } else {

        $exclude_idds = false;
    }

    if (!empty($includeIds)) {

        // $first = array_shift ( $includeIds );

        $includeIds_idds = false;
        // p ( $includeIds );
        // p($includeIds);

        $includeIds_i = implode(',', $includeIds);

        $includeIds_idds .= "   AND id IN ($includeIds_i)   ";
    } else {

        $includeIds_idds = false;
    }
    $to_search = false;
    if ($to_search != false) {

        $fieals = db_get_table_fields($table);

        $where_post = ' OR ';

        if (!$where) {

            $where = " WHERE ";
        }
        $where_q = '';

        foreach ($fieals as $v) {

            $add_to_seachq_q = true;

            if (!empty($to_search_in_those_fields)) {

                if (in_array($v, $to_search_in_those_fields) == false) {

                    $add_to_seachq_q = false;
                }
            }

            if ($add_to_seachq_q == true) {

                if ($v != 'id' && $v != 'password') {

                    // $where .= " $v like '%$to_search%' " . $where_post;
                    if (DB_IS_SQLITE == false) {

                        $where_q .= " $v REGEXP '$to_search' " . $where_post;
                    } else {
                        $where_q .= " $v LIKE '%$to_search%' " . $where_post;
                    }
                    // 'new\\*.\\*line';
                    // $where .= " MATCH($v) AGAINST ('*$to_search* in
                    // boolean mode') " . $where_post;
                }
            }
        }

        $where_q = rtrim($where_q, ' OR ');

        if ($includeIds_idds != false) {
            $where = $where . '  (' . $where_q . ')' . $includeIds_idds;
        } else {

            $where = $where . $where_q;
        }
    } else {

        if (!empty($criteria)) {

            if (!$where) {

                $where = " WHERE ";
            }
            foreach ($criteria as $k => $v) {
                $compare_sign = '=';
                if (stristr($v, '[lt]')) {
                    $compare_sign = '<=';
                    $v = str_replace('[lt]', '', $v);
                }

                if (stristr($v, '[mt]')) {

                    $compare_sign = '>=';

                    $v = str_replace('[mt]', '', $v);
                }
                /*
                 * var_dump ( $k ); var_dump ( $v ); print '<hr>';
                 */
                if (($k == 'updated_on') or ($k == 'created_on')) {

                    $v = strtotime($v);
                    $v = date("Y-m-d H:i:s", $v);
                }

                $where .= "$k {$compare_sign} '$v' AND ";
            }

            $where .= " ID is not null ";
        } else {

            $where = " WHERE ";

            $where .= " ID is not null ";
        }
    }
    if (!isset($idds)) {
        $idds = '';
    }

    if ($where != false) {

        $q = $q . $where . $idds . $exclude_idds;
    } else {
        $q = $q . " WHERE " . $idds . $exclude_idds;
    }
    if ($includeIds_idds != false) {
        $q = $q . $includeIds_idds;
    }
    if ($count_only != true) {
        $q .= " group by ID  ";
    }
    if ($order_by != false) {

        $q = $q . $order_by;
    }

    if (trim($limit_from_paging_q) != "") {
        $limit = $limit_from_paging_q;
    } else {

    }
    if ($limit != false) {

        $q = $q . $limit;
    }

    if ($debug == true) {

        var_dump($table, $q);
    }

    $result = db_query($q);
    if ($count_only == true) {

        // var_dump ( $result );
        // exit ();
    }

    if (isset($result [0] ['qty']) == true) {

        // p($result);
        $ret = $result [0] ['qty'];

        return $ret;
    }

    if ($cache_group != false) {

        if (!empty($result)) {

            // p($original_cache_group);
            // p($cache_id);
            cache_store_data($result, $original_cache_id, $original_cache_group);
        } else {

            cache_store_data('---empty---', $original_cache_id, $original_cache_group);
        }
    }

    // var_dump($result);
    if ($count_only == true) {

        $ret = $result [0] ['qty'];

        return $ret;
    }

    $return = array();

    if (!empty($result)) {

        foreach ($result as $k => $v) {

            $v = remove_slashes_from_array($v);

            $return [$k] = $v;
        }
    }

    // var_dump ( $return );
    return $return;
}

function db_get_table_name($assoc_name) {
    $cms_db_tables = c('db_tables'); // ->'table_options';

    if (!empty($cms_db_tables)) {

        foreach ($cms_db_tables as $k => $v) {

            // var_dump($k, $v);
            if (strtolower($assoc_name) == strtolower($v)) {

                // $table_assoc_name = $k;
                return $v;
            }
        }

        return $assoc_name;
    }
}

function db_get_real_table_name($assoc_name) {
    $cms_db_tables = c('db_tables');

    if (!empty($cms_db_tables)) {

        foreach ($cms_db_tables as $k => $v) {

            // var_dump($k, $v);
            if (strtolower($assoc_name) == strtolower($v)) {

                // $table_assoc_name = $k;
                return $k;
            }
        }

        return $assoc_name;
    }
}

/**
 * returns array that contains only keys that has the same names as the
 * table fields from the database
 *
 * @param
 *        	string
 * @param
 *        	array
 * @return array
 * @author Peter Ivanov
 * @version 1.0
 * @since Version 1.0
 */
function map_array_to_database_table($table, $array) {
    if (empty($array)) {

        return false;
    }
    // $table = db_get_table_name($table);

    $fields = db_get_table_fields($table);

    foreach ($fields as $field) {

        $field = strtolower($field);

        if (array_key_exists($field, $array)) {

            if ($array [$field] != false) {

                // print ' ' . $field. ' <br>';
                $array_to_return [$field] = $array [$field];
            }

            if ($array [$field] == 0) {

                $array_to_return [$field] = $array [$field];
            }
        }
    }
    if (!isset($array_to_return)) {
        return false;
    }
    return $array_to_return;
}

/**
 * Gets all field names from a DB table
 *
 * @param $table string
 *        	- table name
 * @param $exclude_fields array
 *        	- fields to exclude
 * @return array
 * @author Peter Ivanov
 * @version 1.0
 * @since Version 1.0
 */
function db_get_table_fields($table, $exclude_fields = false) {
    if (!$table) {

        return false;
    }

    $function_cache_id = false;

    $args = func_get_args();

    foreach ($args as $k => $v) {

        $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
    }

    $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

    $cache_content = cache_get_content($function_cache_id, 'db');

    if (($cache_content) != false) {

        return $cache_content;
    }

    $table = db_get_table_name($table);

    if (DB_IS_SQLITE != false) {
        $sql = "PRAGMA table_info('{$table}');";
    } else {
        $sql = "show columns from $table";
    }

    //   $sql = "DESCRIBE $table";

    $query = db_query($sql);

    $fields = $query;

    $exisiting_fields = array();

    foreach ($fields as $fivesdraft) {

        $fivesdraft = array_change_key_case($fivesdraft, CASE_LOWER);
        if (isset($fivesdraft ['name'])) {
            $fivesdraft ['field'] = $fivesdraft ['name'];
        }


        $exisiting_fields [strtolower($fivesdraft ['field'])] = true;
    }

    // var_dump ( $exisiting_fields );
    $fields = array();

    foreach ($exisiting_fields as $k => $v) {

        if (!empty($exclude_fields)) {

            if (in_array($k, $exclude_fields) == false) {

                $fields [] = $k;
            }
        } else {

            $fields [] = $k;
        }
    }

    cache_store_data($fields, $function_cache_id, $cache_group = 'db');

    // $fields = (array_change_key_case ( $fields, CASE_LOWER ));
    return $fields;
}

/**
 * Generic save data function, it saves data to the database
 *
 * @param
 *        	string
 * @param
 *        	array
 * @param
 *        	array
 * @return string
 * @author Peter Ivanov
 *
 * @uses add_slashes_to_array()
 * @uses cache_clean_group()
 * @uses session_get()
 * @uses map_array_to_database_table()
 *
 */
function save_data($table, $data, $data_to_save_options = false) {
    $cms_db_tables = c('db_tables');

    if (is_array($data) == false) {

        return false;
    }

    $data ['session_id'] = session_id();

    $original_data = $data;

    $is_quick = isset($original_data ['quick_save']);

    if ($is_quick == false) {
        if (isset($data ['updated_on']) == false) {

            $data ['updated_on'] = date("Y-m-d H:i:s");
        }
    }

    if (isset($data_to_save_options) and !empty($data_to_save_options)) {

        if (!empty($data_to_save_options ['delete_cache_groups'])) {

            foreach ($data_to_save_options ['delete_cache_groups'] as $item) {

                cache_clean_group($item);
            }
        }
    }

    $user_session = session_get('user_session');
    if ($user_session == false) {
        error('You can\'t save data when you are not logged in. ');
    }

    if (isset($data ['cf_temp'])) {
        $cf_temp = $data ['cf_temp'];
    }

    if (isset($data ['created_by'])) {
        $the_user_id = $data ['created_by'];

        $the_user_id = $data ['created_by'];
    } else {
        $the_user_id = user_id();
    }
    if (isset($data ['screenshot_url'])) {
        $screenshot_url = $data ['screenshot_url'];
    }

    if (isset($data ['debug']) and $data ['debug'] == true) {
        $dbg = 1;
        unset($data ['debug']);
    } else {

        $dbg = false;
    }

    if (isset($data ['queue_id']) != false) {
        $queue_id = $data ['queue_id'];
    }

    if (isset($data ['url']) == false) {
        $url = url_string();
        $data ['url'] = $url;
    }



    $data ['user_ip'] = USER_IP;
    if (isset($data ['id']) == false or $data ['id'] == 0) {
        $data ['id'] = 0;
        $data ['new_id'] = intval(db_last_id($table) + 1);
        $original_data ['new_id'] = $data ['new_id'];
    }


    if (isset($data ['custom_field_value'])) {
        if (is_array($data ['custom_field_value'])) {
            $data ['custom_field_values'] = base64_encode(serialize($data ['custom_field_value']));
            $data ['custom_field_value'] = 'Array';
            //$cfvq = "custom_field_values =\"" . $custom_field_to_save ['custom_field_values'] . "\",";
        }
    }
    // var_dump($data);
    if (intval($data ['id']) == 0) {

        if (isset($data ['created_on']) == false) {

            $data ['created_on'] = date("Y-m-d H:i:s");
        }

        $data ['created_by'] = $the_user_id;

        $data ['edited_by'] = $the_user_id;
    } else {

        // $data ['created_on'] = false;
        $data ['edited_by'] = $the_user_id;
    }
    $table_assoc_name = ( $table );

    $criteria_orig = $data;

    $criteria = map_array_to_database_table($table, $data);

    // p($original_data);p($criteria);die;
    //  if ($data_to_save_options ['do_not_replace_urls'] == false) {

    $criteria = replace_site_vars($criteria);
    //  }

    if ($data_to_save_options ['use_this_field_for_id'] != false) {

        $criteria ['id'] = $criteria_orig [$data_to_save_options ['use_this_field_for_id']];
    }

    // $criteria = map_array_to_database_table ( $table, $data );

    if (DB_IS_SQLITE != false) {

    } else {
        $criteria = add_slashes_to_array($criteria);
    }
    $db = new DB(c('db'));
    // $criteria = $this->addSlashesToArray ( $criteria );
    if (intval($criteria ['id']) == 0) {


        if (isset($original_data ['new_id']) and intval($original_data ['new_id']) != 0) {

            $criteria ['id'] = $original_data ['new_id'];
        }

        // insert
        $data = $criteria;

        // $this->db->insert ( $table, $data );


        if (DB_IS_SQLITE == false) {
            $q = " INSERT INTO  $table set ";

            foreach ($data as $k => $v) {

                // $v
                if (strtolower($k) != $data_to_save_options ['use_this_field_for_id']) {

                    if (strtolower($k) != 'id') {

                        // $v =
                        // $this->content_model->applyGlobalTemplateReplaceables
                        // ( $v );
                        if (DB_IS_SQLITE) {
                            $v = sqlite_escape_string($v);
                        }
                        $q .= "$k = '$v' , ";
                    }
                }
            }

            if (isset($original_data ['new_id']) and intval($original_data ['new_id']) != 0) {
                $n_id = $original_data ['new_id'];
            } else {
                $n_id = "NULL";
            }

            if ($data_to_save_options ['use_this_field_for_id'] != false) {

                $q .= " " . $data_to_save_options ['use_this_field_for_id'] . "={$n_id} ";
            } else {

                $q .= " id={$n_id} ";
            }
        }

        if (DB_IS_SQLITE != false) {
            $q = $db->insert($table, $criteria);
        } else {
            db_q($q);
        }




        // exit ();
        // $this->dbQ ( $q );
        //

        $id_to_return = db_last_id($table);
    } else {

        // update
        $data = $criteria;

        // $n = $this->db->update ( $table, $data, "id = {$data ['id']}" );
        $q = " UPDATE  $table set ";

        foreach ($data as $k => $v) {

            // $v = htmlspecialchars ( $v, ENT_QUOTES );
            $q .= "$k = '$v' , ";
        }

        $q .= " id={$data ['id']} WHERE id={$data ['id']} ";

        // db_q($q);

        if (DB_IS_SQLITE != false) {
            $q = $db->update($table, $criteria, $w = array('id' => $data ['id']));
        } else {
            db_q($q);
        }





        $id_to_return = $data ['id'];
    }

    if ($dbg != false) {
        d($q);
    }


    // p($original_data);
    /*
     * if (!empty ( $original_data ['taxonomy_categories_str'] )) {
     * p($original_data ['taxonomy_categories_str'] ,1); foreach (
     * $original_data ['taxonomy_categories_str'] as $taxonomy_item ) {
     * $test_if_exist_cat = get_category ( $taxonomy_item ); } }
     */

    /* $taxonomy_table = $cms_db_tables ['table_taxonomy'];
      $taxonomy_items_table = $cms_db_tables ['table_taxonomy_items'];
      // p ( $original_data );
      if (is_array ( $original_data ['taxonomy_categories'] )) {

      $taxonomy_save = array ();

      $taxonomy_save ['to_table'] = $table_assoc_name;

      $taxonomy_save ['to_table_id'] = $id_to_return;

      $taxonomy_save ['taxonomy_type'] = 'category_item';

      if (trim ( $original_data ['content_type'] ) != '') {

      $taxonomy_save ['content_type'] = $original_data ['content_type'];
      }

      // $this->deleteData ( $taxonomy_table, $taxonomy_save, 'taxonomy'
      // );
      $q = " DELETE FROM  $taxonomy_items_table where to_table='$table_assoc_name' and to_table_id='$id_to_return' and  content_type='{$original_data ['content_type']}' and  taxonomy_type= 'category_item'     ";
      // p ( $q );
      db_query ( $q );

      foreach ( $original_data ['taxonomy_categories'] as $taxonomy_item ) {

      $taxonomy_item = trim ( $taxonomy_item );
      $parent_cat = get_category ( $taxonomy_item );

      $parent_cat_id = intval ( $parent_cat ['id'] );
      // var_dump($parent_cat);
      // $taxonomy_item = explode($taxonomy_item);

      // check if parent category exists
      // $taxonomy_item
      // $q = " SELECT * FROM $taxonomy_table where
      // id='$taxonomy_item' and taxonomy_value LIKE '$taxonomy_item'
      // and taxonomy_type= 'category' ";

      // $catcheck = $this->dbQuery ( $q );

      $q = " INSERT INTO  $taxonomy_items_table set to_table='$table_assoc_name', to_table_id='$id_to_return' , content_type='{$original_data ['content_type']}' ,  taxonomy_type= 'category_item' , parent_id='$parent_cat_id'   ";
      // p ( $q );
      db_query ( $q );
      cache_clean_group ( 'taxonomy/' . $parent_cat_id );
      }
      cache_clean_group ( 'taxonomy/global' );

      // exit ();
      } */

    // upload media
    /*
     * if ($table_assoc_name != 'table_media') { if ($queue_id) {
     * $this->mediaAfterUploadAssociatetheMediaQueueWithTheId (
     * $table_assoc_name, $id_to_return, $queue_id ); } } if ($table_assoc_name
     * != 'table_media') { if (strval ( $original_data ['media_queue_pictures']
     * ) != '') { $this->mediaAfterUploadAssociatetheMediaQueueWithTheId (
     * $table_assoc_name, $id_to_return, $original_data ['media_queue_pictures']
     * ); } if (strval ( $original_data ['media_queue_videos'] ) != '') {
     * $this->mediaAfterUploadAssociatetheMediaQueueWithTheId (
     * $table_assoc_name, $id_to_return, $original_data ['media_queue_videos']
     * ); } if (strval ( $original_data ['media_queue_files'] ) != '') {
     * $this->mediaAfterUploadAssociatetheMediaQueueWithTheId (
     * $table_assoc_name, $id_to_return, $original_data ['media_queue_files'] );
     * } // $this->mediaUpload ( $table_assoc_name, $id_to_return );
     * $this->mediaUploadVideos ( $table_assoc_name, $id_to_return );
     * $this->mediaUploadFiles ( $table_assoc_name, $id_to_return ); } else {
     * $this->mediaUpload ( $table_assoc_name, $id_to_return ); } if (strval (
     * $screenshot_url ) != '') { $this->mediaUploadByUrl ( $screenshot_url,
     * $table_assoc_name, $id_to_return ); }
     */

    // adding custom fields

    if (!isset($original_data ['skip_custom_field_save']) and isset($original_data ['custom_fields']) and $table_assoc_name != 'table_custom_fields') {
        $cms_db_tables = c('db_tables');
        $custom_field_to_save = array();

        foreach ($original_data as $k => $v) {

            if (stristr($k, 'custom_field_') == true) {

                // if (strval ( $v ) != '') {
                $k1 = str_ireplace('custom_field_', '', $k);

                if (trim($k) != '') {

                    $custom_field_to_save [$k1] = $v;
                }

                // }
            }
        }

        if (is_array($original_data ['custom_fields']) and !empty($original_data ['custom_fields'])) {
            $custom_field_to_save = array_merge($custom_field_to_save, $original_data ['custom_fields']);
        }



        if (!empty($custom_field_to_save)) {
            // p($is_quick);
            $custom_field_table = $cms_db_tables ['table_custom_fields'];
            $table_assoc_name = db_get_real_table_name($table_assoc_name);
            if ($is_quick == false) {


                $custom_field_to_delete ['to_table'] = $table_assoc_name;

                $custom_field_to_delete ['to_table_id'] = $id_to_return;
            }
            // p($original_data);
            if (isset($original_data ['skip_custom_field_save']) == false) {



                foreach ($custom_field_to_save as $cf_k => $cf_v) {

                    if (($cf_v != '')) {

                        if ($cf_k != '') {
                            $clean = " delete from $custom_field_table where
				to_table =\"{$table_assoc_name}\"
				and
				to_table_id =\"{$id_to_return}\"
				and
				custom_field_name =\"{$cf_k}\"


				";


                            //	d($clean);
                            db_q($clean);
                        }
                        $cfvq = '';
                        $custom_field_to_save ['custom_field_name'] = $cf_k;
                        if (is_array($cf_v)) {
                            $custom_field_to_save ['custom_field_values'] = base64_encode(json_encode($cf_v));
                            $cfvq = "custom_field_values =\"" . $custom_field_to_save ['custom_field_values'] . "\",";
                        }
                        $custom_field_to_save ['custom_field_value'] = $cf_v;

                        $custom_field_to_save ['to_table'] = $table_assoc_name;

                        $custom_field_to_save ['to_table_id'] = $id_to_return;
                        $custom_field_to_save ['skip_custom_field_save'] = true;








                        if (DB_IS_SQLITE != false) {
                            //  $custom_field_to_save = add_slashes_to_array($custom_field_to_save, $is_sqlite);
                        } else {
                            $custom_field_to_save = add_slashes_to_array($custom_field_to_save);
                        }

                        $add = " insert into $custom_field_table set
			custom_field_name =\"{$cf_k}\",
			$cfvq
			custom_field_value =\"" . $custom_field_to_save ['custom_field_value'] . "\",
			to_table =\"" . $custom_field_to_save ['to_table'] . "\",
			to_table_id =\"" . $custom_field_to_save ['to_table_id'] . "\"
			";
                        $cf_to_save = array();
                        $cf_to_save['id'] = 0;
                        $cf_to_save['custom_field_name'] = $cf_k;
                        $cf_to_save['to_table'] = $custom_field_to_save ['to_table'];
                        $cf_to_save['to_table_id'] = $custom_field_to_save ['to_table_id'];
                        $cf_to_save['custom_field_value'] = $custom_field_to_save ['custom_field_value'];

                        if (isset($custom_field_to_save ['custom_field_values'])) {
                            $cf_to_save['custom_field_values'] = $custom_field_to_save ['custom_field_values'];
                        }
                        $cf_to_save['custom_field_name'] = $cf_k;
                        $cf_to_save['custom_field_name'] = $cf_k;


                        if (DB_IS_SQLITE != false) {
                            $q = $db->insert($custom_field_table, $cf_to_save);
                        } else {
                            db_q($add);
                        }






                        //   print($add);
                        //  db_q($add);
                    }
                }
                cache_clean_group('custom_fields');
                // cache_clean_group ( 'global' );
                //	cache_clean_group ( 'extract_tags' );
            }
        }
    }
    return $id_to_return;
    if (intval($data ['edited_by']) == 0) {

        $data ['edited_by'] = $user_session ['user_id'];
    }

    // p($aUserId,1);
    // var_dump ( $table_assoc_name );
    // p ( $data ['edited_by'], 1 );
    /*
     * if (strval ( $table_assoc_name ) != '') { if (intval ( $data
     * ['edited_by'] ) != 0) { $to_execute_query = false; global
     * $users_log_exclude; global $users_log_include; if (empty (
     * $users_log_include )) { // ..p($users_log_exclude,1); if (empty (
     * $users_log_exclude )) { $to_execute_query = true; // be careful } else {
     * if (in_array ( $table_assoc_name, $users_log_exclude ) == true) {
     * $to_execute_query = false; } else { $to_execute_query = true; } } if
     * ($table_assoc_name == 'table_users_notifications') { $to_execute_query =
     * false; } if ($table_assoc_name == 'table_custom_fields') {
     * $to_execute_query = false; } if ($table_assoc_name == 'table_media') {
     * $to_execute_query = false; } if ($table_assoc_name == 'bb_forums') {
     * $to_execute_query = false; } } else { if (in_array ( $table_assoc_name,
     * $users_log_include ) == true) { $to_execute_query = true; } else {
     * $to_execute_query = false; } } if ($to_execute_query == true) { // @todo
     * later: funtionality and documentation to move the // log in seperate
     * database cause of possible load issues on // social networks created witm
     * microweber $rel_table = $data ['to_table']; $rel_table_id = $data
     * ['to_table_id']; if ($rel_table == false) { $rel_table =
     * $table_assoc_name; } if ($rel_table_id == false) { $rel_table_id =
     * $id_to_return; } global $cms_db_tables; $by = intval ( $data
     * ['edited_by'] ); $by2 = intval ( $data ['created_by'] ); $now = date (
     * "Y-m-d H:i:s" ); $session_id = $this->session->userdata ( 'session_id' );
     * $users_table = $cms_db_tables ['table_users_log']; $q = " INSERT INTO
     * $users_table set "; $q .= " created_on ='{$now}', user_id={$by}, "; $q .=
     * " to_table_id={$id_to_return}, "; $q .= " to_table='{$table_assoc_name}'
     * ,"; $q .= " rel_table='{$rel_table}', "; $q .= "
     * rel_table_id={$rel_table_id} ,"; $q .= " edited_by={$by} ,"; $q .= "
     * created_by={$by2} ,"; $q .= " session_id='{$session_id}' , "; $q .= "
     * is_read='n' , "; $q .= " user_ip='{$_SERVER['REMOTE_ADDR']}'"; } } }
     */

    cache_clean_group('global');

    return intval($id_to_return);
}

/**
 * save data
 *
 * @author Peter Ivanov
 */
function db_last_id($table) {

    $db = new DB(c('db'));

    if (DB_IS_SQLITE == true) {

        // $q = "SELECT last_insert_rowid()   FROM $table limit 1";

        $q = "SELECT ROWID as the_id from $table order by ROWID DESC limit 1";
    } else {
        $q = "SELECT LAST_INSERT_ID() as the_id FROM $table limit 1";
    }

    $q = db_query($q);

    $result = $q [0];

    //
    return intval($result ['the_id']);
}