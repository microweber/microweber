<?php
namespace Mw;
$passed_reps = array();
$parser_cache_object = false; //if apc is found it will automacally use it; you can use any object compatible with the cache interface
//$parse_micrwober_max_nest_level = 3;
class Parser
{


    public function process($layout, $options = false, $coming_from_parent = false, $coming_from_parent_id = false)
    {
        static $checker = array();
        global $passed_reps;
        global $parser_cache_object;



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
                $parser_cache_object = new \Mw\Cache\Apc();

            }


        }


        if (is_array($options)) {
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
//                $mtime = microtime();
//                $mtime = explode(" ", $mtime);
//                $mtime = $mtime[1] + $mtime[0];
//                $endtime = $mtime;
//                $totaltime = ($endtime - T);
//                echo "This page was created in " . $totaltime . " seconds";
//
                    preg_match_all('/.*?class=..*?edit.*?.[^>]*>/', $layout, $layoutmatches);
                    // d($layoutmatches);
                    if (!empty($layoutmatches) and isset($layoutmatches[0][0])) {
                        // include (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . '05_default.php');

                        $layout = $this->_replace_editable_fields($layout);

                    }
                    break;

                case 345434536 :
                    include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . '06_default.php');

                    break;

                default :
                    break;
            }


            $layout = str_replace('<microweber module=', '<module data-type=', $layout);
            $layout = str_replace('</microweber>', '', $layout);
            $layout = str_replace('></module>', '/>', $layout);
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
            // debug_info();
//print_r(debug_backtrace());


            /*
             $layout = str_replace('<mw ', '<module ', $layout);
             $layout = str_replace('<editable ', '<div class="edit" ', $layout);
             $layout = str_replace('</editable>', '</div>', $layout);

             $layout = str_replace('<microweber module=', '<module data-type=', $layout);
             $layout = str_replace('</microweber>', '', $layout);
             $layout = str_replace('></module>', '/>', $layout);
             $script_pattern = "/<module[^>]*>/Uis";

             //$script_pattern = "/<module.*.[^>]*>/is";
             preg_match_all($script_pattern, $layout, $mw_script_matches);

             if (!empty($mw_script_matches)) {
             $matches1 = $mw_script_matches[0];
             foreach ($matches1 as $key => $value) {
             if ($value != '') {
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
             }*/

            //  echo $dom->output();

            /*
             */

            /*
             * foreach($pq['mw'] as $elem) { $name = pq($elem)->attr('module');
             * $attributes = array(); $ats = $elem->attributes; $module_html = "<module
             * "; if (!empty($ats)) { foreach($ats as $attribute_name =>
             * $attribute_node) { $v = $attribute_node->nodeValue; $module_html .= "
             * {$attribute_name}='{$v}' "; } } $module_html. ' />';
             * pq($elem)->replaceWith($module_html) ; } $els = $pq['footer']; //$els =
             * pq('module')->filter(':not(script)'); foreach ($els as $elem) { $name =
             * pq($elem) -> attr('module'); $attrs = $elem -> attributes; $z = 0;
             * foreach ($attrs as $attribute_node) { $nn = $attribute_node -> nodeName;
             * $v = $nv = $attribute_node -> nodeValue; if ($z == 0) { $module_name =
             * $nn; } else { } $mod_attributes[$nn] = $nv; if ($nn == 'module') {
             * $module_name = $nv; } if ($nn == 'type') { $module_name = $nv; } if ($nn
             * == 'data-type') { $module_name = $nv; } if ($nn == 'data-module') {
             * $module_name = $nv; } $z++; } // $mod_content = load_module($module_name,
             * $attrs); $mod_content = mw('parser')->process($mod_content); if
             * ($mod_content != false) { $module_html = "<div class='module' "; if
             * (!empty($attrs)) { foreach ($attrs as $attribute_name => $attribute_node)
             * { $v = $attribute_node -> nodeValue; $module_html .= "
             * {$attribute_name}='{$v}' "; } } $module_html .= '>' . $mod_content .
             * '</div>'; pq($elem) -> replaceWith($module_html); } } if
             * ($options['mw_embed']) { $em = trim($options['mw_embed']); if ($em != '')
             * { foreach ($pq['#'.$em] as $elem) { pq($elem) -> parents('body') ->
             * replaceWith($elem); } } }
             */
            // $layout = $pq;
            // $layout = str_replace('<mw-script ', '<script ', $layout);
            // $layout = str_replace('</mw-script', '</script', $layout);
            // .$layout = html_entity_decode($layout, ENT_NOQUOTES, "UTF-8");
            // if (!empty($scripts)) {
            // if(!empty($mw_script_matches)){
            // $mw_script_matches = $mw_script_matches[0];
            // }
            //
            //

            //

            if (!empty($replaced_scripts)) {
                foreach ($replaced_scripts as $key => $value) {
                    if ($value != '') {

                        $layout = str_replace($key, $value, $layout);
                    }
                    unset($replaced_scripts[$key]);
                }
            }


            if (is_array($replaced_modules)) {

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
                                // $module_title = array();
                                // $module_title['name'] = $module_name;
                                //	d($module_title);
                                // if (!isset($attrs['id'])) {
                                // if (isset($attrs['module-id'])) {
                                // $attrs['id'] = $attrs['module-id'];
                                //
                                // }
                                // }
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
                            if (is_array($module_title) and isset($module_title["name"])) {
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
                                    if (is_array($module_db_data)) {
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
                                    $mod_content = $this->process($mod_content, $options, $coming_from_parentz, $coming_from_parent_strz1);
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
        //	$field_content = mw('parser')->process($field_content, $options, $coming_from_parent, $coming_from_parent_id);
        $layout = str_replace('{rand}', uniqid(), $layout);
        $layout = str_replace('{SITE_URL}', site_url(), $layout);
        $layout = str_replace('{SITEURL}', site_url(), $layout);
        $layout = str_replace('%7BSITE_URL%7D', site_url(), $layout);

        $checker[$d] = 1;


        return $layout;
        exit();
    }


    public function make_tags($layout)
    {
        if ($layout == '') {
            return $layout;
        }

        require_once (MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . 'phpQuery.php');

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

    public function modify_html_preg($layout, $preg_match_all, $content = "", $action = 'append')
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

        // echo $string_html;

        $layout = $string_html;

        //$layout = str_replace($selector, $selector . $content, $layout);

        return $layout;
    }


    public function modify_html($layout, $selector, $content = "", $action = 'append')
    {
        require_once (MW_APPPATH_FULL . 'classes' . DIRECTORY_SEPARATOR . 'Mw' . DIRECTORY_SEPARATOR . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');

        $pq = \phpQuery::newDocument($layout);

        $els = $pq[$selector];
        foreach ($els as $elem) {
            //  pq($elem)->html($field_content);
            pq($elem)->$action($content);
            //
        }
        $layout = $pq->htmlOuter();

        return $layout;
    }

    public function clean_word($html_to_save)
    {
        if (strstr($html_to_save, '<!--[if gte mso')) {
            // word mess up tags
            $tags = extract_tags($html_to_save, 'xml', $selfclosing = false, $return_the_entire_tag = true, $charset = 'UTF-8');

            $matches = $tags;
            if (!empty($matches)) {
                foreach ($matches as $m) {
                    $html_to_save = str_replace($m['full_tag'], '', $html_to_save);
                }

                $html_to_save = str_replace('<!--[if gte mso 8]><![endif]-->', '', $html_to_save);

                $html_to_save = str_replace('<!--[if gte mso 9]><![endif]-->', '', $html_to_save);
                $html_to_save = str_replace('<!--[if gte mso 10]><![endif]-->', '', $html_to_save);
                $html_to_save = str_replace('<!--[if gte mso 11]><![endif]-->', '', $html_to_save);
                $html_to_save = str_replace('class="MsoNormal"', '', $html_to_save);
            }

        }
        $html_to_save = str_replace('class="exec"', '', $html_to_save);
        $html_to_save = str_replace('style=""', '', $html_to_save);

        $html_to_save = str_replace('ui-draggable', '', $html_to_save);
        $html_to_save = str_replace('class="ui-droppable"', '', $html_to_save);
        $html_to_save = str_replace('ui-droppable', '', $html_to_save);
        $html_to_save = str_replace('mw_edited', '', $html_to_save);
        $html_to_save = str_replace('_moz_dirty=""', '', $html_to_save);
        $html_to_save = str_replace('ui-droppable', '', $html_to_save);
        $html_to_save = str_replace('<br >', '<br />', $html_to_save);
        $html_to_save = str_replace('<br>', '<br />', $html_to_save);
        $html_to_save = str_replace(' class=""', '', $html_to_save);
        $html_to_save = str_replace(' class=" "', '', $html_to_save);

        $html_to_save = preg_replace('/<!--(.*)-->/Uis', '', $html_to_save);

        return $html_to_save;
    }

    public function isolate_content_field($l)
    {
        require_once (MW_APPPATH_FULL . 'classes' . DIRECTORY_SEPARATOR . 'Mw' . DIRECTORY_SEPARATOR . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');
        $pq = phpQuery::newDocument($l);

        $isolated_head = pq('head')->eq(0)->html();

        // d($isolated_head);
        $found_field = false;
        if (isset($_REQUEST['isolate_content_field'])) {
            foreach ($pq ['[field=content]'] as $elem) {
                //d($elem);
                $isolated_el = $l = pq($elem)->htmlOuter();
            }
        }


        return $l;
    }

    private function _replace_editable_fields($layout, $no_cache = false)
    {


        if ($layout != '') {
            global $mw_replaced_modules;
            global $passed_reps;


            $mw_found_elems = '';
            $mw_found_elems_arr = array();

            $mw_to_cache = array('orig', $layout);
            $cached = false;
            if (!isset($parser_mem_crc)) {
                $parser_mem_crc = 'parser_' . crc32($layout) . CONTENT_ID;
                $parser_modules_crc = 'parser_modules' . crc32($layout) . CONTENT_ID;

            }


            if (isset($passed_reps[$parser_mem_crc])) {

                return $passed_reps[$parser_mem_crc];
            }

            if ($no_cache == false) {
                $cache = mw('cache')->get($parser_mem_crc, 'content_fields/global/parser');
                if ($cache != false) {


                    return $cache;
                }

            }

            $ch = mw_var($parser_mem_crc);
            if ($cached != false) {
                $mw_to_cache = $cached;

            } else if ($ch != false) {

                $layout = $ch;
            } else {
                require_once (MW_APPPATH_FULL . 'classes' . DIRECTORY_SEPARATOR . 'Mw' . DIRECTORY_SEPARATOR . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');

                $layout = html_entity_decode($layout, ENT_COMPAT, "UTF-8");
                $layout = htmlspecialchars_decode($layout);

                $pq = \phpQuery::newDocument($layout);
                $els = $pq['.edit'];

                foreach ($els as $elem) {

                    // iteration returns PLAIN dom nodes, NOT phpQuery objects
                    $tagName = $elem->tagName;
                    $name = pq($elem)->attr('field');

                    if (strval($name) == '') {
                        //$name = pq($elem) -> attr('id');
                    }

                    if (strval($name) == '') {
                        $name = pq($elem)->attr('data-field');
                    }

                    // $fld_id = pq($elem)->attr('data-field-id');

                    $rel = pq($elem)->attr('rel');
                    if ($rel == false) {
                        $rel = pq($elem)->attr('data-rel');
                        if ($rel == false) {
                            $rel = 'page';
                        }
                    }

                    $option_group = pq($elem)->attr('data-option_group');
                    if ($option_group == false) {
                        $option_group = 'editable_region';
                    }
                    $data_id = pq($elem)->attr('data-id');
                    if ($data_id == false) {
                        $data_id = pq($elem)->attr('rel-id');
                    }

                    $option_mod = pq($elem)->attr('data-module');
                    if ($option_mod == false) {
                        $option_mod = pq($elem)->attr('data-type');
                    }
                    if ($option_mod == false) {
                        $option_mod = pq($elem)->attr('type');
                    }
                    $name = trim($name);
                    $get_global = false;
                    //  $rel = 'page';
                    $field = $name;
                    $use_id_as_field = $name;

                    if ($rel == 'global') {
                        $get_global = true;
                    } else {
                        $get_global = false;
                    }

                    if ($rel == 'mod1111111111ule') {
                        $get_global = true;
                    }
                    if ($get_global == false) {
                        //  $rel = 'page';
                    }

                    $try_inherited = false;
                    if ($rel == 'content') {

                        if (!isset($data_id) or $data_id == false) {
                            $data_id = CONTENT_ID;
                        }

                        $get_global = false;
                        $data_id = intval($data_id);
                        $data = mw('content')->get_by_id($data_id);

                        //$data['custom_fields'] = mw('content')->custom_fields($data_id, 0, 'all');

                    } else if ($rel == 'page') {

                        if (!isset($data_id) or $data_id == false) {
                            $data_id = PAGE_ID;
                        }
                        $data = mw('content')->get_page($data_id);

                        //$data['custom_fields'] = mw('content')->custom_fields($data['id'], 0, 'all');
                        $get_global = false;
                    } else if ($rel == 'post') {
                        $get_global = false;
                        if (!isset($data_id) or $data_id == false) {
                            $data_id = POST_ID;
                        }
                        $data = mw('content')->get_by_id($data_id);

                    } else if ($rel == 'inherit') {
                        $get_global = false;
                        if (!isset($data_id) or $data_id == false) {
                            $data_id = PAGE_ID;
                        }

                        $inh = mw('content')->get_inherited_parent($data_id);

                        if ($inh != false and intval($inh) != 0) {

                            $try_inherited = true;

                            $data_id = $inh;
                            $rel = 'content';
                            $data = mw('content')->get_by_id($data_id);
                        } else {
                            $rel = 'content';
                            $data = mw('content')->get_page($data_id);
                            //d($data);
                            //
                        }

                    } else if (isset($attr['post'])) {
                        $get_global = false;
                        $data = get_post($attr['post']);
                        if ($data == false) {
                            $data = mw('content')->get_page($attr['post']);
                            //$data['custom_fields'] = mw('content')->custom_fields($data['id'], 0, 'all');
                        }
                    } else if (isset($attr['category'])) {
                        $get_global = false;
                        $data = get_category($attr['category']);
                    } else if (isset($attr['global'])) {
                        $get_global = true;
                    }
                    $cf = false;
                    $field_content = false;

                    if (isset($data[$field])) {
                        if (isset($data[$field])) {

                            $field_content = $data[$field];

                        }
                    } else {
                        if ($rel == 'page') {
                            $rel = 'content';
                        }
                        if ($rel == 'post') {
                            $rel = 'content';
                        }
                        $cont_field = false;

                        //template_var

                        if (isset($data_id) and $data_id != 0 and trim($data_id) != '' and trim($field) != '') {
                            //

                            $cont_field = get_content_field("rel={$rel}&field={$field}&rel_id=$data_id");
                            // and $rel == 'inherit'
                            if ($cont_field == false and $try_inherited == true) {

                                $inh = mw('content')->get_inherited_parent($data_id);
                                //d($data_id . $field . $inh);
                                //
                                if ($inh != false and intval($inh) != 0 and $inh != $data_id) {
                                    $data_id = $inh;

                                    $cont_field2 = get_content_field("rel={$rel}&field={$field}&rel_id=$inh");
                                    if ($cont_field2 != false) {
                                        $rel = 'content';
                                        $data = mw('content')->get_by_id($inh);

                                        $cont_field = $cont_field2;
                                    }
                                }
                            }

                        } else {

                            if (isset($data_id) and trim($data_id) != '' and $field_content == false and isset($rel) and isset($field) and trim($field) != '') {
                                $cont_field = get_content_field("rel={$rel}&field={$field}&rel_id=$data_id");

                                if ($cont_field != false) {
                                    $field_content = $cont_field;
                                }
                            } else {

                                $cont_field = get_content_field("rel={$rel}&field={$field}");

                            }
                            if ($cont_field != false and is_string($cont_field)) {
                                $cont_field = htmlspecialchars_decode(html_entity_decode($cont_field, ENT_COMPAT, "UTF-8"));
                            }

                        }
                        if ($cont_field != false) {


                            $field_content = $cont_field;
                        }
                    }

                    if ($field_content == false) {
                        if ($get_global == true) {

                            $cont_field = get_content_field("rel={$rel}&field={$field}");


                            //dbg($cont_field);
                            if ($cont_field == false) {
                                if ($option_mod != false) {
                                    //$field_content = __FILE__ . __LINE__;
                                    //$field_content = mw('option')->get($field, $option_group, $return_full = false, $orderby = false);
                                    $field_content = get_content_field("rel={$option_group}&field={$field}");

                                    //
                                } else {
                                    $field_content = get_content_field("rel={$option_group}&field={$field}");

                                    //$field_content = __FILE__ . __LINE__;
                                    //$field_content = mw('option')->get($field, $option_group, $return_full = false, $orderby = false);
                                }

                            } else {
                                $field_content = $cont_field;
                            }


                        } else {


                            if ($use_id_as_field != false) {
                                if (isset($data[$use_id_as_field])) {
                                    $field_content = $data[$use_id_as_field];

                                }


                            }

                            if (isset($data[$field])) {

                                $field_content = $data[$field];

                            }
                        }

                        if ($field == 'content' and template_var('content') != false) {
                            $field_content = template_var('content');

                            template_var('content', false);
                        }


                        if (isset($data_id) and trim($data_id) != '' and $field_content == false and isset($rel) and isset($field) and trim($field) != '') {
                            $cont_field = get_content_field("rel={$rel}&field={$field}&rel_id=$data_id");

                            if ($cont_field != false) {
                                $field_content = $cont_field;
                            }


                        } else if ($field_content == false and isset($rel) and isset($field) and trim($field) != '') {
                            $cont_field = get_content_field("rel={$rel}&field={$field}");

                            if ($cont_field != false) {
                                $field_content = $cont_field;
                            }

                        }
                        if ($field_content == false and isset($data['custom_fields']) and !empty($data['custom_fields'])) {
                            foreach ($data ['custom_fields'] as $kf => $vf) {

                                if ($kf == $field) {

                                    //$field_content = ($vf);
                                }
                            }
                        }
                    }

                    if ($field_content != false and $field_content != '' and is_string($field_content)) {

                        $field_content = htmlspecialchars_decode(html_entity_decode($field_content, ENT_COMPAT, "UTF-8"));


                        //$field_content = htmlspecialchars_decode($field_content);

                        //$field_content = html_entity_decode($field_content, ENT_COMPAT, "UTF-8");
                        // pecata d($field_content);
                        //d($field_content);
                        $parser_mem_crc2 = 'parser_field_content_' . crc32($field_content);

                        $ch2 = mw_var($parser_mem_crc);

                        /*
                         if ($use_apc == true) {

                         $cache_id_apc = $parser_mem_crc2;

                         $apc_field_content = apc_fetch($cache_id_apc);

                         if ($apc_field_content != false) {
                         $ch2 = true;
                         $field_content = $apc_field_content;
                         //	d($field_content);
                         pq($elem) -> html($field_content);
                         }
                         }*/

                        if ($ch2 == false) {
                            //$field_content = mw('parser')->process($field_content, $options, $coming_from_parent, $coming_from_parent_id);
                            if ($field_content != false and $field_content != '') {
                                $mw_found_elems = ',' . $parser_mem_crc2;
                                //$field_content = htmlspecialchars_decode(html_entity_decode($field_content, ENT_COMPAT, "UTF-8"));
                                $mw_found_elems_arr[$parser_mem_crc2] = $field_content;

                                //pq($elem) -> html('<!--mw_replace_back_this_editable_' . $parser_mem_crc2.'-->');
                                pq($elem)->html('mw_replace_back_this_editable_' . $parser_mem_crc2 . '');

                            }
                            /*
                             if ($use_apc == true) {
                             @apc_store($cache_id_apc, $field_content, APC_EXPIRES);
                             }*/

                        }
                        mw_var($parser_mem_crc2, 1);

                    } else {

                    }
                }
                $layout = $pq->htmlOuter();
                $pq->__destruct();
                $pq = null;

                unset($pq);
                //if(strstr($haystack, $needle))
                mw_var($parser_mem_crc, $layout);
                if ($mw_found_elems != '') {
                    $mw_to_cache['new'] = $layout;
                    $mw_to_cache['to_replace'] = $mw_found_elems;
                    $mw_to_cache['elems'] = $mw_found_elems_arr;
                    //d($mw_to_cache);
                    // $mw_to_cache = base64_encode(serialize($mw_to_cache));


                    //mw('cache')->save($mw_to_cache, $parser_mem_crc, 'content_fields/global/parser');

                } else {
                    $mw_to_cache['new'] = $layout;
                    //as of beta

                    //mw('cache')->save($mw_to_cache, $parser_mem_crc, 'content_fields/global/parser');

                }
            }

        }

        if (isset($mw_to_cache) and !empty($mw_to_cache)) {

            if (isset($mw_to_cache['elems']) and isset($mw_to_cache['to_replace']) and isset($mw_to_cache['new'])) {


                $modified_layout = $mw_to_cache['new'];

                //$parser_mem_crc1 = 'parser_' . crc32($value['orig']);

                //$ch = mw_var($parser_mem_crc1);
                //$reps = explode(',', $mw_to_cache['to_replace']);

                //if ($ch == false) {
                $reps = $mw_to_cache['elems'];

                if ($passed_reps == NULL) {
                    $passed_reps = array();

                }

                foreach ($reps as $elk => $value) {
                    $elk_crc = crc32($elk);
                    if (!in_array($elk_crc, $passed_reps)) {
                        $passed_reps[] = $elk_crc;

                        if ($value != '') {
                            //$layout = $ch;
                            $val_rep = $value;
                            // $options['nested'] = 1;
                            $val_rep = htmlspecialchars_decode($val_rep);
                            //$options['parse_only_modules'] = 1;
                            //$options['no_cache'] = 1;
                            // if(strstr($val_rep,'edit') or strstr($val_rep,'<module')  or strstr($val_rep,'<microweber')){


                            $val_rep = $this->_replace_editable_fields($val_rep, true);
                            //}

                            //$rep = '<!--mw_replace_back_this_editable_' . $elk.'-->';
                            $rep = 'mw_replace_back_this_editable_' . $elk . '';
                            //$modified_layout = $rep;
                            //d($val_rep);
                            $modified_layout = str_replace($rep, $val_rep, $modified_layout);
                            //	mw_var($val_rep_parser_mem_crc, $modified_layout);


                        }
                    } else {
                        //$passed_reps[] = $elk_crc;
                        $rep = 'mw_replace_back_this_editable_' . $elk . '';
                        //$modified_layout = $rep;

                        $value = htmlspecialchars_decode($value);


                        //$value = mw('parser')->process($value, $options, $coming_from_parent, $coming_from_parent_id);
                        $modified_layout = str_replace($rep, $value, $modified_layout);
                    }
                }


                $layout = $modified_layout;
                $layout = html_entity_decode($layout, ENT_COMPAT, "UTF-8");
                $layout = htmlspecialchars_decode($layout);

                //}
            } elseif (isset($mw_to_cache['new'])) {

            }
            if ($no_cache == false) {
                mw('cache')->save($layout, $parser_mem_crc, 'content_fields/global/parser');
            }
            //
        }


        $passed_reps[$parser_mem_crc] = $layout;
        return $layout;


    }
}