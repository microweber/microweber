<?php


namespace mw;


class DbUtils
{

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
    static function mass_save($get_params, $save_params = false)
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

    static function table_exist($table)
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
    static function copy_row_by_id($table, $id = 0, $field_name = 'id')
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

    static function update_position_field($table, $data = array())
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


    static function get_tables()
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
    static function remove_comments_from_sql_string($output)
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


    //
// remove_remarks will strip the sql comment lines out of an uploaded sql file
//
    static function remove_sql_remarks($sql)
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



    static function import_sql_file($full_path_to_file)
    {

        $dbms_schema = $full_path_to_file;

        if (is_file($dbms_schema)) {
            $sql_query = fread(fopen($dbms_schema, 'r'), filesize($dbms_schema)) or die('problem ');
            $sql_query = str_ireplace('{MW_TABLE_PREFIX}', MW_TABLE_PREFIX, $sql_query);
            $sql_query = self::remove_sql_remarks($sql_query);

            $sql_query = self::remove_comments_from_sql_string($sql_query);
            $sql_query = self::split_sql_file($sql_query, ';');

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
// split_sql_file will split an uploaded sql file into single sql statements.
// Note: expects trim() to have already been run on $sql.
//
    static function split_sql_file($sql, $delimiter)
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