<?php

function reduce_double_slashes($str) {
    return preg_replace("#([^:])//+#", "\\1/", $str);
}

//copy_directory('dirnameSource','dirnameDestination');

function copy_directory($source, $destination) {
    if (is_dir($source)) {
        @mkdir($destination);
        $directory = dir($source);
        while (FALSE !== ( $readdirectory = $directory->read() )) {
            if ($readdirectory == '.' || $readdirectory == '..') {
                continue;
            }
            $PathDir = $source . '/' . $readdirectory;
            if (is_dir($PathDir)) {
                copy_directory($PathDir, $destination . '/' . $readdirectory);
                continue;
            }
            copy($PathDir, $destination . '/' . $readdirectory);
        }

        $directory->close();
    } else {
        copy($source, $destination);
    }
}

/**
 * Makes directory recursive, returns TRUE if exists or made and false on error
 *
 * @param string $pathname
 *        	The directory path.
 * @return boolean returns TRUE if exists or made or FALSE on failure.
 */
function mkdir_recursive($pathname) {
    if ($pathname == '') {
        return false;
    }
    is_dir(dirname($pathname)) || mkdir_recursive(dirname($pathname));
    return is_dir($pathname) || @ mkdir($pathname);
}

function array_rpush($arr, $item) {
    $arr = array_pad($arr, - (count($arr) + 1), $item);
    return $arr;
}

/**
 * Converts a path in the appropriate format for win or linux
 *
 * @param string $path
 *        	The directory path.
 * @param boolean $slash_it
 *        	If true, ads a slash at the end, false by default
 * @return string The formated string
 */
function normalize_path($path, $slash_it = true) {
    // DIRECTORY_SEPARATOR is a system variable
    // which contains the right slash for the current
    // system (windows = \ or linux = /)
    $path_original = $path;
    $s = DIRECTORY_SEPARATOR;
    $path = preg_replace('/[\/\\\]/', $s, $path);
    // $path = preg_replace ( '/' . $s . '$/', '', $path ) . $s;
    $path = str_replace($s . $s, $s, $path);
    if (strval($path) == '') {
        $path = $path_original;
    }
    if ($slash_it == false) {
        $path = rtrim($path, DIRECTORY_SEPARATOR);
    } else {
        $path .= DIRECTORY_SEPARATOR;
        $path = rtrim($path, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR);
    }
    if (strval(trim($path)) == '' or strval(trim($path)) == '/') {
        $path = $path_original;
    }
    if ($slash_it == false) {

    } else {
        $path = $path . DIRECTORY_SEPARATOR;
        $path = reduce_double_slashes($path);
        // $path = rtrim ( $path, DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR );
    }
    return $path;
}

function string_clean($var) {
    if (is_array($var)) {
        foreach ($var as $key => $val) {
            $output [$key] = string_clean($val);
        }
    } else {
        $var = html_entity_decode($var);
        $var = strip_tags(trim($var));
        if (function_exists("mysql_real_escape_string")) {
            // $output = mysql_real_escape_string ( $var );
        } else {

        }
        $output = stripslashes($var);
    }
    if (!empty($output))
        return $output;
}

function object_2_array($result) {
    $array = array();
    foreach ($result as $key => $value) {
        if (is_object($value)) {
            $array [$key] = object_2_array($value);
        }
        if (is_array($value)) {
            $array [$key] = object_2_array($value);
        } else {
            $array [$key] = $value;
        }
    }
    return $array;
}

/**
 * Remove Invisible Characters
 *
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 *
 * @access public
 * @param
 *        	string
 * @return string
 */
if (!function_exists('remove_invisible_characters')) {

    function remove_invisible_characters($str, $url_encoded = TRUE) {
        $non_displayables = array();

        // every control character except newline (dec 10)
        // carriage return (dec 13), and horizontal tab (dec 09)

        if ($url_encoded) {
            $non_displayables [] = '/%0[0-8bcef]/'; // url encoded 00-08, 11, 12,
            // 14, 15
            $non_displayables [] = '/%1[0-9a-f]/'; // url encoded 16-31
        }

        $non_displayables [] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S'; // 00-08,
        // 11, 12,
        // 14-31,
        // 127

        do {
            $str = preg_replace($non_displayables, '', $str, - 1, $count);
        } while ($count);

        return $str;
    }

}

function add_slashes_to_array($arr) {






    if (!empty($arr)) {

        $ret = array();

        foreach ($arr as $k => $v) {

            if (is_array($v)) {

                $v = add_slashes_to_array($v);
            } else {
                // $v =utfString( $v );
                // $v =
                // preg_replace("/[^[:alnum:][:space:][:alpha:][:punct:]]/","",$v);
                // $v = htmlentities ( $v, ENT_NOQUOTES, 'UTF-8' );
                // $v = htmlspecialchars ( $v );
                $v = htmlspecialchars($v);
            }

            $ret [$k] = ($v);
        }

        return $ret;
    }
}

function ago($time, $granularity = 2) {
    $date = strtotime($time);
    $difference = time() - $date;
    $retval = '';
    $periods = array('decade' => 315360000, 'year' => 31536000, 'month' => 2628000, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'minute' => 60, 'second' => 1);
    foreach ($periods as $key => $value) {
        if ($difference >= $value) {
            $time = floor($difference / $value);
            $difference %= $value;
            $retval .= ($retval ? ' ' : '') . $time . ' ';
            $retval .= (($time > 1) ? $key . 's' : $key);
            $granularity--;
        }
        if ($granularity == '0') {
            break;
        }
    }
    return '' . $retval . ' ago';
}

function debug_info() {
    if (c('debug_mode')) {

        return include (ADMIN_VIEWS_PATH . 'debug.php');
    }
}

function remove_slashes_from_array($arr) {
    if (!empty($arr)) {

        $ret = array();

        foreach ($arr as $k => $v) {

            if (is_array($v)) {

                $v = remove_slashes_from_array($v);
            } else {

                $v = htmlspecialchars_decode($v);
                // $v = htmlspecialchars_decode ( $v );
                // $v = html_entity_decode ( $v, ENT_NOQUOTES );
                // $v = html_entity_decode( $v );
                $v = stripslashes($v);
            }

            $ret [$k] = $v;
        }

        return $ret;
    }
}

if (!function_exists('pathToURL')) {

    function pathToURL($path) {
        // var_dump($path);
        $path = str_ireplace(ROOTPATH, '', $path);
        $path = str_ireplace('\\', '/', $path);
        // var_dump($path);
        return site_url($path);
    }

}
if (!function_exists('dir2url')) {

    function dir2url($path) {
        return pathToURL($path);
    }

}
if (!function_exists('dirToURL')) {

    function dirToURL($path) {
        return pathToURL($path);
    }

}

function encode_var($var) {
    if ($var == '') {
        return '';
    }

    $var = serialize($var);
    $var = base64_encode($var);
    return $var;
}

function decode_var($var) {
    if ($var == '') {
        return '';
    }
    $var = base64_decode($var);
    $var = unserialize($var);
    return $var;
}

/**
 * Trims a entire array recursivly.
 *
 * @author Jonas John
 * @version 0.2
 * @link http://www.jonasjohn.de/snippets/php/trim-array.htm
 * @param array $Input
 *        	Input array
 */
function array_trim($Input) {
    if (!is_array($Input))
        return trim($Input);
    return array_map('array_trim', $Input);
}

function safe_redirect($url) {
    if (trim($url) == '') {
        return false;
    }
    $url = str_ireplace('Location:', '', $url);
    $url = trim($url);
    if (headers_sent()) {
        print '<meta http-equiv="refresh" content="0;url=' . $url . '">';
    } else {
        header('Location: ' . $url);
    }
    exit();
}

function session_set($name, $val) {
    if (!headers_sent()) {
        if (!isset($_SESSION)) {
            session_start();
        }
        if ($val == false) {
            session_del($name);
        } else {
            $_SESSION [$name] = $val;
        }
    }
}

function session_get($name) {
    if (!headers_sent()) {
        if (!isset($_SESSION)) {
            session_start();
        }
    }
    // d($_SESSION[$name]);
    if (isset($_SESSION[$name])) {
        return $_SESSION [$name];
    } else {
        return false;
    }
}

function session_del($name) {
    if (isset($_SESSION [$name])) {
        unset($_SESSION [$name]);
    }
}

function session_end() {
    $_SESSION = array();
    session_destroy();
}

function recursive_remove_directory($directory, $empty = true) {

    // if the path has a slash at the end we remove it here
    if (substr($directory, - 1) == DIRECTORY_SEPARATOR) {

        $directory = substr($directory, 0, - 1);
    }

    // if the path is not valid or is not a directory ...
    if (!file_exists($directory) || !is_dir($directory)) {

        // ... we return false and exit the function
        return FALSE;

        // ... if the path is not readable
    } elseif (!is_readable($directory)) {

        // ... we return false and exit the function
        return FALSE;

        // ... else if the path is readable
    } else {

        // we open the directory
        $handle = opendir($directory);

        // and scan through the items inside
        while (FALSE !== ($item = readdir($handle))) {

            // if the filepointer is not the current directory
            // or the parent directory
            if ($item != '.' && $item != '..') {

                // we build the new path to delete
                $path = $directory . DIRECTORY_SEPARATOR . $item;

                // if the new path is a directory
                if (is_dir($path)) {
                    // we call this function with the new path
                    recursive_remove_directory($path);
                    // if the new path is a file
                } else {
                    $path = normalize_path($path, false);
                    try {
                       
                        unlink($path);
                    } catch (Exception $e) {

                    }
                }
            }
        }

        // close the directory
        closedir($handle);

        // if the option to empty is not set to true
        if ($empty == FALSE) {

            // try to delete the now empty directory
            if (!rmdir($directory)) {

                // return false if not possible
                return FALSE;
            }
        }

        // return success
        return TRUE;
    }
}

/**
 * Recursive glob()
 */

/**
 * @param int $pattern
 * the pattern passed to glob()
 * @param int $flags
 * the flags passed to glob()
 * @param string $path
 * the path to scan
 * @return mixed
 * an array of files in the given path matching the pattern.
 */
function rglob($pattern = '*', $flags = 0, $path = '') {
    $paths = glob($path . '*', GLOB_MARK | GLOB_ONLYDIR | GLOB_NOSORT);
    $files = glob($path . $pattern, $flags);
    foreach ($paths as $path) {
        $files = array_merge($files, rglob($pattern, $flags, $path));
    }
    return $files;
}

// ------------------------------------------------------------------------

/**
 * Create a Directory Map
 *
 *
 * Reads the specified directory and builds an array
 * representation of it.  Sub-folders contained with the
 * directory will be mapped as well.
 *
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/directory_helper.html
 * @access	public
 * @param	string	path to source
 * @param	int		depth of directories to traverse (0 = fully recursive, 1 = current dir, etc)
 * @return	array
 */
function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE) {
    if ($fp = @opendir($source_dir)) {
        $filedata = array();
        $new_depth = $directory_depth - 1;
        $source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        while (FALSE !== ($file = readdir($fp))) {
            // Remove '.', '..', and hidden files [optional]
            if (!trim($file, '.') OR ($hidden == FALSE && $file[0] == '.')) {
                continue;
            }

            if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir . $file)) {
                $filedata[$file] = directory_map($source_dir . $file . DIRECTORY_SEPARATOR, $new_depth, $hidden);
            } else {
                $filedata[] = $file;
            }
        }

        closedir($fp);
        return $filedata;
    }

    return FALSE;
}

function percent($num_amount, $num_total) {
    $count1 = $num_amount / $num_total;
    $count2 = $count1 * 100;
    $count = number_format($count2, 0);
    echo $count;
}

function lipsum() {
    $arr = array(
        "In eget nibh sit amet dui molestie sodales. Pellentesque pretium sollicitudin enim, ac molestie libero tempus quis. Etiam eu mauris euismod tellus aliquam fermentum. Aenean commodo consectetur mi, ut pellentesque sapien pharetra quis. Etiam iaculis dapibus nisl. Pellentesque sit amet leo enim. Aenean lacinia, tortor auctor placerat gravida, neque dui condimentum erat, ut vehicula leo sem sed erat. Nullam vitae justo id tellus commodo posuere. Sed eget vulputate turpis. Praesent rutrum laoreet ipsum, vel fermentum ipsum placerat nec. Mauris ipsum nulla, tincidunt eget placerat eget, varius ut augue. Morbi lacinia aliquam fermentum. Donec lobortis tincidunt odio vel ullamcorper. Nulla tincidunt, metus ac scelerisque consequat, dui turpis ultrices metus, eu lobortis dui est quis nisi. Nullam aliquet, sapien at facilisis cursus, odio neque aliquam nisl, eget venenatis urna enim et odio.",
        "Cras sagittis venenatis ipsum, quis lacinia lacus elementum eu. Nam in massa odio. Duis mollis, sem ut laoreet aliquet, eros mi aliquet dui, vel hendrerit nisl purus non neque. Nam sit amet mauris a orci iaculis semper et eu velit. Nulla aliquet scelerisque tellus et porta. Duis sed nulla eros, ut volutpat ligula. Nullam et pharetra erat. Sed gravida porttitor purus, quis malesuada mauris scelerisque id. Suspendisse adipiscing placerat neque et convallis. Aliquam condimentum eleifend velit vel viverra. Donec viverra diam eu lorem laoreet vel pellentesque augue hendrerit. Praesent leo tellus, consectetur at eleifend quis, posuere in mi. Proin mollis interdum urna at fermentum.",
        "Aenean porta vestibulum elit, quis sodales risus lobortis vitae. Mauris augue quam, tincidunt ut scelerisque ut, vulputate et dui. Pellentesque viverra felis non magna mollis porttitor. Curabitur faucibus scelerisque neque a hendrerit. Vivamus nec odio nibh. Ut vitae ipsum justo, vel laoreet nisi. In hac habitasse platea dictumst. In euismod scelerisque varius. Nulla facilisi. Sed felis quam, auctor eu volutpat id, pretium a massa. Donec feugiat dui in est auctor facilisis. Cras consequat dictum auctor. Proin non nunc vitae sem bibendum convallis. Aliquam pharetra cursus augue.",
        "Sed vel dolor a dolor dapibus fermentum ut ut nunc. Aenean sit amet diam ac elit bibendum imperdiet at at risus. Pellentesque eu tellus et quam fringilla viverra. Cras mauris massa, imperdiet vel sodales nec, laoreet a libero. Mauris et ligula scelerisque diam tincidunt malesuada ac eget neque. Fusce ut pharetra justo. Donec fringilla blandit orci, in imperdiet lectus accumsan at. Morbi a sem justo. Vivamus tincidunt semper ullamcorper. Nulla facilisi. Aenean sed leo magna, ut gravida turpis. Maecenas congue enim vel eros tempor sagittis.",
        "Nulla ut elit dui, a vehicula felis. Curabitur commodo egestas eros id venenatis. Donec ultricies nisl vitae sem venenatis vitae hendrerit lacus imperdiet. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Maecenas vitae erat in leo pretium luctus placerat nec tortor. Donec ipsum justo, congue tincidunt mattis vel, lacinia a tortor. Suspendisse potenti. Pellentesque sodales imperdiet nisl ut pulvinar. Nam aliquam massa nec justo convallis sollicitudin. Mauris ac nisl ante, vel placerat elit. Sed id metus diam. Morbi sagittis imperdiet neque, porta gravida diam placerat in. Quisque lectus risus, suscipit ut ultricies id, luctus nec mauris. Curabitur egestas dapibus dignissim. Fusce vitae augue sit amet magna condimentum faucibus vel vel nunc.",
        "Donec aliquam nulla tincidunt lorem auctor condimentum. Ut commodo luctus posuere. Pellentesque quam nisl, faucibus ut euismod at, consequat congue diam. Etiam bibendum bibendum leo, ut convallis tellus commodo eu. Nulla sit amet tellus dolor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Etiam nisi lectus, ultricies pretium pretium dignissim, rutrum in lectus. Duis nec tortor arcu. Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        "Vestibulum ut mauris nisl, ut vulputate risus. Cras non lectus leo, quis laoreet nulla. Sed id mollis dolor. Quisque cursus, ligula vitae rhoncus eleifend, odio eros eleifend metus, in dignissim eros arcu et velit. Nulla id velit velit. Fusce pharetra felis sed elit blandit et faucibus neque fermentum. Vivamus ultricies massa vel sem pharetra molestie.",
        "Aliquam gravida cursus urna, vel ornare justo sagittis quis. Donec gravida dapibus diam, non egestas augue posuere quis. Sed bibendum imperdiet vehicula. Ut viverra libero et dolor vulputate consectetur. Nullam ultricies interdum consequat. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nunc tristique sollicitudin leo, id scelerisque mauris ultrices quis. Vivamus imperdiet, massa non commodo ornare, ipsum mauris tristique nisi, vel viverra lectus nibh tempus elit.",
        "Nunc egestas neque mollis tortor viverra eu faucibus lectus euismod. Suspendisse elit erat, porta sed lobortis vitae, consequat vitae purus. Vivamus ornare tincidunt congue. Pellentesque interdum diam a diam volutpat non iaculis purus aliquam. Quisque tristique imperdiet adipiscing. Proin dapibus, tortor sed condimentum lobortis, dui nisi rutrum eros, eget euismod metus massa id lorem. Aenean eleifend, dolor auctor luctus mattis, diam justo feugiat mi, dignissim cursus nisi tortor sit amet magna. In odio dui, rutrum eu iaculis sit amet, auctor eu mi. Donec ut massa libero. Ut lectus erat, lobortis quis ultrices ut, vestibulum placerat erat. In sed justo mauris, ac tincidunt orci. Curabitur bibendum, arcu ut vestibulum lobortis, enim eros accumsan nulla, aliquam laoreet eros velit nec nunc. Donec et blandit ante. Fusce ullamcorper, nunc eget viverra suscipit, erat dolor vehicula eros, nec lacinia ligula ante ut dui.",
        "Nam quis risus eget diam dapibus eleifend sed eget risus. Fusce accumsan interdum enim. Vestibulum sit amet tempor augue. Nunc rhoncus nunc sed odio volutpat eu malesuada velit venenatis. Nam ornare sem non dolor dictum volutpat. Ut fringilla tincidunt aliquet. Quisque consequat odio quis tortor pharetra ac ultrices tortor posuere. Aenean accumsan eros id urna venenatis convallis. Aliquam tempus felis vitae nunc auctor at luctus sem luctus.",
        "Morbi malesuada tellus eget nulla dignissim volutpat. Suspendisse dolor odio, vestibulum at posuere eget, accumsan in ipsum. Nullam sed lacus eu dui porttitor iaculis. Aenean et condimentum tellus. In ultricies sapien at risus mollis placerat. Suspendisse id sapien scelerisque ligula blandit imperdiet. Pellentesque non magna at nisi lobortis gravida. Proin accumsan tincidunt libero, sed imperdiet sapien tincidunt ac. Donec non massa id lacus feugiat facilisis. Sed fermentum accumsan nulla id vulputate. Vivamus facilisis accumsan nulla vel sollicitudin. Curabitur commodo posuere nibh, a suscipit nibh congue at. Phasellus faucibus ornare felis, nec auctor augue pretium id. Curabitur volutpat, est nec adipiscing pharetra, metus dolor consectetur odio, nec aliquet metus magna vel felis. Cras ante nulla, scelerisque non pretium eget, cursus ut mauris.",
        "Nunc sit amet dolor erat, eget convallis lorem. Vivamus congue mi vitae nunc sagittis faucibus. Vivamus convallis erat et nisi pulvinar condimentum volutpat diam posuere. Nulla ac placerat neque. Fusce in purus vel nulla facilisis lacinia. Integer aliquet commodo orci, a adipiscing sem commodo a. Aliquam egestas feugiat nulla sit amet facilisis. Nullam interdum adipiscing bibendum.",
        "Sed nec libero lacus, sed ornare tortor. Fusce interdum, nibh ac rutrum hendrerit, mi ligula mattis elit, adipiscing fringilla massa erat quis dolor. Cras sit amet metus eget eros consequat convallis quis ac ante. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent lacinia nunc euismod orci hendrerit vel hendrerit leo gravida. Nullam at nunc diam, at tempor ipsum. Proin fermentum consequat nibh at aliquam. Integer magna neque, malesuada sit amet hendrerit ac, fringilla non neque. Fusce porta, velit a dignissim volutpat, nunc ligula accumsan sem, eu pretium lacus ipsum ullamcorper urna. Duis quis nibh in lacus eleifend gravida ut vel dolor.",
        "Sed ac ligula vitae odio gravida mollis. Suspendisse risus massa, rhoncus vitae volutpat sed, euismod sed tellus. Nam justo urna, volutpat ac gravida viverra, facilisis nec diam. Integer iaculis euismod porta. Suspendisse eu risus at sapien condimentum dapibus. Etiam viverra erat in velit euismod condimentum. Ut sagittis felis nec arcu imperdiet iaculis rhoncus magna eleifend. Ut ullamcorper dui dui, sed porttitor purus.",
        "In gravida diam eget lectus mattis id pretium metus laoreet. Nulla diam eros, pulvinar sit amet hendrerit sit amet, pretium ut dui. Nullam diam arcu, laoreet sit amet elementum quis, malesuada non arcu. Vestibulum condimentum lobortis eros ut elementum. Pellentesque hendrerit dignissim tortor, ut pellentesque magna vulputate ut. Vivamus aliquam ultricies tincidunt. Etiam erat eros, vestibulum quis varius viverra, suscipit vitae magna. Sed ultrices lacus in leo sollicitudin ut laoreet purus pharetra. Vestibulum mi sapien, sollicitudin non condimentum non, ullamcorper eu neque. Donec et ipsum vitae risus iaculis hendrerit. Proin non accumsan turpis.",
        "Praesent tincidunt erat euismod metus porta vehicula. Proin fringilla orci egestas diam vulputate porta. Pellentesque faucibus odio ac libero volutpat venenatis pharetra mi faucibus. Sed et leo eget metus molestie mattis a non ante. Praesent eget felis vitae metus convallis sodales eget at ante. Nullam orci erat, tincidunt vitae sagittis eget, consequat a nisl. Donec non ligula turpis, et ultricies sapien. Duis congue, erat id tincidunt semper, augue velit hendrerit tellus, nec eleifend dolor velit sed felis. Donec porttitor arcu nulla, at feugiat dolor. Cras tristique velit at turpis hendrerit commodo.",
        "Cras egestas ipsum nec justo vestibulum nec pretium turpis condimentum. Curabitur ante felis, tempus in malesuada a, fringilla at magna. Suspendisse dignissim, purus ut tempor dignissim, nisl quam semper metus, ut commodo nunc lectus elementum ligula. Duis augue justo, consequat id viverra a, euismod a lectus. Curabitur a nulla eu tortor dignissim viverra eu non eros. Pellentesque id nisl nec velit ultrices aliquam at a metus. Etiam faucibus molestie venenatis. Nunc mollis porttitor luctus. Duis quis mi nec diam tristique sodales eu at nisi. Aenean aliquet, tortor a pulvinar varius, nibh eros tempor lorem, ut facilisis ipsum lacus aliquam magna.",
        "Aenean suscipit semper metus vitae scelerisque. Nam convallis laoreet dui facilisis eleifend. Fusce sagittis ornare ipsum sit amet malesuada. Sed euismod accumsan sapien, tempus gravida sem posuere nec. In quam risus, adipiscing vitae ornare lacinia, pellentesque id nisl. Vestibulum iaculis varius iaculis. Suspendisse potenti.",
        "Sed sed velit elit, a tincidunt felis. Ut eget ligula ut dui euismod adipiscing a eu orci. Integer vel tempus est. Donec semper, enim vel viverra tempus, est erat viverra nisi, vel sodales lacus velit in elit. Aenean nec ante diam. Duis adipiscing, nisl vehicula mollis convallis, tellus neque placerat massa, non lacinia ante metus eu lacus. Nam vehicula purus tellus, non eleifend enim. Aliquam sollicitudin justo ut sapien semper quis scelerisque odio porttitor. Nulla faucibus justo eros, id bibendum turpis. Morbi ante nulla, venenatis in dictum et, ullamcorper faucibus turpis. In ultricies nunc nec arcu volutpat at fringilla nibh venenatis. Aenean hendrerit tortor sit amet urna congue egestas. Integer sagittis dolor euismod turpis dapibus eu imperdiet enim ullamcorper.",
        "Phasellus non tortor bibendum nisi luctus lacinia nec vitae velit. Aenean mattis, leo non rhoncus posuere, est tellus feugiat quam, non gravida ante nisl id justo. Suspendisse euismod enim interdum urna ultrices dictum blandit est consectetur. Suspendisse magna velit, imperdiet et congue id, bibendum ac augue. Mauris ac vehicula risus. Nullam congue nisl non felis feugiat volutpat. Nulla dictum, diam porttitor rhoncus dignissim, nisi felis euismod ligula, eget viverra erat sapien sit amet massa. Curabitur ac ligula sem. Sed arcu erat, fermentum in viverra id, ornare quis eros. Pellentesque ultricies dui et dui sollicitudin blandit. Suspendisse ac massa eu nibh facilisis tristique quis sit amet odio. Fusce bibendum semper quam a dictum. Morbi justo diam, ultricies sed malesuada vitae, venenatis eget nulla.",
        "In pretium augue nec orci rhoncus non suscipit urna ullamcorper. Aliquam iaculis neque in lorem mollis eleifend. Proin sed eros ipsum. Aenean ligula purus, egestas et sagittis non, eleifend eget nulla. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nam eu mauris erat, ac sollicitudin tellus. Sed tristique, enim ut tincidunt hendrerit, eros ligula elementum nibh, ut luctus erat tellus iaculis odio. Quisque ut purus sem, sed sollicitudin ante. Ut vitae diam dolor, a commodo velit.",
        "Nam condimentum leo id risus egestas vitae lacinia elit imperdiet. Aenean sed sodales tellus. Cras in nulla at ipsum sodales ultrices. Donec nisi sem, vehicula sit amet porta vel, consectetur hendrerit urna. Mauris ultrices, sem et tempus facilisis, lacus nibh porta justo, eget iaculis urna augue dictum arcu. Integer purus lacus, tincidunt in pulvinar at, vestibulum auctor ante. Nunc erat leo, tincidunt eget consectetur id, varius a quam. Duis faucibus, justo eu viverra dictum, dui nibh laoreet nulla, sit amet accumsan magna dolor at erat. Praesent nunc sapien, auctor at accumsan et, suscipit quis sem. Aliquam in est nec ligula aliquam molestie. Duis tincidunt odio non ligula commodo tempor. Proin fringilla rutrum cursus. Donec pretium placerat lacus vel hendrerit. Phasellus mauris nisi, feugiat id eleifend at, fringilla nec nulla. Nam ut ipsum sit amet nibh ornare auctor.",
        "Sed pellentesque laoreet lorem ac luctus. Integer vehicula tortor a risus ornare ullamcorper. Nunc eget nisl tellus. Sed quis placerat tortor. Sed vel elit non tellus aliquet iaculis eget ac magna. Maecenas id justo id turpis aliquam tempus. Morbi vestibulum feugiat nibh in faucibus. Suspendisse potenti. Etiam leo purus, rhoncus at condimentum nec, pharetra at nunc. In id lorem ipsum.",
        "Duis quis tortor mauris. Donec augue tortor, faucibus non ornare nec, pulvinar eu mi. Morbi pulvinar hendrerit iaculis. Vestibulum a lorem leo, sit amet volutpat massa. Nunc vitae turpis eros, at dictum magna. Nulla vitae magna vel risus rhoncus hendrerit. Nulla massa turpis, porttitor non egestas in, venenatis id ipsum. Sed sodales sollicitudin mollis. Morbi quis mi eros. Proin in justo magna.",
        "Duis et nunc nisl. Cras et tincidunt nulla. Nulla facilisi. Nunc ultricies lectus et purus blandit blandit. Nam ornare mauris tempus magna pellentesque faucibus. Praesent pharetra, mi tincidunt viverra sagittis, massa erat auctor nulla, vel semper purus neque non metus. Donec quam sem, ultricies in semper ut, lacinia ut lectus. Cras id pharetra felis. Integer malesuada, sapien quis rutrum semper, dolor dolor fringilla risus, ac vehicula purus est id turpis. Vivamus a libero erat. Praesent rutrum dignissim purus, sit amet semper lorem dapibus eget. Duis ac eros nisi, nec varius libero. Fusce rutrum mattis sem, et condimentum leo eleifend et. Suspendisse vehicula neque eu erat elementum egestas dignissim tortor pellentesque.",
        "Nunc vitae enim dolor. Aliquam pharetra pretium ligula, convallis posuere magna vestibulum sed. Sed pellentesque vulputate dapibus. Maecenas et nulla eu felis facilisis volutpat. Vivamus faucibus tortor non urna sollicitudin eu pulvinar arcu consectetur. Nunc quis metus nisi. Sed adipiscing tincidunt eros, sit amet iaculis risus suscipit eget.",
        "Nulla et nunc leo. Aenean egestas nisl quis enim posuere venenatis. Aenean nulla nunc, commodo accumsan convallis et, hendrerit quis ipsum. Morbi nec convallis tellus. Nunc dictum sapien non turpis pharetra placerat. Quisque felis risus, mattis in rutrum in, mattis quis leo. Praesent dignissim mattis tempus. Vestibulum non nibh non mi cursus rhoncus vel a tortor. Vestibulum imperdiet imperdiet ligula, vitae tincidunt odio semper sed. Nullam consectetur vulputate libero in volutpat. Nullam eu ipsum mauris, in pulvinar lacus.",
        "Fusce massa eros, consequat eu viverra in, adipiscing ut justo. Curabitur vitae rhoncus est. Suspendisse dignissim scelerisque bibendum. Nunc vel eros a erat aliquam gravida eget at orci. Praesent ornare, dolor non mollis laoreet, tellus velit sagittis mi, in consequat sapien mi consectetur velit. Donec porta, nulla a luctus fringilla, ante lorem sollicitudin tortor, nec blandit libero turpis vitae felis. Nulla interdum euismod diam, et convallis elit euismod et. Aenean id diam lacus. Nunc sit amet neque sit amet tortor adipiscing pulvinar. Ut lobortis laoreet iaculis. Nam gravida, justo sed euismod rhoncus, nisi nibh viverra neque, bibendum faucibus ligula urna ac ante. Etiam libero eros, venenatis vel mollis vitae, laoreet sit amet metus. Suspendisse tempus faucibus imperdiet.",
        "Fusce condimentum nisl non nisl mollis fringilla. Fusce malesuada convallis est at lobortis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla malesuada cursus porttitor. Mauris ac urna nec nisl dapibus commodo. Praesent id neque at eros pulvinar euismod et a mauris. Mauris dictum, augue non venenatis fringilla, enim quam tincidunt turpis, quis molestie odio ante iaculis nisi. Ut sed tempus ipsum. Aliquam faucibus bibendum sollicitudin. Vestibulum a libero enim. Nunc semper nisl sed felis rhoncus id imperdiet ligula mollis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Aliquam erat volutpat. Quisque eleifend ullamcorper est, et euismod nunc facilisis non. Maecenas posuere enim et lacus commodo ac vestibulum diam viverra.",
        "Nullam vel tortor vel mi suscipit dignissim ac dignissim lorem. Donec imperdiet mauris id lorem placerat gravida. Vestibulum volutpat egestas dolor eu rhoncus. Sed aliquam, diam at sollicitudin lobortis, ligula mauris bibendum nibh, in facilisis lorem metus ultrices metus. Cras a augue turpis. In hac habitasse platea dictumst. Etiam blandit, nisl nec auctor egestas, leo lectus mollis mi, vel volutpat augue neque ac dolor. Nunc tempus libero non nibh posuere eget dictum magna condimentum. Duis ac ultrices tortor. Suspendisse non est id nulla blandit posuere. Nulla ut nisl quis erat adipiscing iaculis sit amet et augue. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Proin ut leo aliquam lorem tempor rutrum.",
        "Aliquam lobortis, ipsum at suscipit faucibus, nisi sem imperdiet risus, vitae tincidunt mi odio eget ligula. Sed congue eleifend ipsum, vel elementum mauris pulvinar vel. Nunc massa urna, ullamcorper at rutrum et, pretium nec neque. Phasellus at dignissim mauris. Ut accumsan lectus ut augue fringilla dapibus. In hac habitasse platea dictumst. Pellentesque pharetra volutpat lorem, vitae faucibus orci convallis suscipit.",
        "Donec at velit ligula. Nullam elementum sagittis pulvinar. Aliquam metus erat, varius ultricies venenatis ut, fringilla et felis. Suspendisse molestie egestas dapibus. Quisque aliquet lobortis odio nec cursus. Nam interdum rutrum dignissim. Duis imperdiet, arcu ut suscipit tincidunt, erat sem hendrerit orci, quis ornare risus erat id felis. Fusce non turpis nisl.",
        "Etiam eu ipsum at mi dignissim vestibulum. Nulla bibendum congue massa sed laoreet. Donec quis dui vel neque mollis tincidunt ut eu tellus. Nam nec velit sem, eu tincidunt mi. Nam a diam eget eros lacinia dignissim. Vivamus pretium dolor vitae tortor pharetra sed varius sem auctor. Proin eu tellus augue.",
        "Nulla pretium purus vel leo vulputate tempus. Aliquam erat volutpat. Duis condimentum justo at sapien porta a iaculis diam condimentum. In eget vehicula purus. Donec et egestas enim. Donec tincidunt erat ut nibh consequat dictum. Mauris magna sapien, luctus eget vehicula eget, posuere eu arcu.",
        "Cras tempus facilisis justo, sit amet tristique augue varius quis. Suspendisse luctus nunc eu nisi placerat ullamcorper. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla eget erat nibh. Nulla commodo congue faucibus. Pellentesque id libero quis lectus sodales hendrerit. Curabitur sed lacus sapien. Aenean rutrum tincidunt dignissim. Curabitur mollis justo sit amet quam molestie eu vestibulum diam tristique.",
        "Donec sed pharetra risus. Aliquam aliquet egestas enim, id condimentum elit tempor quis. Pellentesque at tellus elit. Quisque nulla nisl, lobortis vitae lacinia ut, fermentum at nisl. Nunc egestas dapibus turpis, a dignissim erat accumsan eu. Nulla id massa lectus, eget fringilla mauris. Vivamus et luctus lorem. Proin sagittis elementum accumsan. Phasellus felis nibh, semper a tincidunt non, egestas tristique nulla."
    );

    $len = sizeof($arr);

    $rand = mt_rand(0, $len - 1);
    return ($arr[$rand]);
}