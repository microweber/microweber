<?php

define("DB_IS_SQLITE", false);
/**
 * Get items from the database
 *
 * You can use this handy function to get whatever you need from any db table.
 *
 * @params
 *
 * *You can pass those parameters in order to filter the results*
 *  You can also use all defined database fields as parameters
 *
 * .[params-table]
 *|-----------------------------------------------------------------------------
 *| Parameter        | Description      | Values
 *|------------------------------------------------------------------------------
 *| from            | the name of the db table, without prefix | ex. users, content, categories,etc
 *| table        | same as above |
 *| debug            | prints debug information  | true or false
 *| orderby        | you can order by any field in your table  | ex. get("table=content&orderby=id desc")
 *| order_by        | same as above  |
 *| one            | if set returns only the 1st result |
 *| count            | if set returns results count |  ex. get("table=content&count=true")
 *| limit            | limit the results |  ex. get("table=content&limit=5")
 *| curent_page    | get the current page by limit offset |  ex. get("table=content&limit=5&curent_page=2")
 *
 *
 * @param string|array $params parameters for the DB
 * @param string $params['table'] the table name ex. content
 * @param string $params['debug'] if true print the sql
 * @param string $params['cache_group'] sets the cache folder to use to cache the query result
 * @param string $params['no_cache']  if true it will no cache the sql
 * @param string $params['count']  if true it will return results count
 * @param string $params['page_count']  if true it will return pages count
 * @param string|array $params['limit']  if set it will limit the results
 *
 * @function get
 * @return mixed Array with data or false or integer if page_count is set
 *
 *
 *
 * @example
 * <code>
 * //get content
 *  $results = get("table=content&is_active=y");
 * </code>
 *
 * @example
 *  <code>
 *  //get users
 *  $results = get("table=users&is_admin=n");
 * </code>
 *
 * @package Database
 */
function get($params)
{
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
    if (!isset($params['from']) and isset($params['to']) and is_string($params['to'])) {
        $params['from'] = $params['to'];
    }
    if (isset($params['from']) and is_string($params['from'])) {
        $fr = $params['from'];
        if (substr(strtolower($fr), 0, 6) != 'table_') {
            $fr = 'table_' . $fr;
        }
        $params['table'] = $fr;
        unset($params['from']);

    }
    /*
     if (isset($params['table']) and is_string($params['table'])) {
     $fr = $params['table'];
     if (substr(strtolower($fr), 0, 6) != 'table_') {
     $fr = 'table_' . $fr;
     }
     $params['table'] = $fr;
    }*/

    $criteria = array();
    ksort($params);

    foreach ($params as $k => $v) {
        if ($k == 'table') {
            $table = guess_table_name($v);
            ;
        }

        if ($k == 'what' and !isset($params['rel'])) {
            $table = guess_table_name($v);
        }

        if ($k == 'for' and !isset($params['rel'])) {
            $v = db_get_assoc_table_name($v);
            $k = 'rel';
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

        if ($k == 'single') {
            $getone = true;
        } else if ($k == 'one') {
            $getone = true;
        } else {

            $criteria[$k] = $v;
        }

        if ('orderby' == $k) {
            $orderby = $v;
        }
    }
    if (!isset($table) and isset($params['what'])) {
        $table = db_get_real_table_name(guess_table_name($params['what']));

    }

    if (!isset($table)) {
        print "error no table found in params";
        d($params);
        //print_r(debug_backtrace());
        return false;

    }

    if (isset($params['return_criteria'])) {
        return $criteria;
    }

    if ($cache_group == false and $debug == false) {
        $cache_group = guess_cache_group($table);
        if (!isset($criteria['id'])) {
            $cache_group = $cache_group . '/global';
        } else {
            $cache_group = $cache_group . '/' . $criteria['id'];
        }

    } else {
        $cache_group = guess_cache_group($cache_group);
    }

    $mode = 1;
    if (isset($no_cahce) and $no_cahce == true) {
        $mode = 2;
    }
    switch ($mode) {
        case 1 :
            static $results_map = array();
            //static $results_map_hits = array();
            $criteria_id = (int)crc32($table . serialize($criteria));

            if (isset($results_map[$criteria_id])) {
                $ge = $results_map[$criteria_id];
                //$results_map_hits[$criteria_id]++;
            } else {
                $ge = db_get_long($table, $criteria, $limit = false, $offset = false, $orderby, $cache_group, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false);


                //$results_map_hits[$criteria_id] = 1;
                $results_map[$criteria_id] = $ge;

            }
            break;
        case 2 :
        default :
            $ge = db_get_long($table, $criteria, $limit = false, $offset = false, $orderby, $cache_group, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false);

            break;
    }

    if (is_integer($ge)) {


        return ($ge);
    }

    if (empty($ge)) {
        return false;
    }

    if ($getone == true) {

        if (isarr($ge)) {

            $one = array_shift($ge);

            return $one;
        }
    }

    return $ge;
}



/**
 * Get table row by id
 *
 * It returns full db row from a db table
 *
 * @param string $table Your table
 * @param int|string $id The id to get
 * @param string $field_name You can set custom column to get by it, default is id
 *
 * @return array|bool|mixed
 * @example
 * <code>
 * //get content with id 5
 * $cont = db_get_id('content', $id=5);
 * </code>
 *
 * @package Database
 * @subpackage Advanced
 */
function db_get_id($table, $id = 0, $field_name = 'id')
{

    $id = intval($id);

    if ($id == 0) {

        return false;
    }

    if ($field_name == false) {
        $field_name = "id";
    }
    $table = db_get_real_table_name($table);
    $table = db_get_table_name($table);

    $q = "SELECT * FROM $table WHERE {$field_name}='$id' LIMIT 1";

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

/**
 * Generic save data function, it saves data to the database
 *
 * @param $table
 * @param $data
 * @param bool $data_to_save_options
 * @return string|int The id of the saved row.
 *
 * @example
 * <code>
 * $table = MW_TABLE_PREFIX.'content';
 * $data = array();
 * $data['id'] = 0; //if 0 will create new content
 * $data['title'] = 'new title';
 * $data['content'] = '<p>Something</p>';
 * $save = save($table, $data);
 * </code>
 * @package Database
 */
function save($table, $data, $data_to_save_options = false)
{
    return save_data($table, $data, $data_to_save_options);

}
/**
 * Same as save()
 * @see save()
 * @package Database
 * @subpackage Advanced
 */
function save_data($table, $data, $data_to_save_options = false)
{

    if (is_array($data) == false) {

        return false;
    }

    $original_data = $data;

    $is_quick = isset($original_data['quick_save']);

    $skip_cache = isset($original_data['skip_cache']);

    if ($is_quick == false) {
        if (isset($data['updated_on']) == false) {

            $data['updated_on'] = date("Y-m-d H:i:s");
        }
    }

    if ($skip_cache == false and isset($data_to_save_options) and !empty($data_to_save_options)) {

        if (isset($data_to_save_options['delete_cache_groups']) and !empty($data_to_save_options['delete_cache_groups'])) {

            foreach ($data_to_save_options ['delete_cache_groups'] as $item) {

                cache_clean_group($item);
            }
        }
    }
    if (isset($_SESSION)) {
        $user_session = session_get('user_session');

    } else {
        $user_session = false;
    }
    $table = db_get_real_table_name($table);
    $user_sid = false;
    if ($user_session == false) {

        if (mw_var("FORCE_SAVE") != false) {
            //error('You can\'t save data when you are not logged in. ');
        } else if (!defined("FORCE_SAVE")) {
            error('You can\'t save data when you are not logged in. ');
        } else {

            if ($table != FORCE_SAVE or $table != mw_var("FORCE_SAVE")) {
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
    if (!isset($data['session_id']) and isset($_SESSION)) {
        if ($user_sid != false) {
            $data['session_id'] = $user_sid;
        } else {
            $data['session_id'] = session_id();
        }
    } elseif (isset($data['session_id'])) {
        //$user_sid = $data['session_id'] ;
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
        //$url = url_string();
        //$data['url'] = $url;
    }

    $data['user_ip'] = USER_IP;
    if (isset($data['id']) == false or $data['id'] == 0) {
        $data['id'] = 0;
        $l = db_last_id($table);
        //$data['id'] = $l;
        $data['new_id'] = intval($l + 1);
        $original_data['new_id'] = $data['new_id'];
    }

    if (isset($data['custom_field_value']) and isset($data['custom_field_name'])) {
        if (is_array($data['custom_field_value'])) {


            $data['custom_field_values'] = base64_encode(serialize($data['custom_field_value']));
            $data['custom_field_values_plain'] = db_escape_string(implode(', ', array_values_recursive($data['custom_field_value'])));

            $data['custom_field_value'] = 'Array';
            //$cfvq = "custom_field_values =\"" . $custom_field_to_save ['custom_field_values'] . "\",";
        } else if (is_string($data['custom_field_value'])) {
            $data['custom_field_values_plain'] = db_escape_string((strip_tags($data['custom_field_value'])));
        }


        $cf_k_plain = url_title($data['custom_field_name']);
        $cf_k_plain = db_escape_string($cf_k_plain);
        $data['custom_field_name_plain'] = str_replace('-', '_', $cf_k_plain);

    }

    //
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

    if (intval($criteria['id']) == 0) {

        if (isset($original_data['new_id']) and intval($original_data['new_id']) != 0) {

            $criteria['id'] = $original_data['new_id'];
        }

        // insert
        $data = $criteria;

        if (DB_IS_SQLITE == false) {
            $q = "INSERT INTO  " . $table . " set ";

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

        $id_to_return = false;
    } else {

        // update
        $data = $criteria;

        $q = "UPDATE  " . $table . " set ";

        foreach ($data as $k => $v) {
            //$v = db_escape_string($v);
            //$k = db_escape_string($k);
            if (isset($data['session_id'])) {
                if ($k != 'id' and $k != 'edited_by') {
                    // $v = htmlspecialchars ( $v, ENT_QUOTES );
                    $q .= "$k='$v',";
                }
            } else {
                if ($k != 'id' and $k != 'session_id' and $k != 'edited_by') {
                    // $v = htmlspecialchars ( $v, ENT_QUOTES );
                    $q .= "$k='$v',";
                }
            }
        }
        $user_sidq = '';

        $user_createdq = '';
        $user_createdq1 = '';

        if ((mw_var('FORCE_ANON_UPDATE') != false and $table == mw_var('FORCE_ANON_UPDATE')) or (defined('FORCE_ANON_UPDATE') and $table == FORCE_ANON_UPDATE)) {
            $user_createdq1 = " id={$data ['id']} ";
        } else {

            if (is_admin() == false and isset($data['created_by'])) {
                $user_createdq = " AND created_by=$the_user_id ";
            }

            if (isset($data['edited_by'])) {
                $user_createdq1 = " edited_by=$the_user_id ";
            } else {
                $user_createdq1 = " id={$data ['id']} ";
            }
            if (isset($_SESSION)) {
                if (isset($data['session_id'])) {
                    if ($user_sid != false) {
                        $user_sidq = " AND session_id='{$user_sid}' ";
                    }
                }
            }

        }

        $q .= " $user_createdq1 WHERE id={$data ['id']} {$user_sidq}  {$user_createdq} limit 1";

        $id_to_return = $data['id'];
    }

    if ($dbg != false) {
        d($q);
    }

    db_q($q);

    if ($id_to_return == false) {
        $id_to_return = db_last_id($table);
    }

    $cg = guess_cache_group($table);


    cache_clean_group($cg . '/global');
    cache_clean_group($cg . '/' . $id_to_return);


    // d($q);
    // p($original_data);
    /*
     * if (!empty ( $original_data ['categories_categories_str'] )) {
     * p($original_data ['categories_categories_str'] ,1); foreach (
     * $original_data ['categories_categories_str'] as $categories_item ) {
     * $test_if_exist_cat = get_category ( $categories_item ); } }
     */

    // p ( $original_data );
    if (isset($original_data['categories'])) {
        $table_cats = MW_TABLE_PREFIX . 'categories';
        $table_cats_items = MW_TABLE_PREFIX . 'categories_items';
        $categories_table = MW_TABLE_PREFIX . 'categories';
        $categories_items_table = MW_TABLE_PREFIX . 'categories_items';
        $is_a = has_access('save_category');
        $from_save_cats = $original_data['categories'];
        if ($is_a == true and $table_assoc_name != 'categories' and $table_assoc_name != 'categories_items') {
            if (is_string($original_data['categories']) and $original_data['categories'] == '__EMPTY_CATEGORIES__') {
                // exit('__EMPTY_CATEGORIES__');

                $clean_q = "DELETE
				FROM $categories_items_table WHERE
				data_type='category_item' AND
				rel='{$table_assoc_name}' AND
				rel_id={$id_to_return}  ";
                $cats_data_items_modified = true;
                $cats_data_modified = true;
                db_q($clean_q);
            } else {

                if (is_string($original_data['categories'])) {
                    $original_data['categories'] = str_replace('/', ',', $original_data['categories']);
                    $cz = explode(',', $original_data['categories']);
                    $j = 0;
                    $cz_int = array();
                    foreach ($cz as $cname_check) {

                        if (intval($cname_check) == 0) {
                            $cname_check = trim($cname_check);
                            $cname_check = db_escape_string($cname_check);
                            //	$str1 = 'cache_group=false&no_cache=1&table=categories&title=' . $cname_check . '&data_type=category&rel=' . $table_assoc_name;
                            //	$is_ex = get($str1);

                            if ($cname_check != '') {

                                $cncheckq = "SELECT id
								FROM $categories_table WHERE
								data_type='category'
								AND   rel='{$table_assoc_name}'
								AND   title='{$cname_check}'   ";
                                // d($cncheckq);
                                $is_ex = db_query($cncheckq);

                                if (empty($is_ex)) {
                                    $clean_q = "INSERT INTO
									$categories_table SET
									title='{$cname_check}',
									parent_id=0,
									position=999,
									data_type='category',
									rel='{$table_assoc_name}'
									";
                                    $cats_data_items_modified = true;
                                    $cats_data_modified = true;
                                    //d($clean_q);
                                    if ($dbg != false) {
                                        d($clean_q);
                                    }
                                    db_q($clean_q);

                                }
                            }

                            //$is_ex = get($str1);
                            if (!empty($is_ex) and isarr($is_ex[0])) {
                                $cz[$j] = $is_ex[0]['id'];
                                $cz_int[] = intval($is_ex[0]['id']);
                                //	d($cz_int);
                            }

                        }
                        $j++;
                    }

                    $parnotin = '';
                    if (!empty($cz_int)) {
                        $parnotin = implode(',', $cz_int);
                        $parnotin = " parent_id NOT IN ({$parnotin}) and";
                    }

                    $original_data['categories'] = implode(',', $cz);


                    $clean_q = "delete from " . $categories_items_table . " where ";
                    $clean_q .= " data_type='category_item' and ";
                    $clean_q .= " rel='{$table_assoc_name}' and ";
                    $clean_q .= $parnotin;
                    $clean_q .= " rel_id={$id_to_return}";


                    $cats_data_items_modified = true;
                    $cats_data_modified = true;
                    // d($clean_q);
                    if ($dbg != false) {
                        d($clean_q);
                    }
                    db_q($clean_q);

                    $original_data['categories'] = explode(',', $original_data['categories']);
                }
                if (!empty($cz_int)) {
                    $cat_names_or_ids = array_trim($cz_int);
                } else {
                    $cat_names_or_ids = $original_data['categories'];
                    if (is_string($from_save_cats)) {
                        $from_save_cats = explode(',', $from_save_cats);
                    }


                    if (isarr($from_save_cats)) {
                        $cat_names_or_ids = $from_save_cats;
                    }


                    //d($cat_names_or_ids);
//d($from_save_cats);
                }
                $cats_data_modified = false;
                $cats_data_items_modified = false;
                $keep_thosecat_items = array();
                foreach ($cat_names_or_ids as $cat_name_or_id) {
                    $cat_name_or_id = db_escape_string(trim($cat_name_or_id));
                    if ($cat_name_or_id != '') {
                        $q_cat1 = "INSERT INTO $categories_items_table  SET

						parent_id='{$cat_name_or_id}',
						rel='{$table_assoc_name}',
						data_type='category_item',
						rel_id='{$id_to_return}'
						";
                        if ($dbg != false) {
                            d($q_cat1);
                        }
                        db_q($q_cat1);
                    }


                }
            }
            if (!empty($keep_thosecat_items)) {
                $id_in = implode(',', $keep_thosecat_items);
                $clean_fq = "DELETE
				FROM $categories_items_table WHERE                            data_type='category_item' AND
				rel='{$table_assoc_name}' AND
				rel_id='{$id_to_return}' AND
				parent_id NOT IN ($id_in) ";
                $cats_data_items_modified = true;
                $cats_data_modified = true;
                //db_q($clean_q);
                //   d($clean_q);
            }

            if ($cats_data_modified == TRUE) {
                cache_clean_group('categories' . DIRECTORY_SEPARATOR . 'global');
                if (isset($parent_id)) {
                    cache_clean_group('categories' . DIRECTORY_SEPARATOR . $parent_id);
                }
                //cache_clean_group('categories_items' . DIRECTORY_SEPARATOR . '');
            }
            if ($cats_data_items_modified == TRUE) {
                cache_clean_group('categories_items' . DIRECTORY_SEPARATOR . '');
            }
        }


    }

    // adding custom fields
    $table_assoc_name = db_get_assoc_table_name($table_assoc_name);
    $media_table_modified = false;

    if (isset($screenshot_url) and $screenshot_url != false and trim($screenshot_url) != '') {
        $screenshot_url = preg_replace('/\\?.*/', '', $screenshot_url);
        $url_check = getimagesize($screenshot_url);
        if (!is_array($url_check)) {

        } else {

            $save_as = (basename($screenshot_url));
            if ($save_as != false) {
                $download_to = MEDIAFILES . DS . 'downloaded' . DS . $id_to_return . '_' . $save_as;
                $dl = url_download($screenshot_url, $post_params = false, $save_to_file = $download_to);

                if ($save_as != false) {
                    $picfn = replace_site_vars(dir2url($download_to));

                    $media_table = MW_TABLE_PREFIX . 'media';

                    $add = " INSERT INTO $media_table SET
					position ='1',
					media_type ='picture',
					filename ='{$picfn}',
					rel ='{$table_assoc_name}',
					rel_id ='{$id_to_return}' 	";
                    $media_table_modified = true;
                    db_q($add);
                }

            }

        }

    }
    if (!isset($original_data['skip_custom_field_save']) and isset($original_data['custom_fields']) and $table_assoc_name != 'table_custom_fields') {

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
            $custom_field_table = MW_TABLE_PREFIX . 'custom_fields';
            if ($is_quick == false) {

                $custom_field_to_delete['rel'] = $table_assoc_name;

                $custom_field_to_delete['rel_id'] = $id_to_return;
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
                            $clean = " DELETE FROM $custom_field_table WHERE
							rel =\"{$table_assoc_name}\"
							AND
							rel_id =\"{$id_to_return}\"
							AND
							custom_field_name =\"{$cf_k}\"


							";

                            //	d($clean);
                            db_q($clean);
                        }
                        $cfvq = '';
                        $custom_field_to_save['custom_field_name'] = $cf_k;
                        if (is_array($cf_v)) {
                            $cf_k_plain = url_title($cf_k);
                            $cf_k_plain = db_escape_string($cf_k_plain);
                            $cf_k_plain = str_replace('-', '_', $cf_k_plain);
                            $custom_field_to_save['custom_field_values'] = base64_encode(serialize($cf_v));
                            $custom_field_to_save['custom_field_values_plain'] = db_escape_string(array_pop(array_values($cf_v)));
                            $cfvq = "custom_field_values =\"" . $custom_field_to_save['custom_field_values'] . "\",";
                            $cfvq .= "custom_field_values_plain =\"" . $custom_field_to_save['custom_field_values_plain'] . "\",";
                            $cfvq .= "custom_field_name_plain =\"" . $cf_k_plain . "\",";

                        } else {
                            $cf_v = db_escape_string($cf_v);
                        }
                        $custom_field_to_save['custom_field_value'] = $cf_v;

                        $custom_field_to_save['rel'] = $table_assoc_name;

                        $custom_field_to_save['rel_id'] = $id_to_return;
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
						rel =\"" . $custom_field_to_save['rel'] . "\",
						rel_id =\"" . $custom_field_to_save['rel_id'] . "\"
						";

                        $add = " INSERT INTO $custom_field_table SET
						id ='{$next_id}',
						custom_field_name ='{$cf_k}',
						$cfvq
						custom_field_value ='{$custom_field_to_save ['custom_field_value']}',
						custom_field_type = 'content',
						rel ='{$custom_field_to_save ['rel']}',
						rel_id ='{$custom_field_to_save ['rel_id']}'
						";

                        $add = " INSERT INTO $custom_field_table SET

						custom_field_name ='{$cf_k}',
						$cfvq
						custom_field_value ='{$custom_field_to_save ['custom_field_value']}',
						custom_field_type = 'content',
						rel ='{$custom_field_to_save ['rel']}',
						rel_id ='{$custom_field_to_save ['rel_id']}'
						";

                        $cf_to_save = array();
                        $cf_to_save['id'] = $next_id;
                        $cf_to_save['custom_field_name'] = $cf_k;
                        $cf_to_save['rel'] = $custom_field_to_save['rel'];
                        $cf_to_save['rel_id'] = $custom_field_to_save['rel_id'];
                        $cf_to_save['custom_field_value'] = $custom_field_to_save['custom_field_value'];

                        if (isset($custom_field_to_save['custom_field_values'])) {
                            $cf_to_save['custom_field_values'] = $custom_field_to_save['custom_field_values'];
                        }
                        $cf_to_save['custom_field_name'] = $cf_k;
                        $cf_to_save['custom_field_name'] = $cf_k;

                        db_q($add);

                    }
                }
                cache_clean_group('custom_fields/global');
                // cache_clean_group ( 'global' );
                //	cache_clean_group ( 'extract_tags' );
            }
        }
    }

    if ($skip_cache == false) {
        $cg = guess_cache_group($table);
        //

        if ($media_table_modified == true) {
            cache_clean_group('media/global');

        }

        cache_clean_group($cg . '/global');
        cache_clean_group($cg . '/' . $id_to_return);

        if (isset($criteria['parent_id'])) {
            //d($criteria['parent_id']);
            cache_clean_group($cg . '/' . intval($criteria['parent_id']));
        }
    }
    return $id_to_return;
    if (intval($data['edited_by']) == 0) {

        $data['edited_by'] = $user_session['user_id'];
    }


    return intval($id_to_return);
}

/**
 * Get last id from a table
 *
 * @desc Get last inserted id from a table, you must have 'id' column in it.
 * @package Database
 * @param $table
 * @return bool|int
 *
 * @example
 * <pre>
 * $table_name = MW_TABLE_PREFIX . 'content';
 * $id = db_last_id($table_name);
 * </pre>
 *
 */
function db_last_id($table)
{

    //  $db = new DB(c('db'));

    if (DB_IS_SQLITE == true) {

        // $q = "SELECT last_insert_rowid()   FROM $table limit 1";

        $q = "SELECT ROWID AS the_id FROM $table ORDER BY ROWID DESC LIMIT 1";
    } else {
        // $q = "SELECT LAST_INSERT_ID() as the_id FROM $table limit 1";

        $q = "SELECT id AS the_id FROM $table ORDER BY id DESC LIMIT 1";
    }
    //d($q);
    $q = db_query($q);

    $result = $q[0];
    //d($result);
    //
    return intval($result['the_id']);
}



/**
 * Performs a query without returning a result
 *
 * Useful if you want to preform table updates or deletes without the need to see the result
 *
 *
 * @param string $q Your SQL query
 * @param bool|array $connection_settigns
 * @return array|bool|mixed
 * @package Database
 * @uses db_query
 *
 *
 * @example
 *  <code>
 *  //make plain query to the db.
 * 	$table = MW_TABLE_PREFIX.'content';
 *  $sql = "update $table set title='new' WHERE id=1 ";
 *  $q = db_q($sql);
 * </code>
 *
 */
function db_q($q, $connection_settigns = false)
{

    if (MW_IS_INSTALLED == false) {
        //    return false;
    }
    if ($connection_settigns == false) {
        $db = c('db');
    } else {
        $db = $connection_settigns;
    }
    $q = db_query($q, $cache_id = false, $cache_group = false, $only_query = true, $db);

    return $q;
}

/**
 * Executes plain query in the database.
 *
 * You can use this function to make queries in the db by writing your own sql
 * The results are returned as array or `false` if nothing is found
 *
 *
 * @note Please ensure your variables are escaped before calling this function.
 * @package Database
 * @function db_query
 * @desc Executes plain query in the database.
 *
 * @param string $q Your SQL query
 * @param string|bool $cache_id It will save the query result in the cache. Set to false to disable
 * @param string|bool $cache_group Stores the result in certain cache group. Set to false to disable
 * @param bool $only_query If set to true, will perform only a query without returning a result
 * @param array|bool $connection_settigns
 * @return array|bool|mixed
 *
 * @example
 *  <code>
 *  //make plain query to the db
 * $table = MW_TABLE_PREFIX.'content';
 *    $sql = "SELECT id FROM $table WHERE id=1   ORDER BY updated_on DESC LIMIT 0,1 ";
 *  $q = db_query($sql, $cache_id=crc32($sql),$cache_group= 'content/global');
 *
 * </code>
 *
 *
 *
 */
function db_query($q, $cache_id = false, $cache_group = 'global', $only_query = false, $connection_settigns = false)
{
    if (trim($q) == '') {
        return false;
    }


    $error['error'] = array();
    $results = false;
    // if (MW_IS_INSTALLED != false) {
    if ($cache_id != false and $cache_group != false) {
        // $results =false;

        $cache_id = $cache_id . crc32($q);
        $results = cache_get_content($cache_id, $cache_group);
        //	 d($results);
        if ($results != false) {
            if ($results == '---empty---' or (is_array($results) and empty($results))) {
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

    if (trim($q) == 'close') {
        if (isset($link)) {
            mysql_close($link);
        }
        return false;
    }


    $dbtype = 'mysql';
    if (isset($db['type']) and trim($db['type']) != '') {
        $dbtype = $db['type'];
    }

    $dbtype_file = MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . $dbtype . '.php';

    if (!isset($db) or $db == false or $db == NULL) {
        _reload_c();
        $db = c('db', true);


    }

    $temp_db = mw_var('temp_db');
    if ((!isset($db) or $db == false or $db == NULL) and $temp_db != false) {
        $db = $temp_db;
    }

    switch ($dbtype) {
        case file_exists($dbtype_file):
            include ($dbtype_file);

            break;

        default:
            include (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'mysql.php');
            break;
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
    if ($only_query == false and empty($q) or $q == false and $cache_group != false) {
        if ($cache_id != false) {

            cache_save('---empty---', $cache_id, $cache_group);
        }
        return false;
    }
    if ($only_query == false) {
        // $result = $q;
        if ($cache_id != false and $cache_group != false) {
            if (isarr($q) and !empty($q)) {

                cache_save($q, $cache_id, $cache_group);
            } else {
                cache_save('---empty---', $cache_id, $cache_group);
            }
        }
    }
    // }
    if ($cache_id != false) {
        cache_save($q, $cache_id, $cache_group);
    }
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
            if (isset($result['custom_fields_data']) and trim($result['custom_fields_data']) != '') {
                $try_dec = base64_decode($result['custom_fields_data'], true);

                if ($try_dec) {
                    $result['custom_fields_data'] = $try_dec;
                    // is valid
                } else {
                    // not valid
                }

            }

            $results[] = remove_slashes_from_array($result);
        }
    }

    $result = $results;

    if ($cache_id != false) {
        if (!empty($result)) {
            //    cache_save($result, $cache_id, $cache_group);
        } else {
            cache_save('---empty---', $cache_id, $cache_group);
        }
    }
    print '0000000000000000000000000000000---------------------';
    //d($result);
    return $result;
}





/**
 * Returns an array that contains only keys that has the same names as the table fields from the database
 *
 * @param string
 * @param  array
 * @return array
 * @package Database
 * @subpackage Advanced
 * @example
 * <code>
 * $table = MW_TABLE_PREFIX.'content';
 * $data = array();
 * $data['id'] = 1;
 * $data['non_ex'] = 'i do not exist and will be removed';
 * $criteria = map_array_to_database_table($table, $data);
 * var_dump($criteria);
 * </code>
 */
function map_array_to_database_table($table, $array)
{

    static $arr_maps = array();

    $arr_key = crc32($table) + crc32(serialize($array));
    if (isset($arr_maps[$arr_key])) {
        return $arr_maps[$arr_key];
    }

    if (empty($array)) {

        return false;
    }
    // $table = db_get_table_name($table);

    if (isset($arr_maps[$table])) {
        $fields = $arr_maps[$table];
    } else {
        $fields = db_get_table_fields($table);
        $arr_maps[$table] = $fields;
    }
    if (isarr($fields)) {
        foreach ($fields as $field) {

            $field = strtolower($field);

            //if (array_key_exists($field, $array)) {
            if (isset($array[$field])) {
                if ($array[$field] != false) {

                    // print ' ' . $field. ' <br>';
                    $array_to_return[$field] = $array[$field];
                }

                if ($array[$field] == 0) {

                    $array_to_return[$field] = $array[$field];
                }
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



/**
 * Guess the cache group from a table name or a string
 *
 * @uses guess_table_name()
 * @param bool|string $for Your table name
 *
 *
 * @return string The cache group
 * @example
 * <code>
 * $cache_gr = guess_cache_group('content');
 * </code>
 *
 * @package Database
 * @subpackage Advanced
 */
function guess_cache_group($for = false)
{
    return guess_table_name($for, true);
}

/**
 * Get Relative table name from a string
 *
 * @package Database
 * @subpackage Advanced
 * @param string $for string Your table name
 *
 * @param bool $guess_cache_group If true, returns the cache group instead of the table name
 *
 * @return bool|string
 * @example
 * <code>
 * $table = guess_table_name('content');
 * </code>
 */
function guess_table_name($for, $guess_cache_group = false)
{

    if (stristr($for, 'table_') == false) {
        switch ($for) {
            case 'user' :
            case 'users' :
                $rel = 'users';
                break;

            case 'media' :
            case 'picture' :
            case 'video' :
            case 'file' :
                $rel = 'media';
                break;

            case 'comment' :
            case 'comments' :
                $rel = 'comments';
                break;

            case 'module' :
            case 'modules' :
            case 'modules' :
            case 'modul' :
                $rel = 'modules';
                break;

            case 'category' :
            case 'categories' :
            case 'cat' :
            case 'categories' :
            case 'tag' :
            case 'tags' :
                $rel = 'categories';
                break;

            case 'category_items' :
            case 'cat_items' :
            case 'tag_items' :
            case 'tags_items' :
                $rel = 'categories_items';
                break;

            case 'post' :
            case 'page' :
            case 'content' :

            default :
                $rel = $for;
                break;
        }
        $for = $rel;
    }
    if (defined('MW_TABLE_PREFIX') and MW_TABLE_PREFIX != '' and stristr($for, MW_TABLE_PREFIX) == false) {
        //$for = MW_TABLE_PREFIX.$for;
    } else {

    }
    if ($guess_cache_group != false) {

        $for = str_replace('table_', '', $for);
        $for = str_replace(MW_TABLE_PREFIX, '', $for);
    }

    return $for;
}

/**
 * Keep a database query log
 *
 * @param string $q If its string it will add query to the log, its its bool true it will return the log entries as array;
 *
 * @return array
 * @example
 * <code>
 * //add query to the db log
 * db_query_log("select * from my_table");
 *
 * //get the query log
 * $queries = db_query_log(true);
 * var_dump($queries );
 * </code>
 * @package Database
 * @subpackage Advanced
 */
function db_query_log($q)
{
    static $index = array();
    if (is_bool($q)) {
        $index = array_unique($index);
        return $index;
    } else {

        $index[] = $q;

    }
}

/**
 * Updates multiple items in the database
 *
 *
 * @package Database
 * @subpackage Advanced
 * @param string $get_params Your parrams to be passed to the get() function
 * @param bool|string $save_params Array of the new data
 * @return array|bool|string
 * @see get()
 * @see save_data()
 * @example
 * <code>
 * //example updates the is_active flag of all content
 * mass_save("table=content&is_active=n", 'is_active=y');
 * </code>
 */
function mass_save($get_params, $save_params = false)
{
    if (is_admin() != true) {
        error('only admin can save');
    }
    $get_params1 = parse_params($get_params);
    $get_params1['return_criteria'] = 1;
    $test = get($get_params1);
    $upd = array();
    if (isset($test['table'])) {
        $save_params = parse_params($save_params);
        if (!is_arr($save_params)) {
            return 'error $save_params must be array';
        }

        $get = get($get_params); 
		 
        if (!is_arr($get)) {
            //$upd[] = save_data($test['table'], $save_params);
        } else {
            foreach ($get as $value) {
                $sp = $save_params;
			
                if (isset($value['id'])) {
                    $sp['id'] = $value['id']; 
					$upd[] = save_data($test['table'], $sp);
                }
               
            }
        }
    } else {
        error('could not find table');
    }
    if (!empty($upd)) {
		$cg = guess_cache_group($test['table']);
		cache_clean_group($cg);
        return $upd;
    } else {
        return false;
    }
}

function db_get($table, $criteria, $cache_group = false)
{
    return db_get_long($table, $criteria, $limit = false, $offset = false, $orderby = false, $cache_group, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false);
}


/**
 * Gets data from a table
 *
 *
 * @param bool|string $table table name
 * @param array|bool $criteria
 * @param array|bool|int $limit
 * @param array|bool|int $offset
 * @param bool|string $orderby
 * @param bool|string $cache_group
 * @param bool|string $debug
 * @param bool|string|array $ids
 * @param bool $count_only
 * @param bool|array $only_those_fields
 * @param bool|array $exclude_ids
 * @param bool|string $force_cache_id
 * @param bool $get_only_whats_requested_without_additional_stuff
 * @return array
 * @since Version 0.320
 * @package Database
 * @subpackage Advanced
 * @see get
 */
function db_get_long($table = false, $criteria = false, $limit = false, $offset = false, $orderby = false, $cache_group = false, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false)
{

    if ($table == false) {

        return false;
    }

    if (MW_IS_INSTALLED == true) {

        if (function_exists('get_option')) {
            $curent_time_zone = get_option('time_zone', 'website');
            if ($curent_time_zone != false and $curent_time_zone != '') {
                $default_time_zone = date_default_timezone_get();
                if ($default_time_zone != $curent_time_zone) {
                    date_default_timezone_set($curent_time_zone);
                }
            }
        }
    }


    $to_search = false;
    //  $table = db_g($table);
    $table = db_get_real_table_name($table);

    $aTable_assoc = $table_assoc_name = db_get_table_name($table);
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
        static $cfg_default_limit;
        if ($cfg_default_limit == false) {
            if (function_exists('get_option')) {
                $cfg_default_limit = get_option('items_per_page ', 'website');
            }
        }
        if ($cfg_default_limit != false and intval($cfg_default_limit) > 0) {
            $_default_limit = intval($cfg_default_limit);
        }

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

        $table_custom_fields = MW_TABLE_PREFIX . 'custom_fields';

        $only_custom_fieldd_ids = array();

        $use_fetch_db_data = true;

        $ids_q = "";

        if (!empty($ids)) {

            $ids_i = implode(',', $ids);

            $ids_q = " and rel_id in ($ids_i) ";
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

                $category_ids_q = " and rel_id in ($category_ids_q) ";
            } else {

                $category_ids_q = false;
            }

            $only_custom_fieldd_ids_q = false;

            if (!empty($only_custom_fieldd_ids)) {

                $only_custom_fieldd_ids_i = implode(',', $only_custom_fieldd_ids);

                $only_custom_fieldd_ids_q = " and rel_id in ($only_custom_fieldd_ids_i) ";
            }


            if ($is_not_null == true) {
                $cfvq = " custom_field_value IS NOT NULL  ";
            } else {
                $cfvq = " (custom_field_value LIKE '$v'  or custom_field_values_plain LIKE '$v'  )";

            }
            $table_assoc_name1 = db_get_assoc_table_name($table_assoc_name);
            $q = "SELECT  rel_id from " . $table_custom_fields . " where";
            $q .= " rel='$table_assoc_name1' and ";
            $q .= " (custom_field_name = '$k' or custom_field_name_plain='$k' ) and  ";
            $q .= db_escape_string($cfvq);
            $q .= $ids_q;
            $q .= $only_custom_fieldd_ids_q;
            $q2 = $q;

            $q = db_query($q, md5($q), 'custom_fields/global');
            //

            if (!empty($q)) {

                $ids_old = $ids;

                $ids = array();

                foreach ($q as $itm) {

                    $only_custom_fieldd_ids[] = $itm['rel_id'];

                    // if(in_array($itm ['rel_id'],$category_ids)==
                    // false){

                    $includeIds[] = $itm['rel_id'];

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

    if (!empty($criteria['include_categories'])) {

        $include_categories = true;
    } else {

        $include_categories = false;
    }

    if (!empty($criteria['exclude_ids'])) {

        $exclude_ids = $criteria['exclude_ids'];

        // unset($criteria['only_those_fields']);
        // no unset xcause f cache
    }
    if (isset($criteria['ids']) and is_string($criteria['ids'])) {
        $criteria['ids'] = explode(',', $criteria['ids']);
    }

    if (isset($criteria['ids']) and !empty($criteria['ids'])) {
        foreach ($criteria ['ids'] as $itm) {

            $includeIds[] = $itm;
        }
    }

    if (isset($criteria['category-id'])) {
        $criteria['category'] = $criteria['category-id'];
    }
    if (isset($criteria['category'])) {
        //
        $search_n_cats = $criteria['category'];
        if (is_string($search_n_cats)) {
            $search_n_cats = explode(',', $search_n_cats);
        }
        $is_in_category_items = false;
        if (is_array($search_n_cats) and !empty($search_n_cats)) {

            foreach ($search_n_cats as $cat_name_or_id) {

                $str0 = 'fields=id&limit=10000&data_type=category&what=categories&' . 'id=' . $cat_name_or_id . '&rel=' . $table_assoc_name;
                $str1 = 'fields=id&limit=10000&table=categories&' . 'id=' . $cat_name_or_id;

                $cat_name_or_id1 = intval($cat_name_or_id);
                $str1_items = 'fields=rel_id&limit=10000&what=category_items&' . 'parent_id=' . $cat_name_or_id;

                $is_in_category_items = get($str1_items);

                if (!empty($is_in_category_items)) {

                    foreach ($is_in_category_items as $is_in_category_items_tt) {

                        $includeIds[] = $is_in_category_items_tt["rel_id"];

                    }
                }

            }
        }
        // $is_in_category = get('limit=1&data_type=category_item&what=category_items&rel=' . $table_assoc_name . '&rel_id=' . $id_to_return . '&parent_id=' . $is_ex['id']);
        //  $includeIds;
        if ($is_in_category_items == false) {
            return false;
        }

    }

    if (isset($criteria['keyword'])) {
        $criteria['search_by_keyword'] = $criteria['keyword'];
    }

    $groupby = false;

    if (isset($criteria['group_by'])) {
        $groupby = $criteria['group_by'];
        if (is_string($groupby)) {
            $groupby = db_escape_string($groupby);
        }
    }

    if (isset($criteria['group'])) {
        $groupby = $criteria['group'];
        if (is_string($groupby)) {
            $groupby = db_escape_string($groupby);
        }
    }


    if ($count_only == false) {
        if (isset($criteria['order_by'])) {
            $orderby = $criteria['order_by'];
            if (is_string($orderby)) {
                $orderby = db_escape_string($orderby);
            }
        }

        if (isset($criteria['orderby'])) {
            $orderby = $criteria['orderby'];
            if (is_string($orderby)) {
                $orderby = db_escape_string($orderby);
            }
        }

    }


    $is_in_table = false;
    if (isset($criteria['in_table'])) {

        $is_in_table = db_escape_string($criteria['in_table']);

    }
    if (isset($criteria['keyword'])) {
        $criteria['search_by_keyword'] = $criteria['keyword'];
    }
    if (isset($criteria['data-keyword'])) {
        $criteria['search_by_keyword'] = $criteria['data-keyword'];
    }

    if (isset($criteria['search_by_keyword'])) {
        $to_search = db_escape_string($criteria['search_by_keyword']);
    }

    $to_search_in_those_fields = array();
    if (isset($criteria['search_in_fields'])) {
        $to_search_in_those_fields = $criteria['search_by_keyword_in_fields'] = $criteria['search_in_fields'];
    }

    if (!isset($criteria['search_in_fields']) and isset($criteria['search_by_keyword_in_fields'])) {
        $to_search_in_those_fields = ($criteria['search_by_keyword_in_fields']);
    }
    if (is_string($to_search_in_those_fields)) {
        $to_search_in_those_fields = explode(',', $to_search_in_those_fields);
        $to_search_in_those_fields = array_trim($to_search_in_those_fields);
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
            ksort($criteria);
            $function_cache_id = crc32(serialize($criteria));


            /*
                        foreach ($args as $k => $v) {

                            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
                        }
            */


            $function_cache_id = __FUNCTION__ . $table . crc32($function_cache_id);

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

                $ret = intval($cache_content[0]['qty']);

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
                // $orderby = explode(',', $orderby);
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

    if (is_string($orderby)) {
        $order_by = " ORDER BY  $orderby  ";
    } elseif (is_array($orderby) and !empty($orderby)) {

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
    $orig_criteria = $criteria;
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

        $q = "SELECT count(*) AS qty FROM $table ";
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

    $where_search = '';
    if ($to_search != false) {
        $to_search = db_escape_string(strip_tags($to_search));
        $to_search = str_replace('[', ' ', $to_search);
        $to_search = str_replace(']', ' ', $to_search);
        $to_search = str_replace('*', ' ', $to_search);
        $to_search = str_replace(';', ' ', $to_search);

    }

    if ($to_search != false and $to_search != '') {
        $fieals = db_get_table_fields($table);

        $where_post = ' OR ';

        $where_q = '';
        if (isset($to_search_in_those_fields) and is_string($to_search_in_those_fields)) {
            //$to_search_in_those_fields = explode(',', $to_search_in_those_fields);
            //d($to_search_in_those_fields);
        }

        foreach ($fieals as $v) {

            $add_to_seachq_q = true;

            if (!empty($to_search_in_those_fields)) {
                $add_to_seachq_q = FALSE;
                foreach ($to_search_in_those_fields as $fld1z) {
                    if ($fld1z == $v) {
                        $add_to_seachq_q = 1;
                    }
                }

            }


            if ($add_to_seachq_q == true) {
                $to_search = db_escape_string($to_search);
                //if ($v != 'id' && $v != 'password') {
                if (in_array($v, $to_search_in_those_fields) or ($v != '_username' && $v != '_password')) {
                    switch ($v) {

                        case 'title' :
                        case 'description' :
                        case 'name' :
                        case 'help' :
                        case 'content' :
                        case in_array($v, $to_search_in_those_fields) :
                            $where_q .= " $v REGEXP '$to_search' " . $where_post;
                            // $where_q .= " $v LIKE '%$to_search%' " . $where_post;
                            break;
                        case 'id' :
                            $to_search1 = intval($to_search);
                            $where_q .= " $v='$to_search1' " . $where_post;
                            break;
                        default :


                            break;
                    }

                    if (DB_IS_SQLITE == false) {

                        // $where_q .= " $v REGEXP '$to_search' " . $where_post;
                        // $where_q .= " $v REGEXP '$to_search' " . $where_post;
                    } else {

                    }
                }

            }
        }

        if (isset($to_search) and $to_search != '') {
            $table_custom_fields = MW_TABLE_PREFIX . 'custom_fields';
            $table_assoc_name1 = db_get_assoc_table_name($table_assoc_name);

            $where_q1 = " id in (select rel_id from $table_custom_fields where
				rel='$table_assoc_name1' and
				custom_field_values_plain REGEXP '$to_search' )  ";
            $where_q .= $where_q1;
        }


        $where_q = rtrim($where_q, ' OR ');

        if ($includeIds_idds != false) {
            $where_search = $where_search . '  (' . $where_q . ')' . $includeIds_idds;
        } else {

            $where_search = $where_search . $where_q;
        }
    } else {

    }

    if ($where_search != '') {
        $where_search = " AND ({$where_search}) ";
        //exit($where_search);
    }

    if (!empty($criteria)) {

        if (!$where) {

            $where = " WHERE ";
        }
        foreach ($criteria as $k => $v) {
            $compare_sign = '=';
            $is_val_str = true;
            $is_val_int = false;
            if (stristr($v, '[lt]')) {
                $compare_sign = '<';
                $v = str_replace('[lt]', '', $v);
            }

            if (stristr($v, '[mt]')) {

                $compare_sign = '>';

                $v = str_replace('[mt]', '', $v);
            }

            if (stristr($v, '[neq]')) {
                $compare_sign = '!=';
                $v = str_replace('[neq]', '', $v);
            }

            if (stristr($v, '[eq]')) {
                $compare_sign = '=';
                $v = str_replace('[eq]', '', $v);
            }


            if (stristr($v, '[int]')) {

                $is_val_str = false;
                $is_val_int = true;

                $v = str_replace('[int]', '', $v);
            }

            if (stristr($v, '[is]')) {

                $compare_sign = ' IS ';

                $v = str_replace('[is]', '', $v);
            }

            if (stristr($v, '[like]')) {

                $compare_sign = ' LIKE ';

                $v = str_replace('[like]', '', $v);
            }

            if (stristr($v, '[is_not]')) {

                $compare_sign = ' IS NOT ';

                $v = str_replace('[is_not]', '', $v);
            }

            /*
             * var_dump ( $k ); var_dump ( $v ); print '<hr>';
             */

            if (($k == 'updated_on') or ($k == 'created_on')) {

                $v = strtotime($v);
                $v = date("Y-m-d H:i:s", $v);
            }
            if (trim($v) == '[not_null]') {
                $where .= "$k IS NOT NULL AND ";
            } else if (trim($v) == '[null]') {
                $where .= "$k IS NULL AND ";
            } else if ($k == 'module') {
                $module_name = trim($v);
                $module_name = str_replace('\\\\', DS, $module_name);
                $module_name = str_replace('\\', DS, $module_name);
                $module_name = str_replace('//', DS, $module_name);
                $module_name = str_replace('\\', '/', $module_name);
                $module_name = addslashes($module_name);
                //$module_name = reduce_double_slashes($module_name);
                $where .= "$k {$compare_sign} '{$module_name}' AND ";
            } else if ($is_val_int == true and $is_val_str == false) {
                $v = intval($v);

                $where .= "$k {$compare_sign} $v AND ";
            } else {
                $where .= "$k {$compare_sign} '$v' AND ";

            }
        }

        $where .= " id is not null ";
    } else {

        $where = " WHERE ";

        $where .= " id is not null ";
    }

    if ($is_in_table != false) {
        $is_in_table = db_escape_string($is_in_table);
        if (stristr($is_in_table, 'table_') == false and stristr($is_in_table, MW_TABLE_PREFIX) == false) {
            $is_in_table = 'table_' . $is_in_table;
        }
        $v1 = db_get_real_table_name($is_in_table);
        $check_if_ttid = db_get_table_fields($v1);
        if (in_array('rel_id', $check_if_ttid) and in_array('rel', $check_if_ttid)) {
            $aTable_assoc1 = db_get_assoc_table_name($aTable_assoc);
            if ($v1 != false) {
                $where .= " AND id in (select rel_id from $v1 where $v1.rel='{$aTable_assoc1}' and $v1.rel_id=$table.id ) ";
            }
        }
        // d($where);
    }

    if (!isset($idds)) {
        $idds = '';
    }

    if ($where != false) {

        $q = $q . $where . $idds . $exclude_idds . $where_search;
    } else {
        $q = $q . " WHERE " . $idds . $exclude_idds . $where_search;
    }
    if ($includeIds_idds != false) {
        $q = $q . $includeIds_idds . $where_search;
        ;
    }
    if ($where_search != '') {
        //	$where_search = " AND {$where_search} ";
        //  exit($q);
    }

    if ($groupby != false) {
        $q .= " $groupby  ";
    } else {

        if ($count_only != true) {
            $q .= " group by id  ";
        }
    }
    if ($order_by != false) {

        $q = $q . $order_by;
    }

    if (isset($limit_from_paging_q) and trim($limit_from_paging_q) != "") {
        $limit = $limit_from_paging_q;
    } else {

    }
    if ($limit != false) {

        $q = $q . $limit;
    }

    if ($debug == true) {

        var_dump($table, $q, $is_in_table);
    }

    if ($to_search != false) {

    }
    if ($to_search != false) {
        $original_cache_id = false;
        $original_cache_group = false;
        // print($q);
        //	return;
    }
    if ($original_cache_group != false) {
        $result = db_query($q, $original_cache_id, $original_cache_group);
    } else {
        //d($q);
        $result = db_query($q, false, false);

    }
    if ($count_only != true) {
        if ($to_search != false) {
            //	return $result;
        }
    }

    if (isset($result[0]['qty']) == true and $count_only == true) {

        $ret = $result[0]['qty'];
        if ($count_paging == true) {
            $plimit = false;
            if ($limit == false and isset($orig_criteria['limit'])) {
                $limit = intval($orig_criteria['limit']);
            }
            if ($limit == false) {
                $plimit = $_default_limit;
            } else {
                if (is_array($limit)) {
                    $plimit = end($plimit);
                } else {
                    $plimit = intval($limit);
                }
            }
            if ($plimit != false) {
                $pages_qty = ceil($ret / $plimit);
                return $pages_qty;
            }
        } else {

            return intval($ret);
        }

        // p($result);

        return $ret;
    }

    //
    if ($count_only == true) {
       if(is_array($result[0]) and isset($result[0]['qty'])){
        $ret = $result[0]['qty'];

        return $ret;
       } else {
           return 0;
       }
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

            //cache_save($return, $original_cache_id, $original_cache_group);
        } else {

            //  cache_save('---empty---', $original_cache_id, $original_cache_group);
        }
    }
    //
    return $return;
}

function db_get_table_name($assoc_name)
{

    $assoc_name = str_ireplace('table_', MW_TABLE_PREFIX, $assoc_name);
    return $assoc_name;
}

$_mw_db_get_assoc_table_names = array();
function db_get_assoc_table_name($assoc_name)
{

    global $_mw_db_get_assoc_table_names;

    if (isset($_mw_db_get_assoc_table_names[$assoc_name])) {

        return $_mw_db_get_assoc_table_names[$assoc_name];
    }


    $assoc_name_o = $assoc_name;
    $assoc_name = str_ireplace(MW_TABLE_PREFIX, 'table_', $assoc_name);
    $assoc_name = str_ireplace('table_', '', $assoc_name);

    $is_assoc = substr($assoc_name, 0, 5);
    if ($is_assoc != 'table_') {
        //	$assoc_name = 'table_' . $assoc_name;
    }


    $assoc_name = str_replace('table_table_', 'table_', $assoc_name);
    //	d($is_assoc);
    $_mw_db_get_assoc_table_names[$assoc_name_o] = $assoc_name;
    return $assoc_name;
}

$_mw_db_get_real_table_names = array();
function db_get_real_table_name($assoc_name)
{
    global $_mw_db_get_real_table_names;

    if (isset($_mw_db_get_real_table_names[$assoc_name])) {

        return $_mw_db_get_real_table_names[$assoc_name];
    }


    $assoc_name_new = str_ireplace('table_', MW_TABLE_PREFIX, $assoc_name);
    if (defined('MW_TABLE_PREFIX') and MW_TABLE_PREFIX != '' and stristr($assoc_name_new, MW_TABLE_PREFIX) == false) {
        $assoc_name_new = MW_TABLE_PREFIX . $assoc_name_new;
    }
    $_mw_db_get_real_table_names[$assoc_name] = $assoc_name_new;
    return $assoc_name_new;
}

function db_get_tables_list()
{
    $db = c('db');
    $db = $db['dbname'];
    $q = db_query("SHOW TABLES FROM $db", __FUNCTION__, 'db');
    if (isset($q['error'])) {
        return false;
    } else {
        $ret = array();
        if (is_arr($q)) {
            foreach ($q as $value) {
                $v = array_values($value);
                if (isset($v[0]) and is_string($v[0])) {
                    if (strstr($v[0], MW_TABLE_PREFIX)) {
                        $ret[] = ($v[0]);
                    }
                }
            }


        }
        return $ret;
    }
}

function db_table_exist($table)
{
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
 *            - table name
 * @param $exclude_fields array
 *            - fields to exclude
 * @return array
 * @author Peter Ivanov
 * @version 1.0
 * @since Version 1.0
 */
$ex_fields_static = array();
function db_get_table_fields($table, $exclude_fields = false)
{

    global $ex_fields_static;
    if (isset($ex_fields_static[$table])) {
        return $ex_fields_static[$table];

    }

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
        $ex_fields_static[$table] = $cache_content;
        return $cache_content;
    }

    $table = db_get_real_table_name($table);
    $table = db_escape_string($table);

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
        $ex_fields_static[$table] = false;
        return false;
    }

    if (!isarr($fields)) {
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
    $ex_fields_static[$table] = $fields;
    cache_save($fields, $function_cache_id, $cache_group = 'db');
    // $fields = (array_change_key_case ( $fields, CASE_LOWER ));
    return $fields;
}


function db_update_position($table, $data = array())
{
    $table = guess_table_name($table);
    $table_real = db_get_real_table_name($table);
    $i = 0;
    if (isarr($data)) {
        foreach ($data as $value) {
            $value = intval($value);
            if ($value != 0) {
                $q = "UPDATE $table_real SET position={$i} WHERE id={$value} ";
                $q = db_q($q);
            }
            $i++;
        }
    }

    $cg = guess_cache_group($table);
    //
    // d($cg);
    cache_clean_group($cg);
}




/**
 * Escapes a string from sql injection
 *
 * @param string $value to escape
 *
 * @return mixed
 * @example
 * <code>
 * //escape sql string
 *  $results = db_escape_string($_POST['email']);
 * </code>
 *
 *
 *
 * @package Database
 * @subpackage Advanced
 */
$mw_escaped_strings = array();
function db_escape_string($value)
{
    global $mw_escaped_strings;
    if(isset($mw_escaped_strings[$value])){
        return $mw_escaped_strings[$value];
    }

    $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
    $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
    $new = str_replace($search, $replace, $value);
    $mw_escaped_strings[$value] = $new;
    return $new;
}

/**
 * Deletes item by id from db table
 *
 * @param string $table Your da table name
 * @param int|string $id The id to delete
 * @param string $field_name You can set custom column to delete by it, default is id
 *
 * @return bool
 * @example
 * <code>
 * //delete content with id 5
 *  db_delete_by_id('content', $id=5);
 * </code>
 *
 * @package Database
 */
function db_delete_by_id($table, $id = 0, $field_name = 'id')
{
    $table = guess_table_name($table);
    $table_real = db_get_real_table_name($table);
    $id = intval($id);

    if ($id == 0) {

        return false;
    }

    $q = "DELETE FROM $table_real WHERE {$field_name}='$id' ";

    $cg = guess_cache_group($table);
    //
    // d($cg);
    cache_clean_group($cg);
    $q = db_q($q);

    $table1 = MW_TABLE_PREFIX . 'categories';
    $table_items = MW_TABLE_PREFIX . 'categories_items';

    $q = "DELETE FROM $table1 WHERE rel_id='$id'  AND  rel='$table'  ";

    $q = db_q($q);
    //  cache_clean_group('categories');

    $q = "DELETE FROM $table_items WHERE rel_id='$id'  AND  rel='$table'  ";
    //d($q);
    $q = db_q($q);


    if (defined("MW_DB_TABLE_NOTIFICATIONS")) {
        $table_items = MW_DB_TABLE_NOTIFICATIONS;
        $q = "DELETE FROM $table_items WHERE rel_id='$id'  AND  rel='$table'  ";

        $q = db_q($q);
    }

    $c_id = $id;
    if (defined("MW_DB_TABLE_MEDIA")) {
        $table1 = MW_DB_TABLE_MEDIA;
        $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='$table'  ";
        $q = db_query($q);
    }

    if (defined("MW_DB_TABLE_TAXONOMY")) {
        $table1 = MW_DB_TABLE_TAXONOMY;
        $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='$table'  ";
        $q = db_query($q);
    }


    if (defined("MW_DB_TABLE_TAXONOMY_ITEMS")) {
        $table1 = MW_DB_TABLE_TAXONOMY_ITEMS;
        $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='$table'  ";
        $q = db_query($q);
    }


    if (defined("MW_DB_TABLE_CUSTOM_FIELDS")) {
        $table1 = MW_DB_TABLE_CUSTOM_FIELDS;
        $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='$table'  ";
        $q = db_query($q);
    }
}

/**
 * Copy entire database row
 *
 * @param string $table Your table
 * @param int|string $id The id to copy
 * @param string $field_name You can set custom column to copy by it, default is id
 *
 *
 * @return bool|int
 * @example
 * <code>
 * //copy content with id 5
 *  db_copy_by_id('content', $id=5);
 * </code>
 *
 * @package Database
 * @subpackage Advanced
 *
 */
function db_copy_by_id($table, $id = 0, $field_name = 'id')
{

    $q = db_get_id($table, $id, $field_name);
    //	d($q);
    if (isset($q[$field_name])) {
        $data = $q;
        if (isset($data[$field_name])) {
            unset($data[$field_name]);
        }

        $s = save_data($table, $data);
        return $s;
    }

}


/**
 * Will strip the sql comment lines out of an given sql string
 *
 * @param $output the SQL string with comments
 *
 * @return string  $output the SQL string without comments
 * @example
 * <code>
 *  sql_remove_comments($sql_str);
 * </code>
 *
 * @package Database
 * @subpackage Advanced
 */
function sql_remove_comments($output)
{
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

function import_sql_from_file($full_path_to_file)
{

    $dbms_schema = $full_path_to_file;

    if (is_file($dbms_schema)) {
        $sql_query = fread(fopen($dbms_schema, 'r'), filesize($dbms_schema)) or die('problem ');
        $sql_query = str_ireplace('{MW_TABLE_PREFIX}', MW_TABLE_PREFIX, $sql_query);
        $sql_query = sql_remove_remarks($sql_query);

        $sql_query = sql_remove_comments($sql_query);
        $sql_query = split_sql_file($sql_query, ';');

        $i = 1;
        foreach ($sql_query as $sql) {
            $sql = trim($sql);

            //d($sql);
            $qz = db_q($sql);
        }
        //cache_clean_group('db');
        return true;
    } else {
        return false;
    }
}

//
// remove_remarks will strip the sql comment lines out of an uploaded sql file
//
function sql_remove_remarks($sql)
{
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
function split_sql_file($sql, $delimiter)
{
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
    $output = preg_replace('/\x{EF}\x{BB}\x{BF}/', '', $output);
    return $output;
}

/**
 * Creates database table from array
 *
 * You can pass an array of database fields and this function will set up the same db table from it
 *
 * @example
 * <pre>
 * To create custom table use
 *
 *
 * $table_name = MW_TABLE_PREFIX . 'my_new_table'
 *
 * $fields_to_add = array();
 * $fields_to_add[] = array('updated_on', 'datetime default NULL');
 * $fields_to_add[] = array('created_by', 'int(11) default NULL');
 * $fields_to_add[] = array('content_type', 'TEXT default NULL');
 * $fields_to_add[] = array('url', 'longtext default NULL');
 * $fields_to_add[] = array('content_filename', 'TEXT default NULL');
 * $fields_to_add[] = array('title', 'longtext default NULL');
 * $fields_to_add[] = array('is_active', "char(1) default 'y'");
 * $fields_to_add[] = array('is_deleted', "char(1) default 'n'");
 *  set_db_table($table_name, $fields_to_add);
 * </pre>
 *
 * @desc refresh tables in DB
 * @access        public
 * @category Database
 * @package    Database
 * @subpackage Advanced
 * @param        string $table_name to alter table
 * @param        array $fields_to_add to add new columns
 * @param        array $column_for_not_drop for not drop
 * @return bool|mixed
 */
function set_db_table($table_name, $fields_to_add, $column_for_not_drop = array())
{
    $function_cache_id = false;

    $args = func_get_args();

    foreach ($args as $k => $v) {

        $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
    }

    $function_cache_id = __FUNCTION__ . $table_name . crc32($function_cache_id);

    $cache_content = cache_get_content($function_cache_id, 'db/' . $table_name, 'files');

    if (($cache_content) != false) {

        return $cache_content;
    }

    $query = db_query("show tables like '$table_name'");

    if (!is_array($query)) {
        $sql = "CREATE TABLE " . $table_name . " (
			id int(11) NOT NULL auto_increment,
			PRIMARY KEY (id)

			) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

";
        //
        //if (isset($_GET['debug'])) {
        //	d($sql);
        db_q($sql);
        //}
    }

    if ($table_name != 'firecms_sessions') {
        if (empty($column_for_not_drop))
            $column_for_not_drop = array('id');

        $sql = "show columns from $table_name";

        $columns = db_query($sql);

        $exisiting_fields = array();
        $no_exisiting_fields = array();

        foreach ($columns as $fivesdraft) {
            $fivesdraft = array_change_key_case($fivesdraft, CASE_LOWER);
            $exisiting_fields[strtolower($fivesdraft['field'])] = true;
        }

        for ($i = 0; $i < count($columns); $i++) {
            $column_to_move = true;
            for ($j = 0; $j < count($fields_to_add); $j++) {
                if (in_array($columns[$i]['Field'], $fields_to_add[$j])) {
                    $column_to_move = false;
                }
            }
            $sql = false;
            if ($column_to_move) {
                if (!empty($column_for_not_drop)) {
                    if (!in_array($columns[$i]['Field'], $column_for_not_drop)) {
                        $sql = "ALTER TABLE $table_name DROP COLUMN {$columns[$i]['Field']} ";
                    }
                } else {
                    $sql = "ALTER TABLE $table_name DROP COLUMN {$columns[$i]['Field']} ";
                }
                if ($sql) {
                    db_q($sql);

                }
            }
        }

        foreach ($fields_to_add as $the_field) {
            $the_field[0] = strtolower($the_field[0]);

            $sql = false;
            if (isset($exisiting_fields[$the_field[0]]) != true) {
                $sql = "alter table $table_name add column " . $the_field[0] . " " . $the_field[1] . "";
                db_q($sql);
            } else {
                //$sql = "alter table $table_name modify {$the_field[0]} {$the_field[1]} ";

            }

        }

    }

    cache_save('--true--', $function_cache_id, $cache_group = 'db/' . $table_name, 'files');
    // $fields = (array_change_key_case ( $fields, CASE_LOWER ));
    return true;
    //set_db_tables
}

/**
 * Add new table index if not exists
 *
 * @example
 * <pre>
 * db_add_table_index('title', $table_name, array('title'));
 * </pre>
 *
 * @category Database
 * @package    Database
 * @subpackage Advanced
 * @param string $aIndexName Index name
 * @param string $aTable Table name
 * @param string $aOnColumns Involved columns
 * @param bool $indexType
 */
function db_add_table_index($aIndexName, $aTable, $aOnColumns, $indexType = false)
{
    $columns = implode(',', $aOnColumns);

    $query = db_query("SHOW INDEX FROM {$aTable} WHERE Key_name = '{$aIndexName}';");

    if ($indexType != false) {

        $index = $indexType;
    } else {
        $index = " INDEX ";

        //FULLTEXT
    }

    if ($query == false) {
        $q = "ALTER TABLE " . $aTable . " ADD $index `" . $aIndexName . "` (" . $columns . ");";
        // var_dump($q);
        db_q($q);
    }

}

/**
 * Set table's engine
 *
 * @category Database
 * @package    Database
 * @subpackage Advanced
 * @param string $aTable
 * @param string $aEngine
 */
function db_set_engine($aTable, $aEngine = 'MyISAM')
{
    db_q("ALTER TABLE {$aTable} ENGINE={$aEngine};");
}

/**
 * Create foreign key if not exists
 *
 * @category Database
 * @package    Database
 * @subpackage Advanced
 * @param string $aFKName Foreign key name
 * @param string $aTable Source table name
 * @param array $aColumns Source columns
 * @param string $aForeignTable Foreign table name
 * @param array $aForeignColumns Foreign columns
 * @param array $aOptions On update and on delete options
 */
function db_add_foreign_key($aFKName, $aTable, $aColumns, $aForeignTable, $aForeignColumns, $aOptions = array())
{
    $query = db_query("
		SELECT
		*
		FROM
		INFORMATION_SCHEMA.TABLE_CONSTRAINTS
		WHERE
		CONSTRAINT_TYPE = 'FOREIGN KEY'
		AND
		constraint_name = '{$aFKName}'
		;");

    if ($query == false) {

        $columns = implode(',', $aColumns);
        $fColumns = implode(',', $aForeignColumns);
        ;
        $onDelete = 'ON DELETE ' . (isset($aOptions['delete']) ? $aOptions['delete'] : 'NO ACTION');
        $onUpdate = 'ON UPDATE ' . (isset($aOptions['update']) ? $aOptions['update'] : 'NO ACTION');
        $q = "ALTER TABLE " . $aTable;
        $q .= " ADD CONSTRAINT `" . $aFKName . "` ";
        $q .= " FOREIGN KEY(" . $columns . ") ";
        $q .= " {$onDelete} ";
        $q .= " {$onUpdate} ";
        db_q($q);
    }

}