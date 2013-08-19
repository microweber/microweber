<?php
namespace Microweber;
$passed_reps = array();
$parser_cache_object = false; //if apc is found it will automacally use it; you can use any object compatible with the cache interface
//$parse_micrwober_max_nest_level = 3;
$replaced_modules = array();
$replaced_modules_values = array();

$replaced_codes = array();

class Parser
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

    public function process($layout, $options = false, $coming_from_parent = false, $coming_from_parent_id = false)
    {


        global $replaced_modules;

        global $replaced_modules_values;

        global $replaced_codes; 
        // $layout = html_entity_decode($layout, ENT_COMPAT);
     //  $layout = htmlspecialchars_decode($layout);
 //print $layout;

        $layout = str_replace('<?', '&lt;?', $layout);

        //  preg_match_all("'<p class=\"review\">(.*?)</p>'si", $layout, $match);
        $script_pattern = "/<pre[^>]*>(.*)<\/pre>/Uis";
        preg_match_all($script_pattern, $layout, $mw_script_matches);
         // preg_match_all ("/<pre>([^`]*?)<\/pre>/", $layout, $mw_script_matches);

        if (!empty($mw_script_matches)) {
            foreach ($mw_script_matches [0] as $key => $value) {
                if ($value != '') {

                    $v1 = crc32($value);
                    $v1 = '<!-- mw_replace_back_this_process_pre_' . $v1 . ' -->';
                    $layout = str_replace($value, $v1, $layout);
                    if (!isset($replaced_codes[$v1])) {
                        $replaced_codes[$v1] = $value;

                    }
                }
            }
        }
        $layout = html_entity_decode($layout, ENT_COMPAT, "UTF-8");


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
                    $v1 = '<!-- mw_replace_back_this_module_111' . $v1 . ' -->';
                    $layout = str_replace($value, $v1, $layout);
                    if (!isset($replaced_modules[$v1])) {
                        $replaced_modules[$v1] = $value;
                    }
                }
            }
        }


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
                        if (!isset($replaced_scripts[$v1])) {
                            $replaced_scripts[$v1] = $value;
                        }
                    }
                }
            }

            $script_pattern = "/<code[^>]*>(.*)<\/code>/Uis";
            preg_match_all($script_pattern, $layout, $mw_script_matches);

            if (!empty($mw_script_matches)) {
                foreach ($mw_script_matches [0] as $key => $value) {
                    if ($value != '') {
                        $v1 = crc32($value);
                        $v1 = '<!-- mw_replace_back_this_code_' . $v1 . ' -->';
                        $layout = str_replace($value, $v1, $layout);
                        if (!isset($replaced_scripts[$v1])) {
                            $replaced_codes[$v1] = $value;
                        }
                    }
                }
            }


            // d($layout);

            preg_match_all('/.*?class=..*?edit.*?.[^>]*>/', $layout, $layoutmatches);
            if (!empty($layoutmatches) and isset($layoutmatches[0][0])) {

                $layout = $this->_replace_editable_fields($layout);

            }


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
                        // d($value);
                        $v1 = crc32($value);
                        $v1 = '<!-- mw_replace_back_this_module_111' . $v1 . ' -->';
                        $layout = str_replace($value, $v1, $layout);
                        // $layout = str_replace_count($value, $v1, $layout,1);
                        if (!isset($replaced_modules[$v1])) {

                            $replaced_modules[$v1] = $value;
                        }
                        // p($value);
                    }
                }
            }


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
                    //unset($replaced_modules[$key]);
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
                                    $attrs['id'] = $module_class . '-' . $this->app->url->segment(0) . ($mw_mod_counter1);
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
                                $module_name_url = $this->app->url->slug($module_name);

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
                                if ($coming_from_parent == false and isset($module_name) == true) {
                                    $coming_from_parentz = $module_name;
                                } else {
                                    $coming_from_parentz = $coming_from_parent;

                                }
                                $coming_from_parent_str = false;
                                if ($coming_from_parent == true) {
                                    $coming_from_parent_str = " data-parent-module='$coming_from_parent' ";
                                }


                                if ($coming_from_parent_id == false and isset($attrs['id']) == true) {
                                    $coming_from_parent_strz1 = $attrs['id'];
                                } else {
                                    $coming_from_parent_strz1 = $coming_from_parent_id;

                                }
                                if ($coming_from_parent_strz1 == true) {
                                    $attrs['data-parent-module'] = $coming_from_parent;
                                }
                                if ($coming_from_parent_id == true) {
                                    $attrs['data-parent-module-id'] = $coming_from_parent_strz1;
                                }

                                $mod_content = $this->app->module->load($module_name, $attrs);


                                // mwdbg($module_name);
                                $plain_modules = mw_var('plain_modules');
                                if ($plain_modules != false) {
                                    //d($plain_modules);
                                    $module_db_data = $this->app->module->get('one=1&ui=any&module=' . $module_name);
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
                                unset($replaced_modules[$key]);

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
                                $replaced_modules_values[$replace_key] = $module_html;

                                $layout = str_replace($value, $module_html, $layout);
                                $layout = str_replace($replace_key, $module_html, $layout);

                                //
                            }
                        }
                        //
                    }

                    $layout = str_replace($key, $value, $layout);
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


        if (!empty($replaced_modules_values)) {
            foreach ($replaced_modules_values as $key => $value) {
                if ($value != '') {

                    $layout = str_replace($key, $value, $layout);
                }
                //unset($replaced_modules_values[$key]);
            }
        }


        $layout = str_replace('{rand}', uniqid() . rand(), $layout);

        $layout = str_replace('{SITE_URL}', $this->app->url->site(), $layout);
        $layout = str_replace('{MW_SITE_URL}', $this->app->url->site(), $layout);
        $layout = str_replace('%7BSITE_URL%7D', $this->app->url->site(), $layout);


        return $layout;
        exit();
    }


    public function make_tags($layout)
    {


        if ($layout == '') {
            return $layout;
        }
        //$layout =htmlspecialchars_decode($layout ,  ENT_COMPAT );
        // $layout = html_entity_decode($layout);

        // $layout= html_entity_decode($layout,ENT_QUOTES,"UTF-8");
        // $layout= html_entity_decode($layout,ENT_COMPAT);

        require_once (MW_APP_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');



        $layout = str_replace("\u00a0", ' ', $layout);


        $script_pattern = "/<script[^>]*>(.*)<\/script>/Uis";
        $replaced_scripts = array();
        preg_match_all($script_pattern, $layout, $mw_script_matches);

        if (!empty($mw_script_matches)) {
            foreach ($mw_script_matches [0] as $key => $value) {
                if ($value != '') {

                    $v1 = ' ';
                    //  $v1 = htmlentities($value, ENT_QUOTES | ENT_IGNORE, "UTF-8");
                    //..  $v1 =htmlspecialchars ( $v1,  ENT_COMPAT );

                    $v1 = str_replace('<script', '<code language=javascript ', $value);
                    $v1 = str_replace('</script', '</code', $v1);

                    $layout = str_replace($value, $v1, $layout);

                }
            }
        }

        $layout = str_replace('<script>', '<code>', $layout);
        $layout = str_replace('</script>', '</code>', $layout);
        $layout = str_replace('&lt;\/script', '&lt;\/code', $layout);
        $layout = str_replace('&lt;script', '&lt;code', $layout);


        $layout = str_replace('<script', '&lt;pre', $layout);
        $layout = str_replace('</script', '&gt/pre', $layout);

        $layout = str_replace('<?', '&lt;?', $layout);
        $layout = str_replace('?>', '?&gt;', $layout);


//        $script_pattern = "/<pre[^>]*>(.*)<\/pre>/Uis";
//        $replaced_scripts = array();
//        preg_match_all($script_pattern, $layout, $mw_script_matches);
//
//        if (!empty($mw_script_matches)) {
//            foreach ($mw_script_matches [0] as $key => $value) {
//                if ($value != '') {
//
//                    //	$v1 = crc32($value);
//                    $v1 = ' ';
//                   // $v1 = htmlentities($value, ENT_QUOTES | ENT_IGNORE, "UTF-8");
//                   // $v1 =htmlspecialchars ( $v1,  ENT_COMPAT );
//
//                    $layout = str_replace($value, $v1, $layout);
//
//                }
//            }
//        }

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


        $layout = $pq->htmlOuter();
        // d($layout);
        // return $pq->htmlOuter();

        return $layout;
    }

    public function modify_html_preg($layout, $preg_match_all, $content = "", $action = 'append')
    {
        require_once (MW_APP_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');

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
        require_once (MW_APP_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');

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

    public function get_by_id($html_element_id = false, $layout)
    {
        require_once (MW_APP_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');

        if ($html_element_id == false) {
            if (isset($_REQUEST['embed_id'])) {
                $html_element_id = trim($_REQUEST['embed_id']);

            }
        }

        if ($html_element_id != false and trim($html_element_id) != '') {
            require_once (MW_APP_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');

            $pq = \phpQuery::newDocument($layout);

            foreach ($pq ['#' . $html_element_id] as $elem) {

                $isolated_el = pq($elem)->htmlOuter();
                $isolated_body = pq('body')->eq(0)->html($isolated_el);
                $body_new = $isolated_body->htmlOuter();

                $layout = pq(0)->htmlOuter();

            }

        }
        return $layout;
    }

    public function isolate_head($l)
    {
        require_once (MW_APP_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');
        $pq = \phpQuery::newDocument($l);

        $l = pq('head')->eq(0)->html();


        return $l;
    }

    public function get_html($l, $selector = 'body')
    {
        require_once (MW_APP_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');
        $pq = \phpQuery::newDocument($l);


        foreach ($pq [$selector] as $elem) {

            $l = pq($elem)->htmlOuter();
            return $l;
        }


        return false;
    }

    public function isolate_content_field($l)
    {
        require_once (MW_APP_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');
        $pq = \phpQuery::newDocument($l);


        foreach ($pq ['[field=content]'] as $elem) {

            $l = pq($elem)->htmlOuter();
        }


        return $l;
    }

    private function _replace_editable_fields($layout, $no_cache = false)
    {


        if ($layout != '') {
            global $mw_replaced_modules;
            global $passed_reps;
            $replaced_codes = array();
 




 

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
                $cache = $this->app->cache->get($parser_mem_crc, 'content_fields/global/parser');

                if ($cache != false) {


                    return $cache;
                }

            }









                  $script_pattern = "/<pre[^>]*>(.*)<\/pre>/Uis";
        preg_match_all($script_pattern, $layout, $mw_script_matches);
            // preg_match_all ("/<pre>([^`]*?)<\/pre>/", $layout, $mw_script_matches);

            if (!empty($mw_script_matches)) {
                foreach ($mw_script_matches [0] as $key => $value) {
                    if ($value != '') {

                        $v1 = crc32($value);
                        $v1 = '<!-- mw_replace_back_this_pre_' . $v1 . ' -->';
                        $layout = str_replace($value, $v1, $layout);
                        if (!isset($replaced_codes[$v1])) {
                            $replaced_codes[$v1] = $value;

                        }
                    }
                }
            }




            $script_pattern = "/<code[^>]*>(.*)<\/code>/Uis";
            preg_match_all($script_pattern, $layout, $mw_script_matches);
            // preg_match_all ("/<pre>([^`]*?)<\/pre>/", $layout, $mw_script_matches);

            if (!empty($mw_script_matches)) {
                foreach ($mw_script_matches [0] as $key => $value) {
                    if ($value != '') {

                        $v1 = crc32($value);
                        $v1 = '<!-- mw_replace_back_this_pre_code_' . $v1 . ' -->';
                        $layout = str_replace($value, $v1, $layout);
                        if (!isset($replaced_codes[$v1])) {
                            $replaced_codes[$v1] = $value;

                        }
                    }
                }
            }


















            $ch = mw_var($parser_mem_crc);
            if ($cached != false) {
                $mw_to_cache = $cached;

            } else if ($ch != false) {

                $layout = $ch;
            } else {
                require_once (MW_APP_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');

                $layout = html_entity_decode($layout, ENT_COMPAT, "UTF-8");
                //$layout = htmlspecialchars_decode($layout);

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
                                //$cont_field = htmlspecialchars_decode(html_entity_decode($cont_field, ENT_COMPAT, "UTF-8"));
                            }

                        }
                        if ($cont_field != false) {


                            $field_content = $cont_field;
                        }
                    }

                    if ($field_content == false) {
                        if ($get_global == true) {

                            $cont_field = get_content_field("rel={$rel}&field={$field}");


                            //mwdbg($cont_field);
                            if ($cont_field == false) {
                                if ($option_mod != false) {
                                    //$field_content = __FILE__ . __LINE__;
                                    //$field_content = $this->app->option->get($field, $option_group, $return_full = false, $orderby = false);
                                    $field_content = get_content_field("rel={$option_group}&field={$field}");

                                    //
                                } else {
                                    $field_content = get_content_field("rel={$option_group}&field={$field}");

                                    //$field_content = __FILE__ . __LINE__;
                                    //$field_content = $this->app->option->get($field, $option_group, $return_full = false, $orderby = false);
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

                        //$field_content = htmlspecialchars_decode(html_entity_decode($field_content, ENT_COMPAT, "UTF-8"));


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


                    //$this->app->cache->save($mw_to_cache, $parser_mem_crc, 'content_fields/global/parser');

                } else {
                    $mw_to_cache['new'] = $layout;
                    //as of beta

                    //$this->app->cache->save($mw_to_cache, $parser_mem_crc, 'content_fields/global/parser');

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
                            //  $val_rep = htmlspecialchars_decode($val_rep);
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

                        //   $value = htmlspecialchars_decode($value);


                        //$value = mw('parser')->process($value, $options, $coming_from_parent, $coming_from_parent_id);
                        $modified_layout = str_replace($rep, $value, $modified_layout);
                    }
                }


                $layout = $modified_layout;
                // $layout = html_entity_decode($layout, ENT_COMPAT, "UTF-8");
                //    $layout = htmlspecialchars_decode($layout);

                //}
            } elseif (isset($mw_to_cache['new'])) {

            }


            if (!empty($replaced_codes)) {
                foreach ($replaced_codes as $key => $value) {
                    if ($value != '') {

                        $layout = str_replace($key, $value, $layout);
                    }
                    unset($replaced_codes[$key]);
                }
            }

            if ($no_cache == false) {
                $this->app->cache->save($layout, $parser_mem_crc, 'content_fields/global/parser');
            }
            //
        }


        $passed_reps[$parser_mem_crc] = $layout;
        return $layout;


    }


}