<?php



function api($function_name, $params = false)
{
    static $c;

    if ($c == false) {
        if (!defined('MW_API_RAW')) {
            define('MW_API_RAW', true);
        }
        $c = new \mw\Controller();

    }
    $res = $c->api($function_name, $params);
    return $res;

}

function api_expose($function_name)
{
    static $index = ' ';
    if (is_bool($function_name)) {

        return $index;
    } else {
        $index .= ' ' . $function_name;
    }
}

function exec_action($api_function, $data = false)
{
    global $mw_action_hook_index;
    $hooks = $mw_action_hook_index;

    $return = array();
    if (isset($hooks[$api_function]) and is_array($hooks[$api_function]) and !empty($hooks[$api_function])) {

        foreach ($hooks[$api_function] as $hook_key => $hook_value) {

            if (function_exists($hook_value)) {
                if ($data != false) {
                    $return[$hook_value] = $hook_value($data);
                } else {

                    $return[$hook_value] = $hook_value();
                }
                unset($hooks[$api_function][$hook_key]);

            } else {


                try {
                    if ($data != false) {
                        $return[$hook_value] = call_user_func($hook_value, $data); // As of PHP 5.3.0
                    } else {
                        $return[$hook_value] = call_user_func($hook_value, false);
                    }
                } catch (Exception $e) {

                }

            }
        }
        if (!empty($return)) {
            return $return;
        }
    }
}

$mw_action_hook_index = array();
function action_hook($function_name, $next_function_name = false)
{
    global $mw_action_hook_index;

    if (is_bool($function_name)) {
        $mw_action_hook_index = ($mw_action_hook_index);
        return $mw_action_hook_index;
    } else {
        if (!isset($mw_action_hook_index[$function_name])) {
            $mw_action_hook_index[$function_name] = array();
        }

        $mw_action_hook_index[$function_name][] = $next_function_name;

        //  $index .= ' ' . $function_name;
    }
}

$mw_api_hooks = array();
function api_hook($function_name, $next_function_name = false)
{
    //static $index = array();
    global $mw_api_hooks;
    if (is_bool($function_name)) {
        $index = array_unique($mw_api_hooks);
        return $index;
    } else {
        //d($function_name);
        $function_name = trim($function_name);
        $mw_api_hooks[$function_name][] = $next_function_name;

        // $index .= ' ' . $function_name;
    }
}

function document_ready($function_name)
{
    static $index = ' ';
    if (is_bool($function_name)) {

        return $index;
    } else {
        $index .= ' ' . $function_name;
    }
}

function execute_document_ready($l)
{

    $document_ready_exposed = (document_ready(true));

    if ($document_ready_exposed != false) {
        $document_ready_exposed = explode(' ', $document_ready_exposed);
        $document_ready_exposed = array_unique($document_ready_exposed);
        $document_ready_exposed = array_trim($document_ready_exposed);

        foreach ($document_ready_exposed as $api_function) {
            if (function_exists($api_function)) {
                //                for ($index = 0; $index < 20000; $index++) {
                //                     $l = $api_function($l);
                //                }
                $l = $api_function($l);
            }
        }
    }
    //$l = parse_micrwober_tags($l, $options = false);

    return $l;
}

/* JS Usage:
 *
 * var source = new EventSource('<?php print site_url('api/event_stream')?>');
 *	source.onmessage = function (event) {
 *
 * 	mw.$('#mw-admin-manage-orders').html(event.data);
 *	};
 *
 *
 *  */
api_expose('event_stream');
function event_stream()
{

    header("Content-Type: text/event-stream\n\n");

    for ($i = 0; $i < 10; $i++) {

        echo 'data: ' . $i . rand() . rand() . rand() . rand() . rand() . rand() . "\n";

    }

    exit();
}

function array_to_module_params($params, $filter = false)
{
    $str = '';

    if (is_array($params)) {
        foreach ($params as $key => $value) {

            if ($filter == false) {
                $str .= $key . '="' . $value . '" ';
            } else if (is_array($filter) and !empty($filter)) {
                if (in_array($key, $filter)) {
                    $str .= $key . '="' . $value . '" ';
                }
            } else {
                if ($key == $filter) {
                    $str .= $key . '="' . $value . '" ';
                }
            }

        }
    }

    return $str;
}


function parse_params($params)
{


    $params2 = array();
    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
        unset($params2);
    }


    return $params;
}

$mw_var_storage = array();
function mw_vars_destroy()
{
    global $mw_var_storage;
    $mw_var_storage = array();
}

function mw_var($key, $new_val = false)
{
    global $mw_var_storage;
    $contstant = ($key);
    if ($new_val == false) {
        if (isset($mw_var_storage[$contstant]) != false) {
            return $mw_var_storage[$contstant];
        } else {
            return false;
        }
    } else {
        if (isset($mw_var_storage[$contstant]) == false) {
            $mw_var_storage[$contstant] = $new_val;
            return $new_val;
        }
    }
    return false;
}


action_hook('mw_cron', 'mw_cron');
api_expose('mw_cron');
function mw_cron()
{


    $file_loc = CACHEDIR_ROOT . "cron" . DS;
    $file_loc_hour = $file_loc . 'cron_lock' . '.php';

    $time = time();
    if (!is_file($file_loc_hour)) {
        @touch($file_loc_hour);
    } else {
        if ((filemtime($file_loc_hour)) > $time - 2) {
            touch($file_loc_hour);
            return true;
        }
    }


    // touch($file_loc_hour);
    $cron = new \mw\utils\Cron;

    $scheduler = new \mw\utils\Events();

    return $scheduler->registerShutdownEvent(array($cron, 'run'));


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







$passed_reps = array();
$parser_cache_object = false; //if apc is found it will automacally use it; you can use any object compatible with the cache interface
//$parse_micrwober_max_nest_level = 3;
function parse_micrwober_tags($layout, $options = false, $coming_from_parent = false, $coming_from_parent_id = false)
{
    static $checker = array();
    global $passed_reps;
    global $parser_cache_object;
//    global $parse_micrwober_max_nest_level;
//
//
//    $parse_micrwober_max_nest_level--;
//
//    if($parse_micrwober_max_nest_level <0){
//        return $layout;
//    }


    $d = 1;
    $parser_mem_crc = 'parser_' . crc32($layout);

//	if (isset($passed_reps[$parser_mem_crc])) {
//
//	    return $layout;
//	} else {
//       $passed_reps[$parser_mem_crc] = true;
//    }

    $use_apc = false;
    if (defined('APC_CACHE') and APC_CACHE == true) {
        $use_apc = true;


        if (!is_object($parser_cache_object)) {
            $parser_cache_object = new \mw\cache\Apc();

        }


    }


    if (isarr($options)) {
        // d($options);
        if (isset($options['no_apc'])) {
            $use_apc = false;
        }
    }
    //
    //    if ($use_apc == true) {
    //
    //        $function_cache_id = false;
    //
    //        $args = func_get_args();
    //
    //        foreach ($args as $k => $v) {
    //
    //            $function_cache_id = serialize($k) . serialize($v) . $coming_from_parent_id;
    //        }
    //
    //        $function_cache_id = __FUNCTION__ . crc32($layout);
    //
    //        $quote = false;
    //
    //        $quote = apc_fetch($function_cache_id);
    //
    //
    //        if ($quote) {
    //
    //            return $quote;
    //        }
    //    }

    if (!isset($options['parse_only_vars'])) {

        $layout = str_replace('<mw ', '<module ', $layout);
        $layout = str_replace('<editable ', '<div class="edit" ', $layout);
        $layout = str_replace('</editable>', '</div>', $layout);

        $layout = str_replace('<microweber module=', '<module data-type=', $layout);
        $layout = str_replace('</microweber>', '', $layout);
        $layout = str_replace('></module>', '/>', $layout);

        // $layout = preg_match_all('#<script(.*?)>(.*?)</script>#is', '', $layout);
        $script_pattern = "/<script[^>]*>(.*)<\/script>/Uis";
        $replaced_scripts = array();
        preg_match_all($script_pattern, $layout, $mw_script_matches);

        if (!empty($mw_script_matches)) {
            foreach ($mw_script_matches [0] as $key => $value) {
                if ($value != '') {
                    $v1 = crc32($value);
                    $v1 = '<!-- mw_replace_back_this_script_' . $v1 . ' -->';
                    $layout = str_replace($value, $v1, $layout);
                    // $layout = str_replace_count($value, $v1, $layout,1);
                    if (!isset($replaced_scripts[$v1])) {
                        $replaced_scripts[$v1] = $value;
                    }
                    // p($value);
                }
            }
        }

        $script_pattern = "/<code[^>]*>(.*)<\/code>/Uis";
        $replaced_codes = array();
        preg_match_all($script_pattern, $layout, $mw_script_matches);

        if (!empty($mw_script_matches)) {
            foreach ($mw_script_matches [0] as $key => $value) {
                if ($value != '') {
                    $v1 = crc32($value);
                    $v1 = '<!-- mw_replace_back_this_code_' . $v1 . ' -->';
                    $layout = str_replace($value, $v1, $layout);
                    // $layout = str_replace_count($value, $v1, $layout,1);
                    if (!isset($replaced_scripts[$v1])) {
                        $replaced_codes[$v1] = $value;
                    }
                    //  p($replaced_codes);
                }
            }
        }

        $script_pattern = "/<module[^>]*>/Uis";
        //$script_pattern = "/<module.*.[^>]*>/is";
        $replaced_modules = array();
        preg_match_all($script_pattern, $layout, $mw_script_matches);

        if (!empty($mw_script_matches)) {
            $matches1 = $mw_script_matches[0];
            foreach ($matches1 as $key => $value) {
                if ($value != '') {
                    // d($value);
                    $v1 = crc32($value);
                    $v1 = '<!-- mw_replace_back_this_module_' . $v1 . ' -->';
                    $layout = str_replace($value, $v1, $layout);
                    // $layout = str_replace_count($value, $v1, $layout,1);
                    if (!isset($replaced_modules[$v1])) {

                        $replaced_modules[$v1] = $value;
                    }
                    // p($value);
                }
            }
        }



        if (isset($_POST)) {
            $parse_mode = 1;
        }
        $parse_mode = 4;
        if (isset($options['parse_only_modules'])) {
            $parse_mode = false;
        }

        $parse_mode = 5;
        if (isset($options['parse_mode'])) {
            $parse_mode = intval($options['parse_mode']);
        }


        switch ($parse_mode) {
            case 1 :
                include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . '01_default.php');

                break;

            case 2 :
                include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . '02_default.php');

                break;
            case 3 :
                include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . '03_default.php');

                break;

            case 4 :
                include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . '04_default.php');

                break;

            case 5 :

                preg_match_all('/.*?class=..*?edit.*?.[^>]*>/', $layout, $layoutmatches);
                if (!empty($layoutmatches) and isset($layoutmatches[0][0])) {
                    include (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . '05_default.php');
                }
                break;

            case 345434536 :
                include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . '06_default.php');

                break;

            default :
                break;
        }

        if (!empty($replaced_scripts)) {
            foreach ($replaced_scripts as $key => $value) {
                if ($value != '') {

                    $layout = str_replace($key, $value, $layout);
                }
                unset($replaced_scripts[$key]);
            }
        }


        if (isarr($replaced_modules)) {

            $attribute_pattern = '@
			(?P<name>\w+)# attribute name
			\s*=\s*
			(
				(?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote) # a quoted value
				| # or
				(?P<value_unquoted>[^\s"\']+?)(?:\s+|$)  # an unquoted value (terminated by whitespace or EOF)
				)
@xsi';

            // (?P<name>\w+)\s*=\s*((?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)|(?P<value_unquoted>[^\s"\']+?)(?:\s+|$))
            $attribute_pattern = '@(?P<name>[a-z-_A-Z]+)\s*=\s*((?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)|(?P<value_unquoted>[^\s"\']+?)(?:\s+|$))@xsi';
            //$attribute_pattern = '@([a-z-A-Z]+)=\"([^"]*)@xsi';
            $attrs = array();
            foreach ($replaced_modules as $key => $value) {
                if ($value != '') {


                    $replace_key = $key;

                    $attrs = array();
                    if (preg_match_all($attribute_pattern, $value, $attrs1, PREG_SET_ORDER)) {
                        foreach ($attrs1 as $item) {
                            // d($item);
                            $m_tag = trim($item[0], "\x22\x27");
                            $m_tag = trim($m_tag, "\x27\x22");
                            $m_tag = explode('=', $m_tag);

                            $a = trim($m_tag[0], "''");
                            $a = trim($a, '""');

                            $b = trim($m_tag[1], "''");
                            $b = trim($b, '""');

                            $attrs[$a] = $b;

                            // $attrs[$item['name']] = $item['value_quoted'];
                        }
                    }

                    $m_tag = ltrim($value, "<module");

                    $m_tag = rtrim($m_tag, "/>");
                    $m_tag = rtrim($m_tag);
                    $userclass = '';
                    $module_html = "<div class='__USER_DEFINED_CLASS__ __MODULE_CLASS__ __WRAP_NO_WRAP__' __MODULE_ID__ __MODULE_NAME__";

                    // $module_html = "<div class='module __WRAP_NO_WRAP__' ";
                    $module_has_class = false;

                    if (!empty($attrs)) {

                        if (isset($attrs['module']) and $attrs['module']) {
                            $attrs['data-type'] = $attrs['module'];
                            unset($attrs['module']);
                        }

                        if ($coming_from_parent == true) {
                            $attrs['data-parent-module'] = $coming_from_parent;
                        }
                        if ($coming_from_parent_id == true) {
                            $attrs['data-parent-module-id'] = $coming_from_parent_id;
                        }

                        if (isset($attrs['type']) and $attrs['type']) {
                            $attrs['data-type'] = $attrs['type'];
                            unset($attrs['type']);
                        }

                        $z = 0;
                        $mod_as_element = false;
                        $mod_no_wrapper = false;

                        if (isset($attrs['data-module'])) {

                            $attrs['data-type'] = $attrs['data-module'];
                            unset($attrs['data-module']);
                        }
                        // if (!isset($attrs['id'])) {
                        //
                        // $attrs1 = crc32(serialize($attrs));
                        // $s1 = false;
                        // if ($s1 != false) {
                        // $attrs1 = $attrs1 . '-' . $s1;
                        // } else if (defined('PAGE_ID') and PAGE_ID != false) {
                        // $attrs1 = $attrs1 . '-' . PAGE_ID;
                        // }
                        //
                        // $attrs['id'] = ('__MODULE_CLASS_NAME__' . $attrs1);
                        //
                        // }
                        foreach ($attrs as $nn => $nv) {

                            if ($nn == 'class') {
                                $module_has_class = $userclass = $nv;

                                if (strstr($nv, 'module-as-element')) {
                                    $mod_as_element = true;
                                    $userclass = str_replace('module-as-element', '', $userclass);
                                }
                                $userclass = str_replace(' module  module ', 'module ', $userclass);

                                $userclass = str_replace('ui-sortable', '', $userclass);
                                $userclass = str_replace('module-item', '', $userclass);
                                $userclass = str_replace('module module module', 'module', $userclass);

                                $userclass = str_replace('module  module ', 'module ', $userclass);

                            } else {
                                $module_html .= " {$nn}='{$nv}'  ";
                            }

                            if ($nn == 'module') {
                                $module_name = $nv;
                                $attrs['data-type'] = $module_name;
                                unset($attrs[$nn]);
                            }

                            if ($nn == 'no_wrap') {
                                $mod_no_wrapper = true;
                                //  $attrs ['data-no-wrap'] = $module_name;
                                unset($attrs[$nn]);
                            }
                            if ($nn == 'data-no-wrap') {
                                $mod_no_wrapper = true;
                                //  $attrs ['data-no-wrap'] = $module_name;
                                unset($attrs[$nn]);
                            }

                            if ($nn == 'data-module-name') {
                                $module_name = $nv;
                                $attrs['data-type'] = $module_name;
                                unset($attrs[$nn]);
                            }

                            if ($nn == 'data-module-name-enc') {

                                unset($attrs[$nn]);
                            }

                            if ($nn == 'type') {
                                $module_name = $nv;
                                $attrs['data-type'] = $module_name;
                                unset($attrs[$nn]);
                            }

                            if ($nn == 'data-type') {
                                $module_name = $nv;
                            }

                            if ($nn == 'data-module') {
                                $attrs['data-type'] = $module_name;
                                $module_name = $nv;
                            }

                            $z++;
                        }
                        $module_title = false;
                        if (isset($module_name)) {
                            $module_class = module_css_class($module_name);
                            $module_title = module_info($module_name);

                            if (!isset($attrs['id'])) {

                                global $mw_mod_counter;
                                $mw_mod_counter++;
                                $mw_mod_counter1 = crc32(serialize($attrs));
                                $attrs['id'] = $module_class . '-' . url_segment(0) . ($mw_mod_counter1);
                                $module_html = str_replace('__MODULE_ID__', "id='{$attrs['id']}'", $module_html);

                            } else {
                                $module_html = str_replace('__MODULE_ID__', '', $module_html);
                            }
                        }
                        if (isarr($module_title) and isset($module_title["name"])) {
                            $module_title["name"] = addslashes($module_title["name"]);
                            $module_html = str_replace('__MODULE_NAME__', ' data-mw-title="' . $module_title["name"] . '"', $module_html);
                        } else {
                            $module_html = str_replace('__MODULE_NAME__', '', $module_html);

                        }
                        //$module_html = str_replace('__MODULE_NAME__', '', $module_html);

                        //                        if (!isset($module_name)) {
                        //                            if (isset($_POST['module'])) {
                        //                                $module_name = $_POST['module'];
                        //                            }
                        //                        }

                        if (isset($module_name)) {

                            if (strstr($module_name, 'admin')) {

                                $module_html = str_replace('__WRAP_NO_WRAP__', '', $module_html);
                            } else {
                                // $module_html = str_replace('__WRAP_NO_WRAP__', 'element', $module_html);
                                $module_html = str_replace('__WRAP_NO_WRAP__', '', $module_html);
                            }
                            $module_name_url = url_title($module_name);

                            if ($mod_as_element == false) {
                                if (strstr($module_name, 'text')) {

                                    $module_html = str_replace('__MODULE_CLASS__', 'layout-element ' . $module_name_url, $module_html);
                                } else {

                                    $module_html = str_replace('__MODULE_CLASS__', 'module ' . $module_class, $module_html);
                                }
                            } else {

                                $module_html = str_replace('__MODULE_CLASS__', 'element ' . $module_name_url, $module_html);
                            }
                            //

                            $userclass = str_replace(trim($module_class), '', $userclass);
                            $userclass = trim(str_replace(' module ', '', $userclass));
                            //	$userclass = str_replace('module  module', '', $userclass);
                            //$userclass = str_replace('module module', 'module', $userclass);

                            $module_html = str_replace('__MODULE_CLASS_NAME__', '' . $module_class, $module_html);
                            $module_html = str_replace('__USER_DEFINED_CLASS__', $userclass, $module_html);

                            $coming_from_parentz = $module_name;
                            $coming_from_parent_str = false;
                            $coming_from_parent_strz1 = false;
                            if ($coming_from_parent == true) {
                                $coming_from_parent_str = " data-parent-module='$coming_from_parent' ";
                            }


                            if (isset($attrs['id']) == true) {

                                $coming_from_parent_strz1 = $attrs['id'];
                            }
                            if ($coming_from_parent_strz1 == true) {
                                //   $attrs['data-parent-module'] = $coming_from_parentz;
                            }

                            $mod_content = load_module($module_name, $attrs);

                            $plain_modules = mw_var('plain_modules');
                            if ($plain_modules != false) {
                                //d($plain_modules);
                                $module_db_data = get_modules_from_db('one=1&ui=any&module=' . $module_name);
                                $mod_content = '';
                                if (isarr($module_db_data)) {
                                    if (isset($module_db_data["installed"]) and $module_db_data["installed"] != '' and intval($module_db_data["installed"]) != 1) {

                                    } else {
                                        //d($module_db_data);

                                        $mod_content = '<span class="mw-plain-module-holder" data-module="' . addslashes($module_db_data['module']) . '" data-module-name="' . addslashes($module_db_data['name']) . '" data-module-description="' . addslashes($module_db_data['description']) . '" ><img class="mw-plain-module-icon" src="' . $module_db_data['icon'] . '" /><span class="mw-plain-module-name">' . $module_db_data['name'] . '</span></span>';
                                    }
                                }
                                //
                            }
                            //


                            preg_match_all('/.*?class=..*?edit.*?.[^>]*>/', $mod_content, $modinner);
                            $proceed_with_parse = false;
                            if (!empty($modinner) and isset($modinner[0][0])) {

                                $proceed_with_parse = true;
                            } else {
                                preg_match_all('/<module.*[^>]*>/', $mod_content, $modinner);
                                if (!empty($modinner) and isset($modinner[0][0])) {

                                    $proceed_with_parse = true;
                                } else {
                                    preg_match_all('/<mw.*[^>]*>/', $mod_content, $modinner);
                                    if (!empty($modinner) and isset($modinner[0][0])) {

                                        $proceed_with_parse = true;
                                    } else {
                                        preg_match_all('/<microweber.*[^>]*>/', $mod_content, $modinner);
                                        if (!empty($modinner) and isset($modinner[0][0])) {

                                            $proceed_with_parse = true;
                                        }

                                    }

                                }

                            }

                            if ($proceed_with_parse == true) {
                                $mod_content = parse_micrwober_tags($mod_content, $options, $coming_from_parentz, $coming_from_parent_strz1);
                            }
                            //if (trim($mod_content) != '') {
                            if ($mod_no_wrapper == false) {
                                $module_html .= $coming_from_parent_str . '>' . $mod_content . '</div>';
                            } else {
                                $module_html = $mod_content;
                            }
                            //} else {
                            //	$module_html = '';
                            //}


                            $layout = str_replace($value, $module_html, $layout);
                            $layout = str_replace($replace_key, $module_html, $layout);
                            if ($module_name == 'comments') {
//                               d($replace_key);
//                               d($value);
//                           d($module_html);
//                               d($layout);
//                            exit();
                            }
                            //
                        }
                    }
                    //
                }
                unset($replaced_modules[$replace_key]);
                // $layout = str_replace($key, $value, $layout);
            }
        }
    }

    if (!empty($replaced_codes)) {
        foreach ($replaced_codes as $key => $value) {
            if ($value != '') {

                $layout = str_replace($key, $value, $layout);
            }
            unset($replaced_codes[$key]);
        }
    }
    global $mw_rand;
    //	$field_content = parse_micrwober_tags($field_content, $options, $coming_from_parent, $coming_from_parent_id);
    $layout = str_replace('{rand}', uniqid(), $layout);
    $layout = str_replace('{SITE_URL}', site_url(), $layout);
    $layout = str_replace('{SITEURL}', site_url(), $layout);
    $layout = str_replace('%7BSITE_URL%7D', site_url(), $layout);

    $checker[$d] = 1;

    if ($use_apc == true) {
        //d($function_cache_id);
        //   apc_delete($function_cache_id);
        // apc_store($function_cache_id, $layout, 30);
    }

    return $layout;
    exit();
}




function make_microweber_tags($layout)
{
    if ($layout == '') {
        return $layout;
    }

    require_once (MW_APPPATH_FULL . 'classes'. DIRECTORY_SEPARATOR . 'mw'. DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . 'phpQuery.php');

    $script_pattern = "/<script[^>]*>(.*)<\/script>/Uis";
    $replaced_scripts = array();
    preg_match_all($script_pattern, $layout, $mw_script_matches);

    if (!empty($mw_script_matches)) {
        foreach ($mw_script_matches [0] as $key => $value) {
            if ($value != '') {
                //	$v1 = crc32($value);
                $v1 = ' ';
                $layout = str_replace($value, $v1, $layout);

            }
        }
    }

    $pq = \phpQuery::newDocument($layout);
    // print first list outer HTML
    // $edit_fields = $pq['.edit'];
    foreach ($pq ['.module'] as $elem) {
        $name = pq($elem)->attr('module');

        $attrs = $elem->attributes;

        $module_html = "<module ";
        if (!empty($attrs)) {
            foreach ($attrs as $attribute_name => $attribute_node) {
                $v = $attribute_node->nodeValue;
                $module_html .= " {$attribute_name}='{$v}'  ";
            }
        }
        $module_html .= ' />';
        pq($elem)->replaceWith($module_html);
    }

    return $pq->htmlOuter();

    return $layout;
}


function parse_memory_storage($id = false, $content = false)
{
    static $parse_mem = array();
    $path_md = ($id);
    // p($parse_mem);
    if ($parse_mem[$path_md] != false) {
        return $parse_mem[$path_md];
    }

    if ($content != false) {
        $parse_mem[$path_md] = $content;
        return $content;
    }
}




function modify_html($layout, $preg_match_all, $content = "", $action = 'append')
{

    $string_html = $layout;

    $m = preg_match_all($preg_match_all, $string_html, $match);

    if ($m) {
        $match_html = $match[0];
        for ($j = 0; $j < $m; $j++) {
            if (trim($action) == 'append') {
                $string_html = str_replace($match_html[$j], $match_html[$j] . $content, $string_html);
            } else {
                $string_html = str_replace($match_html[$j], $content . $match_html[$j], $string_html);
            }
        }
    }

    $layout = $string_html;


    return $layout;
}


/**
 * Here you will find how to work with the cache
 * These functions will allow you to save and get data from the MW cache system
 *
 * @package Cache
 * @category Cache
 * @desc  These functions will allow you to save and get data from the MW cache system
 */

if (!isset($_mw_cache_obj) or is_object($_mw_cache_obj) == false) {
    $_mw_cache_obj = new \mw\cache\Files();
}


mw_var('is_cleaning_now', false);
/**
 * Deletes cache for given $cache_group recursively.
 *
 * @param string $cache_group
 *            (default is 'global') - this is the subfolder in the cache dir.
 * @param bool $cache_storage_type
 * @return boolean
 *
 * @package Cache
 * @example
 * <code>
 * //delete the cache for the content
 *  cache_clean_group("content");
 *
 * //delete the cache for the content with id 1
 *  cache_clean_group("content/1");
 *
 * //delete the cache for users
 *  cache_clean_group("users");
 *
 * //delete the cache for your custom table eg. my_table
 * cache_clean_group("my_table");
 * </code>
 *
 */
function cache_clean_group($cache_group = 'global', $cache_storage_type = false)
{
    if ($cache_storage_type == false  or $cache_storage_type == 'files') {
        global $_mw_cache_obj;
        $local_obj = $_mw_cache_obj;
    } else {
        $cache_storage_type = "\mw\cache\\" . $cache_storage_type;
        $local_obj = new $cache_storage_type;

    }

    if(!is_object($local_obj)){
        $local_obj = new \mw\cache\Files();
    }

    //d($cache_group);

    $local_obj->delete($cache_group);
}

/**
 *  Gets the data from the cache.
 *
 *  If data is not found it return false
 *
 *
 *  @example
 * <code>
 *
 * $cache_id = 'my_cache_'.crc32($sql_query_string);
 * $cache_content = cache_get_content($cache_id, 'my_cache_group');
 *
 * </code>
 *
 *
 *
 *
 * @param string $cache_id id of the cache
 * @param string $cache_group (default is 'global') - this is the subfolder in the cache dir.
 *
 * @param bool $cache_storage_type You can pass custom cache object or leave false.
 * @return  mixed returns array of cached data or false
 * @package Cache
 *
 */
function cache_get_content($cache_id, $cache_group = 'global', $cache_storage_type = false)
{
    static $cache_default;
    global $_mw_cache_obj;

    if ($cache_storage_type == false or $cache_storage_type == 'files') {
        $local_obj = $_mw_cache_obj;

    } else {
        $cache_storage_type = "\mw\cache\\" . $cache_storage_type;
        $local_obj = new $cache_storage_type;

    }

    if(!is_object($local_obj)){
        if(!is_object($_mw_cache_obj)){
            $local_obj = $_mw_cache_obj = new \mw\cache\Files();

        } else {
            //$local_obj = $cache_default ;

        }
    }

    return $local_obj->get($cache_id, $cache_group);
}

/**
 * Stores your data in the cache.
 * It can store any value that can be serialized, such as strings, array, etc.
 *
 * @example
 * <code>
 * //store custom data in cache
 * $data = array('something' => 'some_value');
 * $cache_id = 'my_cache_id';
 * $cache_content = cache_save($data, $cache_id, 'my_cache_group');
 * </code>
 *
 * @param mixed $data_to_cache
 *            your data, anything that can be serialized
 * @param string $cache_id
 *            id of the cache, you must define it because you will use it later to
 *            retrieve the cached content.
 * @param string $cache_group
 *            (default is 'global') - this is the subfolder in the cache dir.
 *
 * @param bool $cache_storage_type
 * @return boolean
 * @package Cache
 */
function cache_save($data_to_cache, $cache_id, $cache_group = 'global', $cache_storage_type = false)
{

    if ($cache_storage_type == false  or $cache_storage_type == 'files') {
        global $_mw_cache_obj;
        $local_obj = $_mw_cache_obj;

    } else {
        $cache_storage_type = "\mw\cache\\" . $cache_storage_type;
        $local_obj = new $cache_storage_type;

    }
    if(!is_object($local_obj)){
        $local_obj = $_mw_cache_obj= new \mw\cache\Files();
    }



    // d($data_to_cache);
    return $local_obj->save($data_to_cache, $cache_id, $cache_group);

}


api_expose('clearcache');
/**
 * Clears all cache data
 * @example
 * <code>
 * //delete all cache
 *  clearcache();
 * </code>
 * @return boolean
 * @package Cache
 */
function clearcache($cache_storage_type = false)
{
    if ($cache_storage_type == false or trim($cache_storage_type) == ''  or $cache_storage_type == 'files') {
        global $_mw_cache_obj;
        $local_obj = $_mw_cache_obj;
    } else {
        $cache_storage_type = "\mw\cache\\" . $cache_storage_type;
        $local_obj = new $cache_storage_type;

    }

    if(!is_object($local_obj)){
        $local_obj = new \mw\cache\Files();
    }


    return $local_obj->purge();
}

/**
 * Prints cache debug information
 *
 * @return array
 * @package Cache
 * @example
 * <code>
 * //get cache items info
 *  $cached_items = cache_debug();
 * print_r($cached_items);
 * </code>
 */
function cache_debug()
{
    global $_mw_cache_obj;
    if(!is_object($_mw_cache_obj)){
        $_mw_cache_obj = new \mw\cache\Files();
    }



    return $_mw_cache_obj->debug();
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
    return \mw\Db::get($params);
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
    return \mw\Db::save($table, $data, $data_to_save_options);

}





function add_slashes_to_array($arr)
{
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
                $v = addslashes($v);
                $v = htmlspecialchars($v);
            }

            $ret[$k] = ($v);
        }

        return $ret;
    }
}

function remove_slashes_from_array($arr)
{
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

            $ret[$k] = $v;
        }

        return $ret;
    }
}



