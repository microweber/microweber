<?php


namespace Microweber;


if (!defined('MW_USER_IP')) {
    if (isset($_SERVER["REMOTE_ADDR"])) {
        define("MW_USER_IP", $_SERVER["REMOTE_ADDR"]);
    } else {
        define("MW_USER_IP", '127.0.0.1');

    }
}


$ex_fields_static = array();
$_mw_real_table_names = array();
$_mw_assoc_table_names = array();
$mw_escaped_strings = array();


class Db
{


    public $app;
    public $connection_settings = array();
    public $db_link; //holds the connected db object
    public $table_prefix = false;
    /**
     * An instance of the db adapter to use
     *
     * @var $adapter
     */
    public $adapter;
    /**
     * Add new table index if not exists
     *
     * @example
     * <pre>
     * \mw('db')->add_table_index('title', $table_name, array('title'));
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
    var $add_table_index_cache = array();
    public $filter = array();
    private $mw_escaped_strings = array();
    private $table_fields = array();
    private $results_map = array();
    private $build_tables = array();

    function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = Application::getInstance();
            }

        }
        if (!is_object($this->adapter)) {
            if (!isset($this->app->adapters->container['database'])) {
                $app = $this->app;
                $this->app->adapters->container['database'] = function ($c) use (&$app) {
                    return new Adapters\Database\Mysql($app);
                };
            }
            $this->adapter = $this->app->adapters->container['database'];
        }

        event_trigger('db_init');


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
        $this->results_map = array();
        $q = "DELETE FROM $table_real WHERE {$field_name}={$id} ";

        $cache_group = $this->assoc_table_name($table);

        $this->app->cache->delete($cache_group . '/' . $id);
        $this->app->cache->delete($cache_group);

        $this->q($q);

        $table1 = $this->table_prefix . 'categories';
        $table_items = $this->table_prefix . 'categories_items';

        $q = "DELETE FROM $table1 WHERE rel_id='$id'  AND  rel='$table'  ";

        $this->q($q);

        $q = "DELETE FROM $table_items WHERE rel_id='$id'  AND  rel='$table'  ";
        $this->q($q);


        if (defined("MW_DB_TABLE_NOTIFICATIONS")) {
            $table_items = MW_DB_TABLE_NOTIFICATIONS;
            $q = "DELETE FROM $table_items WHERE rel_id='$id'  AND  rel='$table'  ";
            $this->q($q);
        }

        $c_id = $id;
        if (defined("MW_DB_TABLE_MEDIA")) {
            $table1 = MW_DB_TABLE_MEDIA;
            $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='$table'  ";
            $this->query($q);
        }

        if (defined("MW_DB_TABLE_TAXONOMY")) {
            $table1 = MW_DB_TABLE_TAXONOMY;
            $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='$table'  ";
            $this->query($q);
        }

        if (defined("MW_DB_TABLE_TAXONOMY_ITEMS")) {
            $table1 = MW_DB_TABLE_TAXONOMY_ITEMS;
            $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='$table'  ";
            $this->query($q);
        }

        if (defined("MW_DB_TABLE_CUSTOM_FIELDS")) {
            $table1 = MW_DB_TABLE_CUSTOM_FIELDS;
            $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='$table'  ";
            $this->query($q);
        }
        return $c_id;
    }

    public function filter($name, $callback)
    {
        $this->filter[$name] = $callback;
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
     * $table_name = $this->table_prefix . 'my_new_table'
     *
     * $fields_to_add = array();
     * $fields_to_add['updated_on']= 'dateTime';
     * $fields_to_add['created_by']= 'integer';
     * $fields_to_add['content_type']= 'longText';
     * $fields_to_add['url']= 'longlongText';
     * $fields_to_add['content_filename']= 'longText';
     * $fields_to_add['title']= 'longlongText';
     * $fields_to_add['is_active']= "string";
     * $fields_to_add['is_deleted']= "string";
     *   mw('db')->build_table($table_name, $fields_to_add);
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
    public function build_table($table_name, $fields_to_add, $column_for_not_drop = array())
    {
        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        if (isset($this->build_tables[$function_cache_id])) {
            return true;
        } else {
            $this->build_tables[$function_cache_id] = true;
        }


        $prefix = $this->app->config('table_prefix');
        $function_cache_id = __FUNCTION__ . $table_name . crc32($function_cache_id . $prefix);


        $table_name = $this->real_table_name($table_name);

        $cache_group = 'db/' . $table_name;
        $cache_content = $this->app->cache->get($function_cache_id, $cache_group);

        if (($cache_content) != false) {

            return $cache_content;
        }

        $query = $this->query("show tables like '$table_name'");

        if (!is_array($query)) {
            $sql = "CREATE TABLE " . $table_name . " (
			id int(11) NOT NULL auto_increment,
			PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;


            ";

            //
            // http://stackoverflow.com/questions/10377334/the-storage-engine-for-the-table-doesnt-support-repair-innodb-or-myisam
            // http://dba.stackexchange.com/questions/17431/which-is-faster-innodb-or-myisam
            // http://stackoverflow.com/questions/20148/myisam-versus-innodb
            //
            // Some comparison
            // + MyISAM supports full text search
            //  + MyISAM supports full text search
            // - MyISAM doesn't  not support transactions
            // + Frequent reading, almost no writing
            // + Full text search in MySQL <= 5.5
            //
            // + InnoDB supports transactions
            // - InnoDB doesn't support automated table repair
            //
            $this->q($sql);

        }

        if (empty($column_for_not_drop))
            $column_for_not_drop = array('id');

        $sql = "show columns from $table_name";

        $columns = $this->query($sql);

        $exisiting_fields = array();
        $no_exisiting_fields = array();
        if (is_array($columns)) {
            foreach ($columns as $fivesdraft) {
                if (is_array($fivesdraft)) {
                    $fivesdraft = array_change_key_case($fivesdraft, CASE_LOWER);
                    $exisiting_fields[strtolower($fivesdraft['field'])] = true;
                }
            }
        }

        for ($i = 0; $i < count($columns); $i++) {
            $column_to_move = true;
            for ($j = 0; $j < count($fields_to_add); $j++) {


                if (isset($fields_to_add[$j]) and is_array($fields_to_add[$j]) and is_array($columns) and is_array($columns) and in_array($columns[$i]['Field'], $fields_to_add[$j])) {
                    $column_to_move = false;
                }
            }
            $sql = false;
            if ($column_to_move) {
                if (!empty($column_for_not_drop)) {
                    if (isset($columns[$i]) and is_array($columns[$i]) and !in_array($columns[$i]['Field'], $column_for_not_drop)) {
                        //   $sql = "ALTER TABLE $table_name DROP COLUMN {$columns[$i]['Field']} ";
                    }
                }

                if ($sql) {
                    $this->q($sql);

                }
            }
        }

        foreach ($fields_to_add as $key => $the_field) {
            if (is_string($key)) {
                $fld[0] = $key;
                $fld[1] = $the_field;
                $the_field = $fld;
            }
            $the_field[0] = strtolower($the_field[0]);
            if (isset($the_field[1])) {
                $field_type = strtolower(trim($the_field[1]));


                switch ($field_type) {
                    case 'text':
                    case 'content':
                        $the_field[1] = 'longlongText';
                        break;
                    case 'title':
                        $the_field[1] = 'longText';
                        break;
                    case 'char':
                    case 'character':
                        $the_field[1] = 'char(1) default NULL';
                        break;
                    case 'string':
                    case 'shorttext':
                    case 'smalltext':
                        $the_field[1] = 'string(255) default NULL';
                        break;
                    case 'int':
                    case 'integer':
                        $the_field[1] = 'integer';
                        break;
                    case 'datetime':
                        $the_field[1] = 'dateTime';
                        break;
                    case 'date':
                        $the_field[1] = 'date default NULL';
                        break;
                    case 'time':
                        $the_field[1] = 'time default NULL';
                        break;
                }
            } else {

            }

            $sql = false;
            if (!isset($exisiting_fields[$the_field[0]])) {
                $sql = "alter table $table_name add column " . $the_field[0] . " " . $the_field[1] . "";

                $this->q($sql);
            } else {
//                     $sql = "alter table $table_name modify {$the_field[0]} {$the_field[1]} ";
//                    $this->q($sql);

            }
        }

        $this->app->cache->delete('db' . DIRECTORY_SEPARATOR . 'fields');

        $this->app->cache->save('--true--', $function_cache_id, $cache_group);
        return true;
    }

    public function decode_entities($text)
    {
        $text = html_entity_decode($text, ENT_QUOTES, "ISO-8859-1"); #NOTE: UTF-8 does not work!
        $text = preg_replace('/&#(\d+);/me', "chr(\\1)", $text); #decimal notation
        $text = preg_replace('/&#x([a-f0-9]+);/mei', "chr(0x\\1)", $text); #hex notation
        return $text;
    }

    public function stripslashes_array($arr)
    {
        if (!empty($arr)) {
            $ret = array();
            foreach ($arr as $k => $v) {
                if (is_array($v)) {
                    $v = $this->stripslashes_array($v);
                } else {
                    $v = stripslashes($v);
                }
                $ret[$k] = $v;
            }
            return $ret;
        }
    }

    public function get_table_name($assoc_name)
    {
        $assoc_name = str_ireplace('table_', $this->table_prefix, $assoc_name);
        return $assoc_name;
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
     * @see $this->save()
     * @example
     * <code>
     * //example updates the is_active flag of all content
     * mass_save("table=content&is_active=n", 'is_active=y');
     * </code>
     */
    public function mass_save($get_params, $save_params = false)
    {

        $get_params1 = parse_params($get_params);
        $get_params1['return_criteria'] = 1;
        $test = $this->get($get_params1);
        $upd = array();
        if (isset($test['table'])) {
            $save_params = parse_params($save_params);
            if (!is_array($save_params)) {
                return 'error $save_params must be array';
            }

            $get = $this->get($get_params);

            if (!is_array($get)) {
                //$upd[] = $this->save($test['table'], $save_params);
            } else {
                foreach ($get as $value) {
                    $sp = $save_params;

                    if (isset($value['id'])) {
                        $sp['id'] = $value['id'];
                        $upd[] = $this->save($test['table'], $sp);
                    }

                }
            }
        } else {
            mw_error('could not find table');
        }
        if (!empty($upd)) {
            $cache_group = $this->assoc_table_name($test['table']);
            $this->app->cache->delete($cache_group);
            return $upd;
        } else {
            return false;
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
            parse_str($params, $params2);
            $params = $params2;
            extract($params);
        }
        if (!isset($params['table'])) {
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
                $table = $this->guess_table_name($v);

            }

            if (!isset($table) and $k == 'what' and !isset($params['rel'])) {
                //  $table = $this->guess_table_name($v);
            }
            if ($k == 'for' and !isset($params['rel'])) {
                $v = $this->assoc_table_name($v);
                $k = 'rel';
            }
            if ($k == 'rel') {
                $v = $this->assoc_table_name($v);
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


            if (isset($this->filter[$k])) {
                if (isset($this->filter[$k]) and is_callable($this->filter[$k])) {
                    $new_criteria = call_user_func($this->filter[$k], $criteria);
                }
                if ($criteria != $new_criteria) {
                    $criteria = $new_criteria;
                }

            }

        }
        if (!isset($table) and isset($params['what'])) {
            //   $table = $this->real_table_name($this->guess_table_name($params['what']));

        }

        if (!isset($table)) {
            print "error no table found in params";
            return false;
        }

        if (isset($params['return_criteria'])) {
            return $criteria;
        }

        if ($cache_group == false and $debug == false) {
            $cache_group = $this->guess_cache_group($table);
            if (!isset($criteria['id'])) {
                $cache_group = $cache_group . '/global';
            } else {
                $cache_group = $cache_group . '/' . $criteria['id'];
            }

        } else {
            $cache_group = $this->guess_cache_group($cache_group);
        }

        $mode = 1;

        if (isset($no_cache) and $no_cache == true) {
            $mode = 2;
        }
        $mode = 3;
        //$mode = 2;
        switch ($mode) {
            case 1 :
                //static  $this->results_map_hits = array();
                $criteria_id = (int)crc32($table . serialize($criteria));
                if (isset($this->results_map[$criteria_id])) {
                    $get_db_items = $this->results_map[$criteria_id];
                    // $this->results_map_hits[$criteria_id]++;
                } else {
                    $get_db_items = $this->get_long($table, $criteria, $limit = false, $offset = false, $orderby, $cache_group, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false);
                    // $this->results_map_hits[$criteria_id] = 1;
                    $this->results_map[$criteria_id] = $get_db_items;
                }
                break;
            case 2 :
            default :
                $get_db_items = $this->get_long($table, $criteria, $limit = false, $offset = false, $orderby, $cache_group, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false);
                break;


            case 3 :
            default :

                if (isset($criteria['no_cache'])) {
                    $this->app->orm->configure('caching', false);

                }

                $get_db_items = $this->app->orm->get($table, $criteria);

                if (isset($criteria['debug'])) {
                    if (isset($this->app->config['debug_mode'])) {
                        $debug = $this->app->config['debug_mode'];
                        if (($debug) != false) {
                            print_r(mw()->orm->getLastQuery());
                        } else {
                            unset($criteria['debug']);
                        }
                    }
                }

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
     * $table = $this->guess_table_name('content');
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

        if ($guess_cache_group != false) {
            $for = str_replace('table_']= '', $for);
            if (defined("MW_TABLE_PREFIX")) {
                $for = str_replace(MW_TABLE_PREFIX, '', $for);
            }
            $for = str_replace($this->table_prefix, '', $for);
        }

        return $for;
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
     * $cache_gr = $this->guess_cache_group('content');
     * </code>
     *
     * @package Database
     * @subpackage Advanced
     */
    function guess_cache_group($for = false)
    {
        return $this->guess_table_name($for, true);
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
        $table = $this->real_table_name($table);
        $table_alias = $this->assoc_table_name($table);
        $aTable_assoc = $table_assoc_name = $this->real_table_name($table);
        $includeIds = array();

        if (!empty($criteria)) {
            if (isset($criteria['debug'])) {
                if (isset($this->app->config['debug_mode'])) {
                    $debug = $this->app->config['debug_mode'];
                    if (($criteria['debug'])) {
                        $criteria['debug'] = false;
                    } else {
                        unset($criteria['debug']);
                    }
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
                if (defined("MW_IS_INSTALLED") and MW_IS_INSTALLED == true) {
                    $cfg_default_limit = $this->app->option->get('items_per_page ']= 'website');
                }

            }
            static $event_trigger_exists;
            if ($event_trigger_exists == false) {
                if (function_exists('event_trigger')) {
                    $event_trigger_exists = true;
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
            if ($curent_page == false) {
                $curent_page = isset($criteria['current_page']) ? $criteria['current_page'] : false;
            }
            if ($curent_page == false) {
                $curent_page = isset($criteria['page']) ? $criteria['page'] : false;
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
                    $addcf = str_ireplace('custom_field_']= '', $fk);
                    $criteria['custom_fields_criteria'][$addcf] = $fv;
                }
            }
            foreach ($criteria as $fk => $fv) {
                if (strstr($fk, 'data_') == true) {
                    $addcf = str_ireplace('data_']= '', $fk);
                    $criteria['data_fields_criteria'][$addcf] = $fv;
                }
            }
        }


        if (!empty($criteria['custom_fields_criteria'])) {

            $table_custom_fields = $this->table_prefix . 'custom_fields';
            $only_custom_fields_ids = array();
            $use_fetch_db_data = true;

            $ids_q = "";
            if (!empty($ids)) {
                $ids_i = implode(',', $ids);
                $ids_q = " and rel_id in ($ids_i) ";
            }

            $only_custom_fields_ids = array();
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

                $only_custom_fields_ids_q = false;
                if (!empty($only_custom_fields_ids)) {
                    $only_custom_fields_ids_i = implode(',', $only_custom_fields_ids);
                    $only_custom_fields_ids_q = " and rel_id in ($only_custom_fields_ids_i) ";
                }

                if ($is_not_null == true) {
                    $cfvq = " custom_field_value IS NOT NULL  ";
                } else {
                    $v = $this->escape_string($v);
                    $value_q = $this->_value_query_sign($v);

                    if (isset($value_q['value']) and $value_q['value'] != '' and isset($value_q['compare_sign'])) {
                        $cfvq = " (custom_field_value" . $value_q['compare_sign'] . "'" . $value_q['value'] . "'  or custom_field_values_plain" . $value_q['compare_sign'] . "'" . $value_q['value'] . "'  )";
                    } else {
                        $cfvq = " (custom_field_value='$v'  or custom_field_values_plain='$v'  )";
                    }
                    $cfvq .= " or (custom_field_value LIKE '$v'  or custom_field_values_plain LIKE '$v'  )";
                }
                $table_assoc_name1 = $this->assoc_table_name($table_assoc_name);
                $q = "SELECT  rel_id from " . $table_custom_fields . " where";
                $q .= " rel='$table_assoc_name1' and ";
                $q .= " (custom_field_name = '$k' or custom_field_name_plain='$k' ) and  ";
                $q .= trim($cfvq);
                $q .= $ids_q;
                $q .= $only_custom_fields_ids_q;
                $q = $this->query($q);
                if (!empty($q)) {
                    $ids_old = $ids;
                    $ids = array();
                    foreach ($q as $itm) {
                        $only_custom_fields_ids[] = $itm['rel_id'];
                        $includeIds[] = $itm['rel_id'];
                    }
                }
            }
        }

        $original_cache_group = $cache_group;

        if (!empty($criteria['only_those_fields'])) {
            $only_those_fields = $criteria['only_those_fields'];
        }

        if (!empty($criteria['include_categories'])) {
            $include_categories = true;
        } else {
            $include_categories = false;
        }
        if (!isset($criteria['exclude_ids']) and isset($criteria['exclude'])) {
            $criteria['exclude_ids'] = $criteria['exclude'];
        }

        if (!isset($criteria['ids']) and isset($criteria['include'])) {
            $criteria['ids'] = $criteria['include'];
        }

        if (!empty($criteria['exclude_ids'])) {
            $exclude_ids = $criteria['exclude_ids'];
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
            $search_n_cats = $criteria['category'];
            if (is_string($search_n_cats)) {
                $search_n_cats = explode(',', $search_n_cats);
            }
            if (is_string($search_n_cats) or is_int($search_n_cats)) {
                $search_n_cats = array($search_n_cats);
            }

            $is_in_category_items = false;
            if (is_array($search_n_cats) and !empty($search_n_cats)) {
                foreach ($search_n_cats as $cat_name_or_id) {
                    $cat_name_or_id = intval($cat_name_or_id);
                    $str0 = 'fields=id&limit=10000&data_type=category&what=categories&' . 'id=' . $cat_name_or_id . '&rel=' . $table_assoc_name;
                    $str1 = 'fields=id&limit=10000&table=categories&' . 'id=' . $cat_name_or_id;
                    $cat_name_or_id1 = intval($cat_name_or_id);
                    $str1_items = 'fields=rel_id&limit=10000&what=category_items' . '&rel=' . $table_alias . '&parent_id=' . $cat_name_or_id;


                    $is_in_category_items = $this->get($str1_items);
                    if (!empty($is_in_category_items)) {
                        foreach ($is_in_category_items as $is_in_category_items_tt) {
                            $includeIds[] = $is_in_category_items_tt["rel_id"];

                        }
                    }
                }
            }

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
        $sum = false;
        if (isset($criteria['sum'])) {
            $sum = $this->escape_string($criteria['sum']);

        }
        if (isset($criteria['keyword'])) {
            $criteria['search_by_keyword'] = $criteria['keyword'];
        }
        if (isset($criteria['data-keyword'])) {
            $criteria['search_by_keyword'] = $criteria['data-keyword'];
        }

        if (isset($criteria['search_by_keyword'])) {
            $to_search = $this->escape_string($criteria['search_by_keyword']);
            $to_search = str_replace('\\']= '', $to_search);
        }

        $to_search_in_those_fields = array();
        if (isset($criteria['search_in_fields'])) {
            $to_search_in_those_fields = $criteria['search_by_keyword_in_fields'] = $criteria['search_in_fields'];
        }

        if (!isset($criteria['search_in_fields']) and isset($criteria['search_by_keyword_in_fields'])) {
            $to_search_in_those_fields = ($criteria['search_by_keyword_in_fields']);
        }
        $search_data_fields = false;

        if (isset($criteria['search_by_keyword']) and isset($criteria['search_in_content_data_fields'])) {
            $search_data_fields = true;

        }
        if (is_string($to_search_in_those_fields)) {
            $to_search_in_those_fields = explode(',', $to_search_in_those_fields);
            $to_search_in_those_fields = array_trim($to_search_in_those_fields);
        }


        $original_cache_id = false;
        if ($cache_group != false) {
            $cache_group = trim($cache_group);
            if ($force_cache_id != false) {

                $cache_id = $force_cache_id;

                $function_cache_id = $force_cache_id;
            } else {
                $function_cache_id = false;
                $args = func_get_args();
                ksort($criteria);
                $function_cache_id = crc32(serialize($criteria));
                $function_cache_id = __FUNCTION__ . $table . crc32($function_cache_id);
                $cache_id = $function_cache_id;
            }
            $original_cache_id = $cache_id;

            $cache_content = false;
            if (($cache_content) != false) {
                if ($cache_content == '---empty---') {
                    return false;
                }
                if ($count_only == true) {
                    $ret = intval($cache_content[0]['qty']);
                    return $ret;
                } else {
                    return $cache_content;
                }
            }
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
        } else if ($sum != false) {
            $q = "SELECT SUM($sum) AS $sum FROM $table ";
        }

        $precise_select = false;
        if ($event_trigger_exists != false) {
            $event_name = 'db_query_' . $table_alias;
            $event_criteria = $orig_criteria;
            $event_criteria['table'] = $table;
            $event_criteria['table_alias'] = $table_alias;

            $modified_query = event_trigger($event_name, $event_criteria);
            if ($modified_query != false and is_array($modified_query) and !empty($modified_query)) {
                foreach ($modified_query as $modified) {
                    if (is_string($modified) and trim($modified) != '') {
                        $q = $q . " " . $modified;
                        $precise_select = true;
                    }

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
            if ($precise_select) {
                $groupby = " GROUP BY  $table.$groupby  ";
            }
        } else {
            $groupby = false;
        }

        if (is_string($orderby)) {
            $order_by = " ORDER BY  $orderby  ";

            if ($precise_select) {
                $orderby = trim($orderby);
                $order_by = " ORDER BY  $table.$orderby  ";
            }

        } elseif (is_array($orderby) and !empty($orderby)) {
            if (isset($orderby[0])) {
                $orderby[0] = $this->escape_string($orderby[0]);
            }
            if (isset($orderby[1])) {
                $orderby[1] = $this->escape_string($orderby[1]);
            } else {
                $orderby[1] = '';
            }
            if ($precise_select) {
                if (strstr($orderby[0], $table) == false) {
                    $orderby[0] = $table . '.' . $orderby[0];
                }
            }
            $order_by = " ORDER BY  {$orderby[0]}  {$orderby[1]}  ";
            $order_by = str_replace(';']= ' ', $order_by);
            $order_by = str_replace(',']= ' ', $order_by);
            $order_by = str_replace('*']= ' ', $order_by);
            $order_by = str_ireplace('drop ']= ' ', $order_by);
            $order_by = str_ireplace('select ']= ' ', $order_by);
            $order_by = str_ireplace('insert ']= ' ', $order_by);

        } else {
            $order_by = false;
        }

        $where = false;

        if (is_array($ids)) {
            if (!empty($ids)) {
                $idds = false;
                foreach ($ids as $id) {
                    $id = intval($id);
                    $idds .= "   OR $table.id=$id   ";
                }
                $idds = "  and ( $table.id=0 $idds   ) ";
            } else {
                $idds = false;
            }
        }
        if (is_string($exclude_ids)) {
            $exclude_ids = explode(',', $exclude_ids);
        }
        if (!empty($exclude_ids)) {
            $first = array_shift($exclude_ids);
            $exclude_idds = false;
            foreach ($exclude_ids as $id) {
                $id = intval($id);
                $exclude_idds .= "   AND $table.id<>$id   ";
            }
            $exclude_idds = "  and ( $table.id<>$first $exclude_idds   ) ";
        } else {
            $exclude_idds = false;
        }

        if (!empty($includeIds)) {
            $includeIds_idds = false;
            $includeIds_i = implode(',', $includeIds);
            $includeIds_idds .= "   AND $table.id IN ($includeIds_i)   ";
        } else {
            $includeIds_idds = false;
        }

        $where_search = '';
        if ($to_search != false) {
            $to_search = $this->escape_string(strip_tags(html_entity_decode($to_search)));
            $to_search = str_replace('<']= '', $to_search);
            $to_search = str_replace('>']= '', $to_search);
            $to_search = str_replace('[']= '', $to_search);
            $to_search = str_replace(']']= '', $to_search);
            $to_search = str_replace('*']= '', $to_search);
            $to_search = str_replace(';']= '', $to_search);

            $to_search = str_replace('\077']= '', $to_search);
            $to_search = str_replace('<?']= '', $to_search);
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

                    if (in_array($v, $to_search_in_those_fields) or ($v != '_username' && $v != '_password')) {
                        switch ($v) {
                            case 'title' :
                            case 'description' :
                            case 'name' :
                            case 'help' :
                            case 'content' :
                            case 'content_body' :
                            case 'content_meta_title' :
                            case in_array($v, $to_search_in_those_fields) :
                                //  $where_q .= " $v REGEXP '$to_search' " . $where_post;

                                // $where_q .= "  $table.$v REGEXP '$to_search' " . $where_post;
                                //  $where_q .= "  $table.$v REGEXP '$to_search' " . $where_post;
                                $lower_func = 'strtolower';
                                if (function_exists('mb_strtolower')) {
                                    $lower_func = 'mb_strtolower';
                                }


                                $upper_func = 'strtolower';
                                $to_search_uc3 = ucfirst($to_search);
                                if (function_exists('mb_convert_case')) {
                                    $to_search_uc3 = mb_convert_case($to_search, MB_CASE_TITLE, 'UTF-8');

                                }
                                $to_search_uc = ucfirst($to_search);
                                $to_search_uc2 = strtoupper($to_search);


                                $where_q .= " (  $table.$v REGEXP '$to_search'
                             OR
                             $table.$v REGEXP '$to_search_uc3'
                             OR
                             $table.$v LIKE '%$to_search%'

                             ) " . $where_post;


                                break;
                            case 'id' :
                                $to_search1 = intval($to_search);
                                $where_q .= " $table.$v='$to_search1' " . $where_post;
                                break;
                            default :

                                break;
                        }

                    }
                }
            }

            if (isset($search_data_fields) and $search_data_fields != false) {
                if (defined('MW_DB_TABLE_CONTENT_DATA')) {
                    $table_custom_fields = MW_DB_TABLE_CONTENT_DATA;
                    $where_q1 = " $table.id in (select content_id from $table_custom_fields where
			 				field_value REGEXP '$to_search' ) OR ";
                    $where_q .= $where_q1;
                }
            }


            if (isset($to_search) and $to_search != '') {
                $table_custom_fields = $this->table_prefix . 'custom_fields';
                $table_assoc_name1 = $this->assoc_table_name($table_assoc_name);
                $where_q1 = " $table.id in (select rel_id from $table_custom_fields where
				rel='$table_assoc_name1' and
				(custom_field_value REGEXP '$to_search') or custom_field_values_plain REGEXP '$to_search' ) OR ";
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
                if ($precise_select and stristr($q, 'where')) {
                    $where = " and ";
                }

            }
            foreach ($criteria as $k => $v) {
                $compare_sign = '=';
                $is_val_str = true;
                $is_val_int = false;

                if (is_string($k) != false) {
                    $k = $this->escape_string(strip_tags($k));
                    $k = str_replace('{']= '', $k);
                    $k = str_ireplace(' and ']= ' ', $k);
                    $k = str_replace('}']= '', $k);
                    $k = str_replace("'", '', $k);
                    $k = str_replace('"']= '', $k);
                    $k = str_replace('*']= '', $k);
                    $k = str_replace(';']= '', $k);
                    $k = str_replace('\077']= '', $k);
                    $k = str_replace('<?']= '', $k);
                    if (strstr($k, $table) == false) {
                        $k = $table . '.' . $k;
                    }
                }

                if (is_string($v) != false) {
                    $v = $this->escape_string(strip_tags($v));
                    $v = str_replace('*']= '', $v);
                    $v = str_replace(';']= '', $v);
                    $v = str_replace('\077']= '', $v);
                    $v = str_replace('<?']= '', $v);
                    $v = str_replace("'", '', $v);
                    $v = str_replace('"']= '', $v);
                }


                if (stristr($v, '[lt]')) {
                    $compare_sign = '<';
                    $v = str_replace('[lt]']= '', $v);
                }
                if (stristr($v, '[lte]')) {
                    $compare_sign = '<=';
                    $v = str_replace('[lte]']= '', $v);
                }
                if (stristr($v, '[st]')) {
                    $compare_sign = '<';
                    $v = str_replace('[st]']= '', $v);
                }
                if (stristr($v, '[ste]')) {
                    $compare_sign = '<=';
                    $v = str_replace('[ste]']= '', $v);
                }
                if (stristr($v, '[gt]')) {
                    $compare_sign = '>';
                    $v = str_replace('[gt]']= '', $v);
                }
                if (stristr($v, '[gte]')) {
                    $compare_sign = '>=';
                    $v = str_replace('[gte]']= '', $v);
                }
                if (stristr($v, '[mt]')) {
                    $compare_sign = '>';
                    $v = str_replace('[mt]']= '', $v);
                }
                if (stristr($v, '[mte]')) {
                    $compare_sign = '>=';
                    $v = str_replace('[mte]']= '', $v);
                }

                if (stristr($v, '[neq]')) {
                    $compare_sign = '!=';
                    $v = str_replace('[neq]']= '', $v);
                }

                if (stristr($v, '[eq]')) {
                    $compare_sign = '=';
                    $v = str_replace('[eq]']= '', $v);
                }


                if (stristr($v, '[int]')) {

                    $is_val_str = false;
                    $is_val_int = true;

                    $v = str_replace('[int]']= '', $v);
                }

                if (stristr($v, '[is]')) {

                    $compare_sign = ' IS ';

                    $v = str_replace('[is]']= '', $v);
                }

                if (stristr($v, '[like]')) {

                    $compare_sign = ' LIKE ';

                    $v = str_replace('[like]']= '', $v);
                }

                if (stristr($v, '[is_not]')) {

                    $compare_sign = ' IS NOT ';

                    $v = str_replace('[is_not]']= '', $v);
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
                    $module_name = str_replace('\\']= '/', $module_name);
                    $module_name = addslashes($module_name);
                    $where .= "$k {$compare_sign} '{$module_name}' AND ";
                } else if ($is_val_int == true and $is_val_str == false) {
                    $v = intval($v);

                    $where .= "$k {$compare_sign} $v AND ";
                } else {
                    $where .= "$k {$compare_sign} '$v' AND ";

                }
            }
            $where .= " $table.id is not null ";
        } else {
            $where = " WHERE ";
            if ($precise_select and stristr($q, 'where')) {
                $where = " and ";

            }
            $where .= " $table.id is not null ";
        }

        if ($is_in_table != false) {
            $is_in_table = $this->escape_string($is_in_table);
            if (stristr($is_in_table, 'table_') == false and stristr($is_in_table, $this->table_prefix) == false) {
                $is_in_table = 'table_' . $is_in_table;
            }
            $v1 = $this->real_table_name($is_in_table);
            $check_if_ttid = $this->get_fields($v1);
            if (in_array('rel_id', $check_if_ttid) and in_array('rel', $check_if_ttid)) {
                $aTable_assoc1 = $this->assoc_table_name($aTable_assoc);
                if ($v1 != false) {
                    // $where .= " AND id in (select rel_id from $v1 where $v1.rel='{$aTable_assoc1}' and $v1.rel_id=$table.id ) ";
                    $where .= " AND $table.id in (select rel_id from $v1 where $v1.rel='{$aTable_assoc1}' and $v1.rel_id=$table.id ) ";

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
                if (!$precise_select) {
                    $q .= " group by $table.id  ";
                }
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

        if (!empty($result)) {
            $result = $this->app->url->replace_site_url_back($result);
            $return = $result;

        }
        if (empty($return)) {
            return $result;

        }

        return $return;
    }

    private function _value_query_sign($v)
    {
        $compare_sign = '=';
        if (is_string($v) != false) {
            $v = $this->escape_string(strip_tags($v));
            $v = str_replace('{']= '', $v);
            $v = str_replace('}']= '', $v);
            $v = str_replace('*']= '', $v);
            $v = str_replace(';']= '', $v);
            $v = str_replace('\077']= '', $v);
            $v = str_replace('<?']= '', $v);
            $v = str_replace("'", '', $v);
            $v = str_replace('"']= '', $v);

            $first_char = substr($v, 0, 1);
            $first_two_chars = substr($v, 0, 2);


            if (stristr($first_two_chars, '=<')) {
                $compare_sign = '<=';
                $v = str_replace('=<']= '', $v);
            } elseif (stristr($first_two_chars, '<=')) {
                $compare_sign = '<=';
                $v = str_replace('<=']= '', $v);
            } elseif (stristr($first_two_chars, '<')) {
                $compare_sign = '<';
                $v = str_replace('<']= '', $v);
            } elseif (stristr($first_char, '<')) {
                $compare_sign = '<';
                $v = str_replace('<']= '', $v);
            } elseif (stristr($first_two_chars, '=>')) {
                $compare_sign = '>=';
                $v = str_replace('=>']= '', $v);
            } elseif (stristr($first_two_chars, '>=')) {
                $compare_sign = '>=';
                $v = str_replace('>=']= '', $v);
            } elseif (stristr($first_two_chars, '>=')) {

                $compare_sign = '>=';
                $v = str_replace('>=']= '', $v);
            } elseif (stristr($first_char, '>')) {

                $compare_sign = '>';
                $v = str_replace('>']= '', $v);

            }
        }


        if (stristr($v, '[lt]')) {
            $compare_sign = '<';
            $v = str_replace('[lt]']= '', $v);
        }
        if (stristr($v, '[lte]')) {
            $compare_sign = '<=';
            $v = str_replace('[lte]']= '', $v);
        }
        if (stristr($v, '[st]')) {
            $compare_sign = '<';
            $v = str_replace('[st]']= '', $v);
        }
        if (stristr($v, '[ste]')) {
            $compare_sign = '<=';
            $v = str_replace('[ste]']= '', $v);
        }
        if (stristr($v, '[gt]')) {
            $compare_sign = '>';
            $v = str_replace('[gt]']= '', $v);
        }
        if (stristr($v, '[gte]')) {
            $compare_sign = '>=';
            $v = str_replace('[gte]']= '', $v);
        }


        if (stristr($v, '[mt]')) {
            $compare_sign = '>';
            $v = str_replace('[mt]']= '', $v);
        }
        if (stristr($v, '[mte]')) {
            $compare_sign = '>=';
            $v = str_replace('[mte]']= '', $v);
        }

        if (stristr($v, '[neq]')) {
            $compare_sign = '!=';
            $v = str_replace('[neq]']= '', $v);
        }

        if (stristr($v, '[eq]')) {
            $compare_sign = '=';
            $v = str_replace('[eq]']= '', $v);
        }


        if (stristr($v, '[int]')) {

            $is_val_str = false;
            $is_val_int = true;

            $v = str_replace('[int]']= '', $v);
        }

        if (stristr($v, '[is]')) {

            $compare_sign = ' IS ';

            $v = str_replace('[is]']= '', $v);
        }

        if (stristr($v, '[like]')) {

            $compare_sign = ' LIKE ';

            $v = str_replace('[like]']= '', $v);
        }

        if (stristr($v, '[is_not]')) {

            $compare_sign = ' IS NOT ';

            $v = str_replace('[is_not]']= '', $v);
        }
        return array('value' => $v, 'compare_sign' => $compare_sign);
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
     *  \mw('db')->copy_row_by_id('content', $id=5);
     * </code>
     *
     * @package Database
     * @subpackage Advanced
     *
     */
    public function copy_row_by_id($table, $id = 0, $field_name = 'id')
    {

        $q = $this->get_by_id($table, $id, $field_name);
        if (isset($q[$field_name])) {
            $data = $q;
            if (isset($data[$field_name])) {
                unset($data[$field_name]);
            }

            $s = $this->save($table, $data);
            return $s;
        }

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
        $field_name = $this->escape_string($field_name);

        $q = "SELECT * FROM $table WHERE {$field_name}='$id' LIMIT 1";

        $q = $this->query($q);
        if (isset($q[0])) {
            $q = $q[0];
        }
        if (count($q) > 0) {
            return $q;
        } else {
            return false;
        }
    }

    /**
     * Escapes a string from sql injection
     *
     * @param string|array $value to escape
     * @return string|array Escaped string
     * @return mixed Es
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

        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = $this->escape_string($v);
            }
            return $value;
        } else {


            if (!is_string($value)) {
                return $value;
            }
            $str_crc = 'esc' . crc32($value);
            if (isset($this->mw_escaped_strings[$str_crc])) {
                return $this->mw_escaped_strings[$str_crc];
            }

            $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
            $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
            $new = str_replace($search, $replace, $value);
            $this->mw_escaped_strings[$str_crc] = $new;
            return $new;
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
     * $table = $this->table_prefix.'content';
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

        if (!is_array($data)) {

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
        if (!isset($data['id'])) {
            $data['id'] = 0;
        }
        if (isset($data['cf_temp'])) {
            $cf_temp = $data['cf_temp'];
        }

        $allow_html = false;
        $allow_scripts = false;
        if (isset($data['allow_html']) and (!isset($_REQUEST['allow_html']))) {
            $allow_html = $data['allow_html'];
        }
        if (isset($data['allow_scripts']) and (!isset($_REQUEST['allow_scripts']))) {
            $allow_scripts = $data['allow_scripts'];
        }


        if (isset($data['debug']) and $data['debug'] == true) {
            $dbg = 1;
            unset($data['debug']);
        } else {
            $dbg = false;
        }
        if ($dbg != false) {
            var_dump($data);
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

        if ($allow_html == false) {

            $criteria = $this->app->format->clean_html($criteria);

        } else {
            if ($allow_scripts == false) {
                $criteria = $this->clean_input($criteria);
            }

        }

        $table = $this->app->format->clean_html($table);

        $criteria = $this->app->url->replace_site_url($criteria);

        if ($data_to_save_options['use_this_field_for_id'] != false) {

            $criteria['id'] = $criteria_orig[$data_to_save_options['use_this_field_for_id']];
        }

        // $criteria = $this->map_array_to_table ( $table, $data );
        if ($dbg != false) {
            var_dump($criteria);
        }

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

            $q = "INSERT INTO  " . $table . " set ";

            foreach ($data as $k => $v) {
                if (is_array($v)) {
                    $v = implode(',', $v);
                    $v = ltrim($v, '0,');
                }
                if (is_string($k) and strtolower($k) != $data_to_save_options['use_this_field_for_id']) {
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

            $n_id = $this->app->format->clean_html($n_id);
            if ($data_to_save_options['use_this_field_for_id'] != false) {
                $q .= " " . $data_to_save_options['use_this_field_for_id'] . "={$n_id} ";
            } else {
                $q .= " id={$n_id} ";
            }


            $id_to_return = false;
        } else {

            // update
            $data = $criteria;

            $q = "UPDATE  " . $table . " set ";

            foreach ($data as $k => $v) {
                if (is_array($v)) {
                    $v = implode(',', $v);
                }

                if (is_string($k)) {
                    if ($k != 'id' and $k != 'session_id' and $k != 'edited_by') {
                        if ($v == '[null]') {
                            $q .= "$k=NULL,";
                        } else {
                            $q .= "$k='$v',";
                        }

                    }
//                    if (isset($data['session_id'])) {
//                        if ($k != 'id' and $k != 'edited_by') {
//                            $q .= "$k='$v',";
//                        }
//                    } else {
//
//                    }
                }
            }
            $user_session_q = '';
            $q = rtrim($q, ',');
            $created_by_user_q = '';
            $edited_by_user_q = '';
            $idq = '';
            if ((mw_var('FORCE_ANON_UPDATE') != false and $table == mw_var('FORCE_ANON_UPDATE')) or (defined('FORCE_ANON_UPDATE') and $table == FORCE_ANON_UPDATE)) {
                $idq = " and id={$data['id']} ";
            } elseif ((mw_var('FORCE_ANON_SAVE') != false and $table == mw_var('FORCE_ANON_SAVE')) or (defined('FORCE_ANON_SAVE') and $table == FORCE_ANON_SAVE)) {
                $idq = " and id={$data['id']} ";
            } else {
                if ($the_user_id != 0) {
                    if (isset($data['created_by'])) {
                        $created_by_user_q = " , created_by=$the_user_id ";
                    }
                    if (isset($data['edited_by'])) {
                        $edited_by_user_q = " , edited_by=$the_user_id ";
                    } else {
                        $edited_by_user_q = " , id={$data ['id']} ";
                    }
                }
                if ($edited_by_user_q == '') {
                    if (isset($_SESSION)) {
                        if (isset($data['session_id'])) {
                            if ($user_sid != false) {
                                $user_session_q = " AND session_id='{$user_sid}'  ";
                            }
                        }

                    }
                }
            }


            $q .= " $edited_by_user_q WHERE id={$data['id']} {$idq} {$user_session_q}   limit 1";
            $id_to_return = $data['id'];
        }

        if ($dbg != false) {
            var_dump($q);
        }
        $this->results_map = array();
        $this->q($q);
        if ($id_to_return == false) {
            $id_to_return = $this->last_id($table);
        }


        $original_data['table'] = $table;
        $original_data['id'] = $id_to_return;

        $this->save_extended_data($original_data);


        $cache_group = $this->assoc_table_name($table);

        $this->app->cache->delete($cache_group . '/global');
        $this->app->cache->delete($cache_group . '/' . $id_to_return);

        if ($skip_cache == false) {
            $cache_group = $this->assoc_table_name($table);
            $this->app->cache->delete($cache_group . '/global');
            $this->app->cache->delete($cache_group . '/' . $id_to_return);
            if (isset($criteria['parent_id'])) {
                $this->app->cache->delete($cache_group . '/' . intval($criteria['parent_id']));
            }
        }
        return $id_to_return;

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
     * $table_name = $this->table_prefix . 'content';
     * $id = $this->last_id($table_name);
     * </pre>
     *
     */
    public function last_id($table)
    {

        $table = $this->escape_string($table);
        $q = "SELECT id AS the_id FROM $table ORDER BY id DESC LIMIT 1";
        $q = $this->query($q);
        $result = $q[0];
        return intval($result['the_id']);
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
     * $table = $this->table_prefix.'content';
     * $data = array();
     * $data['id'] = 1;
     * $data['non_ex'] = 'i do not exist and will be removed';
     * $criteria = $this->map_array_to_table($table, $array);
     * var_dump($criteria);
     * </code>
     */
    public function map_array_to_table($table, $array)
    {


        $arr_key = crc32($table) + crc32(serialize($array));
        if (isset($this->table_fields[$arr_key])) {
            return $this->table_fields[$arr_key];
        }

        if (empty($array)) {

            return false;
        }
        // $table = $this->real_table_name($table);

        if (isset($this->table_fields[$table])) {
            $fields = $this->table_fields[$table];
        } else {
            $fields = $this->get_fields($table);
            $this->table_fields[$table] = $fields;
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
            $this->table_fields[$arr_key] = $array_to_return;
        }
        return $array_to_return;
    }

    /**
     * Gets all field names from a DB table
     *
     * @param $table string
     *            - table name
     * @param array|bool $exclude_fields array
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
        $cache_group = 'db/fields';
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
        $table = $this->escape_string($table);
        $function_cache_id = __FUNCTION__ . $table . crc32($function_cache_id);

        $cache_content = $this->app->cache->get($function_cache_id, $cache_group);

        if (($cache_content) != false) {
            $ex_fields_static[$table] = $cache_content;
            return $cache_content;
        }

        $table = $this->real_table_name($table);
        $table = $this->escape_string($table);


        $sql = " show columns from $table ";

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
        $this->app->cache->save($fields, $function_cache_id, $cache_group);
        return $fields;
    }

    function clean_input($input)
    {
        if (is_array($input)) {
            $output = array();
            foreach ($input as $var => $val) {
                $output[$var] = $this->clean_input($val);
            }
        } elseif (is_string($input)) {
            $search = array(
                '@<script[^>]*?>.*?</script>@si', // Strip out javascript

                '@<![\s\S]*?--[ \t\n\r]*>@' // Strip multi-line comments
            );
            $output = preg_replace($search, '', $input);
        } else {
            return $input;
        }
        return $output;
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
                        // $v = htmlentities($v, ENT_QUOTES, "UTF-8");

                        // $v = htmlspecialchars($v);
                    }

                }

                $ret[$k] = ($v);
            }

            return $ret;
        }
    }

    public function save_extended_data($original_data)
    {


        if (!defined("MW_FORCE_SAVE_EXTENDED")) {

            if (!defined("MW_IS_INSTALLED") or MW_IS_INSTALLED == false) {
                return false;
            }
        }
        //
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
            $categories_table = $this->table_prefix . 'categories';
        }


        if (defined("MW_DB_TABLE_CUSTOM_FIELDS")) {
            $custom_field_table = MW_DB_TABLE_CUSTOM_FIELDS;
        } else {
            $custom_field_table = $this->table_prefix . 'custom_fields';
        }
        if (defined("MW_DB_TABLE_TAXONOMY_ITEMS")) {
            $table_cats_items = $categories_items_table = MW_DB_TABLE_TAXONOMY_ITEMS;
        } else {
            $table_cats_items = $categories_items_table = $this->table_prefix . 'categories_items';
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
                    $clean_q = "DELETE
				FROM $categories_items_table WHERE
				data_type='category_item' AND
				rel='{$table_assoc_name}' AND
				rel_id={$id_to_return}  ";
                    $cats_data_items_modified = true;
                    $cats_data_modified = true;
                    $this->q($clean_q);
                } else {
                    if (is_array($original_data['categories'])) {
                        $original_data['categories'] = implode(',', $original_data['categories']);
                    }

                    if (is_string($original_data['categories'])) {
                        $original_data['categories'] = str_replace('/']= ',', $original_data['categories']);
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
                                    if (isset($original_data['parent']) and $original_data['parent'] != '') {
                                        $par_rel = intval($original_data['parent']);
                                        $cncheckq = $cncheckq . " " . " AND rel_id='{$par_rel}' ";
                                    }
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
                                        if (isset($original_data['parent']) and $original_data['parent'] != '') {
                                            $par_rel = intval($original_data['parent']);
                                            $clean_q = $clean_q . " " . ",rel_id='{$par_rel}' ";
                                        }
                                        $cats_data_items_modified = true;
                                        $cats_data_modified = true;
                                        $this->q($clean_q);

                                    }
                                }
                                if (isset($cncheckq) and $cncheckq != false) {
                                    $is_ex = $this->query($cncheckq);
                                    if (!empty($is_ex) and isarr($is_ex[0])) {
                                        $cz[$j] = $is_ex[0]['id'];
                                        $cz_int[] = intval($is_ex[0]['id']);
                                    }
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
                    $cats_data_items_modified = true;
                    $cats_data_modified = true;
                }

                if ($cats_data_modified == TRUE) {
                    $this->app->cache->delete('categories' . DIRECTORY_SEPARATOR . 'global');
                    if (isset($parent_id)) {
                        $this->app->cache->delete('categories' . DIRECTORY_SEPARATOR . $parent_id);
                    }
                }
                if ($cats_data_items_modified == TRUE) {
                    $this->app->cache->delete('categories_items' . DIRECTORY_SEPARATOR . '');
                }
            }

        }

        // adding custom fields
        $table_assoc_name = $this->assoc_table_name($table_assoc_name);
        $custom_field_to_save = array();

        if (!empty($original_data)) {
            foreach ($original_data as $k => $v) {
                if (is_string($k) and stristr($k, 'custom_field_') == true) {
                    $k1 = str_ireplace('custom_field_']= '', $k);
                    if (trim($k) != '') {
                        $custom_field_to_save[$k1] = $v;
                    }
                }
            }
        }

        if (!isset($original_data['skip_custom_field_save']) and ((!empty($custom_field_to_save) or (isset($original_data['custom_fields'])) and $table_assoc_name != 'table_custom_fields' and $table_assoc_name != 'custom_fields'))) {
            if (isset($original_data['custom_fields']) and is_array($original_data['custom_fields']) and !empty($original_data['custom_fields'])) {
                $custom_field_to_save = array_merge($custom_field_to_save, $original_data['custom_fields']);
            }
            if (!empty($custom_field_to_save)) {
                if (!$this->table_exist($custom_field_table)) {
                    return false;
                }
                if ($table_assoc_name == 'custom_fields') {
                    return false;
                } elseif ($table_assoc_name == 'table_custom_fields') {
                    return false;
                } elseif ($table_assoc_name == $this->table_prefix . 'custom_fields') {
                    return false;
                }
                if (isset($original_data['skip_custom_field_save']) == false) {
                    foreach ($custom_field_to_save as $cf_k => $cf_v) {
                        $new_custom_field_to_save = array();
                        $new_custom_field_to_save['name'] = $cf_k;
                        $new_custom_field_to_save['value'] = $cf_v;

                        $cftype = 'content';


                        $new_custom_field_to_save['rel'] = $table_assoc_name;
                        $new_custom_field_to_save['rel_id'] = $id_to_return;
                        $new_custom_field_to_save['skip_custom_field_save'] = true;

                        if (is_string($cf_k) and strtolower(trim($cf_k)) == 'price') {
                            $cftype = $custom_field_to_save['type'] = 'price';
                        }

                        if (is_string($cf_k) and strtolower(trim($cf_k)) != '') {
                            $make_as_array = false;
                            if (stristr($cf_k, '[radio]') !== FALSE) {
                                $cf_k = str_ireplace('[radio]']= '', $cf_k);
                                $cftype = 'radio';
                                $make_as_array = 1;
                            }
                            if (stristr($cf_k, '[dropdown]') !== FALSE) {
                                $cf_k = str_ireplace('[dropdown]']= '', $cf_k);
                                $cftype = 'dropdown';
                                $make_as_array = 1;
                            }
                            if (stristr($cf_k, '[select]') !== FALSE) {
                                $cf_k = str_ireplace('[select]']= '', $cf_k);
                                $cftype = 'dropdown';
                                $make_as_array = 1;
                            }
                            if (stristr($cf_k, '[radio]') !== FALSE) {
                                $cf_k = str_ireplace('[radio]']= '', $cf_k);
                                $cftype = 'radio';
                                $make_as_array = 1;
                            }
                            if (stristr($cf_k, '[price]') !== FALSE) {
                                $cf_k = str_ireplace('[price]']= '', $cf_k);
                                $cftype = 'price';
                                $make_as_array = 1;
                            }
                            if (stristr($cf_k, '[address]') !== FALSE) {
                                $cf_k = str_ireplace('[address]']= '', $cf_k);
                                $cftype = 'address';
                                $make_as_array = 1;
                            }
                            if (stristr($cf_k, '[textarea]') !== FALSE) {
                                $cf_k = str_ireplace('[textarea]']= '', $cf_k);
                                $cftype = 'textarea';
                                $make_as_array = 1;
                            }
                            if (stristr($cf_k, '[checkbox]') !== FALSE) {
                                $cf_k = str_ireplace('[checkbox]']= '', $cf_k);
                                $cftype = 'checkbox';
                                $make_as_array = 1;
                            }

                            if ($make_as_array != false) {
                                if (is_string($cf_v)) {
                                    $cf_v = explode(',', $cf_v);
                                    if ($cftype == 'content') {
                                        $cftype = 'dropdown';
                                    }
                                }
                            }
                        }
                        $new_custom_field_to_save['type'] = $cftype;

                        $this->app->fields->save($new_custom_field_to_save);
                    }
                }

                $custom_field_to_delete['rel'] = $table_assoc_name;

                $custom_field_to_delete['rel_id'] = $id_to_return;

                if (isset($original_data['skip_custom_field_ssssave']) == true) {

                    $custom_field_to_save = $this->app->url->replace_site_url($custom_field_to_save);
                    $custom_field_to_save = $this->addslashes_array($custom_field_to_save);

                    foreach ($custom_field_to_save as $cf_k => $cf_v) {

                        if (($cf_v != '') and $table_assoc_name != 'custom_fields') {

                            if ($cf_k != '') {
                                $clean = " DELETE FROM $custom_field_table WHERE
							rel =\"{$table_assoc_name}\"
							AND
							rel_id =\"{$id_to_return}\"
							AND
							custom_field_name =\"{$cf_k}\"

							";
                                $this->q($clean);
                            }
                            $cfvq = '';
                            $cftype = 'default_content';
                            $cftype = 'content';
                            $cftitle = false;
                            if (is_string($cf_k) and strtolower(trim($cf_k)) == 'price') {
                                $cftype = $custom_field_to_save['type'] = 'price';
                            }

                            if (is_string($cf_k) and strtolower(trim($cf_k)) != '') {
                                $make_as_array = false;
                                if (stristr($cf_k, '[radio]') !== FALSE) {
                                    $cf_k = str_ireplace('[radio]']= '', $cf_k);
                                    $cftype = 'radio';
                                    $make_as_array = 1;
                                }
                                if (stristr($cf_k, '[dropdown]') !== FALSE) {
                                    $cf_k = str_ireplace('[dropdown]']= '', $cf_k);
                                    $cftype = 'dropdown';
                                    $make_as_array = 1;
                                }
                                if (stristr($cf_k, '[select]') !== FALSE) {
                                    $cf_k = str_ireplace('[select]']= '', $cf_k);
                                    $cftype = 'dropdown';
                                    $make_as_array = 1;
                                }
                                if (stristr($cf_k, '[radio]') !== FALSE) {
                                    $cf_k = str_ireplace('[radio]']= '', $cf_k);
                                    $cftype = 'radio';
                                    $make_as_array = 1;
                                }
                                if (stristr($cf_k, '[price]') !== FALSE) {
                                    $cf_k = str_ireplace('[price]']= '', $cf_k);
                                    $cftype = 'price';
                                    $make_as_array = 1;
                                }
                                if (stristr($cf_k, '[address]') !== FALSE) {
                                    $cf_k = str_ireplace('[address]']= '', $cf_k);
                                    $cftype = 'address';
                                    $make_as_array = 1;
                                }
                                if (stristr($cf_k, '[textarea]') !== FALSE) {
                                    $cf_k = str_ireplace('[textarea]']= '', $cf_k);
                                    $cftype = 'textarea';
                                    $make_as_array = 1;
                                }
                                if (stristr($cf_k, '[checkbox]') !== FALSE) {
                                    $cf_k = str_ireplace('[checkbox]']= '', $cf_k);
                                    $cftype = 'checkbox';
                                    $make_as_array = 1;
                                }

                                if ($make_as_array != false) {
                                    if (is_string($cf_v)) {
                                        $cf_v = explode(',', $cf_v);
                                    }
                                }
                            }

                            $custom_field_to_save['custom_field_name'] = $cf_k;
                            if (is_array($cf_v)) {
                                $cf_k_plain = $this->app->url->slug($cf_k);
                                $cf_k_plain = $this->escape_string($cf_k_plain);
                                $cf_k_plain = str_replace('-']= '_', $cf_k_plain);
                                $cftitle = false;
                                $val_to_serilize = $cf_v;
                                if (isset($custom_field_to_save['values'])) {
                                    $val_to_serilize = $custom_field_to_save['values'];
                                }
                                if ($cftype == false) {
                                    if (isset($custom_field_to_save['type'])) {
                                        $cftype = $custom_field_to_save['type'];
                                    } elseif (isset($cf_v['type'])) {
                                        $cftype = $custom_field_to_save['type'] = $cf_v['type'];
                                    }
                                }

                                if (isset($custom_field_to_save['title'])) {
                                    $cftitle = $custom_field_to_save['title'];
                                } elseif (isset($cf_v['title'])) {
                                    $cftitle = $custom_field_to_save['title'] = $cf_v['title'];
                                }
                                if (isset($custom_field_to_save['name'])) {
                                    $cftitle = $custom_field_to_save['name'];
                                } elseif (isset($cf_v['name'])) {
                                    $cftitle = $custom_field_to_save['name'] = $cf_v['name'];
                                }

                                if ($cftitle != false) {
                                    $custom_field_to_save['custom_field_name'] = $cftitle;
                                }
                                $temp = serialize($val_to_serilize);
                                $custom_field_to_save['custom_field_values'] = base64_encode($temp);

                                $temp2 = array_pop($val_to_serilize);
                                $temp = implode(',', $val_to_serilize);
                                $temp = ltrim($temp, '0,');

                                $custom_field_to_save['custom_field_values_plain'] = $this->escape_string($temp);
                                if (is_array($custom_field_to_save['custom_field_values_plain'])) {
                                    $custom_field_to_save['custom_field_values_plain'] = implode(',', $custom_field_to_save['custom_field_values_plain']);
                                }
                                $custom_field_to_save['num_value'] = floatval($temp2);

                                $cfvq = "custom_field_values =\"" . $custom_field_to_save['custom_field_values'] . "\",";
                                $cfvq .= "custom_field_values_plain =\"" . $custom_field_to_save['custom_field_values_plain'] . "\",";
                                $cfvq .= "custom_field_name_plain =\"" . $cf_k_plain . "\",";


                                if ($cftype == 'price' and isset($cf_v['value']) and is_array($cf_v['value'])) {
                                    $custom_field_to_save['num_value'] = floatval($cf_v['value']);

                                    $custom_field_to_save['custom_field_value'] = array_pop($cf_v['value']);
                                } elseif ($cftype == 'price' and isset($cf_v['value']) and is_string($cf_v['value'])) {
                                    $custom_field_to_save['num_value'] = floatval($cf_v['value']);
                                    $custom_field_to_save['custom_field_value'] = trim($cf_v['value']);
                                } else {
                                    $custom_field_to_save['custom_field_value'] = 'Array';

                                }


                            } else {
                                $cf_v = $this->escape_string($cf_v);

                                $custom_field_to_save['custom_field_value'] = $cf_v;
                                $flval = floatval($cf_v);
                                if ($flval != 0) {
                                    $custom_field_to_save['num_value'] = $flval;
                                }
                            }


                            $custom_field_to_save['rel'] = $table_assoc_name;
                            $custom_field_to_save['rel_id'] = $id_to_return;
                            $custom_field_to_save['skip_custom_field_save'] = true;
                            $next_id = intval($this->last_id($custom_field_table) + 1);
                            $add = " INSERT INTO $custom_field_table SET
                            custom_field_name ='{$cf_k}',
                            $cfvq
                            custom_field_value ='{$custom_field_to_save['custom_field_value']}',
                            custom_field_type = '{$cftype}',

						    ";
                            if (isset($custom_field_to_save['num_value'])) {
                                $add .= "num_value ='{$custom_field_to_save['num_value']}',";
                            }


                            $add .= "rel ='{$custom_field_to_save ['rel']}',
                            rel_id ='{$custom_field_to_save ['rel_id']}'";


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

                            if ($cftype != 'default_content') {
                                $this->q($add);
                            }

                        }
                    }
                    $this->app->cache->delete('custom_fields/global');
                }
            }
        }


    }

    public function table_exist($table)
    {

        $table = $this->escape_string($table);
        $sql_check = "show tables like '$table'";
        $function_cache_id = 'table_exist' . $table . crc32($sql_check);

        $q = $this->query($sql_check, $function_cache_id, 'db');

        if (!is_array($q)) {
            return false;
        }
        if (isset($q['error'])) {
            return false;
        } else {
            return $q;
        }

    }

    public function update_position_field($table, $data = array())
    {
        $table_real = $this->real_table_name($table);
        $i = 0;
        if (is_array($data)) {
            foreach ($data as $value) {
                $value = intval($value);
                if ($value != 0) {
                    $q = "UPDATE $table_real SET position={$i} WHERE id={$value} ";
                    //d($q);
                    $q = $this->q($q);
                }
                $i++;
            }
        }

        $cache_group = $this->assoc_table_name($table);

        $this->app->cache->delete($cache_group);
    }

    public function real_table_name($assoc_name)
    {

        $assoc_name_new = $assoc_name;


        if ($this->table_prefix == false) {
            $this->table_prefix = $this->app->config('table_prefix');
        }


        if ($this->table_prefix != false) {
            $assoc_name_new = str_ireplace('table_', $this->table_prefix, $assoc_name_new);
        } else if (defined('MW_TABLE_PREFIX')) {
            $assoc_name_new = str_ireplace('table_', MW_TABLE_PREFIX, $assoc_name_new);
        }

        $assoc_name_new = str_ireplace('table_', $this->table_prefix, $assoc_name_new);
        $assoc_name_new = str_ireplace($this->table_prefix . $this->table_prefix, $this->table_prefix, $assoc_name_new);

        if ($this->table_prefix and $this->table_prefix != '' and stristr($assoc_name_new, $this->table_prefix) == false) {
            $assoc_name_new = $this->table_prefix . $assoc_name_new;
        } else if ($this->table_prefix == false and defined('MW_TABLE_PREFIX') and MW_TABLE_PREFIX != '' and stristr($assoc_name_new, MW_TABLE_PREFIX) == false) {
            $assoc_name_new = MW_TABLE_PREFIX . $assoc_name_new;
        }

        return $assoc_name_new;
    }

    /**
     * Performs a query without returning a result
     *
     * Useful if you want to preform table updates or deletes without the need to see the result
     *
     *
     * @param string $q Your SQL query
     * @param bool|array $connection_settings
     * @return array|bool|mixed
     * @package Database
     * @uses $this->query
     *
     *
     * @example
     *  <code>
     *  //make plain query to the db.
     *    $table = $this->table_prefix.'content';
     *  $sql = "update $table set title='new' WHERE id=1 ";
     *  $q = $this->q($sql);
     * </code>
     *
     */
    public function q($q, $connection_settings = false)
    {
        if ($connection_settings == false) {
            if (!empty($this->connection_settings)) {
                $db = $this->connection_settings;
            } else {
                $db = $this->app->config('db');
            }
        } else {
            $db = $connection_settings;
        }
        $q = $this->query($q, $cache_id = false, $cache_group = false, $only_query = true, $db);
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
     * @function $this->query
     * @desc Executes plain query in the database.
     *
     * @param string $q Your SQL query
     * @param string|bool $cache_id It will save the query result in the cache. Set to false to disable
     * @param string|bool $cache_group Stores the result in certain cache group. Set to false to disable
     * @param bool $only_query If set to true, will perform only a query without returning a result
     * @param array|bool $connection_settings
     * @return array|bool|mixed
     *
     * @example
     *  <code>
     *  //make plain query to the db
     * $table = $this->table_prefix.'content';
     *    $sql = "SELECT id FROM $table WHERE id=1   ORDER BY updated_on DESC LIMIT 0,1 ";
     *  $q = $this->query($sql, $cache_id=crc32($sql),$cache_group= 'content/global');
     *
     * </code>
     *
     *
     *
     */
    public function query($q, $cache_id = false, $cache_group = 'global', $only_query = false, $connection_settings = false)
    {
        if (trim($q) == '') {
            return false;
        }


        $error['error'] = array();
        $results = false;

        if ($cache_id != false and $cache_group != false) {

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


        $this->query_log($q);
        if ($connection_settings != false and is_array($connection_settings) and !empty($connection_settings)) {
            $db = $connection_settings;
        } elseif (!empty($this->connection_settings)) {
            $db = $this->connection_settings;
        } else {
            $db = $this->app->config('db');
        }


        $temp_db = mw_var('temp_db');
        if ((!isset($db) or $db == false or $db == NULL) and $temp_db != false) {
            $db = $temp_db;
        }

        // if we didnt set the connection settings will try to get them from global constants
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

        $q = $this->adapter->query($q, $db);

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
        if ($cache_id != false) {
            $this->app->cache->save($q, $cache_id, $cache_group);
        }
        return $q;
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

    public function assoc_table_name($assoc_name)
    {
        global $_mw_assoc_table_names;
        if (isset($_mw_assoc_table_names[$assoc_name])) {
            return $_mw_assoc_table_names[$assoc_name];
        }
        $assoc_name_o = $assoc_name;
        $assoc_name = str_ireplace(MW_TABLE_PREFIX, 'table_', $assoc_name);
        $assoc_name = str_ireplace('table_']= '', $assoc_name);
        $assoc_name = str_replace($this->table_prefix, '', $assoc_name);
        $is_assoc = substr($assoc_name, 0, 5);
        $assoc_name = str_replace('table_table_']= 'table_', $assoc_name);
        $_mw_assoc_table_names[$assoc_name_o] = $assoc_name;
        return $assoc_name;
    }

    public function add_table_index($aIndexName, $aTable, $aOnColumns, $indexType = false)
    {

        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {
            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
            $function_cache_id = 'add_table_index' . crc32($function_cache_id);
        }

        if (isset($this->add_table_index_cache[$function_cache_id])) {
            return true;
        } else {
            $this->add_table_index_cache[$function_cache_id] = true;
        }


        $table_name = $function_cache_id;
        $cache_group = 'db/' . $table_name;
        $cache_content = $this->app->cache->get($function_cache_id, $cache_group);

        if (($cache_content) != false) {

            return $cache_content;
        }


        $columns = implode(',', $aOnColumns);
        $query = $this->query("SHOW INDEX FROM {$aTable} WHERE Key_name = '{$aIndexName}';");
        if ($indexType != false) {
            $index = $indexType;
        } else {
            $index = " INDEX ";
            //FULLTEXT
        }

        if ($query == false) {
            $q = "ALTER TABLE " . $aTable . " ADD $index `" . $aIndexName . "` (" . $columns . ");";
            $this->q($q);
        }


        $this->app->cache->save('--true--', $function_cache_id, $cache_group);


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
    public function set_table_engine($aTable, $aEngine = 'MyISAM')
    {
        $this->q("ALTER TABLE {$aTable} ENGINE={$aEngine};");
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
    public function add_foreign_key($aFKName, $aTable, $aColumns, $aForeignTable, $aForeignColumns, $aOptions = array())
    {
        $query = $this->query("
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
            $onDelete = 'ON DELETE ' . (isset($aOptions['delete']) ? $aOptions['delete'] : 'NO ACTION');
            $onUpdate = 'ON UPDATE ' . (isset($aOptions['update']) ? $aOptions['update'] : 'NO ACTION');
            $q = "ALTER TABLE " . $aTable;
            $q .= " ADD CONSTRAINT `" . $aFKName . "` ";
            $q .= " FOREIGN KEY(" . $columns . ") ";
            $q .= " {$onDelete} ";
            $q .= " {$onUpdate} ";
            $this->q($q);
        }

    }

    public function get_tables()
    {

        if (!empty($this->connection_settings)) {
            $db = $this->connection_settings;
        } else {
            $db = $this->app->config('db');
        }
        $db = $db['dbname'];

        $q = $this->query("SHOW TABLES FROM $db", __FUNCTION__, 'db');

        if (isset($q['error'])) {
            return false;
        } else {
            $ret = array();
            if (is_array($q)) {
                foreach ($q as $value) {
                    $v = array_values($value);
                    if (isset($v[0]) and is_string($v[0])) {
                        if (strstr($v[0], $this->table_prefix)) {
                            $ret[] = ($v[0]);
                        }
                    }
                }
            }
            return $ret;
        }
    }

    /**
     * Imposts SQL file in the DB
     * @category Database
     * @package    Database
     * @subpackage Advanced
     * @param $full_path_to_file
     * @return bool
     */
    public function import_sql_file($full_path_to_file)
    {

        $dbms_schema = $full_path_to_file;
        if (is_file($dbms_schema)) {
            $sql_query = fread(fopen($dbms_schema, 'r'), filesize($dbms_schema)) or die('problem ');
            $sql_query = str_ireplace('{MW_TABLE_PREFIX}', $this->table_prefix, $sql_query);

            $sql_query = str_ireplace('{MW_TABLE_PREFIX}', MW_TABLE_PREFIX, $sql_query);


            $sql_query = $this->remove_sql_remarks($sql_query);

            $sql_query = $this->remove_comments_from_sql_string($sql_query);
            $sql_query = $this->split_sql_file($sql_query, ';');

            $i = 1;
            foreach ($sql_query as $sql) {
                $sql = trim($sql);

                $qz = $this->q($sql);
            }
            //$this->app->cache->delete('db');
            return true;
        } else {
            return false;
        }
    }

    public function remove_sql_remarks($sql)
    {
        $lines = explode("\n", $sql);
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
                $lines[$i] = "";
            }
        }
        return $output;
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
    public function remove_comments_from_sql_string($output)
    {
        $lines = explode("\n", $output);
        $output = "";
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

    public function split_sql_file($sql, $delimiter)
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
        $output = preg_replace('/\x{EF}\x{BB}\x{BF}/']= '', $output);
        return $output;
    }

    function sanitize($input)
    {
        if (is_array($input)) {
            foreach ($input as $var => $val) {
                $output[$var] = $this->sanitize($val);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                $input = stripslashes($input);
            }
            $input = $this->clean_input($input);
            if (function_exists('mysql_real_escape_string')) {
                $output = mysql_real_escape_string($input);
            } else {
                $output = ($input);
            }

        }
        return $output;
    }
}
