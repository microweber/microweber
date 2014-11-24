<?php
namespace Microweber;

$parser_cache_object = false; //global cache storage
$mw_replaced_modules = array();
$mw_replaced_edit_fields_vals = array();
$mw_replaced_edit_fields_vals_inner = array();

$mw_replaced_modules_values = array();
$mw_parser_nest_counter_level = 0;

class Parser
{
    public $app;
    public $page = array();
    public $params = array();

    private $_mw_parser_passed_hashes = array();
    private $_mw_parser_passed_replaces = array();
    private $_replaced_modules_values = array();
    private $_replaced_modules = array();
    private $_replaced_codes = array();
    private $_existing_module_ids = array();
    private $_current_parser_rel = false;

    function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }

    }

    public function process($layout, $options = false, $coming_from_parent = false, $coming_from_parent_id = false)
    {
        global $mw_replaced_edit_fields_vals;

        if (!isset($parser_mem_crc)) {
            $parser_mem_crc = 'parser_' . crc32($layout) . CONTENT_ID;
            $parser_modules_crc = 'parser_modules' . crc32($layout) . CONTENT_ID;
        }
        //$this->layout = $layout;
        static $process_started;
        if($process_started == false){
            $process_started = true;

            $this->app->event->trigger('parser.process',$layout);
        }


        if (isset($mw_replaced_edit_fields_vals[$parser_mem_crc])) {
            return $mw_replaced_edit_fields_vals[$parser_mem_crc];
        }


        global $mw_replaced_modules;
         global $mw_replaced_modules_values;

        $layout = str_replace('<?']= '&lt;?', $layout);

//        $script_pattern = "/<pre[^>]*>(.*)<\/pre>/Uis";
//        preg_match_all($script_pattern, $layout, $mw_script_matches);
//
//        if (!empty($mw_script_matches)) {
//            foreach ($mw_script_matches [0] as $key => $value) {
//                if ($value != '') {
//                    $v1 = crc32($value);
//                    $v1 = '<!-- mw_replace_back_this_process_pre_' . $v1 . ' -->';
//                    $layout = str_replace($value, $v1, $layout);
//                    if (!isset($this->_replaced_codes[$v1])) {
//                        $this->_replaced_codes[$v1] = $value;
//                    }
//                }
//            }
//        }


        $layout = str_replace('<microweber module=']= '<module data-type=', $layout);
        $layout = str_replace('</microweber>']= '', $layout);
        $layout = str_replace('></module>']= '/>', $layout);


        $script_pattern = "/<module[^>]*>/Uis";
        preg_match_all($script_pattern, $layout, $mw_script_matches);

        if (!empty($mw_script_matches)) {
            $matches1 = $mw_script_matches[0];
            foreach ($matches1 as $key => $value) {
                if ($value != '') {
                    $v1 = crc32($value);
                    $v1 = '<!-- mw_replace_back_this_module_' . $v1 . ' -->';
                    $layout = str_replace($value, $v1, $layout);
                    if (!isset($mw_replaced_modules[$v1])) {
                        $mw_replaced_modules[$v1] = $value;
                    }
                }
            }
        }


        if (!isset($options['parse_only_vars'])) {

            $layout = str_replace('<mw ']= '<module ', $layout);
            $layout = str_replace('<editable ']= '<div class="edit" ', $layout);
            $layout = str_replace('</editable>']= '</div>', $layout);

            $layout = str_replace('<microweber module=']= '<module data-type=', $layout);
            $layout = str_replace('</microweber>']= '', $layout);
            $layout = str_replace('></module>']= '/>', $layout);
            $replaced_scripts = array();



            $script_pattern = "/<script[^>]*>(.*)<\/script>/Uis";




            preg_match_all($script_pattern, $layout, $mw_script_matches);

            if (!empty($mw_script_matches)) {
                foreach ($mw_script_matches [0] as $key => $value) {
                    if ($value != '') {
                        $v1 = crc32($value);

                        $v1 = '<x-tag> mw_replace_back_this_script_' . $v1 . ' </x-tag>';
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
                        //$v1 = '[[[mw_replace_back_this_code_' . $v1 . ' ]]]';
                        $v1 = '<tag>mw_replace_back_this_code_' . $v1 . '</tag>';

                        $layout = str_replace($value, $v1, $layout);
                        if (!isset($replaced_scripts[$v1])) {
                            $this->_replaced_codes[$v1] = $value;
                        }
                    }
                }
            }

            preg_match_all('/.*?class=..*?edit.*?.[^>]*>/', $layout, $layoutmatches);
            if (!empty($layoutmatches) and isset($layoutmatches[0][0])) {

                $layout = $this->_replace_editable_fields($layout);

            }

            $layout = str_replace('<microweber module=']= '<module data-type=', $layout);
            $layout = str_replace('</microweber>']= '', $layout);
            $layout = str_replace('></module>']= '/>', $layout);
            $script_pattern = "/<module[^>]*>/Uis";

            preg_match_all($script_pattern, $layout, $mw_script_matches);
            if (!empty($mw_script_matches)) {
                $matches1 = $mw_script_matches[0];
                foreach ($matches1 as $key => $value) {
                    if ($value != '') {
                        $v1 = crc32($value);
                        $v1 = '<!-- mw_replace_back_this_module_111' . $v1 . ' -->';
                        $layout = str_replace($value, $v1, $layout);
                        if (!isset($mw_replaced_modules[$v1])) {
                            $mw_replaced_modules[$v1] = $value;
                        }
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


            if (is_array($mw_replaced_modules)) {
                $attribute_pattern = '@
			(?P<name>\w+)# attribute name
			\s*=\s*
			(
				(?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote) # a quoted value
				| # or
				(?P<value_unquoted>[^\s"\']+?)(?:\s+|$)  # an unquoted value (terminated by whitespace or EOF)
				)
@xsi';

                $attribute_pattern = '@(?P<name>[a-z-_A-Z]+)\s*=\s*((?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)|(?P<value_unquoted>[^\s"\']+?)(?:\s+|$))@xsi';
                $attribute_pattern = '@(?P<name>[a-z-_A-Z]+)\s*=\s*((?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)|(?P<value_unquoted>[^\s"\']+?)(?:\s+|$))@xsi';

                $attrs = array();
                foreach ($mw_replaced_modules as $key => $value) {
                    if ($value != '') {


                        $replace_key = $key;
                        $attrs = array();
                        if (preg_match_all($attribute_pattern, $value, $attrs1, PREG_SET_ORDER)) {
                            foreach ($attrs1 as $item) {

                                $m_tag = trim($item[0], "\x22\x27");
                                $m_tag = trim($m_tag, "\x27\x22");
                                $m_tag = explode('=', $m_tag);

                                $a = trim($m_tag[0], "''");
                                $a = trim($a, '""');
                                $b = trim($m_tag[1], "''");
                                $b = trim($b, '""');
                                if (isset($m_tag[2])) {
                                    $rest_pieces = $m_tag;
                                    if (isset($rest_pieces[0])) {
                                        unset($rest_pieces[0]);
                                    }
                                    if (isset($rest_pieces[1])) {
                                        unset($rest_pieces[1]);
                                    }
                                    $rest_pieces = implode('=', $rest_pieces);
                                    $b = $b . $rest_pieces;
                                }

                                $attrs[$a] = $b;
                            }
                        }

                        $m_tag = ltrim($value, "<module");

                        $m_tag = rtrim($m_tag, "/>");
                        $m_tag = rtrim($m_tag);
                        $userclass = '';


                        $module_html = "<div class='__USER_DEFINED_CLASS__ __MODULE_CLASS__ __WRAP_NO_WRAP__' __MODULE_ID__ __MODULE_NAME__";

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
                            foreach ($attrs as $nn => $nv) {
                                if ($nn == 'class') {
                                    $module_has_class = $userclass = $nv;
                                    if (strstr($nv, 'module-as-element')) {
                                        $mod_as_element = true;
                                        $userclass = str_replace('module-as-element']= '', $userclass);
                                    }
                                    $userclass = str_replace(' module  module ']= 'module ', $userclass);
                                    $userclass = str_replace('ui-sortable']= '', $userclass);
                                    $userclass = str_replace('module-item']= '', $userclass);
                                    $userclass = str_replace('module module module']= 'module', $userclass);
                                    $userclass = str_replace('module  module ']= 'module ', $userclass);
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
                                    unset($attrs[$nn]);
                                }
                                if ($nn == 'data-no-wrap') {
                                    $mod_no_wrapper = true;
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
                                    $seg_clean = $this->app->url->segment(0);

                                    if (defined('IS_HOME')) {
                                        $seg_clean = '';
                                    }




                                    $seg_clean = str_replace('.']= '', $seg_clean);
                                    $seg_clean = str_replace('%20']= '-', $seg_clean);
                                    $mod_id = $module_class . '-' . $seg_clean . ($mw_mod_counter1);

                                    if($this->_current_parser_rel == 'global'){
                                        $mod_id = $module_class .  ($mw_mod_counter1);
                                    } else {
                                        $mod_id = $module_class . '-' . $seg_clean . ($mw_mod_counter1);
                                    }


                                     if(!in_array($mod_id, $this->_existing_module_ids)){
                                         $this->_existing_module_ids[] = $mod_id;
                                    } else {
                                        $mod_id = $mod_id.uniqid();
                                    }

                                    $attrs['id'] = $mod_id;
                                    $module_html = str_replace('__MODULE_ID__', "id='{$attrs['id']}'", $module_html);
                                } else {
                                    $module_html = str_replace('__MODULE_ID__']= '', $module_html);
                                }
                            }
                            if (is_array($module_title) and isset($module_title["name"])) {
                                $module_title["name"] = addslashes($module_title["name"]);
                                $module_html = str_replace('__MODULE_NAME__']= ' data-mw-title="' . $module_title["name"] . '"', $module_html);
                            } else {
                                $module_html = str_replace('__MODULE_NAME__']= '', $module_html);
                            }

                            if (isset($module_name)) {
                                if (strstr($module_name, 'admin')) {
                                    $module_html = str_replace('__WRAP_NO_WRAP__']= '', $module_html);
                                } else {
                                    $module_html = str_replace('__WRAP_NO_WRAP__']= '', $module_html);
                                }
                                $module_name_url = $this->app->url->slug($module_name);
                                if ($mod_as_element == false) {
                                    if (strstr($module_name, 'text')) {
                                        $module_html = str_replace('__MODULE_CLASS__']= 'layout-element ' . $module_name_url, $module_html);
                                    } else {
                                        $module_html = str_replace('__MODULE_CLASS__']= 'module ' . $module_class, $module_html);
                                    }
                                } else {
                                    $module_html = str_replace('__MODULE_CLASS__']= 'element ' . $module_name_url, $module_html);
                                }

                                $userclass = str_replace(trim($module_class), '', $userclass);
                                $userclass = trim(str_replace(' module ']= ' ', $userclass));
                                $userclass = trim(str_replace(' disabled module ']= ' module ', $userclass));
                                $module_class = trim(str_replace(' disabled module ']= ' module ', $module_class));
                                $userclass = trim(str_replace(' module module ']= ' module ', $userclass));
                                $module_html = str_replace('__MODULE_CLASS_NAME__']= '' . $module_class, $module_html);
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
                                $plain_modules = mw_var('plain_modules');

                                if ($plain_modules != false) {
                                    if (!defined('MW_PLAIN_MODULES')) {
                                        define('MW_PLAIN_MODULES', true);
                                    }
                                }


//                                if ($plain_modules != false) {
//
//                                        $module_db_data = $this->app->module->get('one=1&ui=any&module=' . $module_name);
//                                        $mod_content = '';
//                                        if (is_array($module_db_data)) {
//                                            if (isset($module_db_data["installed"]) and $module_db_data["installed"] != '' and intval($module_db_data["installed"]) != 1) {
//                                            } else {
//                                                $mod_content = '<span class="mw-plain-module-holder" data-module="' . addslashes($module_db_data['module']) . '" data-module-name="' . addslashes($module_db_data['name']) . '" data-module-description="' . addslashes($module_db_data['description']) . '" ><img class="mw-plain-module-icon" src="' . $module_db_data['icon'] . '" /><span class="mw-plain-module-name">' . $module_db_data['name'] . '</span></span>';
//                                            }
//                                        }
//
//                                }


                                $plain_modules = false;


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
                                unset($mw_replaced_modules[$key]);


                                if ($proceed_with_parse == true) {
                                    $mod_content = $this->process($mod_content, $options, $coming_from_parentz, $coming_from_parent_strz1);
                                }
                                if ($mod_no_wrapper == false) {
                                    $module_html .= $coming_from_parent_str . '>' . $mod_content . '</div>';
                                } else {
                                    $module_html = $mod_content;
                                }


                                $mw_replaced_modules_values[$replace_key] = $module_html;
                                $layout = str_replace($value, $module_html, $layout);
                                $layout = str_replace($replace_key, $module_html, $layout);
                            }
                        }
                    }
                    $layout = str_replace($key, $value, $layout);
                }
            }
        }

        if (!empty($this->_replaced_codes)) {
            foreach ($this->_replaced_codes as $key => $value) {
                if ($value != '') {

                    $layout = str_replace($key, $value, $layout);
                }
                unset($this->_replaced_codes[$key]);
            }
        }
        if (!empty($mw_replaced_modules_values)) {


            $reps_arr = array();
            $reps_arr2 = array();
            foreach ($mw_replaced_modules_values as $key => $value) {
                 if ($value != '') {
                    $reps_arr[] = $key;
                    $reps_arr2[] = $value;
                    // $layout = str_replace($key, $value, $layout);
                }
            }
            $layout = str_replace($reps_arr, $reps_arr2, $layout);
        }
        $layout = str_replace('{rand}', uniqid() . rand(), $layout);
        $layout = str_replace('{SITE_URL}', $this->app->url->site(), $layout);
        $layout = str_replace('{MW_SITE_URL}', $this->app->url->site(), $layout);
        $layout = str_replace('%7BSITE_URL%7D', $this->app->url->site(), $layout);
        $mw_replaced_edit_fields_vals[$parser_mem_crc] = $layout;
        return $layout;
    }
    public $filter = array();
    public function filter($callback){
        $this->filter[] = $callback;
    }
    private function _replace_editable_fields($layout, $no_cache = false)
    {

        if ($layout != '') {
            global $mw_replaced_modules;
            global $mw_replaced_edit_fields_vals;
            global $mw_parser_nest_counter_level;
            global $mw_replaced_edit_fields_vals_inner;
            $mw_parser_nest_counter_level++;
            $replaced_code_tags = array();
            if ($this->_mw_parser_passed_replaces == NULL) {
                $this->_mw_parser_passed_replaces = array();
            }

            $mw_found_elems = '';
            $mw_found_elems_arr = array();
            $mw_elements_array = array('orig', $layout);
            $cached = false;

            if (!isset($parser_mem_crc)) {
                $parser_mem_crc = 'parser_' . crc32($layout) . CONTENT_ID;
                $parser_modules_crc = 'parser_modules' . crc32($layout) . CONTENT_ID;
            }

            if (isset($this->_mw_parser_passed_replaces[$parser_mem_crc])) {
                return $this->_mw_parser_passed_replaces[$parser_mem_crc];
            }
            if (isset($mw_replaced_edit_fields_vals[$parser_mem_crc])) {
                // return false;
                return $mw_replaced_edit_fields_vals[$parser_mem_crc];
            }

            $script_pattern = "/<pre[^>]*>(.*)<\/pre>/Uis";
            preg_match_all($script_pattern, $layout, $mw_script_matches);
            if (!empty($mw_script_matches)) {
                foreach ($mw_script_matches [0] as $key => $value) {
                    if ($value != '') {
                        $v1 = crc32($value);
                        $v1 = '<!-- mw_replace_back_this_pre_' . $v1 . ' -->';
                        $layout = str_replace($value, $v1, $layout);
                        if (!isset($replaced_code_tags[$v1])) {
                            $replaced_code_tags[$v1] = $value;
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
                        $v1 = '<!-- mw_replace_back_this_pre_code_' . $v1 . ' -->';
                        $layout = str_replace($value, $v1, $layout);
                        if (!isset($replaced_code_tags[$v1])) {
                            $replaced_code_tags[$v1] = $value;
                        }
                    }
                }
            }


            $ch = mw_var($parser_mem_crc);
            if ($cached != false) {
                $mw_elements_array = $cached;

            } else if ($ch != false) {

                $layout = $ch;
            } else {
                require_once (MW_APP_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');
                $pq = \phpQuery::newDocument($layout);
                $els = $pq['.edit'];
                $is_editable = true;
                foreach ($els as $elem) {


                    // iteration returns PLAIN dom nodes, NOT phpQuery objects
                    $tagName = $elem->tagName;
                    $name = pq($elem)->attr('field');
                    if (strval($name) == '') {
                        $name = pq($elem)->attr('data-field');
                    }
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
                    if ($data_id == false) {
                        $data_id = pq($elem)->attr('rel_id');
                    }
                    if ($data_id == false) {
                        $data_id = pq($elem)->attr('data-rel-id');
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
                    $field = $name;
                    $use_id_as_field = $name;
                    if ($rel == 'global') {
                        $get_global = true;
                    } else {
                        $get_global = false;
                    }




                    $try_inherited = false;
                    if ($rel == 'content') {
                        if (!isset($data_id) or $data_id == false) {
                            $data_id = CONTENT_ID;
                        }
                        $get_global = false;
                        $data_id = intval($data_id);
                        $data = $this->app->content->get_by_id($data_id);
                    } else if ($rel == 'page') {
                        if (!isset($data_id) or $data_id == false) {
                            $data_id = PAGE_ID;
                        }
                        if (!isset($data_id) or $data_id == false) {
                            $data_id = CONTENT_ID;
                        }
                        $data = $this->app->content->get_by_id($data_id);
                        $get_global = false;
                    } else if ($rel == 'post') {
                        $get_global = false;
                        if (!isset($data_id) or $data_id == false) {
                            $data_id = POST_ID;
                        }
                        if (!isset($data_id) or $data_id == false) {
                            $data_id = PAGE_ID;
                        }
                        $data = $this->app->content->get_by_id($data_id);
                    } else if ($rel == 'inherit') {
                        $get_global = false;
                        if (!isset($data_id) or $data_id == false) {
                            $data_id = PAGE_ID;
                        }
                        $data_inh_check = $this->app->content->get_by_id($data_id);





                        if (isset($data_inh_check['id']) and isset($data_inh_check['layout_file']) and (trim($data_inh_check['layout_file']) != '') and $data_inh_check['layout_file'] != 'inherit') {
                            $inh = $data_inh_check['id'];

                        } else {
                            $inh = $this->app->content->get_inherited_parent($data_id);

                        }
                        if ($inh != false and intval($inh) != 0) {
                            $try_inherited = true;
                            $data_id = $inh;
                            $rel = 'content';
                            $data = $this->app->content->get_by_id($data_id);

                        } else {
                            $rel = 'content';
                            $data = $this->app->content->get_page($data_id);
                        }
                    } else if ($rel == 'global') {
                        $get_global = 1;
                        $cont_field = false;
                    } else if (isset($attr['post'])) {
                        $get_global = false;
                        $data = $this->app->content->get_by_id($attr['post']);
                        if ($data == false) {
                            $data = $this->app->content->get_page($attr['post']);
                        }
                    } else if (isset($attr['category'])) {
                        $get_global = false;
                        $data = $this->app->category->get_by_id($attr['category']);
                    } else if (isset($attr['global'])) {
                        $get_global = true;
                    }
                    $cf = false;
                    $field_content = false;
                    $orig_rel = $rel;





                    if(!empty($this->filter)){
                        foreach($this->filter as $filter){
                            if(isset($data)){
                                $new_data = call_user_func($filter, $data,$elem);
                                if(is_array($new_data) and !empty($new_data)){
                                    $data = array_merge($data,$new_data);

                                }
                            }
                        }
                    }

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
                        if (isset($data_id) and $data_id != 0 and trim($data_id) != '' and trim($field) != '') {
                            $cont_field = $this->app->content->edit_field("rel={$rel}&field={$field}&rel_id=$data_id");
                            if ($cont_field == false and $try_inherited == true) {
                                $inh = $this->app->content->get_inherited_parent($data_id);
                                if ($inh != false and intval($inh) != 0 and $inh != $data_id) {
                                    $data_id = $inh;
                                    $cont_field2 = $this->app->content->edit_field("rel={$rel}&field={$field}&rel_id=$inh");
                                    if ($cont_field2 != false) {
                                        $rel = 'content';
                                        $data = $this->app->content->get_by_id($inh);
                                        $cont_field = $cont_field2;
                                    }
                                }
                            }
                        } else {
                            if (isset($data_id) and trim($data_id) != '' and $field_content == false and isset($rel) and isset($field) and trim($field) != '') {
                                $cont_field = $this->app->content->edit_field("rel={$rel}&field={$field}&rel_id=$data_id");
                                if ($cont_field != false) {
                                    $field_content = $cont_field;
                                }
                            } else {
                                $cont_field = $this->app->content->edit_field("rel={$rel}&field={$field}");
                            }
                        }

                        if ($cont_field != false) {
                            $field_content = $cont_field;
                        }
                        $mw_replaced_edit_fields_vals[$parser_mem_crc] = $field_content;

                    }
                    if ($rel == 'global') {
                        $field_content = false;
                        $get_global = 1;
                    }

                 //   $filter



                    $this->_current_parser_rel = $rel;



                    $no_edit = false;


                    if ($field_content == false) {
                        if ($get_global == true) {
                            if (isset($data_id)) {
                                $cont_field = $this->app->content->edit_field("rel={$rel}&field={$field}&rel_id=$data_id");
                            }

                            if (isset($cont_field) and !empty($cont_field)) {
                                $cont_field = $this->app->content->edit_field("rel={$rel}&field={$field}");
                            }

                            if ($cont_field == false) {
                                if ($option_mod != false) {
                                    $field_content = $this->app->content->edit_field("rel={$option_group}&field={$field}");
                                } else {
                                    $field_content = $this->app->content->edit_field("rel={$option_group}&field={$field}");
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
                            if ($field_content == false) {
                                if (isset($data_id) and $data_id != false) {
                                    $cont_field = $this->app->content->edit_field("rel={$orig_rel}&field={$field}&rel_id=$data_id");
                                } else {
                                    $cont_field = $this->app->content->edit_field("rel={$orig_rel}&field={$field}&rel_id=" . PAGE_ID);
                                }
                            }
                            if (isset($data[$field])) {
                                $field_content = $data[$field];
                            } else {
                                if (isset($cont_field) and $cont_field != false) {
                                    $field_content = $cont_field;
                                }
                            }
                        }
                        if ($field == 'content' and template_var('content') != false) {
                            $field_content = template_var('content');
                            template_var('content', false);
                            $no_edit = template_var('no_edit');



                        }
                        if (isset($data_id) and trim($data_id) != '' and $field_content == false and isset($rel) and isset($field) and trim($field) != '') {
                            $cont_field = $this->app->content->edit_field("rel={$rel}&field={$field}&rel_id=$data_id");
                            if ($cont_field != false) {
                                $field_content = $cont_field;
                            }
                        } else if ($field_content == false and isset($rel) and isset($field) and trim($field) != '') {
                            $cont_field = $this->app->content->edit_field("rel={$rel}&field={$field}");
                            if ($cont_field != false) {
                                $field_content = $cont_field;
                            }
                        }
                    }

                    if ($field_content != false and $field_content != '' and is_string($field_content)) {
                        $parser_mem_crc2 = 'parser_field_content_' . $field . crc32($field_content);
                        $ch2 = mw_var($parser_mem_crc);
                        if ($ch2 == false) {
                            $this->_mw_parser_passed_hashes[] = $parser_mem_crc2;
                            if (!isset($mw_replaced_edit_fields_vals[$parser_mem_crc2]) and $field_content != false and $field_content != '') {
                                $mw_replaced_edit_fields_vals[$parser_mem_crc2] = $ch2;
                                $parser_mem_crc3 = 'mw_replace_back_this_editable_' . $parser_mem_crc2 . '';

                                $mw_found_elems = ',' . $parser_mem_crc2;
                                $mw_found_elems_arr[$parser_mem_crc2] = $field_content;
                                $rep = pq($elem)->html();

                                if($no_edit != false or (isset($data) and isset($data['no_edit']) and $data['no_edit'] !=false)){
                                    $is_editable = false;
                                    if($is_editable === false){
                                        pq($elem)->removeClass('edit');
                                    } else {

                                    }
                                    $is_editable = 1;

                                }
                                $mw_replaced_edit_fields_vals_inner[$parser_mem_crc3] = array('s' => $rep, 'r' => $field_content);


                            }
                        }
                        mw_var($parser_mem_crc2, 1);

                    }

                }


                $layout = $pq->htmlOuter();

                $pq->__destruct();
                $pq = null;

                unset($pq);
                if (!empty($mw_replaced_edit_fields_vals_inner)) {
                    $reps_arr = array();
                    $reps_arr2 = array();
                    foreach ($mw_replaced_edit_fields_vals_inner as $k => $v) {

                        if (isset($v['s'])) {
                            $reps_arr[] = $v['s'];
                            $reps_arr2[] = $v['r'];
                            //$reps_arr2[] = $k;
                            //$layout = str_replace($v['s'], $v['r'], $layout);
                            unset($mw_replaced_edit_fields_vals_inner[$k]);
                        }

                    }
                    $layout = str_replace($reps_arr, $reps_arr2, $layout);
                }


                mw_var($parser_mem_crc, $layout);
                if ($mw_found_elems != '') {
                    $mw_elements_array['new'] = $layout;
                    $mw_elements_array['to_replace'] = $mw_found_elems;
                    $mw_elements_array['elems'] = $mw_found_elems_arr;
                } else {
                    $mw_elements_array['new'] = $layout;
                }
            }
        }
        if (isset($mw_elements_array) and !empty($mw_elements_array)) {
            if (isset($mw_elements_array['elems']) and isset($mw_elements_array['to_replace']) and isset($mw_elements_array['new'])) {
                $modified_layout = $mw_elements_array['new'];
                $reps = $mw_elements_array['elems'];

                $c = 1;
                foreach ($reps as $elk => $value) {
                    $elk_crc = crc32($elk);

                    $global_holder_hash = 'replaced' . $elk_crc;


                    if (!isset($mw_replaced_edit_fields_vals[$global_holder_hash])) {
                        $this->_mw_parser_passed_replaces[] = $elk_crc;
                        $mw_replaced_edit_fields_vals[$global_holder_hash] = $modified_layout;

                        if ($value != '') {
                            $val_rep = $value;

                            $val_rep = $this->_replace_editable_fields($val_rep, true);
                            $rep = 'mw_replace_back_this_editable_' . $elk . '';
                            $modified_layout = str_replace($rep, $val_rep, $modified_layout);


                        }
                    } else {
                        $rep = 'mw_replace_back_this_editable_' . $elk . '';
                        $modified_layout = str_replace($rep, $value, $modified_layout);
                    }
                }

                $layout = $modified_layout;
                $mw_replaced_edit_fields_vals[$parser_mem_crc] = $layout;

            }

            if (!empty($replaced_code_tags)) {
                foreach ($replaced_code_tags as $key => $value) {
                    if ($value != '') {
                        $layout = str_replace($key, $value, $layout);
                    }
                    unset($replaced_code_tags[$key]);
                }
            }

            if ($no_cache == false) {
                //    $this->app->cache_manager->save($layout, $parser_mem_crc, 'content_fields/global/parser');
            }

        }
        $this->_mw_parser_passed_replaces[$parser_mem_crc] = $layout;
        $mw_replaced_edit_fields_vals[$parser_mem_crc] = $layout;
        return $layout;


    }

    public function make_tags($layout)
    {
        if ($layout == '') {
            return $layout;
        }
        require_once (MW_APP_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');

        $pq = \phpQuery::newDocument($layout);
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
        $layout = str_replace("\u00a0", ' ', $layout);
        $layout = str_replace('<?']= '&lt;?', $layout);
        $layout = str_replace('?>']= '?&gt;', $layout);
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
        $layout = $string_html;
        return $layout;
    }

    public function modify_html($layout, $selector, $content = "", $action = 'append')
    {
        require_once (MW_APP_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');

        $pq = \phpQuery::newDocument($layout);

        $els = $pq[$selector];
        foreach ($els as $elem) {
            pq($elem)->$action($content);
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

                $html_to_save = str_replace('<!--[if gte mso 8]><![endif]-->']= '', $html_to_save);

                $html_to_save = str_replace('<!--[if gte mso 9]><![endif]-->']= '', $html_to_save);
                $html_to_save = str_replace('<!--[if gte mso 10]><![endif]-->']= '', $html_to_save);
                $html_to_save = str_replace('<!--[if gte mso 11]><![endif]-->']= '', $html_to_save);
                $html_to_save = str_replace('class="MsoNormal"']= '', $html_to_save);
            }

        }
        $html_to_save = str_replace('class="exec"']= '', $html_to_save);
        $html_to_save = str_replace('style=""']= '', $html_to_save);

        $html_to_save = str_replace('ui-draggable']= '', $html_to_save);
        $html_to_save = str_replace('class="ui-droppable"']= '', $html_to_save);
        $html_to_save = str_replace('ui-droppable']= '', $html_to_save);
        $html_to_save = str_replace('mw_edited']= '', $html_to_save);
        $html_to_save = str_replace('_moz_dirty=""']= '', $html_to_save);
        $html_to_save = str_replace('ui-droppable']= '', $html_to_save);
        $html_to_save = str_replace('<br >']= '<br />', $html_to_save);
        $html_to_save = str_replace('<br>']= '<br />', $html_to_save);
        $html_to_save = str_replace(' class=""']= '', $html_to_save);
        $html_to_save = str_replace(' class=" "']= '', $html_to_save);

        // $html_to_save = preg_replace('/<!--(.*)-->/Uis']= '', $html_to_save);

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

    public function query($l, $selector = 'body', $return_function = 'htmlOuter')
    {
        require_once (MW_APP_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');
        $pq = \phpQuery::newDocument($l);
        $res = array();
        foreach ($pq [$selector] as $elem) {
            $l = pq($elem)->$return_function();
            $res[] = $l;
        }
        return $res;
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

    public function isolate_content_field($l, $strict = false)
    {


        require_once (MW_APP_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');

//        $field =  qp($l, '[field="content"]')->innerHTML();
//        if($field == null){
//            $field =  qp($l, '[field="content_body"]')->innerHTML();
//
//        }
//        return $field;
        // $field =  qp($l, '[field="content"]')->innerHTML();
        //d($field);
//exit;
        $pq = \phpQuery::newDocument($l);
        $found = false;
        foreach ($pq ['[field=content]'] as $elem) {
            $l = pq($elem)->htmlOuter();

            $found = true;
        }

        if ($found == false) {
            foreach ($pq ['[field=content_body]'] as $elem) {
                $l = pq($elem)->htmlOuter();
                $found = true;

            }
        }

        if($strict == true and $found == false){
            return false;
        }

        return $l;
    }

    public function isolate_content_field_old($l)
    {
        require_once (MW_APP_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'phpQuery.php');

        $pq = \phpQuery::newDocument($l);
        $found = false;
        foreach ($pq ['[field=content][rel=content]:last'] as $elem) {
            $l = pq($elem)->htmlOuter();

            $found = true;
        }

        if ($found == false) {
            foreach ($pq ['[field=content_body][rel=content]:last'] as $elem) {
                $l = pq($elem)->htmlOuter();
                $found = true;

            }
        }

        return $l;
    }

    function setInnerHTML($DOM, $element, $content)
    {
        $DOMInnerHTML = new \DOMDocument();
        $DOMInnerHTML->loadHTML('<?xml encoding="UTF-8">' . $content);
        $contentNode = $DOMInnerHTML->getElementsByTagName('body')->item(0)->firstChild;

        $contentNode = $DOM->importNode($contentNode, true);
        //$element->appendChild($contentNode);
        //  $element->parentNode->appendChild($contentNode);
        $fragment = $DOM->createDocumentFragment();
        $fragment->appendChild($contentNode);
        $element->replaceChild($fragment, $element->cloneNode(true));


        //$fragment = $DOM->createDocumentFragment();
        //$fragment->appendChild($contentNode);

        // $element->parentNode->replaceChild($element, $contentNode);


        //$element->appendChild($contentNode);
        //  $element->replaceChild($contentNode, $DOMInnerHTML);
        // $contentNode->innerHTML = ($content);
        // $DOMInnerHTML->saveXML();
        return $contentNode;
    }
}