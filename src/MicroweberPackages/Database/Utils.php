<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */
namespace MicroweberPackages\Database;
use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

/**
 * Database Utils class.
 *
 * Query helper class
 *
 * @category Database
 * @desc     Various utils functions to work with the database
 *
 * @property \MicroweberPackages\Application $app
 */
class Utils
{
    public $cache_seconds = 36000;

    public function build_tables($tables)
    {
        foreach ($tables as $name => $schema) {
            $this->build_table($name, $schema);
        }
    }

    public function build_table($table_name, $fields_to_add, $use_cache = false)
    {

        if ($use_cache) {
            $key = 'mw_build_table' . $table_name;
            if (defined('MW_VERSION')) {
                $key = $key . MW_VERSION;
            }

            $value = Cache::get($key);

            if (!$value) {
                $value = 1;
                $seconds = $this->cache_seconds;
                Cache::put($key, $value, $seconds);

                return $this->_exec_table_builder($table_name, $fields_to_add);
            } else {
                return $value;
            }
        } else {
            return $this->_exec_table_builder($table_name, $fields_to_add);
        }
    }

    public function table_exists($table_name)
    {
        $table_name = $this->assoc_table_name($table_name);
        if (Schema::hasTable($table_name)) {
            return true;
        }
    }


    private function _exec_table_builder($table_name, $fields_to_add)
    {
        $engine = $this->get_sql_engine();
        $table_name = $this->assoc_table_name($table_name);
        $real_table_name = $this->real_table_name($table_name);
        // DB::transaction(function () use ($table_name) {
        if (!Schema::hasTable($table_name)) {
            Schema::create($table_name, function ($table) {
                $table->increments('id');
            });

        }
        // });
        $class = $this;
        // DB::transaction(function () use ($table_name, $fields_to_add, $class) {
        if (is_array($fields_to_add)) {
            Schema::table($table_name, function ($schema) use ($fields_to_add, $table_name, $class, $engine) {
                foreach ($fields_to_add as $name => $meta) {
                    $is_index = substr($name, 0, 1) === '$';
                    $is_default = null;
                    $is_nullable = true;
                    if (!$is_index) {
                        if (is_array($meta)) {
                            if (!isset($meta['type'])) {
                                $name = array_shift($meta);
                                $type = array_shift($meta);
                            } else {
                                $type = $meta['type'];
                            }
                            if (isset($meta['default'])) {
                                $is_default = $meta['default'];
                                if ($is_default == 'not_null') {
                                    $is_nullable = false;
                                }
                            }
                        } else {
                            $type = $meta;
                        }

                        $col_exist = Schema::hasColumn($table_name, $name);


                        if (!$col_exist) {
                            $fluent = $schema->$type($name);
                            if ($is_default !== null) {
                                $fluent->default($is_default)->nullable();
                            }
                            if ($is_nullable) {
                                $fluent->nullable();
                            }
                        } else {
                            if ($name == '$id') continue;


                            try {

                                if (!class_exists('\Doctrine\DBAL\Driver\PDOPgSql\Driver', true)) {
                                    throw new \Exception('Doctrine DBAL is missing');
                                }

                                $type = DB::getSchemaBuilder()->getColumnType($table_name, $name);
                                if (!is_string($meta) && isset($meta['type'])) {
                                    $meta = $meta['type'];
                                }
                                if ($engine == 'pgsql' && $meta == 'char') {
                                    $meta = 'string';
                                }
                                if (is_string($meta) && $type != $meta) {
                                    Schema::table($table_name, function ($table) use ($meta, $name) {
                                        $table->{$meta}($name)->change();
                                    });
                                }

                            } catch (\Exception $e) {


                            }
                        }
                    }
                }
            });


        }
        //   });
//

        if (Schema::hasTable($table_name)) {

            if ($engine == 'pgsql') {
                $tableToCheck = $table_name;
                $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
                if (!isset($highestId->max)) {
                    $highestId->max = 1;
                }
                DB::select('SELECT setval(\'' . $real_table_name . '_id_seq\', ' . $highestId->max . ')');

            }

        }


    }

    public function assoc_table_name($assoc_name)
    {
        $config_prefix = $this->get_prefix();
        $assoc_name = str_ireplace($config_prefix, '', $assoc_name);

        return $assoc_name;
    }

    public function get_tables_list($only_cms_tables = false)
    {

        $system_tables = [
            "sqlite_sequence",
            "information_schema",
            "columns_priv",
            "db",
            "engine_cost",
            "event",
            "func",
            "general_log",
            "gtid_executed",
            "help_category",
            "help_keyword",
            "help_relation",
            "help_topic",
            "innodb_index_stats",
            "innodb_table_stats",
            "ndb_binlog_index",
            "plugin",
            "proc",
            "procs_priv",
            "proxies_priv",
            "server_cost",
            "servers",
            "slave_master_info",
            "slave_relay_log_info",
            "slave_worker_info",
            "slow_log",
            "tables_priv",
            "time_zone",
            "time_zone_leap_second",
            "time_zone_name",
            "time_zone_transition",
            "time_zone_transition_type",
            "user"
         ];

        $tables = array();
        $engine = $this->get_sql_engine();

        if ($engine == 'sqlite') {
            $sql = DB::select("SELECT * FROM sqlite_master WHERE type='table';");
            if (is_array($sql) and !empty($sql)) {
                foreach ($sql as $item) {
                    $item = (array)$item;
                    $val = false;
                    if (isset($item['tbl_name'])) {
                        $val = $item['tbl_name'];
                    } elseif (isset($item['name'])) {
                        $val = $item['name'];
                    }
                    if ($val and $val != 'sqlite_sequence') {
                        $tables[] = $val;
                    }

                }
            }
        } else if ($engine == 'pgsql') {
            // http://stackoverflow.com/a/29232803/731166
            // ? AND table_name NOT LIKE 'valid%'
            $result = DB::select('
            SELECT table_name FROM information_schema.tables
              WHERE table_schema NOT IN (\'pg_catalog\', \'information_schema\')
                AND table_type = \'BASE TABLE\' ;
            ');

            if (!empty($result)) {
                foreach ($result as $item) {
                    $item = (array)$item;
                    if (count($item) > 0) {
                        $item_vals = (array_values($item));
                        //  dd($item_vals);
                        $tables[] = $item_vals[0];
                    }
                }
            }
        } else {
            $result = DB::select('SHOW TABLES');
            if (!empty($result)) {
                foreach ($result as $item) {
                    $item = (array)$item;
                    if (count($item) > 0) {
                        $item_vals = (array_values($item));
                        $tables[] = $item_vals[0];
                    }
                }
            }
        }

        if ($only_cms_tables and $tables) {
            $cms_tables = array();
            $local_prefix = $this->get_prefix();


            foreach ($tables as $k => $v) {

                if (in_array($k, $system_tables)) {
                    continue;
                }

                if ($local_prefix) {
                    //   $starts_with = starts_with($local_prefix, $v);
                    $starts_with = substr($v, 0, strlen($local_prefix)) === $local_prefix;

                    if ($starts_with) {
                           $v1 = str_replace_first($local_prefix, '', $v);
                        $cms_tables[$k] = $v;
                    } else {
                        //  $cms_tables[$k] = $v;
                    }
                } else {

                    $cms_tables[$k] = $v;

                }

            }

            return $cms_tables;
        }

        return $tables;
    }

    public function get_table_ddl($full_table_name)
    {
        $engine = $this->get_sql_engine();
        if ($engine != 'sqlite') {
            $qs = 'SHOW CREATE TABLE ' . $full_table_name;
            $sql = DB::select($qs);
            if (isset($sql[0])) {
                $sql[0] = (array)$sql[0];
                $row = array_values($sql[0]);
                if (isset($row[1])) {
                    return $row[1];
                }
            }
        } else {
            $sql = DB::select("SELECT * FROM sqlite_master WHERE type='table' and (tbl_name='{$full_table_name}');");
            if (is_array($sql) and !empty($sql)) {
                foreach ($sql as $item) {
                    $item = (array)$item;
                    if (isset($item['sql'])) {
                        return $item['sql'];
                    }
                }
            }
        }
    }

    public function get_sql_engine()
    {
        $default_sql_engine = Config::get('database.default');

        return $default_sql_engine;
    }

    public function get_prefix()
    {
        $default_sql_engine = $this->get_sql_engine();
        $config_prefix = Config::get('database.connections.' . $default_sql_engine . '.prefix');

        return $config_prefix;
    }

    public $table_prefix;

    public function real_table_name($assoc_name)
    {
        $assoc_name_new = $assoc_name;
        static $config_prefix = false;
        if (!$config_prefix) {
            $config_prefix = $this->get_prefix();
        }
        $this->table_prefix = $config_prefix;

        if ($this->table_prefix != false) {
            $assoc_name_new = str_ireplace('table_', $this->table_prefix, $assoc_name_new);
        }

        $assoc_name_new = str_ireplace('table_', $this->table_prefix, $assoc_name_new);
        $assoc_name_new = str_ireplace($this->table_prefix . $this->table_prefix, $this->table_prefix, $assoc_name_new);

        if ($this->table_prefix and $this->table_prefix != '' and stristr($assoc_name_new, $this->table_prefix) == false) {
            $assoc_name_new = $this->table_prefix . $assoc_name_new;
        }

        return $assoc_name_new;
    }

    public $default_limit = 30;

    public $table_fields = array();

    /**
     * Returns an array that contains only keys that has the same names as the table fields from the database.
     *
     * @param  string
     * @param  array
     *
     * @return array
     *
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

        if (isset($this->table_fields[$table])) {
            $fields = $this->table_fields[$table];
         } else {
            $this->table_fields[$table] =  $fields = $this->get_fields($table);

        }


        if (is_array($fields)) {
            foreach ($fields as $field) {
                $field = strtolower($field);
                if (isset($array[$field])) {
                    if ($array[$field] != false) {
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

        }

        return $array_to_return;
    }

    /**
     * Gets all field names from a DB table.
     *
     * @param            $table          string
     *                                   - table name
     * @param array|bool $exclude_fields array
     *                                   - fields to exclude
     *
     * @return array
     *
     * @author  Peter Ivanov
     *
     * @version 1.0
     *
     * @since   Version 1.0
     */
    public static $get_fields_fields_memory = [];

    public function get_fields($table, $use_cache = true, $advanced_info = false)
    {
        $fields = array();
        $expiresAt = 99999;

        $cache_group = 'db';
        if (!$table) {
            return false;
        }
         if($use_cache and isset(self::$get_fields_fields_memory[$table])){
           return self::$get_fields_fields_memory[$table];
        }


        $key = 'mw_db_get_fields_single' . crc32($table);
      //  $hash = $table;




        if ($use_cache) {
            $fields = mw()->cache_manager->get($key, 'db', $expiresAt);
            if($fields){
                return $fields;
            }
        }


        $db_driver = Config::get("database.default");

        $engine = $this->get_sql_engine();
        if ($engine == 'mysql') {
            $table_name = $this->real_table_name($table);
            $fields = DB::select('SHOW COLUMNS FROM ' . $table_name . '');

        } else if ($engine == 'sqlite') {
            $table_name = $this->real_table_name($table);
            $fields = DB::select('PRAGMA table_info(' . $table_name . ')');
        } else if ($engine == 'pgsql') {
            $table_name = $this->real_table_name($table);
            // getColumnListing returns table hidden fields in pgsql
            $fields = DB::select("
                            SELECT attrelid::regclass, attnum, attname
                FROM   pg_attribute
                WHERE  attrelid = '{$table_name}'::regclass
                AND    attnum > 0
                AND    NOT attisdropped
                ORDER  BY attnum;
                ");

        } else {
            // getColumnListing has a bug in mysql 8.0 and sqlite
            $fields = DB::connection($db_driver)->getSchemaBuilder()->getColumnListing($table);
        }

        $original_fields = $fields;

        if (count($fields) && !is_string($fields[0]) && (isset($fields[0]->name) or isset($fields[0]->column_name) or isset($fields[0]->Field) or isset($fields[0]->attname))) {
            $fields = array_map(function ($f) {
                if (isset($f->column_name)) {
                    return $f->column_name;
                } else if (isset($f->Field)) {
                    return $f->Field;
                } else if (isset($f->attname)) {
                    return $f->attname;
                } else {
                    return $f->name;
                }
            }, $fields);
        }

        if ($advanced_info) {
            $ready_fields = [];
            foreach ($fields as $field) {
                try {
                    $column = Schema::getConnection()->getDoctrineColumn($table_name, $field);
                    $ready_fields[] = [
                        'name' => $field,
                        'type' => $column->getType()->getName()
                    ];
                } catch (\Exception $e) {
                    foreach ($original_fields as $o_field) {
                        if (isset($o_field->name)) {
                            $ready_fields[] = [
                                'name' => $o_field->name,
                                'type' => $o_field->type
                            ];
                        }
                    }
                }
            }

            return $ready_fields;
        }


        // Caching
        if ($use_cache) {
            self::$get_fields_fields_memory[$table] = $fields;
            mw()->cache_manager->save($fields, $key, $cache_group,$expiresAt);
        }

        return $fields;
    }

    public function guess_cache_group($group)
    {
        return $group;
    }

    public function update_position_field($table, $data = array())
    {
        $i = 0;


        if (is_array($data)) {
            foreach ($data as $value) {
                $value = intval($value);

                if ($value !== null) {
                    DB::table($table)->whereId($value)->update(['position' => $i]);
                }
                ++$i;
            }
        }
        $cache_group = $this->assoc_table_name($table);
        $this->app->cache_manager->delete($cache_group);
     //   $this->app->cache_manager->delete('global/full_page_cache');

    }

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


    public function clean_input($input)
    {

//         $input = $this->app->format->clean_xss($input, true);

        if (is_array($input)) {
            $output = array();
            foreach ($input as $var => $val) {
                $output[$var] = $this->clean_input($val);
            }
        } elseif (is_string($input)) {
            $search = array(
                '@<script[^>]*?>.*?</script>@si', // Strip out javascript

          //      '@<![\s\S]*?--[ \t\n\r]*>@', // Strip multi-line comments
            );
            if (is_string($input)) {
                $output = preg_replace($search, '', $input);
            } else {
                $output = $input;
            }
        } else {
            return $input;
        }

        return $output;
    }

    /**
     * Escapes a string from sql injection.
     *
     * @param string|array $value to escape
     *
     * @return string|array Escaped string
     * @return mixed        Es
     *
     * @example
     * <code>
     * //escape sql string
     *  $results = $this->escape_string($_POST['email']);
     * </code>
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
            $search = array('\\', "\x00", "\n", "\r", "'", '"', "\x1a");
            $replace = array('\\\\', '\\0', '\\n', '\\r', "\'", '\"', '\\Z');
            $new = str_replace($search, $replace, $value);
            $this->mw_escaped_strings[$str_crc] = $new;

            return $new;
        }
    }

    public function add_table_index($aIndexName, $aTable, $aOnColumns, $indexmeta = false)
    {
        $aTable = $this->real_table_name($aTable);
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
        $cache_content = $this->app->cache_manager->get($function_cache_id, $cache_group);
        if (($cache_content) != false) {
            return $cache_content;
        }

        $columns = implode(',', $aOnColumns);
        $query = $this->query("SHOW INDEX FROM {$aTable} WHERE Key_name = '{$aIndexName}';");
        if ($indexmeta != false) {
            $index = $indexmeta;
        } else {
            $index = ' INDEX ';
            //FULLTEXT
        }
        if ($query == false) {
            $q = 'ALTER TABLE ' . $aTable . " ADD $index `" . $aIndexName . '` (' . $columns . ');';
            $this->q($q);
        }
        $this->app->cache_manager->save('--true--', $function_cache_id, $cache_group);
    }

    /**
     * Imposts SQL file in the DB.
     *
     * @category   Database
     *
     * @param $full_path_to_file
     *
     * @return bool
     */
    public function import_sql_file($full_path_to_file)
    {
        $dbms_schema = $full_path_to_file;

        if (is_file($dbms_schema)) {
            $prefix = get_table_prefix();
            $sql_query = fread(fopen($dbms_schema, 'r'), filesize($dbms_schema)) or die('problem ' . __FILE__ . __LINE__);
            $sql_query = str_ireplace('{MW_TABLE_PREFIX}', $prefix, $sql_query);
            $sql_query = $this->remove_sql_remarks($sql_query);
            $sql_query = $this->remove_comments_from_sql_string($sql_query);
            $sql_query = $this->split_sql_file($sql_query, ';');

            $i = 1;
            foreach ($sql_query as $sql) {
                $sql = trim($sql);
                DB::statement($sql);
            }

            return true;
        } else {
            return false;
        }
    }

    public function remove_sql_remarks($sql)
    {
        $lines = explode("\n", $sql);
        $sql = '';
        $linecount = count($lines);
        $output = '';
        for ($i = 0; $i < $linecount; ++$i) {
            if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0)) {
                if (isset($lines[$i][0]) && $lines[$i][0] != '#') {
                    $output .= $lines[$i] . "\n";
                } else {
                    $output .= "\n";
                }
                $lines[$i] = '';
            }
        }

        return $output;
    }

    /**
     * Will strip the sql comment lines out of an given sql string.
     *
     * @param $output the SQL string with comments
     *
     * @return string $output the SQL string without comments
     *
     * @example
     * <code>
     *  sql_remove_comments($sql_str);
     * </code>
     */
    public function remove_comments_from_sql_string($output)
    {
        $lines = explode("\n", $output);
        $output = '';
        $linecount = count($lines);
        $in_comment = false;
        for ($i = 0; $i < $linecount; ++$i) {
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

    public function query_log()
    {
        return DB::getQueryLog();
    }

    public function split_sql_file($sql, $delimiter)
    {
        // Split up our string into "possible" SQL statements.
        $tokens = explode($delimiter, $sql);
        // try to save mem.
        $sql = '';
        $output = array();
        // we don't actually care about the matches preg gives us.
        $matches = array();
        // this is faster than calling count($oktens) every time thru the loop.
        $token_count = count($tokens);
        for ($i = 0; $i < $token_count; ++$i) {
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
                    $tokens[$i] = '';
                } else {
                    // incomplete sql statement. keep adding tokens until we have a complete one.
                    // $temp will hold what we have so far.
                    $temp = $tokens[$i] . $delimiter;
                    // save memory..
                    $tokens[$i] = '';
                    // Do we have a complete statement yet?
                    $complete_stmt = false;
                    for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); ++$j) {
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
                            $tokens[$j] = '';
                            $temp = '';

                            // exit the loop.
                            $complete_stmt = true;
                            // make sure the outer loop continues at the right point.
                            $i = $j;
                        } else {
                            // even number of unescaped quotes. We still don't have a complete statement.
                            // (1 odd and 1 even always make an odd)
                            $temp .= $tokens[$j] . $delimiter;
                            // save memory.
                            $tokens[$j] = '';
                        }
                    } // for..
                } // else
            }
        }
        $output = preg_replace('/\x{EF}\x{BB}\x{BF}/', '', $output);

        return $output;
    }
}
