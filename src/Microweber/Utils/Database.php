<?php

namespace Microweber\Utils;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;


/**
 * Database Utils class
 *
 * Query helper class
 *
 * @package Database
 * @category Database
 * @desc Various utils functions to work with the database
 *
 * @property \Microweber\Application $app
 */
class Database
{
    public $cache_minutes = 60;

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
            $value = Cache::get($key);
            if (!$value) {
                $value = 1;
                $minutes = $this->cache_minutes;
                Cache::put($key, $value, $minutes);
                return $this->_exec_table_builder($table_name, $fields_to_add);
            } else {
                return $value;
            }
        }


        return $this->_exec_table_builder($table_name, $fields_to_add);
    }


    private function _exec_table_builder($table_name, $fields_to_add)
    {
        $table_name = $this->assoc_table_name($table_name);
        if (!Schema::hasTable($table_name)) {
            Schema::create($table_name, function ($table) {
                $table->increments('id');
            });
        }
        if (is_array($fields_to_add)) {
            Schema::table($table_name, function ($schema) use ($fields_to_add, $table_name) {
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
                        if (!Schema::hasColumn($table_name, $name)) {
                            $fluent = $schema->$type($name);
                            if ($is_default !== null) {
                                $fluent->default($is_default)->nullable();
                            }
                            if ($is_nullable) {
                                $fluent->nullable();
                            }
                        }
                    }
                }
            });
        }
    }


    public function assoc_table_name($assoc_name)
    {
        $config_prefix = $this->get_prefix();
        $assoc_name = str_ireplace($config_prefix, '', $assoc_name);
        return $assoc_name;
    }

    public function get_tables_list()
    {
        $tables = array();
        $engine = $this->get_sql_engine();
        if ($engine != 'sqlite') {
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
        } else {
            $sql = DB::select("SELECT * FROM sqlite_master WHERE type='table';");
            if (is_array($sql) and !empty($sql)) {
                foreach ($sql as $item) {
                    $item = (array)$item;
                    if (isset($item['tbl_name'])) {
                        $tables[] = $item['tbl_name'];
                    } elseif (isset($item['name'])) {
                        $tables[] = $item['name'];
                    }
                }
            }
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

        $fields = $this->get_fields($table);

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

    public function get_fields($table)
    {
        static $ex_fields_static;
        if (isset($ex_fields_static[$table])) {
            return $ex_fields_static[$table];
        }
        $expiresAt = 300;

        $cache_group = 'db/fields';
        if (!$table) {
            return false;
        }
        $key = 'mw_db_get_fields' . crc32($table);
        $hash = $table;
        $value = $this->app->cache_manager->get($key, 'db', $expiresAt);

        if (isset($value[$hash])) {
            return $value[$hash];
        }
        $fields = DB::connection()->getSchemaBuilder()->getColumnListing($table);

        // TODO: Temp fix for Laravel
        if (count($fields) && !is_string($fields[0]) && (isset($fields[0]->name) or isset($fields[0]->column_name))) {
            $fields = array_map(function ($f) {
                if (isset($f->column_name)) {
                    return $f->column_name;
                } else {
                    return $f->name;
                }
            }, $fields);
        }

        // Caching
        $ex_fields_static[$table] = $fields;
        $value[$hash] = $fields;
        $this->app->cache_manager->save($value, $key, $cache_group);

        return $fields;
    }

    public function guess_cache_group($group)
    {
        return $group;
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
                    DB::statement($q);
                }
                $i++;
            }
        }
        $cache_group = $this->assoc_table_name($table);
        $this->app->cache_manager->delete($cache_group);
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
            $index = " INDEX ";
            //FULLTEXT
        }
        if ($query == false) {
            $q = "ALTER TABLE " . $aTable . " ADD $index `" . $aIndexName . "` (" . $columns . ");";
            $this->q($q);
        }
        $this->app->cache_manager->save('--true--', $function_cache_id, $cache_group);
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

    public function query_log()
    {
        return DB::getQueryLog();
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
        $output = preg_replace('/\x{EF}\x{BB}\x{BF}/', '', $output);
        return $output;
    }

}
