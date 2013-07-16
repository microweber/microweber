<?php

function parse_elem_callback($elem)
{
    $name = $elem->getAttr('field');

    if (strval($name) == '') {
        $name = $elem->getAttr('id');
    }

    if (strval($name) == '') {
        $name = $elem->getAttr('data-field');
    }

    // $fld_id = $elem->getAttr('data-field-id');

    $rel = $elem->getAttr('rel');
    if ($rel == false) {
        $rel = 'page';
    }

    $option_group = $elem->getAttr('data-option_group');
    if ($option_group == false) {
        $option_group = 'editable_region';
    }
    $data_id = $elem->getAttr('data-id');

    $option_mod = $elem->getAttr('data-module');
    if ($option_mod == false) {
        $option_mod = $elem->getAttr('data-type');
    }
    if ($option_mod == false) {
        $option_mod = $elem->getAttr('type');
    }

    // d($name);

    $get_global = false;
    //  $rel = 'page';
    $field = $name;
    $use_id_as_field = $name;

    if ($rel == 'global') {
        $get_global = true;
    } else {
        $get_global = false;
    }

    if ($rel == 'module') {
        $get_global = true;
    }
    if ($get_global == false) {
        //  $rel = 'page';
    }

    if ($rel == 'content') {
        if ($data_id != false) {
            $data_id = intval($data_id);
            $data = get_content_by_id($data_id);
            $data['custom_fields'] = get_custom_fields_for_content($data_id, 0);
        }
    } else if ($rel == 'page') {
        $data = get_page(PAGE_ID);
        $data['custom_fields'] = get_custom_fields_for_content($data['id'], 0);
    } else if (isset($attr['post'])) {
        $data = get_post($attr['post']);
        if ($data == false) {
            $data = get_page($attr['post']);
            $data['custom_fields'] = get_custom_fields_for_content($data['id'], 0);
        }
    } else if (isset($attr['category'])) {
        $data = get_category($attr['category']);
    } else if (isset($attr['global'])) {
        $get_global = true;
    }
    $cf = false;
    $field_content = false;

    if ($get_global == true) {

        if ($option_mod != false) {
            //   d($field);

            $field_content = get_option($field, $option_group, $return_full = false, $orderby = false, $option_mod);
            //
        } else {
            $field_content = get_option($field, $option_group, $return_full = false, $orderby = false);
        }
    } else {

        if ($use_id_as_field != false) {
            if (isset($data[$use_id_as_field])) {
                $field_content = $data[$use_id_as_field];
            }
            if ($field_content == false) {
                if (isset($data['custom_fields'][$use_id_as_field])) {
                    $field_content = $data['custom_fields'][$use_id_as_field];
                }
                // d($field_content);
            }
        }

        //  if ($field_content == false) {
        if (isset($data[$field])) {

            $field_content = $data[$field];
        }
        //}
    }

    if ($field_content == false and isset($data['custom_fields']) and !empty($data['custom_fields'])) {
        foreach ($data ['custom_fields'] as $kf => $vf) {

            if ($kf == $field) {

                $field_content = ($vf);
            }
        }
    }

    //  d($field);

    if ($field_content != false and $field_content != '') {
        $field_content = htmlspecialchars_decode($field_content);

        //$field_content = html_entity_decode($field_content, ENT_COMPAT, "UTF-8");
        // d($field_content);
        $field_content = parse_micrwober_tags($field_content);

        $elem->setInnerHTML($field_content);
    } else {

    }
    return $elem;
}

class MwDom extends DOMDocument
{

    function getElementById($id)
    {

        //thanks to: http://www.php.net/manual/en/domdocument.getelementbyid.php#96500
        $xpath = new DOMXPath($this);
        return $xpath->query("//*[@id='$id']")->item(0);
    }

    function getElementByClass($id)
    {

        $xpath = new DOMXPath($this);
        return $xpath->query('//div[contains(@class,"' . $id . '")]');
    }

    function output()
    {

        // thanks to: http://www.php.net/manual/en/domdocument.savehtml.php#85165

        /*
         $output = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $this->saveHTML()));
         */
        $output = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $this->saveHTML(), $c = 1), 1);

        return html_entity_decode($output, false, "UTF-8");
    }

}

// include_once ('parser/phpQuery.php');


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

        //        $script_pattern = "/<head[^>]*>(.*)<\/head>/Uis";
        //        $replaced_head = array();
        //        preg_match_all($script_pattern, $layout, $mw_script_matches);
        //
        //        if (!empty($mw_script_matches)) {
        //            foreach ($mw_script_matches [0] as $key => $value) {
        //                if ($value != '') {
        //                    $v1 = crc32($value);
        //                    $v1 = '<!-- mw_replace_back_this_head_' . $v1 . ' -->';
        //                    $layout = str_replace($value, $v1, $layout);
        //                    // $layout = str_replace_count($value, $v1, $layout,1);
        //                    if (!isset($replaced_scripts [$v1])) {
        //                        $replaced_head [$v1] = $value;
        //                    }
        //                    // p($value);
        //                }
        //            }
        //        }
        // $layout = html_entity_decode($layout, ENT_COMPAT, "UTF-8");
        // $layout = str_replace('<script ', '<TEXTAREA ', $layout);
        // $layout = str_replace('</script', '</TEXTAREA', $layout);
        //        if (isset($_GET['test_cookie'])) {
        //            $parse_mode = intval($_GET['test_cookie']);
        //        } else {
        //            $parse_mode = 1;
        //        }

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
         * $attrs); $mod_content = parse_micrwober_tags($mod_content); if
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

$mw_rand = rand();

function OLD_make_microweber_tags($layout)
{
    if ($layout == '') {
        return $layout;
    }

    require_once (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . 'phpQuery.php');

    $pq = phpQuery::newDocument($layout);
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

function make_microweber_tags($layout)
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

    $pq = phpQuery::newDocument($layout);
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

/**
 *
 * @author Peter Ivanov
 *
 *         function groupsSave($data) {
 *         $table = $table = MW_TABLE_PREFIX . 'groups';
 *         $criteria = $this->input->xss_clean ( $data );
 *         $criteria = $this->core_model->mapArrayToDatabaseTable ( $table,
 *         $data );
 *         $save = $this->core_model->saveData ( $table, $criteria );
 *         return $save;
 *         }
 */
function replace_in_long_text($sRegExpPattern, $sRegExpReplacement, $sVeryLongText, $normal_replace = false)
{
    $function_cache_id = false;

    $test_for_long = strlen($sVeryLongText);
    if ($test_for_long > 1000) {
        $args = func_get_args();
        $i = 0;
        foreach ($args as $k => $v) {
            if ($i != 2) {
                $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
            } else {

            }
            $i++;
        }

        $function_cache_id = __FUNCTION__ . crc32($sVeryLongText) . crc32($function_cache_id);

        $cache_group = 'extract_tags';
        // $cache_content = $this->cacheGetContent ( $function_cache_id,
        // $cache_group );
        if (($cache_content) != false) {
            // return $cache_content;
        }
    }

    if ($normal_replace == false) {
        $iSet = 0;
        // Count how many times we increase the limit
        while ($iSet < 10) { // If the default limit is 100'000 characters
            // the highest new limit will be 250'000
            // characters
            $sNewText = preg_replace($sRegExpPattern, $sRegExpReplacement, $sVeryLongText);
            // Try
            // to
            // use
            // PREG
            if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR) { // Only
                // check on
                // backtrack
                // limit
                // failure
                ini_set('pcre.backtrack_limit', (int)ini_get('pcre.backtrack_limit') + 15000);
                // Get
                // current
                // limit
                // and
                // increase
                $iSet++;
                // Do not overkill the server
            } else { // No fail
                $sVeryLongText = $sNewText;
                // On failure $sNewText would be NULL
                break;
                // Exit loop
            }
        }
    } else {
        $sNewText = str_replace($sRegExpPattern, $sRegExpReplacement, $sVeryLongText);
        // $sNewText = preg_replace($sRegExpPattern,$sRegExpReplacement,
        // $sVeryLongText);
    }

    return $sNewText;
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

function parseTextForEmail($text)
{
    $email = array();
    $invalid_email = array();

    $text = ereg_replace("[^A-Za-z._0-9@ ]", " ", $text);

    $token = trim(strtok($text, " "));

    while ($token !== "") {

        if (strpos($token, "@") !== false) {

            $token = ereg_replace("[^A-Za-z._0-9@]", "", $token);

            // checking to see if this is a valid email address
            if (is_valid_email($email) !== true) {
                $email[] = strtolower($token);
            } else {
                $invalid_email[] = strtolower($token);
            }
        }

        $token = trim(strtok(" "));
    }

    $email = array_unique($email);
    $invalid_email = array_unique($invalid_email);

    return array("valid_email" => $email, "invalid_email" => $invalid_email);
}

function is_valid_email($email)
{
    if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.([a-z]){2,4})$", $email))
        return true;
    else
        return false;
}

/**
 * Decode Unicode Characters from \u0000 ASCII syntax.
 *
 * This algorithm was originally developed for the
 * Solar Framework by Paul M. Jones
 *
 * @link http://solarphp.com/
 * @link http://svn.solarphp.com/core/trunk/Solar/Json.php
 * @param string $value
 * @return string
 */
function decodeUnicodeString($chrs)
{
    $delim = substr($chrs, 0, 1);
    $utf8 = '';
    $strlen_chrs = strlen($chrs);

    for ($i = 0; $i < $strlen_chrs; $i++) {

        $substr_chrs_c_2 = substr($chrs, $i, 2);
        $ord_chrs_c = ord($chrs[$i]);

        switch (true) {
            case preg_match('/\\\u[0-9A-F]{4}/i', substr($chrs, $i, 6)) :
                // single, escaped unicode character
                $utf16 = chr(hexdec(substr($chrs, ($i + 2), 2))) . chr(hexdec(substr($chrs, ($i + 4), 2)));
                $utf8 .= self::_utf162utf8($utf16);
                $i += 5;
                break;
            case ($ord_chrs_c >= 0x20) && ($ord_chrs_c <= 0x7F) :
                $utf8 .= $chrs{$i};
                break;
            case ($ord_chrs_c & 0xE0) == 0xC0 :
                // characters U-00000080 - U-000007FF, mask 110XXXXX
                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                $utf8 .= substr($chrs, $i, 2);
                ++$i;
                break;
            case ($ord_chrs_c & 0xF0) == 0xE0 :
                // characters U-00000800 - U-0000FFFF, mask 1110XXXX
                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                $utf8 .= substr($chrs, $i, 3);
                $i += 2;
                break;
            case ($ord_chrs_c & 0xF8) == 0xF0 :
                // characters U-00010000 - U-001FFFFF, mask 11110XXX
                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                $utf8 .= substr($chrs, $i, 4);
                $i += 3;
                break;
            case ($ord_chrs_c & 0xFC) == 0xF8 :
                // characters U-00200000 - U-03FFFFFF, mask 111110XX
                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                $utf8 .= substr($chrs, $i, 5);
                $i += 4;
                break;
            case ($ord_chrs_c & 0xFE) == 0xFC :
                // characters U-04000000 - U-7FFFFFFF, mask 1111110X
                // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                $utf8 .= substr($chrs, $i, 6);
                $i += 5;
                break;
        }
    }

    return $utf8;
}

/**
 * Convert a string from one UTF-16 char to one UTF-8 char.
 *
 * Normally should be handled by mb_convert_encoding, but
 * provides a slower PHP-only method for installations
 * that lack the multibye string extension.
 *
 * This method is from the Solar Framework by Paul M. Jones
 *
 * @link http://solarphp.com
 * @param string $utf16
 *            UTF-16 character
 * @return string UTF-8 character
 */
function utf162utf8($utf16)
{
    // Check for mb extension otherwise do by hand.
    if (function_exists('mb_convert_encoding')) {
        return mb_convert_encoding($utf16, 'UTF-8', 'UTF-16');
    }

    $bytes = (ord($utf16{0}) << 8) | ord($utf16{1});

    switch (true) {
        case ((0x7F & $bytes) == $bytes) :
            // this case should never be reached, because we are in ASCII range
            // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
            return chr(0x7F & $bytes);

        case (0x07FF & $bytes) == $bytes :
            // return a 2-byte UTF-8 character
            // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
            return chr(0xC0 | (($bytes >> 6) & 0x1F)) . chr(0x80 | ($bytes & 0x3F));

        case (0xFFFF & $bytes) == $bytes :
            // return a 3-byte UTF-8 character
            // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
            return chr(0xE0 | (($bytes >> 12) & 0x0F)) . chr(0x80 | (($bytes >> 6) & 0x3F)) . chr(0x80 | ($bytes & 0x3F));
    }

    // ignoring UTF-32 for now, sorry
    return '';
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

    // echo $string_html;

    $layout = $string_html;

    //$layout = str_replace($selector, $selector . $content, $layout);

    return $layout;
}

function mw_dom($layout, $selector, $content = "", $action = 'append')
{
    return modify_html_slow($layout, $selector, $content, $action);
}

function modify_html_slow($layout, $selector, $content = "", $action = 'append')
{

    $pq = phpQuery::newDocument($layout);

    $els = $pq[$selector];
    foreach ($els as $elem) {
        //  pq($elem)->html($field_content);
        pq($elem)->$action($content);
        //
    }
    $layout = $pq->htmlOuter();

    return $layout;
}

function clean_word($html_to_save)
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

        // $tags = extract_tags ( $html_to_save, 'style', $selfclosing = false,
        // $return_the_entire_tag = true, $charset = 'UTF-8' );
        //
        // $matches = $tags;
        // if (! empty ( $matches )) {
        // foreach ( $matches as $m ) {
        // $html_to_save = str_replace ( $m ['full_tag'], '', $html_to_save );
        // }
        // }
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
    // $html_to_save = str_replace ( '<br><br>', '<div><br><br></div>',
    // $html_to_save );
    // $html_to_save = str_replace ( '<br /><br />', '<div><br /><br /></div>',
    // $html_to_save );
    $html_to_save = preg_replace('/<!--(.*)-->/Uis', '', $html_to_save);
    // $html_to_save = '<p>' . str_replace("<br />","<br />", str_replace("<br
    // /><br />", "</p><p>", $html_to_save)) . '</p>';
    // $html_to_save = str_replace(array("<p></p>", "<p><h2>", "<p><h1>",
    // "<p><div", "</pre></p>", "<p><pre>", "</p></p>", "<p></td>", "<p><p",
    // "<p><table", "<p><p", "<p><table"), array("<p>&nbsp;</p>", "<h2>",
    // "<h1>", "<div", "</pre>", "<pre>", "</p>", "</td>", "<p", "<table", "<p",
    // "<table"), $html_to_save);
    // p($html_to_save);
    return $html_to_save;
}
