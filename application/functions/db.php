<?php

define("DB_IS_SQLITE", false);

function db_escape_string($value) {
    $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
    $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");

    return str_replace($search, $replace, $value);
}

function db_delete_by_id($table, $id = 0, $field_name = 'id') {
    $table = guess_table_name($table);
    $table_real = db_get_real_table_name($table);
    $id = intval($id);

    if ($id == 0) {

        return false;
    }

    $q = "DELETE from $table_real where {$field_name}=$id ";

    $cg = guess_cache_group($table);
    //
    // d($cg);
    cache_clean_group($cg);
    $q = db_q($q);

    $cms_db_tables = c('db_tables');

    $table1 = $cms_db_tables['table_taxonomy'];
    $table_items = $cms_db_tables['table_taxonomy_items'];

    $q = "DELETE from $table1 where to_table_id=$id  and  to_table='$table'  ";

    $q = db_q($q);
    //  cache_clean_group('taxonomy');

    $q = "DELETE from $table_items where to_table_id=$id  and  to_table='$table'  ";
    //d($q);
    $q = db_q($q);

    //   cache_clean_group('taxonomy_items');
    //	d($q);
}

function db_get_id($table, $id = 0, $field_name = 'id') {

    $id = intval($id);

    if ($id == 0) {

        return false;
    }

    if ($field_name == false) {
        $field_name = "id";
    }

    $table = db_get_table_name($table);

    $q = "SELECT * from $table where {$field_name}=$id limit 1";
    // d($q);
    $q = db_query($q);
    if (isset($q[0])) {
        $q = $q[0];
    }
    // /$q = intval ( $q );

    if (count($q) > 0) {

        return $q;
    } else {

        return false;
    }
}

function guess_cache_group($for = false) {
    return guess_table_name($for, true);
}

function guess_table_name($for = false, $guess_cache_group = false) {
    $cms_db_tables = c('db_tables');

    foreach ($cms_db_tables as $k => $cms_db_table) {
        if (strtolower($k) == strtolower($for) or strtolower($k) == strtolower('table_' . $for)) {
            $to_table = $cms_db_table;
        }
    }

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

            case 'category_items' :
            case 'cat_items' :
            case 'tag_items' :
            case 'tags_items' :
                $to_table = 'table_taxonomy_items';
                break;

            case 'post' :
            case 'page' :
            case 'content' :

            default :
                $to_table = $for;
                break;
        }
        $for = $to_table;
    } else {
        
    }
    if ($guess_cache_group != false) {

        $for = str_replace('table_', '', $for);
        $for = str_replace(TABLE_PREFIX, '', $for);
    }

    return $for;
}

function db_query_log($q) {
    static $index = array();
    if (is_bool($q)) {
        $index = array_unique($index);
        return $index;
    } else {

        $index[] = $q;
    }
}

function db_q($q, $connection_settigns = false) {

    if (MW_IS_INSTALLED == false) {
        //    return false;
    }
    if ($connection_settigns == false) {
        $db = c('db');
    } else {
        $db = $connection_settigns;
    }
    $q = db_query($q, $cache_id = false, $cache_group = false, $only_query = true, $db);
    //    $db = c('db');
    //
	//    $mysqli = new mysqli($db['host'], $db['user'], $db['pass'], $db['dbname']);
    //    db_query_log($q);
    //    //   $mysqli->query("SET NAMES 'utf8'");
    //    $q = $mysqli->query($q);
    return $q;
}

function db_query($q, $cache_id = false, $cache_group = 'global', $only_query = false, $connection_settigns = false) {
    if (trim($q) == '') {
        return false;
    }
    $error['error'] = array();
    $results = false;
    // if (MW_IS_INSTALLED != false) {
    if ($cache_id != false and $only_query == false) {
        // $results =false;
        $cache_id = $cache_id . crc32($q);
        $results = cache_get_content($cache_id, $cache_group);
        if ($results != false) {
            if ($results == '---empty---') {
                return false;
            } else {
                return $results;
            }
        }
    }
    // }
    db_query_log($q);
    if ($connection_settigns != false and is_array($connection_settigns) and !empty($connection_settigns)) {
        $db = $connection_settigns;
    } else {
        $db = c('db');
    }

    //  var_dump($db);
    // $is_mysqli = function_exists('mysqli_connect');
    $is_mysqli = false;
    if ($is_mysqli != false) {
        $mysqli = new mysqli($db['host'], $db['user'], $db['pass'], $db['dbname']);

        $result = $mysqli->query($q);

        if (!$result) {
            $error['error'][] = $mysqli->database->error;

            return $error;
            // throw new Exception("Database Error [{$this->database->errno}] {$this->database->error}");
        } else {

            if ($only_query == false) {
                $nwq = array();
                while ($row = $result->fetch_array()) {

                    $nwq[] = $row;
                }
                $q = $nwq;
            }
        }
    } else {
        static $link;
        if ($link == false) {
            $link = mysql_connect($db['host'], $db['user'], $db['pass']);
        }
        if ($link == false) {
            $error['error'][] = 'Could not connect: ' . mysql_error();
            return $error;
        }

        if (mysql_select_db($db['dbname']) == false) {
            $error['error'][] = 'Could not select database ' . $db['dbname'];
            return $error;
        }

        // Performing SQL query
        $query = $q;
        $result = mysql_query($query);
        if (!$result) {


            $error['error'][] = 'Query failed: ' . mysql_error();
            return $error;
        }
        $nwq = array();

        if (!$result) {
            $error['error'][] = 'Can\'t connect to the database';
            return $error;
        } else {
            if ($only_query == false) {
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

                    $nwq[] = $row;
                }
                $q = $nwq;
            }
        }

        // Free resultset
        if ($only_query == false) {
            if (is_array($result)) {
                mysql_free_result($result);
            }
        }
        // Closing connection
        // mysql_close($link);
        // $result = null;
    }

    if ($only_query != false) {
        return true;
    }

    // $mysqli->close();
    // $db = new DB(c('db'));
    //  $q = $db->get($q);
    // d($q);
    //  unset($db);
    // if (MW_IS_INSTALLED != false) {
    if ($only_query == false and empty($q) or $q == false) {
        if ($cache_id != false) {


            // cache_store_data('---empty---', $cache_id, $cache_group);
        }
        return false;
    }
    if ($only_query == false) {
        // $result = $q;
        if ($cache_id != false) {
            if (isarr($q)) {

                cache_save($q, $cache_id, $cache_group);
            } else {
                cache_store_data('---empty---', $cache_id, $cache_group);
            }
        }
    }
    // }
    return $q;
//remove below
    $results = array();
    if (!empty($q)) {
        foreach ($q as $result) {

            if (isset($result['custom_field_value'])) {
                if (strtolower($result['custom_field_value']) == 'array') {
                    if (isset($result['custom_field_values'])) {
                        $result['custom_field_values'] = unserialize(base64_decode($result['custom_field_values']));
                        $result['custom_field_value'] = 'Array';
                        //$cfvq = "custom_field_values =\"" . $custom_field_to_save ['custom_field_values'] . "\",";
                    }
                }
            }
            $results[] = remove_slashes_from_array($result);
        }
    }

    $result = $results;

    if ($cache_id != false) {
        if (!empty($result)) {
            //    cache_store_data($result, $cache_id, $cache_group);
        } else {
            cache_store_data('---empty---', $cache_id, $cache_group);
        }
    }
    print '0000000000000000000000000000000---------------------';
    //d($result);
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
 *              $post_params_paging['page_count'] = true;  //get the page count
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
if (is_admin() == true) {
    api_expose('get');
}

function get($params) {
    $orderby = false;
    $cache_group = false;
    $debug = false;
    $getone = false;
    $no_cahce = false;

    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
        extract($params);
    }
    $criteria = array();
    foreach ($params as $k => $v) {
        if ($k == 'table') {
            $table = $v;
        }

        if ($k == 'what') {
            $table = guess_table_name($v);
        }


        if ($k == 'for' and !isset($params['to_table'])) {
            $v = db_get_assoc_table_name($v);
            $k = 'to_table';
        }

        if ($k == 'debug') {
            $debug = ($v);
        }

        if ($k == 'cache_group') {
            if ($no_cahce == false) {
                $cache_group = $v;
            }
        }

        if ($k == 'no_cache') {
            $cache_group = false;
            $no_cahce = true;
        }

        if ($k == 'one') {
            $getone = true;
        } else {

            $criteria[$k] = $v;
        }

        if ('orderby' == $k) {
            $orderby = $v;
        }
    }

    if ($cache_group == false and $debug == false) {
        $cache_group = guess_cache_group($table);
        if (!isset($criteria['id'])) {
            $cache_group = $cache_group . '/global';
        } else {
            $cache_group = $cache_group . '/' . $criteria['id'];
        }

        // d($cache_group);
    }

    $ge = db_get_long($table, $criteria, $limit = false, $offset = false, $orderby, $cache_group, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false);
    //d($ge);
    if ($getone == true) {
        if (isset($ge[0])) {
            return $ge[0];
        }
    }

    return $ge;
}

function db_get($table, $criteria, $cache_group = false) {
    return db_get_long($table, $criteria, $limit = false, $offset = false, $orderby = false, $cache_group, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false);
}

/**
 * get data
 * Microweber CMS.
 * Everything relies on it.
 *
 * @author Peter Ivanov
 */
function db_get_long($table = false, $criteria = false, $limit = false, $offset = false, $orderby = false, $cache_group = false, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false) {
    $cms_db_tables = c('db_tables');
    // ->'table_options';
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
    //  $table = db_g($table);
    $table = db_get_real_table_name($table);

    $aTable_assoc = db_get_table_name($table);
    $includeIds = array();
    if (!empty($criteria)) {
        if (isset($criteria['debug'])) {
            $debug = true;
            if (($criteria['debug'])) {
                $criteria['debug'] = false;
            } else {
                unset($criteria['debug']);
            }
        }
        if (isset($criteria['cache_group'])) {
            $cache_group = $criteria['cache_group'];
        }
        if (isset($criteria['no_cache'])) {
            $cache_group = false;
            if (($criteria['no_cache']) == true) {
                $criteria['no_cache'] = false;
            } else {
                unset($criteria['no_cache']);
            }
        }

        if (isset($criteria['count_only']) and $criteria['count_only'] == true) {
            $count_only = $criteria['count_only'];

            unset($criteria['count_only']);
        }

        if (isset($criteria['count'])) {
            $count_only = $criteria['count'];

            unset($criteria['count']);
        }

        if (isset($criteria['get_count']) and $criteria['get_count'] == true) {
            $count_only = true;

            unset($criteria['get_count']);
        }

        if (isset($criteria['count']) and $criteria['count'] == true) {
            $count_only = $criteria['count'];
            unset($criteria['count']);
        }

        if (isset($criteria['with_pictures']) and $criteria['with_pictures'] == true) {
            $with_pics = true;
        }

        if (isset($criteria['data-limit'])) {
            $limit = $criteria['limit'] = $criteria['data-limit'];
        }

        $count_paging = false;
        if (isset($criteria['page_count']) or isset($criteria['data-page-count'])) {
            $count_only = true;
            $count_paging = true;
        }

        $_default_limit = 30;

        if (isset($criteria['limit']) and $criteria['limit'] == true and $count_only == false) {
            $limit = $criteria['limit'];
        }
        if (isset($criteria['limit'])) {
            $limit = $criteria['limit'];
        }

        $curent_page = isset($criteria['curent_page']) ? $criteria['curent_page'] : false;

        if ($curent_page == false) {
            $curent_page = isset($criteria['data-curent-page']) ? $criteria['data-curent-page'] : false;
        }

        $offset = isset($criteria['offset']) ? $criteria['offset'] : false;

        if ($limit == false) {
            $limit = isset($criteria['limit']) ? $criteria['limit'] : false;
        }
        if ($offset == false) {
            $offset = isset($criteria['offset']) ? $criteria['offset'] : false;
        }
        $qLimit = $limit_from_paging_q = "";
        if ($limit == false) {
            if ($count_only == false) {
                $limit = $_default_limit;
            }
        }

        // d($limit);
        if (is_string($limit) or is_int($limit)) {
            $items_per_page = intval($limit);

            if ($count_only == false) {

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

                $limit_from_paging_q = $qLimit;
            }
        }

        if ($debug) {
            
        }
    }
    if (isset($criteria['fields'])) {
        $only_those_fields = $criteria['fields'];

        if (is_string($criteria['fields'])) {
            $criteria['fields'] = explode(',', $criteria['fields']);
            $only_those_fields = $criteria['fields'];
            //     d($only_those_fields);
            unset($criteria['fields']);
        } else {
            unset($criteria['fields']);
        }
    }
    if (!empty($criteria)) {
        foreach ($criteria as $fk => $fv) {
            if (strstr($fk, 'custom_field_') == true) {

                $addcf = str_ireplace('custom_field_', '', $fk);

                // $criteria ['custom_fields_criteria'] [] = array ($addcf =>
                // $fv );

                $criteria['custom_fields_criteria'][$addcf] = $fv;
            }
        }
    }
    if (!empty($criteria['custom_fields_criteria'])) {

        $table_custom_fields = $cms_db_tables['table_custom_fields'];

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

                    $only_custom_fieldd_ids[] = $itm['to_table_id'];

                    // if(in_array($itm ['to_table_id'],$category_ids)==
                    // false){

                    $includeIds[] = $itm['to_table_id'];

                    // }
                //
				}
            } else {

                // $ids = array();

                $remove_all_ids = true;

                //  $includeIds = array();
                //  $includeIds [] = '0';
                // $includeIds [] = 0;
            }
        }
    }

    $original_cache_group = $cache_group;

    if (!empty($criteria['only_those_fields'])) {

        $only_those_fields = $criteria['only_those_fields'];

        // unset($criteria['only_those_fields']);
        // no unset xcause f cache
    }

    if (!empty($criteria['include_taxonomy'])) {

        $include_taxonomy = true;
    } else {

        $include_taxonomy = false;
    }

    if (!empty($criteria['exclude_ids'])) {

        $exclude_ids = $criteria['exclude_ids'];

        // unset($criteria['only_those_fields']);
        // no unset xcause f cache
    }

    if (!empty($criteria['ids'])) {
        foreach ($criteria ['ids'] as $itm) {

            $includeIds[] = $itm;
        }
    }

    $to_search = false;

    if (isset($criteria['category'])) {
        $search_n_cats = $criteria['category'];
        if (is_string($search_n_cats)) {
            $search_n_cats = explode(',', $search_n_cats);
        }
        if (is_array($search_n_cats) and !empty($search_n_cats)) {

            foreach ($search_n_cats as $cat_name_or_id) {

                $str0 = 'fields=id&limit=100&data_type=category&what=categories&' . 'id=' . $cat_name_or_id . '&to_table=' . $table_assoc_name;
                $str1 = 'fields=id&limit=100&data_type=category&what=categories&' . 'title=' . $cat_name_or_id . '&to_table=' . $table_assoc_name;
                //  d($str0);

                $is_in_category = get($str0);
                if (empty($is_in_category)) {
                    $is_in_category = get($str1);
                }

                if (!empty($is_in_category)) {
                    foreach ($is_in_category as $is_in_category_item) {
                        $cat_name_or_id1 = $is_in_category_item['id'];
                        $str1_items = 'fields=to_table_id&limit=100&data_type=category_item&what=category_items&' . 'parent_id=' . $cat_name_or_id1 . '&to_table=' . $table_assoc_name;
                        $is_in_category_items = get($str1_items);

                        if (!empty($is_in_category_items)) {

                            foreach ($is_in_category_items as $is_in_category_items_tt) {

                                $includeIds[] = $is_in_category_items_tt["to_table_id"];
                                // d($includeIds);
                            }
                        }
                        // d($is_in_category_items);
                        //d($is_in_category_items);
                    }
                }
            }
        }
        // $is_in_category = get('limit=1&data_type=category_item&what=category_items&to_table=' . $table_assoc_name . '&to_table_id=' . $id_to_return . '&parent_id=' . $is_ex['id']);
        //  $includeIds;
    }

    if (isset($criteria['keyword'])) {
        $criteria['search_by_keyword'] = $criteria['keyword'];
    }

    $groupby = false;
    if (isset($criteria['group'])) {
        $groupby = $criteria['group'];
        if (is_string($orderby)) {
            $groupby = db_escape_string($groupby);
        }
    }

    if (isset($criteria['orderby'])) {
        $orderby = $criteria['orderby'];
        if (is_string($orderby)) {
            $orderby = db_escape_string($orderby);
        }
    }

    if (isset($criteria['data-keyword'])) {
        $criteria['search_by_keyword'] = $criteria['data-keyword'];
    }

    if (isset($criteria['search_by_keyword']) and strval(trim($criteria['search_by_keyword'])) != '') {
        $to_search = trim($criteria['search_by_keyword']);
    }

    if (isset($criteria['search_in_fields'])) {
        $criteria['search_by_keyword_in_fields'] = $criteria['search_in_fields'];
    }
    $original_cache_id = false;
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

        //  $cache_content = cache_get_content($original_cache_id, $original_cache_group);
        $cache_content = false;
        if (($cache_content) != false) {

            if ($cache_content == '---empty---') {

                return false;
            }

            if ($count_only == true) {

                $ret = $cache_content[0]['qty'];

                return $ret;
            } else {
                //  $cache_content = replace_site_vars_back($cache_content);
                // $cache_content = remove_slashes_from_array($cache_content);

                return $cache_content;
            }
        }
    }
    if (isset($orderby) and $orderby != false) {
        if (!is_array($orderby)) {
            if (is_string($orderby)) {
                $orderby = explode(',', $orderby);
            }
        }
    }


    if (isset($groupby) and $groupby != false) {
        if (is_array($groupby)) {
            $groupby = implode(',', $groupby);
            $groupby = db_escape_string($groupby);
        }
    }

    if (is_string($groupby)) {

        $groupby = " GROUP BY  {$groupby}  ";
    } else {

        $groupby = false;
    }


    if (!empty($orderby)) {

        $order_by = " ORDER BY  {$orderby[0]}  {$orderby[1]}  ";
    } else {

        $order_by = false;
    }

    if (!isset($qLimit) and ($limit != false) and $count_only == false) {
        if (is_array($limit)) {
            $offset = $limit[1] - $limit[0];
            $limit = " limit  {$limit[0]} , $offset  ";
        } else {
            $limit = " limit  0 , {$limit}  ";
        }
    } else {

        $limit = false;
    }

    $criteria = map_array_to_database_table($table, $criteria);
    $criteria = add_slashes_to_array($criteria);
    if ($only_those_fields == false) {

        $q = "SELECT * FROM $table ";
    } else {

        if (is_array($only_those_fields)) {

            if (!empty($only_those_fields)) {
                $ex_fields = db_get_table_fields($table);
                $flds1 = array();
                foreach ($ex_fields as $ex_field) {
                    foreach ($only_those_fields as $ex_f_d) {
                        if (trim(strtolower($ex_field)) == trim(strtolower($ex_f_d))) {
                            $flds1[] = $ex_f_d;
                        }
                    }
                }

                $flds = implode(',', $flds1);
                $flds = db_escape_string($flds);

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
        //  d($includeIds);
        $includeIds_idds = false;
        $includeIds_i = implode(',', $includeIds);
        $includeIds_idds .= "   AND id IN ($includeIds_i)   ";
    } else {
        $includeIds_idds = false;
    }
    // $to_search = false;
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

                    if (DB_IS_SQLITE == false) {

                        $where_q .= " $v REGEXP '$to_search' " . $where_post;
                    } else {
                        $where_q .= " $v LIKE '%$to_search%' " . $where_post;
                    }
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

    if ($groupby != false) {
        $q .= " $groupby  ";
    } else {

        if ($count_only != true) {
            $q .= " group by ID  ";
        }
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

    $result = db_query($q, $original_cache_id, $original_cache_group);
    if ($count_only == true) {

        // var_dump ( $result );
        // exit ();
    }

    if (isset($result[0]['qty']) == true) {

        $ret = $result[0]['qty'];

        if ($count_paging == true) {
            $plimit = false;

            if ($limit == false) {
                $plimit = $_default_limit;
            } else {
                if (is_array($limit)) {
                    $plimit = end($plimit);
                } else {
                    $plimit = intval($plimit);
                }
            }

            if ($plimit != false) {
                return ceil($ret / $plimit);
                // d($plimit);
            }
        }

        // p($result);

        return $ret;
    }

    // var_dump($result);
    if ($count_only == true) {


        $ret = $result[0]['qty'];

        return $ret;
    }

    $return = array();

    if (!empty($result)) {
        $result = replace_site_vars_back($result);
        $return = $result;
        /*        foreach ($result as $k => $v) {
          // if (DB_IS_SQLITE == false) {
          // $v = remove_slashes_from_array($v);
          // }
          $return [$k] = $v;
          } */
    }
    if ($cache_group != false) {

        if (is_arr($return)) {

            //  cache_store_data($return, $original_cache_id, $original_cache_group);
        } else {

            //   cache_store_data('---empty---', $original_cache_id, $original_cache_group);
        }
    }
    //
    return $return;
}

function db_get_table_name($assoc_name) {
    $cms_db_tables = c('db_tables');
    // ->'table_options';

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

function db_get_assoc_table_name($assoc_name) {
    $cms_db_tables = c('db_tables');

    if (!empty($cms_db_tables)) {

        foreach ($cms_db_tables as $k => $v) {
            if (trim(strtolower('table_' . $assoc_name)) == trim(strtolower($k))) {

                $table_assoc_name = $k;
                return $k;
            }

            if (trim(strtolower($assoc_name)) == trim(strtolower($v))) {

                $table_assoc_name = $k;
                return $k;
            }
        }

        return $assoc_name;
    }
}

function db_get_real_table_name($assoc_name) {
    $cms_db_tables = c('db_tables');

    if (!empty($cms_db_tables)) {

        foreach ($cms_db_tables as $k => $v) {

            if (trim(strtolower($assoc_name)) == trim(strtolower($k))) {

                $table_assoc_name = $k;
                return $v;
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

    static $arr_maps = array();


    $arr_key = crc32($table) + crc32(serialize($array));
    if (isset($arr_maps[$arr_key])) {
        return $arr_maps[$arr_key];
    }

    if (empty($array)) {

        return false;
    }
    // $table = db_get_table_name($table);

    $fields = db_get_table_fields($table);

    foreach ($fields as $field) {

        $field = strtolower($field);

        if (array_key_exists($field, $array)) {

            if ($array[$field] != false) {

                // print ' ' . $field. ' <br>';
                $array_to_return[$field] = $array[$field];
            }

            if ($array[$field] == 0) {

                $array_to_return[$field] = $array[$field];
            }
        }
    }

    if (!isset($array_to_return)) {
        return false;
    } else {
        $arr_maps[$arr_key] = $array_to_return;
    }
    return $array_to_return;
}

function db_table_exist($table) {
    // $sql_check = "SELECT * FROM sysobjects WHERE name='$table' ";
    $sql_check = "DESC {$table};";

    $q = db_query($sql_check);
    if (isset($q['error'])) {
        return false;
    } else {
        return $q;
    }
    // var_dump($q);
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

    $db_get_table_fields = array();
    if (!$table) {

        return false;
    }
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


    $table = db_get_real_table_name($table);

    if (DB_IS_SQLITE != false) {
        $sql = "PRAGMA table_info('{$table}');";
    } else {
        $sql = "show columns from $table";
    }
    // var_dump($sql);
    //   $sql = "DESCRIBE $table";

    $query = db_query($sql);
//d($query);
    $fields = $query;

    $exisiting_fields = array();
    if ($fields == false or $fields == NULL) {
        return false;
    }
    foreach ($fields as $fivesdraft) {
        if ($fivesdraft != NULL and is_array($fivesdraft)) {
            $fivesdraft = array_change_key_case($fivesdraft, CASE_LOWER);
            if (isset($fivesdraft['name'])) {
                $fivesdraft['field'] = $fivesdraft['name'];
                $exisiting_fields[strtolower($fivesdraft['field'])] = true;
            } else {
                if (isset($fivesdraft['field'])) {

                    $exisiting_fields[strtolower($fivesdraft['field'])] = true;
                }
            }
        }
    }

    // var_dump ( $exisiting_fields );
    $fields = array();

    foreach ($exisiting_fields as $k => $v) {

        if (!empty($exclude_fields)) {

            if (in_array($k, $exclude_fields) == false) {

                $fields[] = $k;
            }
        } else {

            $fields[] = $k;
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



    $original_data = $data;

    $is_quick = isset($original_data['quick_save']);

    if ($is_quick == false) {
        if (isset($data['updated_on']) == false) {

            $data['updated_on'] = date("Y-m-d H:i:s");
        }
    }

    if (isset($data_to_save_options) and !empty($data_to_save_options)) {

        if (isset($data_to_save_options['delete_cache_groups']) and !empty($data_to_save_options['delete_cache_groups'])) {

            foreach ($data_to_save_options ['delete_cache_groups'] as $item) {

                cache_clean_group($item);
            }
        }
    }

    $user_session = session_get('user_session');

    $user_sid = false;
    if ($user_session == false) {


        if (!defined("FORCE_SAVE")) {
            error('You can\'t save data when you are not logged in. ');
        } else {

            if ($table != FORCE_SAVE) {
                error('You can\'t save data to ' . $table);
            }
        }
    }

    if (!isset($user_session['user_id'])) {
        $user_sid = session_id();
        //d($user_sid);
    } else {
        if (intval($user_session['user_id']) == 0) {
            unset($user_session['user_id']);
            $user_sid = session_id();
        }
    }
    if ($user_sid != false) {
        $data['session_id'] = $user_sid;
    } else {
        $data['session_id'] = session_id();
    }


    if (isset($data['cf_temp'])) {
        $cf_temp = $data['cf_temp'];
    }
    $the_user_id = user_id();
    if ($the_user_id == false) {
        $the_user_id = 0;
    }

    if (isset($data['screenshot_url'])) {
        $screenshot_url = $data['screenshot_url'];
    }

    if (isset($data['debug']) and $data['debug'] == true) {
        $dbg = 1;
        unset($data['debug']);
    } else {

        $dbg = false;
    }

    if (isset($data['queue_id']) != false) {
        $queue_id = $data['queue_id'];
    }

    if (isset($data['url']) == false) {
        $url = url_string();
        $data['url'] = $url;
    }

    $data['user_ip'] = USER_IP;
    if (isset($data['id']) == false or $data['id'] == 0) {
        $data['id'] = 0;
        $l = db_last_id($table);

        $data['new_id'] = intval($l + 1);
        $original_data['new_id'] = $data['new_id'];
    }

    if (isset($data['custom_field_value'])) {
        if (is_array($data['custom_field_value'])) {
            $data['custom_field_values'] = base64_encode(serialize($data['custom_field_value']));
            $data['custom_field_value'] = 'Array';
            //$cfvq = "custom_field_values =\"" . $custom_field_to_save ['custom_field_values'] . "\",";
        }
    }
    // var_dump($data);
    if (intval($data['id']) == 0) {

        if (isset($data['created_on']) == false) {

            $data['created_on'] = date("Y-m-d H:i:s");
        }

        $data['created_by'] = $the_user_id;

        $data['edited_by'] = $the_user_id;
    } else {

        // $data ['created_on'] = false;
        $data['edited_by'] = $the_user_id;
    }

    $table_assoc_name = db_get_assoc_table_name($table);

    $criteria_orig = $data;

    $criteria = map_array_to_database_table($table, $data);

    //
    //  if ($data_to_save_options ['do_not_replace_urls'] == false) {

    $criteria = replace_site_vars($criteria);

    //  }

    if ($data_to_save_options['use_this_field_for_id'] != false) {

        $criteria['id'] = $criteria_orig[$data_to_save_options['use_this_field_for_id']];
    }

    // $criteria = map_array_to_database_table ( $table, $data );

    if (DB_IS_SQLITE != false) {
        $criteria = add_slashes_to_array($criteria);
    } else {
        $criteria = add_slashes_to_array($criteria);
    }

    if (!isset($criteria['id'])) {
        $criteria['id'] = 0;
    }
    $criteria['id'] = intval($criteria['id']);
    //  $db = new DB(c('db'));
    // $criteria = $this->addSlashesToArray ( $criteria );
    if (intval($criteria['id']) == 0) {

        if (isset($original_data['new_id']) and intval($original_data['new_id']) != 0) {

            $criteria['id'] = $original_data['new_id'];
        }

        // insert
        $data = $criteria;

        // $this->db->insert ( $table, $data );

        if (DB_IS_SQLITE == false) {
            $q = " INSERT INTO  $table set ";

            foreach ($data as $k => $v) {

                // $v
                if (strtolower($k) != $data_to_save_options['use_this_field_for_id']) {

                    if (strtolower($k) != 'id') {


                        $q .= "$k='$v',";
                    }
                }
            }

            if (isset($original_data['new_id']) and intval($original_data['new_id']) != 0) {
                $n_id = $original_data['new_id'];
            } else {
                $n_id = "NULL";
            }

            if ($data_to_save_options['use_this_field_for_id'] != false) {

                $q .= " " . $data_to_save_options['use_this_field_for_id'] . "={$n_id} ";
            } else {

                $q .= " id={$n_id} ";
            }
        }



        $id_to_return = db_last_id($table);
    } else {

        // update
        $data = $criteria;


        $q = " UPDATE  $table set ";

        foreach ($data as $k => $v) {
            if ($k != 'id' and $k != 'session_id' and $k != 'edited_by') {
                // $v = htmlspecialchars ( $v, ENT_QUOTES );
                $q .= "$k='$v',";
            }
        }
        $user_sidq = '';
        if ($user_sid != false) {
            $user_sidq = " AND session_id='{$user_sid}' ";
        } else {
            
        }
        $user_createdq = '';
        if (is_admin() == false) {
            $user_createdq = " AND created_by=$the_user_id ";
        }

        $q .= " edited_by=$the_user_id WHERE id={$data ['id']} {$user_sidq}  {$user_createdq} limit 1";


        $id_to_return = $data['id'];
    }

    if ($dbg != false) {
        d($q);
    }
    db_q($q);

    // d($q);
    // p($original_data);
    /*
     * if (!empty ( $original_data ['taxonomy_categories_str'] )) {
     * p($original_data ['taxonomy_categories_str'] ,1); foreach (
     * $original_data ['taxonomy_categories_str'] as $taxonomy_item ) {
     * $test_if_exist_cat = get_category ( $taxonomy_item ); } }
     */

    // p ( $original_data );
    if (isset($original_data['categories'])) {
        $table_cats = $cms_db_tables['table_taxonomy'];
        $table_cats_items = $cms_db_tables['table_taxonomy_items'];
        $taxonomy_table = $cms_db_tables['table_taxonomy'];
        $taxonomy_items_table = $cms_db_tables['table_taxonomy_items'];
        $is_a = has_access('save_category');

        if ($is_a == true and $table_assoc_name != 'table_taxonomy' and $table_assoc_name != 'table_taxonomy_items') {
            if (is_string($original_data['categories']) and $original_data['categories'] == '__EMPTY_CATEGORIES__') {
                // exit('__EMPTY_CATEGORIES__');

                $clean_q = "delete
                    from $taxonomy_items_table where                            data_type='category_item' and
                    to_table='{$table_assoc_name}' and
                    to_table_id='{$id_to_return}'  ";
                $cats_data_items_modified = true;
                $cats_data_modified = true;
                db_q($clean_q);
            } else {

                if (is_string($original_data['categories'])) {
                    $original_data['categories'] = explode(',', $original_data['categories']);
                }
                $cat_names_or_ids = array_trim($original_data['categories']);

                $cats_data_modified = false;
                $cats_data_items_modified = false;
                $keep_thosecat_items = array();
                foreach ($cat_names_or_ids as $cat_name_or_id) {
                    if (trim($cat_name_or_id) != '') {

                        $cat_name_or_id = str_replace('\\', '/', $cat_name_or_id);
                        $cat_name_or_id = explode('/', $cat_name_or_id);

                        $parent_id = 0;

                        $all_cat_name_or_ids = $cat_name_or_id;
                        $cat_name_or_id = end($cat_name_or_id);
                        $ccount = count($all_cat_name_or_ids);
                        if ($ccount > 1) {
                            $gc = $ccount - 2;
                            $prev_cat = $all_cat_name_or_ids[$gc];

                            $str0 = 'limit=1&data_type=category&what=categories&' . 'id=' . $cat_name_or_id . '&to_table=' . $table_assoc_name;
                            $str00 = 'limit=1&data_type=category&what=categories&' . 'title=' . $prev_cat . '&to_table=' . $table_assoc_name;
                            $is_ex_parent = get($str0);
                            if ($is_ex_parent == false or empty($is_ex_parent)) {
                                $is_ex_parent = get($str00);
                                $parent_id = $is_ex_parent[0]['id'];
                            } else {
                                $parent_id = $is_ex_parent[0]['parent_id'];
                            }
                            //                        if (isset($is_ex_parent[0])) {
                            //                            $parent_id = $is_ex_parent[0]['id'];
                            //                        }
                            unset($all_cat_name_or_ids[$gc]);
                            // $cat_name_or_id = implode('/', $all_cat_name_or_ids);
                        }

                        $str1 = 'title=' . $cat_name_or_id . '&data_type=category&to_table=' . $table_assoc_name;
                        $is_ex = get('limit=1&data_type=category&what=categories&' . $str1);

                        $gotten_by_id = false;
                        if (empty($is_ex)) {

                            $str1 = 'id=' . $cat_name_or_id . '&to_table=' . $table_assoc_name;
                            $is_ex = get('limit=1&data_type=category&what=categories&' . $str1);
                            $gotten_by_id = true;
                        } else {
                            
                        }
                        if ($gotten_by_id == false and isset($is_ex[0])) {

                            $is_expar = $is_ex[0];

                            if ($parent_id == ($is_expar['parent_id'])) {
                                // d($parent_id);
                                // d($is_expar);
                            } else {

                                if (intval($parent_id) != intval($is_expar['parent_id'])) {
                                    $new_cat = array();

                                    $new_cat['id'] = $is_expar['id'];
                                    $new_cat['parent_id'] = $parent_id;
                                    // d($new_cat);
                                    $new_c = save_data($taxonomy_table, $new_cat);
                                    $keep_thosecat_items[] = $new_c;
                                    $cats_data_modified = TRUE;
                                }
                            }
                        }

                        $new_c = false;

                        if (!isset($is_ex[0])) {

                            // $cat = $is_ex[0];

                            $new_cat = array();
                            $new_cat['to_table'] = $table_assoc_name;
                            // $new_cat['to_table_id'] = $id_to_return;
                            $new_cat['data_type'] = 'category';
                            $new_cat['parent_id'] = $parent_id;
                            //  d($table_cats);
                            $new_cat['title'] = $cat_name_or_id;

                            // d($new_cat);

                            $new_c = save_data($table_cats, $new_cat);

                            $keep_thosecat_items[] = $new_c;
                            $cats_data_modified = TRUE;
                            $parent_id = $new_c;
                            // cache_clean_group('taxonomy' . DIRECTORY_SEPARATOR . 'global');

                            $is_ex = get('limit=1&data_type=category&what=categories&id=' . $new_c);
                        }

                        if (isset($is_ex[0])) {

                            $is_ex = $is_ex[0];

                            $new_cat = array();
                            $keep_thosecat_items[] = $is_ex['id'];
                            $new_cat['to_table'] = $table_assoc_name;
                            $new_cat['to_table_id'] = $id_to_return;
                            $new_cat['data_type'] = 'category_item';
                            $new_cat['parent_id'] = $is_ex['id'];

                            $is_ex1 = get('limit=1&data_type=category_item&what=category_items&to_table=' . $table_assoc_name . '&to_table_id=' . $id_to_return . '&parent_id=' . $is_ex['id']);
                            // d($is_ex1);
                            if (!isset($is_ex1[0])) {
                                //   d($table_cats_items);
                                $new_c = save_data($table_cats_items, $new_cat);
                                // $keep_thosecat_items[] = $new_c;
                                $cats_data_modified = TRUE;
                                $cats_data_items_modified = TRUE;
                            } else {
                                
                            }
                            //
                            //  d($is_ex);
                        }
                    }
                }
            }
            if (!empty($keep_thosecat_items)) {
                $id_in = implode(',', $keep_thosecat_items);
                $clean_q = "delete
                    from $taxonomy_items_table where                            data_type='category_item' and
                    to_table='{$table_assoc_name}' and
                    to_table_id='{$id_to_return}' and
                    parent_id NOT IN ($id_in) ";
                $cats_data_items_modified = true;
                $cats_data_modified = true;
                db_q($clean_q);
                // d($clean_q);
            }

            if ($cats_data_modified == TRUE) {
                cache_clean_group('taxonomy' . DIRECTORY_SEPARATOR . 'global');
                if (isset($parent_id)) {
                    cache_clean_group('taxonomy' . DIRECTORY_SEPARATOR . $parent_id);
                }
                //cache_clean_group('taxonomy_items' . DIRECTORY_SEPARATOR . '');
            }
            if ($cats_data_items_modified == TRUE) {
                cache_clean_group('taxonomy_items' . DIRECTORY_SEPARATOR . '');
            }
        }

        //
        //
		//        $q = " DELETE FROM  $taxonomy_items_table where to_table='$table_assoc_name' and to_table_id='$id_to_return'  and  data_type= 'category_item'     ";
        //        // p ( $q );
        //        db_query($q);
        //
		//        foreach ($original_data ['taxonomy_categories'] as $taxonomy_item) {
        //
		//            $taxonomy_item = trim($taxonomy_item);
        //            $parent_cat = get_category($taxonomy_item);
        //
		//            $parent_cat_id = intval($parent_cat ['id']);
        //
		//
		//            $q = " INSERT INTO  $taxonomy_items_table set to_table='$table_assoc_name', to_table_id='$id_to_return' , content_type='{$original_data ['content_type']}' ,  data_type= 'category_item' , parent_id='$parent_cat_id'   ";
        //            // p ( $q );
        //            db_query($q);
        //            cache_clean_group('taxonomy/' . $parent_cat_id);
        //        }
        //        cache_clean_group('taxonomy/global');
        // exit ();
    }

    // adding custom fields

    if (!isset($original_data['skip_custom_field_save']) and isset($original_data['custom_fields']) and $table_assoc_name != 'table_custom_fields') {
        $cms_db_tables = c('db_tables');
        $custom_field_to_save = array();

        foreach ($original_data as $k => $v) {

            if (stristr($k, 'custom_field_') == true) {

                // if (strval ( $v ) != '') {
                $k1 = str_ireplace('custom_field_', '', $k);

                if (trim($k) != '') {

                    $custom_field_to_save[$k1] = $v;
                }

                // }
            }
        }

        if (is_array($original_data['custom_fields']) and !empty($original_data['custom_fields'])) {
            $custom_field_to_save = array_merge($custom_field_to_save, $original_data['custom_fields']);
        }

        if (!empty($custom_field_to_save)) {
            // p($is_quick);
            $custom_field_table = $cms_db_tables['table_custom_fields'];
            $table_assoc_name = db_get_assoc_table_name($table_assoc_name);
            if ($is_quick == false) {

                $custom_field_to_delete['to_table'] = $table_assoc_name;

                $custom_field_to_delete['to_table_id'] = $id_to_return;
            }
            // p($original_data);
            if (isset($original_data['skip_custom_field_save']) == false) {

                $custom_field_to_save = replace_site_vars($custom_field_to_save);
                $custom_field_to_save = add_slashes_to_array($custom_field_to_save);

                foreach ($custom_field_to_save as $cf_k => $cf_v) {

                    if (($cf_v != '')) {
                        $cf_v = replace_site_vars($cf_v);
                        //d($cf_v);
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
                        $custom_field_to_save['custom_field_name'] = $cf_k;
                        if (is_array($cf_v)) {
                            $custom_field_to_save['custom_field_values'] = base64_encode(json_encode($cf_v));
                            $cfvq = "custom_field_values =\"" . $custom_field_to_save['custom_field_values'] . "\",";
                        }
                        $custom_field_to_save['custom_field_value'] = $cf_v;

                        $custom_field_to_save['to_table'] = $table_assoc_name;

                        $custom_field_to_save['to_table_id'] = $id_to_return;
                        $custom_field_to_save['skip_custom_field_save'] = true;

                        if (DB_IS_SQLITE != false) {
                            //  $custom_field_to_save = add_slashes_to_array($custom_field_to_save, $is_sqlite);
                        } else {
                            // $custom_field_to_save = add_slashes_to_array($custom_field_to_save);
                        }

                        $next_id = intval(db_last_id($custom_field_table) + 1);

                        $add = " insert into $custom_field_table set
                        id =\"{$next_id}\",
			custom_field_name =\"{$cf_k}\",
			$cfvq
			custom_field_value =\"" . $custom_field_to_save['custom_field_value'] . "\",
			to_table =\"" . $custom_field_to_save['to_table'] . "\",
			to_table_id =\"" . $custom_field_to_save['to_table_id'] . "\"
			";

                        $add = " insert into $custom_field_table set
                        id ='{$next_id}',
			custom_field_name ='{$cf_k}',
			$cfvq
			custom_field_value ='{$custom_field_to_save ['custom_field_value']}',
                         custom_field_type = 'content',
			to_table ='{$custom_field_to_save ['to_table']}',
			to_table_id ='{$custom_field_to_save ['to_table_id']}'
			";

                        $cf_to_save = array();
                        $cf_to_save['id'] = $next_id;
                        $cf_to_save['custom_field_name'] = $cf_k;
                        $cf_to_save['to_table'] = $custom_field_to_save['to_table'];
                        $cf_to_save['to_table_id'] = $custom_field_to_save['to_table_id'];
                        $cf_to_save['custom_field_value'] = $custom_field_to_save['custom_field_value'];

                        if (isset($custom_field_to_save['custom_field_values'])) {
                            $cf_to_save['custom_field_values'] = $custom_field_to_save['custom_field_values'];
                        }
                        $cf_to_save['custom_field_name'] = $cf_k;
                        $cf_to_save['custom_field_name'] = $cf_k;
                        //d($add);
                        db_q($add);

                        if (DB_IS_SQLITE != false) {
                            //   $q = $db->insert($custom_field_table, $cf_to_save);
                            //   db_q($add);
                        } else {
                            //   db_q($add);
                        }

                        //  $q = $db->insert($custom_field_table, $cf_to_save);
                        //  print($add);
                        //  db_q($add);
                    }
                }
                cache_clean_group('custom_fields');
                // cache_clean_group ( 'global' );
                //	cache_clean_group ( 'extract_tags' );
            }
        }
    }

    $cg = guess_cache_group($table);
    //   d($cg);
    cache_clean_group($cg . '/global');
    cache_clean_group($cg . '/' . $id_to_return);
    return $id_to_return;
    if (intval($data['edited_by']) == 0) {

        $data['edited_by'] = $user_session['user_id'];
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



    return intval($id_to_return);
}

/**
 * save data
 *
 * @author Peter Ivanov
 */
function db_last_id($table) {

    //  $db = new DB(c('db'));

    if (DB_IS_SQLITE == true) {

        // $q = "SELECT last_insert_rowid()   FROM $table limit 1";

        $q = "SELECT ROWID as the_id from $table order by ROWID DESC limit 1";
    } else {
        //   $q = "SELECT LAST_INSERT_ID() as the_id FROM $table limit 1";

        $q = "SELECT id as the_id FROM $table order by id DESC limit 1";
    }
    //d($q);
    $q = db_query($q);

    $result = $q[0];
    //d($result);
    //
	return intval($result['the_id']);
}

/* * *************************************************************************
 *                             sql_parse.php
 *                              -------------------
 *     begin                : Thu May 31, 2001
 *     copyright            : (C) 2001 The phpBB Group
 *     email                : support@phpbb.com
 *
 *     $Id: sql_parse.php,v 1.8 2002/03/18 23:53:12 psotfx Exp $
 *
 * ************************************************************************** */

/* * *************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * ************************************************************************* */

/* * *************************************************************************
 *
 *   These functions are mainly for use in the db_utilities under the admin
 *   however in order to make these functions available elsewhere, specifically
 *   in the installation phase of phpBB I have seperated out a couple of
 *   functions into this file.  JLH
 *
  \************************************************************************** */

//
// remove_comments will strip the sql comment lines out of an uploaded sql file
// specifically for mssql and postgres type files in the install....
//
function sql_remove_comments($output) {
    $lines = explode("\n", $output);
    $output = "";

    // try to keep mem. use down
    $linecount = count($lines);

    $in_comment = false;
    for ($i = 0; $i < $linecount; $i++) {
        if (preg_match("/^\/\*/", preg_quote($lines[$i]))) {
            $in_comment = true;
        }

        if (!$in_comment) {
            $output .= $lines[$i] . "\n";
        }

        if (preg_match("/\*\/$/", preg_quote($lines[$i]))) {
            $in_comment = false;
        }
    }

    unset($lines);
    return $output;
}

function import_sql_from_file($full_path_to_file) {

    $dbms_schema = $full_path_to_file;

    if (is_file($dbms_schema)) {
        $sql_query = fread(fopen($dbms_schema, 'r'), filesize($dbms_schema)) or die('problem ');
        $sql_query = str_ireplace('{TABLE_PREFIX}', TABLE_PREFIX, $sql_query);
        $sql_query = sql_remove_remarks($sql_query);

        $sql_query = sql_remove_comments($sql_query);
        $sql_query = split_sql_file($sql_query, ';');

        $i = 1;
        foreach ($sql_query as $sql) {
            d($sql);
            $qz = db_q($sql);
        }
        cache_clean_group('db');
        return true;
    } else {
        return false;
    }
}

//
// remove_remarks will strip the sql comment lines out of an uploaded sql file
//
function sql_remove_remarks($sql) {
    $lines = explode("\n", $sql);

    // try to keep mem. use down
    $sql = "";

    $linecount = count($lines);
    $output = "";

    for ($i = 0; $i < $linecount; $i++) {
        if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0)) {
            if (isset($lines[$i][0]) && $lines[$i][0] != "#") {
                $output .= $lines[$i] . "\n";
            } else {
                $output .= "\n";
            }
            // Trading a bit of speed for lower mem. use here.
            $lines[$i] = "";
        }
    }

    return $output;
}

//
// split_sql_file will split an uploaded sql file into single sql statements.
// Note: expects trim() to have already been run on $sql.
//
function split_sql_file($sql, $delimiter) {
    // Split up our string into "possible" SQL statements.
    $tokens = explode($delimiter, $sql);

    // try to save mem.
    $sql = "";
    $output = array();

    // we don't actually care about the matches preg gives us.
    $matches = array();

    // this is faster than calling count($oktens) every time thru the loop.
    $token_count = count($tokens);
    for ($i = 0; $i < $token_count; $i++) {
        // Don't wanna add an empty string as the last thing in the array.
        if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))) {
            // This is the total number of single quotes in the token.
            $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
            // Counts single quotes that are preceded by an odd number of backslashes,
            // which means they're escaped quotes.
            $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);

            $unescaped_quotes = $total_quotes - $escaped_quotes;

            // If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
            if (($unescaped_quotes % 2) == 0) {
                // It's a complete sql statement.
                $output[] = $tokens[$i];
                // save memory.
                $tokens[$i] = "";
            } else {
                // incomplete sql statement. keep adding tokens until we have a complete one.
                // $temp will hold what we have so far.
                $temp = $tokens[$i] . $delimiter;
                // save memory..
                $tokens[$i] = "";

                // Do we have a complete statement yet?
                $complete_stmt = false;

                for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++) {
                    // This is the total number of single quotes in the token.
                    $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
                    // Counts single quotes that are preceded by an odd number of backslashes,
                    // which means they're escaped quotes.
                    $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);

                    $unescaped_quotes = $total_quotes - $escaped_quotes;

                    if (($unescaped_quotes % 2) == 1) {
                        // odd number of unescaped quotes. In combination with the previous incomplete
                        // statement(s), we now have a complete statement. (2 odds always make an even)
                        $output[] = $temp . $tokens[$j];

                        // save memory.
                        $tokens[$j] = "";
                        $temp = "";

                        // exit the loop.
                        $complete_stmt = true;
                        // make sure the outer loop continues at the right point.
                        $i = $j;
                    } else {
                        // even number of unescaped quotes. We still don't have a complete statement.
                        // (1 odd and 1 even always make an odd)
                        $temp .= $tokens[$j] . $delimiter;
                        // save memory.
                        $tokens[$j] = "";
                    }
                } // for..
            } // else
        }
    }

    return $output;
}
