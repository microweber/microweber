<?php


namespace Microweber;


if (!defined('DB_IS_SQLITE')) {
    define('DB_IS_SQLITE', false);
}

if (!defined('MW_USER_IP')) {
    if (isset($_SERVER["REMOTE_ADDR"])) {
        define("MW_USER_IP", $_SERVER["REMOTE_ADDR"]);
    } else {
        define("MW_USER_IP", '127.0.0.1');

    }
}
$mw_db_arr_maps = array();
class Db
{


    public $app;

    function __construct($app = null)
    {


        if (!is_object($this->app)) {

            if (is_object($app)) {
                $this->app = $app;
            } else {

                $this->app = mw('application');
            }

        }


    }

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
     *  $results = $this->get("table=content&is_active=y");
     * </code>
     *
     * @example
     *  <code>
     *  //get users
     *  $results = $this->get("table=users&is_admin=n");
     * </code>
     *
     * @package Database
     */
    public function get($params)
    {


        $orderby = false;
        $cache_group = false;
        $debug = false;
        $getone = false;
        $no_cache = false;

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

            }

            if ($k == 'what' and !isset($params['rel'])) {
                $table = guess_table_name($v);
            }

            if ($k == 'for' and !isset($params['rel'])) {
                $v = $this->assoc_table_name($v);
                $k = 'rel';
            }

            if ($k == 'debug') {
                $debug = ($v);
            }

            if ($k == 'cache_group') {
                if ($no_cache == false) {
                    $cache_group = $v;
                }
            }

            if ($k == 'no_cache') {
                $cache_group = false;
                $no_cache = true;
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
        if (isset($no_cache) and $no_cache == true) {
            $mode = 2;
        }
        //$mode = 2;
        switch ($mode) {
            case 1 :
                static $results_map = array();
                //static $results_map_hits = array();
                $criteria_id = (int)crc32($table . serialize($criteria));

                if (isset($results_map[$criteria_id])) {
                    $get_db_items = $results_map[$criteria_id];
                    //$results_map_hits[$criteria_id]++;
                } else {
                    $get_db_items = $this->get_long($table, $criteria, $limit = false, $offset = false, $orderby, $cache_group, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false);


                    //$results_map_hits[$criteria_id] = 1;
                    $results_map[$criteria_id] = $get_db_items;

                }
                break;
            case 2 :
            default :
                $get_db_items = $this->get_long($table, $criteria, $limit = false, $offset = false, $orderby, $cache_group, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false);

                break;
        }

        if (is_integer($get_db_items)) {


            return ($get_db_items);
        }

        if (empty($get_db_items)) {
            return false;
        }

        if ($getone == true) {

            if (is_array($get_db_items)) {

                $one = array_shift($get_db_items);

                return $one;
            }
        }

        return $get_db_items;
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
     * $cont = $this->get_by_id('content', $id=5);
     * </code>
     *
     * @package Database
     * @subpackage Advanced
     */
    public function get_by_id($table, $id = 0, $field_name = 'id')
    {

        $id = intval($id);

        if ($id == 0) {

            return false;
        }

        if ($field_name == false) {
            $field_name = "id";
        }
        $table = $this->real_table_name($table);
        $table = $this->real_table_name($table);

        $q = "SELECT * FROM $table WHERE {$field_name}='$id' LIMIT 1";

        $q = $this->query($q);
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
    public function save($table, $data = false, $data_to_save_options = false)
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

                    $this->app->cache->delete($item);
                }
            }
        }
        if (isset($_SESSION) and !empty($_SESSION) and isset($_SESSION["user_session"])) {
            $user_session = $_SESSION["user_session"];

        } else {
            $user_session = false;
        }
        $table = $this->real_table_name($table);
        $user_sid = false;


        if (!isset($user_session['user_id'])) {
            $user_sid = session_id();

        } else {
            if (intval($user_session['user_id']) == 0) {
                unset($user_session['user_id']);
                $user_sid = session_id();
            }
        }

        if (isset($user_session['user_id'])) {
            $the_user_id = $user_session['user_id'];

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


        $data['user_ip'] = MW_USER_IP;
        if (isset($data['id']) == false or $data['id'] == 0) {
            $data['id'] = 0;
            $l = $this->last_id($table);
            //$data['id'] = $l;
            $data['new_id'] = intval($l + 1);
            $original_data['new_id'] = $data['new_id'];
        }

        if (!isset($the_user_id)) {
            $the_user_id = 0;
            $the_user_id = null;
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

        $table_assoc_name = $this->assoc_table_name($table);

        $criteria_orig = $data;

        $criteria = $this->map_array_to_table($table, $data);


        //
        //  if ($data_to_save_options ['do_not_replace_urls'] == false) {

        $criteria = $this->app->url->replace_site_url($criteria);

        //  }

        if ($data_to_save_options['use_this_field_for_id'] != false) {

            $criteria['id'] = $criteria_orig[$data_to_save_options['use_this_field_for_id']];
        }

        // $criteria = $this->map_array_to_table ( $table, $data );

        $criteria = $this->addslashes_array($criteria);

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
//                    if(is_string($v)){
//                        $v = $this->escape_string($v);
//                    }
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

                //$k = $this->escape_string($k);
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
            $q = rtrim($q, ',');
            $user_createdq = '';
            $user_createdq1 = '';
            $idq = '';
            if ((mw_var('FORCE_ANON_UPDATE') != false and $table == mw_var('FORCE_ANON_UPDATE')) or (defined('FORCE_ANON_UPDATE') and $table == FORCE_ANON_UPDATE)) {
                $idq = " id={$data['id']} ";
            } else {
                if ($the_user_id != 0) {
                    if (isset($data['created_by'])) {
                        $user_createdq = " , created_by=$the_user_id ";
                    }

                    if (isset($data['edited_by'])) {
                        $user_createdq1 = " , edited_by=$the_user_id ";
                    } else {
                        $user_createdq1 = " , id={$data ['id']} ";
                    }
                }


            }
            if ($user_createdq1 == '') {
                if (isset($_SESSION)) {
                    if (isset($data['session_id'])) {
                        if ($user_sid != false) {
                            $user_sidq = " AND session_id='{$user_sid}'  ";
                        }
                    }

                }
            }
            $q .= " $user_createdq1 WHERE id={$data ['id']} {$idq} {$user_sidq}  {$user_createdq} limit 1";

            $id_to_return = $data['id'];
        }

        if ($dbg != false) {
            var_dump($q);
        }

        $this->q($q);

        if ($id_to_return == false) {
            $id_to_return = $this->last_id($table);
        }


        $original_data['table'] = $table;
        $original_data['id'] = $id_to_return;

        $this->save_extended_data($original_data);


        $cg = $this->assoc_table_name($table);


        $this->app->cache->delete($cg . '/global');
        $this->app->cache->delete($cg . '/' . $id_to_return);


        if ($skip_cache == false) {
            $cg = $this->assoc_table_name($table);


            $this->app->cache->delete($cg . '/global');
            $this->app->cache->delete($cg . '/' . $id_to_return);

            if (isset($criteria['parent_id'])) {
                //d($criteria['parent_id']);
                $this->app->cache->delete($cg . '/' . intval($criteria['parent_id']));
            }
        }
        return $id_to_return;

    }


    public function save_extended_data($original_data)
    {

        if (!defined("MW_TABLE_PREFIX")) {
            return false;
        }


        if (!defined("MW_IS_INSTALLED") or MW_IS_INSTALLED == false) {
            return false;
        }
        if (!isset($original_data['table'])) {
            return false;
        }
        if (!isset($original_data['id'])) {
            return false;
        }

        $id_to_return = $original_data['id'];
        $table_assoc_name = $original_data['table'];
        $table_assoc_name = $this->assoc_table_name($table_assoc_name);
        if (defined("MW_DB_TABLE_TAXONOMY")) {
            $categories_table = MW_DB_TABLE_TAXONOMY;
        } else {
            $categories_table = MW_TABLE_PREFIX . 'categories';
        }


        if (defined("MW_DB_TABLE_CUSTOM_FIELDS")) {
            $custom_field_table = MW_DB_TABLE_CUSTOM_FIELDS;
        } else {
            $custom_field_table = MW_TABLE_PREFIX . 'custom_fields';
        }
        if (defined("MW_DB_TABLE_TAXONOMY_ITEMS")) {
            $table_cats_items = $categories_items_table = MW_DB_TABLE_TAXONOMY_ITEMS;
        } else {
            $table_cats_items = $categories_items_table = MW_TABLE_PREFIX . 'categories_items';
        }


        if (isset($original_data['categories'])) {
            if (!$this->table_exist($categories_table)) {
                return false;
            }
            if (!$this->table_exist($table_cats_items)) {
                return false;
            }

            $is_a = $this->app->user->has_access('save_category');
            $is_a = 1;
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
                    $this->q($clean_q);
                } else {

                    if (is_string($original_data['categories'])) {
                        $original_data['categories'] = str_replace('/', ',', $original_data['categories']);
                        $cz = explode(',', $original_data['categories']);
                        $j = 0;
                        $cz_int = array();
                        foreach ($cz as $cname_check) {

                            if (intval($cname_check) == 0) {
                                $cname_check = trim($cname_check);
                                $cname_check = $this->escape_string($cname_check);

                                if ($cname_check != '') {

                                    $cncheckq = "SELECT id
								FROM $categories_table WHERE
								data_type='category'
								AND   rel='{$table_assoc_name}'
								AND   title='{$cname_check}'   ";
                                    // d($cncheckq);
                                    $is_ex = $this->query($cncheckq);

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

                                        $this->q($clean_q);

                                    }
                                }

                                $is_ex = $this->query($cncheckq);
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

                        $this->q($clean_q);

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

                    }
                    $cats_data_modified = false;
                    $cats_data_items_modified = false;
                    $keep_thosecat_items = array();

                    foreach ($cat_names_or_ids as $cat_name_or_id) {
                        $cat_name_or_id = $this->escape_string(trim($cat_name_or_id));
                        if ($cat_name_or_id != '') {
                            $q_cat1 = "INSERT INTO $categories_items_table  SET

						parent_id='{$cat_name_or_id}',
						rel='{$table_assoc_name}',
						data_type='category_item',
						rel_id='{$id_to_return}'
						";

                            $this->q($q_cat1);
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
                    //$this->q($clean_q);
                    //   d($clean_q);
                }

                if ($cats_data_modified == TRUE) {
                    $this->app->cache->delete('categories' . DIRECTORY_SEPARATOR . 'global');
                    if (isset($parent_id)) {
                        $this->app->cache->delete('categories' . DIRECTORY_SEPARATOR . $parent_id);
                    }
                    //$this->app->cache->delete('categories_items' . DIRECTORY_SEPARATOR . '');
                }
                if ($cats_data_items_modified == TRUE) {
                    $this->app->cache->delete('categories_items' . DIRECTORY_SEPARATOR . '');
                }
            }


        }

        // adding custom fields
        $table_assoc_name = $this->assoc_table_name($table_assoc_name);
        $custom_field_to_save = array();

        if (!isset($original_data['skip_custom_field_save']) and isset($original_data['custom_fields']) and $table_assoc_name != 'table_custom_fields') {


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
                if (!$this->table_exist($custom_field_table)) {
                    return false;
                }

                $custom_field_to_delete['rel'] = $table_assoc_name;

                $custom_field_to_delete['rel_id'] = $id_to_return;

                if (isset($original_data['skip_custom_field_save']) == false) {

                    $custom_field_to_save = $this->app->url->replace_site_url($custom_field_to_save);
                    $custom_field_to_save = $this->addslashes_array($custom_field_to_save);

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
                                $this->q($clean);
                            }
                            $cfvq = '';
                            $custom_field_to_save['custom_field_name'] = $cf_k;
                            if (is_array($cf_v)) {
                                $cf_k_plain = $this->app->url->slug($cf_k);
                                $cf_k_plain = $this->escape_string($cf_k_plain);
                                $cf_k_plain = str_replace('-', '_', $cf_k_plain);
                                $custom_field_to_save['custom_field_values'] = base64_encode(serialize($cf_v));
                                $custom_field_to_save['custom_field_values_plain'] = $this->escape_string(array_pop(array_values($cf_v)));
                                $cfvq = "custom_field_values =\"" . $custom_field_to_save['custom_field_values'] . "\",";
                                $cfvq .= "custom_field_values_plain =\"" . $custom_field_to_save['custom_field_values_plain'] . "\",";
                                $cfvq .= "custom_field_name_plain =\"" . $cf_k_plain . "\",";

                            } else {
                                $cf_v = $this->escape_string($cf_v);
                            }
                            $custom_field_to_save['custom_field_value'] = $cf_v;

                            $custom_field_to_save['rel'] = $table_assoc_name;

                            $custom_field_to_save['rel_id'] = $id_to_return;
                            $custom_field_to_save['skip_custom_field_save'] = true;


                            $next_id = intval($this->last_id($custom_field_table) + 1);

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
						custom_field_value ='{$custom_field_to_save['custom_field_value']}',
						custom_field_type = 'content',
						rel ='{$custom_field_to_save ['rel']}',
						rel_id ='{$custom_field_to_save ['rel_id']}'
						";

                            $add = " INSERT INTO $custom_field_table SET

						custom_field_name ='{$cf_k}',
						$cfvq
						custom_field_value ='{$custom_field_to_save['custom_field_value']}',
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

                            $this->q($add);

                        }
                    }
                    $this->app->cache->delete('custom_fields/global');

                }
            }
        }


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
     * @function $this->query
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
     *  $q = $this->query($sql, $cache_id=crc32($sql),$cache_group= 'content/global');
     *
     * </code>
     *
     *
     *
     */
    public function query($q, $cache_id = false, $cache_group = 'global', $only_query = false, $connection_settigns = false)
    {
        if (trim($q) == '') {
            return false;
        }


        $error['error'] = array();
        $results = false;

        if ($cache_id != false and $cache_group != false) {
            // $results =false;

            $cache_id = $cache_id . crc32($q);
            $results = $this->app->cache->get($cache_id, $cache_group);

            if ($results != false) {
                if ($results == '---empty---' or (is_array($results) and empty($results))) {
                    return false;
                } else {
                    return $results;
                }
            }
        }


        if (!defined("MW_DB_ADAPTER_DIR")) {
            $adapter_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR;
            define("MW_DB_ADAPTER_DIR", $adapter_dir);
        }


        // }
        $this->query_log($q);
        if ($connection_settigns != false and is_array($connection_settigns) and !empty($connection_settigns)) {
            $db = $connection_settigns;

        } else {
            $db =$this->app->config('db');
        }


        $temp_db = mw_var('temp_db');
        if ((!isset($db) or $db == false or $db == NULL) and $temp_db != false) {
            $db = $temp_db;
        }


        if (!isset($db) or $db == false or $db == NULL) {
            $db = array();
            if (defined("DB_HOST")) {
                $db['host'] = DB_HOST;
            }
            if (defined("DB_USER")) {
                $db['user'] = DB_USER;
            }
            if (defined("DB_PASS")) {
                $db['pass'] = DB_PASS;
            }
            if (defined("DB_NAME")) {
                $db['dbname'] = DB_NAME;
            }
        }


        if (!isset($db) or $db == false or $db == NULL or empty($db)) {


            return false;

        }

        require (MW_DB_ADAPTER_DIR . 'mysql.php');


        if ($only_query != false) {
            return true;
        }

        if ($only_query == false and empty($q) or $q == false and $cache_group != false) {
            if ($cache_id != false) {

                $this->app->cache->save('---empty---', $cache_id, $cache_group);
            }
            return false;
        }
        if ($only_query == false) {
             if ($cache_id != false and $cache_group != false) {
                if (is_array($q) and !empty($q)) {

                    $this->app->cache->save($q, $cache_id, $cache_group);
                } else {
                    $this->app->cache->save('---empty---', $cache_id, $cache_group);
                }
            }
        }
        // }
        if ($cache_id != false) {
            $this->app->cache->save($q, $cache_id, $cache_group);
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

                $results[] = $this->stripslashes_array($result);
            }
        }

        $result = $results;

        if ($cache_id != false) {
            if (!empty($result)) {
                //$this->app->cache->save($result, $cache_id, $cache_group);
            } else {
                $this->app->cache->save('---empty---', $cache_id, $cache_group);
            }
        }
        return $result;
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
     * @uses $this->query
     *
     *
     * @example
     *  <code>
     *  //make plain query to the db.
     *    $table = MW_TABLE_PREFIX.'content';
     *  $sql = "update $table set title='new' WHERE id=1 ";
     *  $q = $this->q($sql);
     * </code>
     *
     */
    public function q($q, $connection_settigns = false)
    {


        if ($connection_settigns == false) {
            $db = array();
            if (defined("DB_HOST")) {
                $db['host'] = DB_HOST;
            }
            if (defined("DB_USER")) {
                $db['user'] = DB_USER;
            }
            if (defined("DB_PASS")) {
                $db['pass'] = DB_PASS;
            }
            if (defined("DB_NAME")) {
                $db['dbname'] = DB_NAME;
            }
        } else {
            $db = $connection_settigns;
        }
        $q = $this->query($q, $cache_id = false, $cache_group = false, $only_query = true, $db);

        return $q;
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
     * $id = $this->last_id($table_name);
     * </pre>
     *
     */
    public function last_id($table)
    {

        // for sqlite
        //$q = "SELECT ROWID AS the_id FROM $table ORDER BY ROWID DESC LIMIT 1";


        $q = "SELECT id AS the_id FROM $table ORDER BY id DESC LIMIT 1";


        $q = $this->query($q);

        $result = $q[0];

        return intval($result['the_id']);
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
     * $this->query_log("select * from my_table");
     *
     * //get the query log
     * $queries = $this->query_log(true);
     * var_dump($queries );
     * </code>
     * @package Database
     * @subpackage Advanced
     */
    public function query_log($q)
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
     * $criteria = $this->map_array_to_table($table, $array);
     * var_dump($criteria);
     * </code>
     */
    public function map_array_to_table($table, $array)
    {

        global $mw_db_arr_maps;

        $arr_key = crc32($table) + crc32(serialize($array));
        if (isset($mw_db_arr_maps[$arr_key])) {
            return $mw_db_arr_maps[$arr_key];
        }

        if (empty($array)) {

            return false;
        }
        // $table = $this->real_table_name($table);

        if (isset($mw_db_arr_maps[$table])) {
            $fields = $mw_db_arr_maps[$table];
        } else {
            $fields = $this->get_fields($table);
            $mw_db_arr_maps[$table] = $fields;
        }
        if (is_array($fields)) {
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
            $mw_db_arr_maps[$arr_key] = $array_to_return;
        }
        return $array_to_return;
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
    public function get_long($table = false, $criteria = false, $limit = false, $offset = false, $orderby = false, $cache_group = false, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false)
    {

        if ($table == false) {

            return false;
        }


        $to_search = false;
        //  $table = db_g($table);
        $table = $this->real_table_name($table);

        $aTable_assoc = $table_assoc_name = $this->real_table_name($table);
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
                    $cfg_default_limit = $this->app->option->get('items_per_page ', 'website');
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
                $table_assoc_name1 = $this->assoc_table_name($table_assoc_name);
                $q = "SELECT  rel_id from " . $table_custom_fields . " where";
                $q .= " rel='$table_assoc_name1' and ";
                $q .= " (custom_field_name = '$k' or custom_field_name_plain='$k' ) and  ";
                $q .= $this->escape_string($cfvq);
                $q .= $ids_q;
                $q .= $only_custom_fieldd_ids_q;
                $q2 = $q;

                $q = $this->query($q, md5($q), 'custom_fields/global');
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

                    $is_in_category_items = $this->get($str1_items);

                    if (!empty($is_in_category_items)) {

                        foreach ($is_in_category_items as $is_in_category_items_tt) {

                            $includeIds[] = $is_in_category_items_tt["rel_id"];

                        }
                    }

                }
            }
            // $is_in_category = $this->get('limit=1&data_type=category_item&what=category_items&rel=' . $table_assoc_name . '&rel_id=' . $id_to_return . '&parent_id=' . $is_ex['id']);
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
                $groupby = $this->escape_string($groupby);
            }
        }

        if (isset($criteria['group'])) {
            $groupby = $criteria['group'];
            if (is_string($groupby)) {
                $groupby = $this->escape_string($groupby);
            }
        }


        if ($count_only == false) {
            if (isset($criteria['order_by'])) {
                $orderby = $criteria['order_by'];
                if (is_string($orderby)) {
                    $orderby = $this->escape_string($orderby);
                }
            }

            if (isset($criteria['orderby'])) {
                $orderby = $criteria['orderby'];
                if (is_string($orderby)) {
                    $orderby = $this->escape_string($orderby);
                }
            }

        }


        $is_in_table = false;
        if (isset($criteria['in_table'])) {

            $is_in_table = $this->escape_string($criteria['in_table']);

        }
        if (isset($criteria['keyword'])) {
            $criteria['search_by_keyword'] = $criteria['keyword'];
        }
        if (isset($criteria['data-keyword'])) {
            $criteria['search_by_keyword'] = $criteria['data-keyword'];
        }

        if (isset($criteria['search_by_keyword'])) {
            $to_search = $this->escape_string($criteria['search_by_keyword']);
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

            //  $cache_content = $this->app->cache->get($original_cache_id, $original_cache_group);
            $cache_content = false;
            if (($cache_content) != false) {

                if ($cache_content == '---empty---') {

                    return false;
                }

                if ($count_only == true) {

                    $ret = intval($cache_content[0]['qty']);

                    return $ret;
                } else {
                    //  $cache_content = $this->app->url->replace_site_url_back($cache_content);
                    // $cache_content = $this->stripslashes_array($cache_content);

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
                $groupby = $this->escape_string($groupby);
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
        $criteria = $this->map_array_to_table($table, $criteria);
        $criteria = $this->addslashes_array($criteria);
        if ($only_those_fields == false) {

            $q = "SELECT * FROM $table ";
        } else {

            if (is_array($criteria) and is_array($only_those_fields)) {

                if (!empty($only_those_fields)) {

                    $ex_fields = $this->get_fields($table);
                    $flds1 = array();
                    foreach ($ex_fields as $ex_field) {
                        foreach ($only_those_fields as $ex_f_d) {
                            if (trim(strtolower($ex_field)) == trim(strtolower($ex_f_d))) {
                                $flds1[] = $ex_f_d;
                            }
                        }
                    }

                    $flds = implode(',', $flds1);
                    $flds = $this->escape_string($flds);

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
            $includeIds_idds = false;
            $includeIds_i = implode(',', $includeIds);
            $includeIds_idds .= "   AND id IN ($includeIds_i)   ";
        } else {
            $includeIds_idds = false;
        }
        // $to_search = false;

        $where_search = '';
        if ($to_search != false) {
            $to_search = $this->escape_string(strip_tags($to_search));
            $to_search = str_replace('[', ' ', $to_search);
            $to_search = str_replace(']', ' ', $to_search);
            $to_search = str_replace('*', ' ', $to_search);
            $to_search = str_replace(';', ' ', $to_search);

            $to_search = str_replace('{', ' ', $to_search);
            $to_search = str_replace('}', ' ', $to_search);

            $to_search = str_replace('\077', ' ', $to_search);
            $to_search = str_replace('<?', ' ', $to_search);


        }

        if ($to_search != false and $to_search != '') {
            $fieals = $this->get_fields($table);


            $where_post = ' OR ';

            $where_q = '';


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
                    $to_search = $this->escape_string($to_search);
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
                $table_assoc_name1 = $this->assoc_table_name($table_assoc_name);

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
        }

        if (!empty($criteria)) {

            if (!$where) {

                $where = " WHERE ";
            }
            foreach ($criteria as $k => $v) {
                $compare_sign = '=';
                $is_val_str = true;
                $is_val_int = false;


                if (is_string($k) != false) {
                    $k = $this->escape_string(strip_tags($k));
                    $k = str_replace('[', ' ', $k);
                    $k = str_replace(']', ' ', $k);
                    $k = str_replace('*', ' ', $k);
                    $k = str_replace(';', ' ', $k);
                    $k = str_replace('\077', ' ', $k);
                    $k = str_replace('<?', ' ', $k);
                }

                if (is_string($v) != false) {
                    $v = $this->escape_string(strip_tags($v));
                    $v = str_replace('{', ' ', $v);
                    $v = str_replace('}', ' ', $v);
                    $v = str_replace('*', ' ', $v);
                    $v = str_replace(';', ' ', $v);
                    $v = str_replace('\077', ' ', $v);
                    $v = str_replace('<?', ' ', $v);
                }


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
            $is_in_table = $this->escape_string($is_in_table);
            if (stristr($is_in_table, 'table_') == false and stristr($is_in_table, MW_TABLE_PREFIX) == false) {
                $is_in_table = 'table_' . $is_in_table;
            }
            $v1 = $this->real_table_name($is_in_table);
            $check_if_ttid = $this->get_fields($v1);
            if (in_array('rel_id', $check_if_ttid) and in_array('rel', $check_if_ttid)) {
                $aTable_assoc1 = $this->assoc_table_name($aTable_assoc);
                if ($v1 != false) {
                    $where .= " AND id in (select rel_id from $v1 where $v1.rel='{$aTable_assoc1}' and $v1.rel_id=$table.id ) ";
                }
            }
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

        }
        if ($original_cache_group != false) {
            $result = $this->query($q, $original_cache_id, $original_cache_group);
        } else {

            $result = $this->query($q, false, false);

        }
        //

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


            return $ret;
        }


        if ($count_only == true) {
            if (is_array($result[0]) and isset($result[0]['qty'])) {
                $ret = $result[0]['qty'];

                return $ret;
            } else {
                return 0;
            }
        }

        $return = array();
//d($result);
        if (!empty($result)) {
            $result = $this->app->url->replace_site_url_back($result);
            $return = $result;

        }
        if ($cache_group != false) {

            if (is_array($return)) {

                //$this->app->cache->save($return, $original_cache_id, $original_cache_group);
            } else {

                //  $this->app->cache->save('---empty---', $original_cache_id, $original_cache_group);
            }
        }


        if (empty($return)) {
            return $result;

        }

        //
        return $return;
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
     *  $this->delete_by_id('content', $id=5);
     * </code>
     *
     * @package Database
     */
    public function delete_by_id($table, $id = 0, $field_name = 'id')
    {
        $table = $this->real_table_name($table);
        $table_real = $this->real_table_name($table);
        $id = intval($id);

        if ($id == 0) {

            return false;
        }

        $q = "DELETE FROM $table_real WHERE {$field_name}='$id' ";

        $cg = $this->assoc_table_name($table);

        $this->app->cache->delete($cg);
        $q = $this->q($q);

        $table1 = MW_TABLE_PREFIX . 'categories';
        $table_items = MW_TABLE_PREFIX . 'categories_items';

        $q = "DELETE FROM $table1 WHERE rel_id='$id'  AND  rel='$table'  ";

        $q = $this->q($q);
        //  $this->app->cache->delete('categories');

        $q = "DELETE FROM $table_items WHERE rel_id='$id'  AND  rel='$table'  ";
        $q = $this->q($q);


        if (defined("MW_DB_TABLE_NOTIFICATIONS")) {
            $table_items = MW_DB_TABLE_NOTIFICATIONS;
            $q = "DELETE FROM $table_items WHERE rel_id='$id'  AND  rel='$table'  ";

            $q = $this->q($q);
        }

        $c_id = $id;
        if (defined("MW_DB_TABLE_MEDIA")) {
            $table1 = MW_DB_TABLE_MEDIA;
            $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='$table'  ";
            $q = $this->query($q);
        }

        if (defined("MW_DB_TABLE_TAXONOMY")) {
            $table1 = MW_DB_TABLE_TAXONOMY;
            $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='$table'  ";
            $q = $this->query($q);
        }


        if (defined("MW_DB_TABLE_TAXONOMY_ITEMS")) {
            $table1 = MW_DB_TABLE_TAXONOMY_ITEMS;
            $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='$table'  ";
            $q = $this->query($q);
        }


        if (defined("MW_DB_TABLE_CUSTOM_FIELDS")) {
            $table1 = MW_DB_TABLE_CUSTOM_FIELDS;
            $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='$table'  ";
            $q = $this->query($q);
        }
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

    public function get_fields($table, $exclude_fields = false)
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

        $cache_content = $this->app->cache->get($function_cache_id, 'db');

        if (($cache_content) != false) {
            $ex_fields_static[$table] = $cache_content;
            return $cache_content;
        }

        $table = $this->real_table_name($table);
        $table = $this->escape_string($table);

        if (DB_IS_SQLITE != false) {
            $sql = "PRAGMA table_info('{$table}');";
        } else {
            $sql = "show columns from $table";
        }


        $query = $this->query($sql);

        $fields = $query;

        $exisiting_fields = array();
        if ($fields == false or $fields == NULL) {
            $ex_fields_static[$table] = false;
            return false;
        }

        if (!is_array($fields)) {
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
        $this->app->cache->save($fields, $function_cache_id, $cache_group = 'db');
        // $fields = (array_change_key_case ( $fields, CASE_LOWER ));
        return $fields;
    }

    public function table_exist($table)
    {
        // $sql_check = "SELECT * FROM sysobjects WHERE name='$table' ";
        //$sql_check = "DESC {$table};";
        $sql_check = "show tables like '$table'";


        $q = $this->query($sql_check);

        if (!is_array($q)) {
            return false;
        }
        if (isset($q['error'])) {
            return false;
        } else {
            return $q;
        }
        // var_dump($q);
    }

    public function real_table_name($assoc_name)
    {
        global $_mw_real_table_names;

        if (isset($_mw_real_table_names[$assoc_name])) {

            return $_mw_real_table_names[$assoc_name];
        }


        $assoc_name_new = str_ireplace('table_', MW_TABLE_PREFIX, $assoc_name);
        if (defined('MW_TABLE_PREFIX') and MW_TABLE_PREFIX != '' and stristr($assoc_name_new, MW_TABLE_PREFIX) == false) {
            $assoc_name_new = MW_TABLE_PREFIX . $assoc_name_new;
        }
        $_mw_real_table_names[$assoc_name] = $assoc_name_new;
        return $assoc_name_new;
    }


    public function assoc_table_name($assoc_name)
    {

        global $_mw_assoc_table_names;

        if (isset($_mw_assoc_table_names[$assoc_name])) {

            return $_mw_assoc_table_names[$assoc_name];
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
        $_mw_assoc_table_names[$assoc_name_o] = $assoc_name;
        return $assoc_name;
    }


    public function addslashes_array($arr)
    {
        if (!empty($arr)) {
            $ret = array();

            foreach ($arr as $k => $v) {
                if (is_array($v)) {
                    $v = $this->addslashes_array($v);
                } else {
                    if ($k == 'id') {
                        $v = intval($v);
                    } else {
                        $v = addslashes($v);
                        $v = htmlspecialchars($v);
                    }

                }

                $ret[$k] = ($v);
            }

            return $ret;
        }
    }


    public function stripslashes_array($arr)
    {
        if (!empty($arr)) {
            $ret = array();

            foreach ($arr as $k => $v) {
                if (is_array($v)) {
                    $v = $this->stripslashes_array($v);
                } else {
                    $v = htmlspecialchars_decode($v);

                    $v = stripslashes($v);
                }

                $ret[$k] = $v;
            }

            return $ret;
        }
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
     *  $results = $this->escape_string($_POST['email']);
     * </code>
     *
     *
     *
     * @package Database
     * @subpackage Advanced
     */

    public function escape_string($value)
    {
        global $mw_escaped_strings;
        if (isset($mw_escaped_strings[$value])) {
            return $mw_escaped_strings[$value];
        }

        $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
        $new = str_replace($search, $replace, $value);
        $mw_escaped_strings[$value] = $new;
        return $new;
    }


    public function get_table_name($assoc_name)
    {

        $assoc_name = str_ireplace('table_', MW_TABLE_PREFIX, $assoc_name);
        return $assoc_name;
    }
}
