<?php

namespace Microweber\Utils;

use Microweber\Providers\Modules;

$parser_cache_object = false; //global cache storage
$mw_replaced_edit_fields_vals = array();
$mw_replaced_edit_fields_vals_inner = array();

$mw_parser_nest_counter_level = 0;
$mod_tag_replace_inc = 0;
$other_html_tag_replace_inc = 0;

class Parser
{
    public $app;
    public $page = array();
    public $params = array();

    private $mw_replaced_modules_tags = array();
    private $mw_replaced_modules_values = array();

    private $_mw_parser_passed_hashes = array();
    private $_mw_parser_passed_hashes_rel = array();
    private $_mw_parser_passed_replaces = array();
    private $_mw_parser_replaced_tags = array();
    private $_mw_parser_replaced_html_comments = array();
    private $_replaced_modules_values = array();
    private $_replaced_modules = array();
    private $_replaced_codes = array();
    private $_existing_module_ids = array();
    private $_current_parser_rel = false;
    private $_current_parser_field = false;
    private $_current_parser_module_of_type = array();
    private $have_more = false;
    private $have_more_is_set = false;
    private $prev_module_data = array();
    private $iter_parent = array();
    private $_mw_edit_field_map = array();

    public function __construct($app = null)
    {
        if (!is_object($app)) {
            $this->app = mw();
        } else {
            $this->app = $app;
        }
    }

    public function process($layout, $options = false, $coming_from_parent = false, $coming_from_parent_id = false, $previous_attrs = false)
    {
        static $first_known_mod;
        static $it_loop2;


        if (!$it_loop2) {
            $it_loop2 = 0;
        }

        global $mw_replaced_edit_fields_vals;
        // global $mod_tag_replace_inc;
        global $other_html_tag_replace_inc;
        $coming_from_parent_strz1 = false;
        $root_module_id = false;
        $coming_from_parentz = false;
        $par_id_mod_count = 'global';
        $static_parser_mem_crc = 'global';


        $it = 0;
        $it_loop = 0;
        $it_loop1 = 0;

//d('have_more'.$this->have_more);

        $mod_tag_replace_inc = 0;
        $previous_attrs2 = $previous_attrs;
        if (!isset($parser_mem_crc)) {
            $parser_mem_crc = 'parser_' . crc32($layout) . content_id();
            if ($previous_attrs) {
                $parser_modules_crc = 'parser_modules' . crc32($layout) . content_id() . md5(serialize($previous_attrs));

            } else {
                $parser_modules_crc = 'parser_modules' . crc32($layout) . content_id();

            }

        }
        $static_parser_mem_crc = $parser_mem_crc;

        if (!$static_parser_mem_crc) {
            //    $static_parser_mem_crc = $parser_mem_crc;
        }
        //   $local_mw_replaced_modules = array();
        //$this->layout = $layout;
        static $process_started;
        $local_mw_replaced_modules = array();;
        if ($process_started == false) {
            $process_started = true;

            $this->app->event_manager->trigger('parser.process', $layout);
        }

        if (isset($mw_replaced_edit_fields_vals[$parser_mem_crc])) {
            //d($parser_mem_crc);
            //  return $mw_replaced_edit_fields_vals[$parser_mem_crc];
        }


        $layout = str_replace('<?', '&lt;?', $layout);


        $script_pattern = "/<!--(?!<!)[^\[>].*?-->/";
        preg_match_all($script_pattern, $layout, $mw_script_matches);
        if (!empty($mw_script_matches)) {
            foreach ($mw_script_matches [0] as $key => $value) {
                if ($value != '') {
                    $v1 = crc32($value);
                    $v1 = crc32($value) . '-' . $other_html_tag_replace_inc++;

                    $v1 = '<tag-comment>mw_replace_back_this_html_comment_code_' . $v1 . '</tag-comment>';
                    $layout = str_replace($value, $v1, $layout);
                    if (!isset($this->_mw_parser_replaced_html_comments[$v1])) {
                        $this->_mw_parser_replaced_html_comments[$v1] = $value;
                    }
                }
            }
        }

        $layout = str_replace('<microweber module=', '<module data-type=', $layout);
        $layout = str_replace('</microweber>', '', $layout);
        $layout = str_replace('></module>', '/>', $layout);

        $script_pattern = '/<module[^>]*>/Uis';
        preg_match_all($script_pattern, $layout, $mw_script_matches);
        if (!isset($local_mw_replaced_modules[$static_parser_mem_crc])) {
            $local_mw_replaced_modules[$static_parser_mem_crc] = array();
        }
        if (!empty($mw_script_matches)) {
            $matches1 = $mw_script_matches[0];
            foreach ($matches1 as $key => $value) {
                if ($value != '') {
                    $v1 = crc32($value) . '-' . $parser_modules_crc . $mod_tag_replace_inc++;
                    //  $v1 =$mod_tag_replace_inc++.'_'. crc32($value) . '-' .$parser_modules_crc;
                    //  $v1 = crc32($value) . '-' .$parser_modules_crc. $it_loop2;
                    // $v1 = crc32($value);

                    $v1 = '<tag>mw_replace_back_this_module_' . $v1 . '</tag>';
                    // \Log::info($v1);
                    //d($this->mw_replaced_modules);
                    $layout = $this->_str_replace_first($value, $v1, $layout);
                    if (!isset($local_mw_replaced_modules[$static_parser_mem_crc][$v1])) {
                        $local_mw_replaced_modules[$static_parser_mem_crc][$v1] = $value;
                    }
                }
            }
        }

        //  $this->have_more = !empty($mw_script_matches);

        if (!isset($options['parse_only_vars'])) {
            $layout = str_replace('<mw ', '<module ', $layout);
            $layout = str_replace('<editable ', '<div class="edit" ', $layout);
            $layout = str_replace('</editable>', '</div>', $layout);

            $layout = str_replace('<microweber module=', '<module data-type=', $layout);
            $layout = str_replace('</microweber>', '', $layout);
            $layout = str_replace('></module>', '/>', $layout);
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
                        $v1 = '<tag>mw_replace_back_this_code_' . $v1 . '</tag>';
                        $layout = str_replace($value, $v1, $layout);
                        if (!isset($replaced_scripts[$v1])) {
                            $this->_replaced_codes[$v1] = $value;
                        }
                    }
                }
            }


//


            preg_match_all('/.*?class=..*?edit.*?.[^>]*>/', $layout, $layoutmatches);
            if (!empty($layoutmatches) and isset($layoutmatches[0][0])) {
                $layout = $this->_replace_editable_fields($layout);
            }

            $layout = str_replace('<microweber module=', '<module data-type=', $layout);
            $layout = str_replace('</microweber>', '', $layout);
            $layout = str_replace('></module>', '/>', $layout);
            $script_pattern = '/<module[^>]*>/Uis';

            preg_match_all($script_pattern, $layout, $mw_script_matches);
            if (!empty($mw_script_matches)) {
                $matches1 = $mw_script_matches[0];
                foreach ($matches1 as $key => $value) {
                    if ($value != '') {
                        $v1 = crc32($value) . '-' . $parser_modules_crc . $mod_tag_replace_inc++;
                        //$v1 = crc32($value) . '-' .  $mod_tag_replace_inc++;
                        //    $v1 =$mod_tag_replace_inc++.'_'. crc32($value) . '-' .$parser_modules_crc;

                        //    $v1 = crc32($value) . '-' . $parser_modules_crc.$it_loop2 ;
                        //$v1 = crc32($value) ;
                        ///   $v1 = crc32($value);
                        $v1 = '<tag>mw_replace_back_this_module_111' . $v1 . '</tag>';
                        $layout = $this->_str_replace_first($value, $v1, $layout);
                        if (!isset($local_mw_replaced_modules[$static_parser_mem_crc][$v1])) {
                            $local_mw_replaced_modules[$static_parser_mem_crc][$v1] = $value;
                        }
                    }
                }
            }
//d($local_mw_replaced_modules);
            $this->have_more = !empty($mw_script_matches);

            if (!empty($replaced_scripts)) {
                foreach ($replaced_scripts as $key => $value) {
                    if ($value != '') {
                        $layout = str_replace($key, $value, $layout);
                    }
                    unset($replaced_scripts[$key]);
                }
            }

            $parser_ed_field = array();
            if (isset($this->_mw_edit_field_map[$parser_mem_crc])) {
                //  dd($this->_mw_edit_field_map[$parser_mem_crc]);
            }
//            \Log::info($coming_from_parent);
//            \Log::info($coming_from_parent_id);
//            \Log::info($this->prev_module_data);
            //\Log::info($layout);
//           d('-----------------');
////            d($mod_tag_replace_inc);
////            d($local_mw_replaced_modules);
//          d($coming_from_parent);
//          d($coming_from_parent_id);
//          d($this->prev_module_data);


            if ($this->_mw_edit_field_map) {
                //  d($parser_mem_crc);
                // d($this->_mw_edit_field_map);
            }
//d($local_mw_replaced_modules);
            if (is_array($local_mw_replaced_modules) and !empty($local_mw_replaced_modules)) {


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
                foreach ($local_mw_replaced_modules as $parse_key => $parse_item) {


                    $parent_of_iteration = false;


                    //$parse_item  = array_reverse($parse_item);
                    foreach ($parse_item as $key => $value) {


                        if ($value != '') {
                            $mw_attrs_key_value_seperator = "__MW_PARSER_ATTR_VAL__";
                            $replace_key = $key;

                            if (isset($this->mw_replaced_modules_values[$replace_key])) {

                                continue;
                            }

                            $attrs = array();
                            if (preg_match_all($attribute_pattern, $value, $attrs1, PREG_SET_ORDER)) {
                                foreach ($attrs1 as $item) {
                                    $m_tag = trim($item[0], "\x22\x27");
                                    $m_tag = trim($m_tag, "\x27\x22");
                                    $m_tag = preg_replace('/=/', $mw_attrs_key_value_seperator, $m_tag, 1);


                                    $m_tag = explode($mw_attrs_key_value_seperator, $m_tag);

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
                                        $rest_pieces = implode($mw_attrs_key_value_seperator, $rest_pieces);
                                        $b = $b . $rest_pieces;
                                    }

                                    $attrs[$a] = $b;
                                }
                            }

                            $m_tag = ltrim($value, '<module');

                            $m_tag = rtrim($m_tag, '/>');
                            $m_tag = rtrim($m_tag);
                            $userclass = '';

                            $module_html = "<div class='__USER_DEFINED_CLASS__ __MODULE_CLASS__ __WRAP_NO_WRAP__' __MODULE_ID__ __MODULE_NAME__";

                            $module_has_class = false;
                            if (!empty($attrs)) {
                                if (isset($attrs['module']) and $attrs['module']) {
                                    $attrs['data-type'] = $attrs['module'];
                                    unset($attrs['module']);
                                }
                              if(isset( $attrs['parent-module'] )){
                                  $coming_from_parent =  $attrs['parent-module'];
                              }
                              if(isset( $attrs['parent-module-id'] )){
                                  $coming_from_parent_id =  $attrs['parent-module-id'];
                              }


//                            if ($coming_from_parent == true) {
//                                $attrs['parent-module'] = $coming_from_parent;
//                            }
//                            if ($coming_from_parent_id == true) {
//                                $attrs['parent-module-id'] = $coming_from_parent_id;
//                            }
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
                                            $userclass = str_replace('module-as-element', '', $userclass);
                                        }
                                        $userclass = str_replace(' module  module ', 'module ', $userclass);
                                        $userclass = str_replace('ui-sortable', '', $userclass);
                                        $userclass = str_replace('module-item', '', $userclass);
                                        $userclass = str_replace('module module module', 'module', $userclass);
                                        $userclass = str_replace('module  module ', 'module ', $userclass);
                                    } else {
                                        //  $module_html .= " {$nn}='{$nv}'  ";
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
                                    ++$z;
                                }
                                $module_title = false;

                                if (isset($module_name)) {
                                    $module_class = $this->module_css_class($module_name);
                                    $module_title = module_info($module_name);


                                    if (!isset($attrs['id'])) {

                                        global $mw_mod_counter;
                                        ++$mw_mod_counter;

                                        if (!defined('MW_1_0_4_COMPAT')) {
                                            $mw_mod_counter1 = md5(serialize($attrs));
                                        } else {
                                            $mw_mod_counter1 = crc32(serialize($attrs));
                                        }

                                        $seg_clean = $this->app->url_manager->segment(0, url_current());

                                        //
                                        if (defined('IS_HOME')) {
                                            $seg_clean = '';
                                        }

                                        $seg_clean = str_replace('.', '', $seg_clean);
                                        $seg_clean = str_replace('%20', '-', $seg_clean);
                                        // $mod_id = $module_class . '-' . crc32($seg_clean) . ($mw_mod_counter1);

//                                    if (defined('CONTENT_ID') and CONTENT_ID != 0) {
//                                        $mod_id = $module_class . '-' . ($mw_mod_counter1);
//                                    }

                                        //    $mod_id = $module_class . ($mw_mod_counter1).crc32($replace_key);
                                        //  $mod_id = $module_class . ($mw_mod_counter1);

                                        $mod_id = '';


                                        if (!defined('CONTENT_ID')) {
                                            $mod_id = $mod_id . '-uid-fixme-' . uniqid();

                                        }


                                        $found_par_mod = false;
                                        $found_par_mod_id = false;


                                        if (!$found_par_mod_id) {
                                            //if (!isset($this->prev_module_data['parent-module-id'])) {
                                            if ($coming_from_parent) {
                                                //    $mod_id = $mod_id . '-' . $coming_from_parent;
                                            }


                                            // }
                                        }

                                        if (!$mod_id) {
                                            $mod_id = $module_class;
                                            if ($coming_from_parent_id and $coming_from_parent) {
                                                //  $mod_id = $module_name;

                                            }
                                            if ($coming_from_parent_id and !$coming_from_parent) {
                                                $mod_id = $mod_id . '-ssss-' . $coming_from_parent_id;

                                            } else {


                                            }

                                        }
                                        if ($coming_from_parent_id and $coming_from_parent) {
                                            //   $mod_id = $mod_id . '--' . $coming_from_parent_id;
                                            $mod_id = $coming_from_parent_id . '-' . $module_name;
                                            // $mod_id =  $mod_id . '-' .$coming_from_parent_id;
                                            //  $mod_id =  $mod_id . '-' .$coming_from_parent;
                                            // $mod_id = str_replace($coming_from_parent, 'ggggg', $mod_id);

                                            // $mod_id = $coming_from_parent_id . '--' . $mod_id;
                                        }


                                        $mod_id = $this->_str_clean_mod_id($mod_id);

//
//d('==========');
//d('=====$mod_id====='.$mod_id);
//d('=====$found_par_mod_id====='.$found_par_mod_id);
//d('=====$coming_from_parent_id====='.$coming_from_parent_id);
//                                    d($coming_from_parent);
//                                    d($this->prev_module_data);
//                                    d($previous_attrs2);
                                        if (!$this->have_more) {
                                            // $root_module_id = false;
                                            // $mod_id = $mod_id . '-nomore-module-id-';

                                        }
                                        if ($root_module_id) {
                                            // $mod_id = $mod_id . '-root-mod-' . $root_module_id;

                                        }


                                        static $last_content_id = null;
                                        if (!$coming_from_parent) {
                                            if (defined('CONTENT_ID') and CONTENT_ID == 0) {
                                                if ($last_content_id == null) {
                                                    $last_content_id = $this->app->database_manager->last_id('content');
                                                }
                                                $last_content_id = intval($last_content_id) + 1;
                                                $mod_id = $mod_id . '-' . $last_content_id;
                                            } elseif (defined('CONTENT_ID')) {
                                                $mod_id = $mod_id . '-' . CONTENT_ID;
                                            }
                                        }

                                        $it++;
//                                        if (defined('MW_MODULE_ONDROP')) {
//                                            $mod_id = $module_class . 'xxxxx-' . date("YmdHis") . uniqid();
////dd(MW_MODULE_ONDROP);
//                                        }
                                        if (isset($this->_existing_module_ids[$mod_id])) {
                                             ++$it_loop;
                                            $inc_mod_num = 0;

                                            if ($coming_from_parent_id != false) {
                                                $par_id_mod_count = $coming_from_parent_id;

                                            }

                                            if (isset($this->_current_parser_module_of_type[$par_id_mod_count])) {
                                                if (isset($this->_current_parser_module_of_type[$par_id_mod_count][$module_name])) {
                                                    $inc_mod_num = $this->_current_parser_module_of_type[$par_id_mod_count][$module_name];
                                                }
                                                //d($this->_current_parser_module_of_type);
                                                // d($mod_id);
                                            } else {
                                                //   $inc_mod_num = $it_loop;

                                            }
                                            if ($coming_from_parent_id) {
                                                //$mod_id =  $mod_id . '-' .$coming_from_parent_id;
                                            }

                                            //$mod_id = $mod_id . '--' . ++$it;
                                            if ($inc_mod_num) {
                                                $mod_id = $mod_id . '--' . $inc_mod_num;
                                            }
                                            // $mod_id = $mod_id . '--' . ++$this->_current_parser_module_of_type[$module_name];
                                            if (isset($this->_existing_module_ids[$mod_id])) {


                                                $mod_id_probe = false;
                                                if ($coming_from_parent_id) {
                                                    //$mod_id_probe =  $mod_id . '-coming_from_parent_id' .$coming_from_parent_id;
                                                    $mod_id_probe = $coming_from_parent_id . '-' . $mod_id;
                                                    $mod_id_probe = str_replace('module-', '', $mod_id_probe);

                                                } else {
                                                    $mod_id = $mod_id . '-' . $last_content_id;
                                                }

                                                if ($mod_id_probe and !isset($this->_existing_module_ids[$mod_id_probe])) {
                                                    $mod_id = $mod_id_probe;
                                                } else {

                                                    $mod_id = $mod_id . '-random-fixme-id-' . uniqid();

                                                }


                                            }
                                        }
                                        //  d($mod_id);
                                        $this->_existing_module_ids[$mod_id] = $mod_id;


                                        $attrs['id'] = $mod_id;

                                        $module_html = str_replace('__MODULE_ID__', "id='{$attrs['id']}'", $module_html);
                                    } else {
                                        $module_html = str_replace('__MODULE_ID__', '', $module_html);
                                    }
                                }
//                            print_r('--------------');
//
//                             var_dump($attrs);                              // }
//                            var_dump('root: '.$root_module_id);                              // }
//                            var_dump('par: '.$coming_from_parent_id);                              // }
//                            print_r('--------------');

                                $attrs2 = array();
                                if (is_array($module_title) and isset($module_title['name'])) {
                                    $module_title['name'] = addslashes($module_title['name']);
                                    $module_html = str_replace('__MODULE_NAME__', ' data-mw-title="' . $module_title['name'] . '"', $module_html);
                                } else {
                                    $module_html = str_replace('__MODULE_NAME__', '', $module_html);
                                }


                                if (strstr($module_name, 'admin')) {
                                    $module_html = str_replace('__WRAP_NO_WRAP__', '', $module_html);
                                } else {
                                    $module_html = str_replace('__WRAP_NO_WRAP__', '', $module_html);
                                }
                                $module_name_url = $this->app->url_manager->slug($module_name);

                                if ($mod_as_element == false) {
                                    if (strstr($module_name, 'text')) {
                                        $module_html = str_replace('__MODULE_CLASS__', 'layout-element ' . $module_name_url, $module_html);
                                    } else {
                                        $module_html = str_replace('__MODULE_CLASS__', 'module ' . $module_class, $module_html);
                                    }
                                } else {
                                    $module_html = str_replace('__MODULE_CLASS__', 'element ' . $module_name_url, $module_html);
                                }

                                $userclass = str_replace(trim($module_class), '', $userclass);
                                $userclass = trim(str_replace(' module ', ' ', $userclass));
                                $userclass = trim(str_replace(' disabled module ', ' module ', $userclass));
                                $module_class = trim(str_replace(' disabled module ', ' module ', $module_class));
                                $userclass = trim(str_replace(' module module ', ' module ', $userclass));
                                $module_html = str_replace('__MODULE_CLASS_NAME__', '' . $module_class, $module_html);
                                $module_html = str_replace('__USER_DEFINED_CLASS__', $userclass, $module_html);

                                if ($coming_from_parent == false) {

                                    $coming_from_parentz = $module_name;
                                    $coming_from_parent_strz1 = $attrs['id'];
                                    $previous_attrs2 = $attrs;
                                    $attrs['parent-module'] = $coming_from_parentz;
                                    $attrs['parent-module-id'] = $coming_from_parent_strz1;
                                    $this->prev_module_data = $attrs;

                                } else {
                                    $par_id_mod_count = $coming_from_parent_id;
                                    $attrs['parent-module-id'] = $coming_from_parent_id;
                                    $attrs['parent-module'] = $coming_from_parent;
                                    $this->prev_module_data = $attrs;

                                    $coming_from_parentz = $module_name;
                                    $coming_from_parent_strz1 = $attrs['id'];
                                }


//                            if (empty($this->prev_module_data) and $coming_from_parent_id == false and isset($attrs['id']) == true) {
//
//                                $coming_from_parent_strz1 = $attrs['id'];
//                                $coming_from_parentz = $module_name;
//                               // $this->prev_module_data
//                            } else if(isset($this->prev_module_data ['id']) and $this->prev_module_data ['id']){
//                                //! do not remove
//                                $coming_from_parent_strz1 = $this->prev_module_data ['id'];
//                                $coming_from_parentz =  $this->prev_module_data ['data-type'];
//                            } elseif($coming_from_parent_id) {
//                                //$coming_from_parent_strz1 = false;
//                                //$coming_from_parentz = $module_name;
//                               // $coming_from_parent_strz1 = $coming_from_parent_id;
//
//                            }
//
//                            if ($coming_from_parent!= true) {
//                                if ($coming_from_parentz == true) {
//                                    $attrs2['parent-module'] = $coming_from_parentz;
//                                }
//                                if ($coming_from_parent_strz1 == true) {
//                                    $attrs2['parent-module-id'] = $coming_from_parent_strz1;
//                                }
//                            }

                                // $this->prev_module_data = $attrs;


                                if (is_array($attrs2)) {
                                    //   $attrs = array_merge($attrs, $attrs2);
                                }
//                                if (isset($attrs['parent-module-id']) and ($attrs['parent-module-id'] == $attrs['id'])) {
//                                    // if (!isset($attrs['module_settings'])) {
//                                    $attrs['parent-module'] = false;
//                                    $attrs['parent-module-id'] = false;
//                                    $coming_from_parent_strz1 = false;
//                                    $coming_from_parentz = false;
//                                    $previous_attrs2 = array();
//                                    $this->prev_module_data = array();
//
//                                    //  }
//                                }


                                $attrs = array_filter($attrs, function ($value) {
                                    return ($value !== null && $value !== false && $value !== '');
                                });
                                if (is_array($previous_attrs2)) {

                                    $previous_attrs2 = array_filter($previous_attrs2, function ($value) {
                                        return ($value !== null && $value !== false && $value !== '');
                                    });

                                }
                                //   if($par_id_mod_count != 'global'){
                                if (!isset($this->_current_parser_module_of_type[$par_id_mod_count])) {
                                    $this->_current_parser_module_of_type[$par_id_mod_count] = array();
                                }
                                if (!isset($this->_current_parser_module_of_type[$par_id_mod_count][$module_name])) {
                                    $this->_current_parser_module_of_type[$par_id_mod_count][$module_name] = 0;
                                }
                                $this->_current_parser_module_of_type[$par_id_mod_count][$module_name]++;
                                // }
                                $mod_content = $this->load($module_name, $attrs);
                                $plain_modules = mw_var('plain_modules');

                                if ($plain_modules != false) {
                                    if (!defined('MW_PLAIN_MODULES')) {
                                        define('MW_PLAIN_MODULES', true);
                                    }
                                }

                                foreach ($attrs as $nn => $nv) {
                                    if ($nn != 'class') {
                                        $pass = true;
                                        if($mod_no_wrapper){
                                            if ($nn == 'id') {
                                                $pass = false;

                                            }
                                        }

                                        if ($pass and $nv) {
                                            $module_html .= " {$nn}='{$nv}'  ";
                                        }
                                    }
                                }

                                $plain_modules = false;
                                unset($local_mw_replaced_modules[$parse_key][$key]);

                                $proceed_with_parse = $this->_do_we_have_more_for_parse($mod_content);
//d('aaaaaaa'.$proceed_with_parse);
//d($attrs);
//d('aaaaaaa'.$mod_content);
                                //  unset($this->mw_replaced_modules[$parse_key][$key]);

                                if ($proceed_with_parse == true) {
                                    $this->have_more = true;
                                    preg_match_all('/.*?class=..*?edit.*?.[^>]*>/', $mod_content, $layoutmatches);
                                    if (!empty($layoutmatches) and isset($layoutmatches[0][0])) {
                                        $mod_content = $this->_replace_editable_fields($mod_content);
                                    }

                                    // $it_loop1++;
                                    // $it_loop2++;

                                    //   d($coming_from_parentz);
                                    //   d($coming_from_parent_strz1);
                                    $mod_content = $this->process($mod_content, $options, $coming_from_parentz, $coming_from_parent_strz1, $previous_attrs2);
                                } else {
                                    $this->have_more = false;
                                    $this->prev_module_data = array();
                                    $it_loop2 = 0;
                                    $coming_from_parent_str = '';

                                }

                                global $other_html_tag_replace_inc;

                                if ($mod_no_wrapper == false) {
                                    $coming_from_parent_str = '';

                                    $module_html .= $coming_from_parent_str . '>' . $mod_content . '</div>';
                                } else {


                                    $module_html = $mod_content;
                                }

                                $it_loop1++;
                                $it_loop2++;
                                //   $module_html = $it_loop1 . '---' . $it_loop2 . '---' . $other_html_tag_replace_inc++ . $module_html;


                                $this->mw_replaced_modules_values[$replace_key] = $module_html;
                                // $layout = str_replace($value, $module_html, $layout);
                                $layout = $this->_str_replace_first($value, $module_html, $layout);

                                //$layout = str_replace($replace_key, $module_html, $layout);

//\Log::info($module_html);
                                $layout = $this->_str_replace_first($replace_key, $module_html, $layout);
                                //  $layout = str_replace($replace_key, $module_html, $layout);


                                //    \Log::info($layout);


                                //}
                            }
                        }
                        //  \Log::info($key, $value,$layout);
                        //  \Log::info($key);
                        // \Log::info($value);
                        //  $layout = str_replace($key, $value, $layout);
                        $layout = $this->_str_replace_first($key, $value, $layout);
                    }
                }
            }
        } else {
            $this->have_more = false;
            $this->prev_module_data = array();
            $it_loop2 = 0;
        }

        if (!empty($this->_replaced_codes)) {
            foreach ($this->_replaced_codes as $key => $value) {
                if ($value != '') {
                    $layout = str_replace($key, $value, $layout);
                }
                unset($this->_replaced_codes[$key]);
            }
        }


        if (!empty($this->mw_replaced_modules_values)) {
            $reps_arr = array();
            $reps_arr2 = array();
            foreach ($this->mw_replaced_modules_values as $key => $value) {
                if ($value != '') {
                    $reps_arr[] = $key;
                    $reps_arr2[] = $value;
                    $layout = $this->_str_replace_first($key, $value, $layout);

                    // $layout = str_replace($key, $value, $layout);
                }
            }
            //   $layout = str_replace($reps_arr, $reps_arr2, $layout);
        }


        //if (!$this->have_more or $root_module_id) {
        if (!empty($this->_mw_parser_replaced_html_comments)) {
            foreach ($this->_mw_parser_replaced_html_comments as $key => $value) {
                if ($value != '') {
                    $layout = str_replace($key, $value, $layout);
                }
                //unset($this->_mw_parser_replaced_html_comments[$key]);
            }
        }
        //}

        $layout = str_replace('{rand}', uniqid() . rand(), $layout);
        $layout = str_replace('{SITE_URL}', $this->app->url_manager->site(), $layout);
        $layout = str_replace('{MW_SITE_URL}', $this->app->url_manager->site(), $layout);
        $layout = str_replace('%7BSITE_URL%7D', $this->app->url_manager->site(), $layout);
        //  $mw_replaced_edit_fields_vals[$parser_mem_crc] = $layout;

        return $layout;
    }

    public $filter = array();

    public function filter($callback)
    {
        $this->filter[] = $callback;
    }

    private function _replace_editable_fields($layout, $no_cache = false)
    {
        if ($layout != '') {
            global $mw_replaced_edit_fields_vals;
            global $mw_parser_nest_counter_level;
            global $mw_replaced_edit_fields_vals_inner;
            ++$mw_parser_nest_counter_level;
            $replaced_code_tags = array();
            $replaced_html_comment_tags = array();
            if ($this->_mw_parser_passed_replaces == null) {
                $this->_mw_parser_passed_replaces = array();
            }

            $mw_found_elems = '';
            $mw_found_elems_arr = array();
            $mw_elements_array = array('orig', $layout);
            $cached = false;

            if (!isset($parser_mem_crc)) {
                $parser_mem_crc = 'parser_' . crc32($layout) . content_id();
                $parser_modules_crc = 'parser_modules' . crc32($layout) . content_id();
            }

            if (isset($this->_mw_parser_passed_replaces[$parser_mem_crc])) {
              //  dd($parser_mem_crc);
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
            } elseif ($ch != false) {
                $layout = $ch;
            } else {
                require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';
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


                    if (isset($this->_mw_edit_field_map[$parser_mem_crc])) {
//                        d($field);
//                        d($rel);
//                        continue;
                    }


                    if ($rel == 'content') {
                        if (!isset($data_id) or $data_id == false) {
                            $data_id = content_id();
                        }

                        $get_global = false;
                        $data_id = intval($data_id);
                        $data = $this->app->content_manager->get_by_id($data_id);
//if($field != 'content'){
//                        $data[$field] = $this->app->content_manager->edit_field("rel_type={$rel}&field={$field}&rel_id".content_id());
//}
//d($data);
                    } elseif ($rel == 'page') {
                        if (!isset($data_id) or $data_id == false) {
                            $data_id = PAGE_ID;
                        }
                        if (!isset($data_id) or $data_id == false) {
                            $data_id = content_id();
                        }
                        $data = $this->app->content_manager->get_by_id($data_id);
                        $get_global = false;
                    } elseif ($rel == 'post') {
                        $get_global = false;
                        if (!isset($data_id) or $data_id == false) {
                            $data_id = POST_ID;
                        }
                        if (!isset($data_id) or $data_id == false) {
                            $data_id = PAGE_ID;
                        }
                        $data = $this->app->content_manager->get_by_id($data_id);
                    } elseif ($rel == 'inherit') {
                        $get_global = false;
                        if (!isset($data_id) or $data_id == false) {
                            $data_id = PAGE_ID;
                        }
                        $data_inh_check = $this->app->content_manager->get_by_id($data_id);

                        if (isset($data_inh_check['id']) and isset($data_inh_check['layout_file']) and (trim($data_inh_check['layout_file']) != '') and $data_inh_check['layout_file'] != 'inherit') {
                            $inh = $data_inh_check['id'];
                        } else {
                            $inh = $this->app->content_manager->get_inherited_parent($data_id);
                        }

                        if ($inh != false and intval($inh) != 0) {
                            $try_inherited = true;
                            $data_id = $inh;
                            $rel = 'content';
                            $data = $this->app->content_manager->get_by_id($data_id);
                        } else {
                            $rel = 'content';
                            $data = $this->app->content_manager->get_page($data_id);
                        }
                    } elseif ($rel == 'global') {
                        $get_global = 1;
                        $cont_field = false;
                    } elseif ($rel == 'module') {
                        $data[$field] = $this->app->content_manager->edit_field("rel_type={$rel}&field={$field}");


                    } elseif (isset($attr['post'])) {
                        $get_global = false;
                        $data = $this->app->content_manager->get_by_id($attr['post']);
                        if ($data == false) {
                            $data = $this->app->content_manager->get_page($attr['post']);
                        }
                    } elseif (isset($attr['category'])) {
                        $get_global = false;
                        $data = $this->app->category_manager->get_by_id($attr['category']);
                    } elseif (isset($attr['global'])) {
                        $get_global = true;
                    }
                    $cf = false;
                    $field_content = false;
                    $orig_rel = $rel;

                    $this->_current_parser_rel = $rel;
                    $this->_current_parser_field = $field;

                    if (!empty($this->filter)) {
                        foreach ($this->filter as $filter) {
                            if (isset($data)) {
                                $new_data = call_user_func($filter, $data, $elem);
                                if (is_array($new_data) and !empty($new_data)) {
                                    $data = array_merge($data, $new_data);
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
                            $cont_field = $this->app->content_manager->edit_field("rel_type={$rel}&field={$field}&rel_id=$data_id");
                            if ($cont_field == false and $try_inherited == true) {
                                $inh = $this->app->content_manager->get_inherited_parent($data_id);
                                if ($inh != false and intval($inh) != 0 and $inh != $data_id) {
                                    $data_id = $inh;
                                    $cont_field2 = $this->app->content_manager->edit_field("rel_type={$rel}&field={$field}&rel_id=$inh");
                                    if ($cont_field2 != false) {
                                        $rel = 'content';
                                        $data = $this->app->content_manager->get_by_id($inh);
                                        $cont_field = $cont_field2;
                                    }
                                }
                            }
                        } else {

                            if (isset($data_id) and trim($data_id) != '' and $field_content == false and isset($rel) and isset($field) and trim($field) != '') {
                                $cont_field = $this->app->content_manager->edit_field("rel_type={$rel}&field={$field}&rel_id=$data_id");
                                if ($cont_field != false) {
                                    $field_content = $cont_field;
                                }
                            } else {


                                $field_content = $cont_field = $this->app->content_manager->edit_field("rel_type={$rel}&field={$field}");


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
                                $cont_field = $this->app->content_manager->edit_field("rel_type={$rel}&field={$field}&rel_id=$data_id");
                            }

                            if (isset($cont_field) and !empty($cont_field)) {
                                $cont_field = $this->app->content_manager->edit_field("rel_type={$rel}&field={$field}");
                            }

                            if ($cont_field == false) {
                                if ($option_mod != false) {
                                    $field_content = $this->app->content_manager->edit_field("rel_type={$option_group}&field={$field}");
                                } else {
                                    $field_content = $this->app->content_manager->edit_field("rel_type={$option_group}&field={$field}");
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
                                    $cont_field = $this->app->content_manager->edit_field("rel_type={$orig_rel}&field={$field}&rel_id=$data_id");
                                } else {
                                    $cont_field = $this->app->content_manager->edit_field("rel_type={$orig_rel}&field={$field}&rel_id=" . PAGE_ID);
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
                            $cont_field = $this->app->content_manager->edit_field("rel_type={$rel}&field={$field}&rel_id=$data_id");
                            if ($cont_field != false) {
                                $field_content = $cont_field;
                            }
                        } elseif ($field_content == false and isset($rel) and isset($field) and trim($field) != '') {
                            $cont_field = $this->app->content_manager->edit_field("rel_type={$rel}&field={$field}");
                            if ($cont_field != false) {
                                $field_content = $cont_field;
                            }
                        }
                    }
                    // d($parser_mem_crc);

                    if ($field_content != false and $field_content != '' and is_string($field_content)) {
                        $parser_mem_crc2 = 'parser_field_content_' . $field . $rel . $data_id . crc32($field_content);

                        $ch2 = mw_var($parser_mem_crc);
                        if ($ch2 == false) {
                            $this->_mw_parser_passed_hashes[] = $parser_mem_crc2;
                            $this->_mw_parser_passed_hashes_rel[$rel][] = $parser_mem_crc2;
                            if (!isset($mw_replaced_edit_fields_vals[$parser_mem_crc2]) and $field_content != false and $field_content != '') {
                                $mw_replaced_edit_fields_vals[$parser_mem_crc2] = $ch2;
                                $parser_mem_crc3 = 'mw_replace_back_this_editable_' . $parser_mem_crc2 . '';

                                $mw_found_elems = ',' . $parser_mem_crc2;
                                $mw_found_elems_arr[$parser_mem_crc2] = $field_content;
                                // $rep = pq($elem)->html();
                                $rep = pq($elem)->html();
                                //  $rep = trim($rep);
                                $rep = preg_replace("/(^\s+)|(\s+$)/us", "", $rep);

                                if ($no_edit != false or (isset($data) and isset($data['no_edit']) and $data['no_edit'] != false)) {
                                    $is_editable = false;
                                    if ($is_editable === false) {
                                        pq($elem)->removeClass('edit');
                                    } else {
                                    }
                                    $is_editable = 1;
                                }
                                $parser_mem_crc2_inner = 'parser_' . crc32($rep) . content_id();

                                $mw_replaced_edit_fields_vals_inner[$parser_mem_crc3] = array('s' => $rep, 'r' => $field_content, 'rel' => $rel, 'field' => $field);
                                $this->_mw_edit_field_map[$parser_mem_crc] = array(
                                    'field' => $field,
                                    'rel' => $rel,
                                );
                            }
                        } else {

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
                        $repc = 1;
                        if (isset($v['s'])) {
                            $reps_arr[] = $v['s'];
                            $reps_arr2[] = $v['r'];

                            $layout = $this->_str_replace_first($v['s'], $v['r'], $layout, $repc);
                            // $layout = str_ireplace($v['s'], $v['r'], $layout, $repc);

                            unset($mw_replaced_edit_fields_vals_inner[$k]);
                        }
                    }
                    // $layout = str_replace($reps_arr, $reps_arr2, $layout,$repc);
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
                            $ct = 1;

                            //   $modified_layout = str_replace($rep, $val_rep, $modified_layout,$ct);
                            $modified_layout = $this->_str_replace_first($rep, $val_rep, $modified_layout);
                        }
                    } else {
                        $rep = 'mw_replace_back_this_editable_' . $elk . '';
                        $modified_layout = $this->_str_replace_first($rep, $value, $modified_layout);

                        // $modified_layout = str_replace($rep, $value, $modified_layout);
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
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';

        $pq = \phpQuery::newDocument($layout);
        foreach ($pq ['.module'] as $elem) {
            $name = pq($elem)->attr('module');
            $attrs = $elem->attributes;
            $module_html = '<module ';
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
        $layout = str_replace('<?', '&lt;?', $layout);
        $layout = str_replace('?>', '?&gt;', $layout);

        return $layout;
    }

    public function modify_html_preg($layout, $preg_match_all, $content = '', $action = 'append')
    {
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';

        $string_html = $layout;
        $m = preg_match_all($preg_match_all, $string_html, $match);
        if ($m) {
            $match_html = $match[0];
            for ($j = 0; $j < $m; ++$j) {
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

    public function modify_html($layout, $selector, $content = '', $action = 'append')
    {
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';

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

        // $html_to_save = preg_replace('/<!--(.*)-->/Uis', '', $html_to_save);

        return $html_to_save;
    }

    public function get_by_id($html_element_id = false, $layout)
    {
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';

        if ($html_element_id == false) {
            if (isset($_REQUEST['embed_id'])) {
                $html_element_id = trim($_REQUEST['embed_id']);
            }
        }

        if ($html_element_id != false and trim($html_element_id) != '') {
            require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';
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
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';
        $pq = \phpQuery::newDocument($l);
        $l = pq('head')->eq(0)->html();

        return $l;
    }

    public function query($l, $selector = 'body', $return_function = 'htmlOuter')
    {
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';
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
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';
        $pq = \phpQuery::newDocument($l);
        foreach ($pq [$selector] as $elem) {
            $l = pq($elem)->htmlOuter();

            return $l;
        }

        return false;
    }

    public function isolate_content_field($l, $strict = false)
    {
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';

        $pq = \phpQuery::newDocument($l);
        $found = false;

        foreach ($pq ['[data-mw=main]'] as $elem) {
            if ($found == false) {
                $l = pq($elem)->htmlOuter();
                $found = true;
            }
        }
        if ($found == false) {
            foreach ($pq ['[field=content]'] as $elem) {
                $l = pq($elem)->htmlOuter();

                $found = true;
            }
        }

        if ($found == false) {
            foreach ($pq ['[field=content_body]'] as $elem) {
                $l = pq($elem)->htmlOuter();
                $found = true;
            }
        }

        if ($strict == true and $found == false) {
            return false;
        }

        return $l;
    }

    public function isolate_content_field_old($l)
    {
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';

        $pq = \phpQuery::newDocument($l);
        $found = false;
        foreach ($pq ['[field=content][rel_type=content]:last'] as $elem) {
            $l = pq($elem)->htmlOuter();

            $found = true;
        }

        if ($found == false) {
            foreach ($pq ['[field=content_body][rel_type=content]:last'] as $elem) {
                $l = pq($elem)->htmlOuter();
                $found = true;
            }
        }

        return $l;
    }

    public function setInnerHTML($DOM, $element, $content)
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

    public function load($module_name, $attrs = array())
    {
        $is_element = false;
        $custom_view = false;
        if (isset($attrs['view'])) {
            $custom_view = $attrs['view'];
            $custom_view = trim($custom_view);
            $custom_view = str_replace('\\', '/', $custom_view);
            $attrs['view'] = $custom_view = str_replace('..', '', $custom_view);
        }

        if ($custom_view != false and strtolower($custom_view) == 'admin') {
            if ($this->app->user_manager->is_admin() == false) {
                mw_error('Not logged in as admin');
            }
        }

        $module_name = trim($module_name);
        $module_name = str_replace('\\', '/', $module_name);
        $module_name = str_replace('..', '', $module_name);
        // prevent hack of the directory
        $module_name = reduce_double_slashes($module_name);

        $module_namei = $module_name;

        if (strstr($module_name, 'admin')) {
            $module_namei = str_ireplace('\\admin', '', $module_namei);
            $module_namei = str_ireplace('/admin', '', $module_namei);
        }

        //$module_namei = str_ireplace($search, $replace, $subject)e

        $uninstall_lock = $this->app->modules->get('one=1&ui=any&module=' . $module_namei);

        if (isset($uninstall_lock['installed']) and $uninstall_lock['installed'] != '' and intval($uninstall_lock['installed']) != 1) {
            return '';
        }

        if (!defined('ACTIVE_TEMPLATE_DIR')) {
            $this->app->content_manager->define_constants();
        }

        $module_in_template_dir = ACTIVE_TEMPLATE_DIR . 'modules/' . $module_name . '';
        $module_in_template_dir = normalize_path($module_in_template_dir, 1);
        $module_in_template_file = ACTIVE_TEMPLATE_DIR . 'modules/' . $module_name . '.php';
        $module_in_template_file = normalize_path($module_in_template_file, false);

        $try_file1 = false;

        $mod_d = $module_in_template_dir;
        $mod_d1 = normalize_path($mod_d, 1);
        $try_file1zz = $mod_d1 . 'index.php';
        $in_dir = false;

        if ($custom_view == true) {
            $try_file1zz = $mod_d1 . trim($custom_view) . '.php';
        } else {
            $try_file1zz = $mod_d1 . 'index.php';
        }

        if (is_dir($module_in_template_dir) and is_file($try_file1zz)) {
            $try_file1 = $try_file1zz;

            $in_dir = true;
        } elseif (is_file($module_in_template_file)) {
            $try_file1 = $module_in_template_file;
            $in_dir = false;
        } else {
            $module_in_default_dir = modules_path() . $module_name . '';
            $module_in_default_dir = normalize_path($module_in_default_dir, 1);
            // d($module_in_default_dir);
            $module_in_default_file = modules_path() . $module_name . '.php';
            $module_in_default_file_custom_view = modules_path() . $module_name . '_' . $custom_view . '.php';

            $element_in_default_file = elements_path() . $module_name . '.php';
            $element_in_default_file = normalize_path($element_in_default_file, false);

            //

            $module_in_default_file = normalize_path($module_in_default_file, false);

            if (is_file($module_in_default_file)) {
                $in_dir = false;
                if ($custom_view == true and is_file($module_in_default_file_custom_view)) {
                    $try_file1 = $module_in_default_file_custom_view;
                } else {
                    $try_file1 = $module_in_default_file;
                }
            } else {
                if (is_dir($module_in_default_dir)) {
                    $in_dir = true;
                    $mod_d1 = normalize_path($module_in_default_dir, 1);

                    if ($custom_view == true) {
                        $try_file1 = $mod_d1 . trim($custom_view) . '.php';
                    } else {
                        $try_file1 = $mod_d1 . 'index.php';
                    }
                } elseif (is_file($element_in_default_file)) {
                    $in_dir = false;
                    $is_element = true;

                    $try_file1 = $element_in_default_file;
                }
            }
        }
        //

        if (isset($try_file1) != false and $try_file1 != false and is_file($try_file1)) {
            if (isset($attrs) and is_array($attrs) and !empty($attrs)) {
                $attrs2 = array();
                foreach ($attrs as $attrs_k => $attrs_v) {
                    $attrs_k2 = substr($attrs_k, 0, 5);
                    if (strtolower($attrs_k2) == 'data-') {
                        $attrs_k21 = substr($attrs_k, 5);
                        $attrs2[$attrs_k21] = $attrs_v;
                    } elseif (!isset($attrs['data-' . $attrs_k])) {
                        $attrs2['data-' . $attrs_k] = $attrs_v;
                    }

                    $attrs2[$attrs_k] = $attrs_v;
                }
                $attrs = $attrs2;
            }

            $config['path_to_module'] = $config['mp'] = $config['path'] = normalize_path((dirname($try_file1)) . '/', true);
            $config['the_module'] = $module_name;
            $config['module'] = $module_name;
            $module_name_dir = dirname($module_name);
            $config['module_name'] = $module_name_dir;

            $config['module_name_url_safe'] = $this->module_name_encode($module_name);

            $find_base_url = $this->app->url_manager->current(1);
            if ($pos = strpos($find_base_url, ':' . $module_name) or $pos = strpos($find_base_url, ':' . $config['module_name_url_safe'])) {
                $find_base_url = substr($find_base_url, 0, $pos) . ':' . $config['module_name_url_safe'];
            }
            $config['url'] = $find_base_url;

            $config['url_main'] = $config['url_base'] = strtok($find_base_url, '?');

            if ($in_dir != false) {
                $mod_api = str_replace('/admin', '', $module_name);
            } else {
                $mod_api = str_replace('/admin', '', $module_name_dir);
            }

            $config['module_api'] = $this->app->url_manager->site('api/' . $mod_api);
            $config['module_view'] = $this->app->url_manager->site('module/' . $module_name);
            $config['ns'] = str_replace('/', '\\', $module_name);
            $config['module_class'] = $this->module_css_class($module_name);

            $config['url_to_module'] = $this->app->url_manager->link_to_file($config['path_to_module']);

            if (isset($attrs['id'])) {
                $attrs['id'] = str_replace('__MODULE_CLASS_NAME__', $config['module_class'], $attrs['id']);

                $template = false;
            }

            //$config['url_to_module'] = rtrim($config['url_to_module'], '///');
            $lic = $this->app->modules->license($module_name);
            //  $lic = 'valid';
            if ($lic != false) {
                $config['license'] = $lic;
            }

            if (isset($attrs['module-id']) and $attrs['module-id'] != false) {
                $attrs['id'] = $attrs['module-id'];
            }

            if (!isset($attrs['id'])) {
                global $mw_mod_counter;
                ++$mw_mod_counter;
                //  $seg_clean = $this->app->url_manager->segment(0);
                $seg_clean = $this->app->url_manager->segment(0, url_current());


                if (defined('IS_HOME')) {
                    $seg_clean = '';
                }
                $seg_clean = str_replace('%20', '-', $seg_clean);
                $seg_clean = str_replace(' ', '-', $seg_clean);
                $seg_clean = str_replace('.', '', $seg_clean);
                $attrs1 = crc32(serialize($attrs) . $seg_clean . $mw_mod_counter);
                $attrs1 = str_replace('%20', '-', $attrs1);
                $attrs1 = str_replace(' ', '-', $attrs1);
                $attrs['id'] = ($config['module_class'] . '-' . $attrs1);
            }
            if (isset($attrs['id']) and strstr($attrs['id'], '__MODULE_CLASS_NAME__')) {
                $attrs['id'] = str_replace('__MODULE_CLASS_NAME__', $config['module_class'], $attrs['id']);
                //$attrs['id'] = ('__MODULE_CLASS__' . '-' . $attrs1);
            }

            $l1 = new \Microweber\View($try_file1);
            $l1->config = $config;
            $l1->app = $this->app;

            if (!isset($attrs['module'])) {
                $attrs['module'] = $module_name;
            }

//            if (!isset($attrs['parent-module'])) {
//                $attrs['parent-module'] = $module_name;
//            }
//
//            if (!isset($attrs['parent-module-id'])) {
//                $attrs['parent-module-id'] = $attrs['id'];
//            }
//            $mw_restore_get = mw_var('mw_restore_get');
//            if ($mw_restore_get != false and is_array($mw_restore_get)) {
//                $l1->_GET = $mw_restore_get;
//                $_GET = $mw_restore_get;
//            }
            if (defined('MW_MODULE_ONDROP')) {
                if (!isset($attrs['ondrop'])) {
                    $attrs['ondrop'] = true;
                }
            }
            $l1->params = $attrs;
            if ($config) {
                $this->current_module = ($config);
            }
            if ($attrs) {
                $this->current_module_params = ($attrs);
            }
            if (isset($attrs['view']) && (trim($attrs['view']) == 'empty')) {
                $module_file = EMPTY_MOD_STR;
            } elseif (isset($attrs['view']) && (trim($attrs['view']) == 'admin')) {
                $module_file = $l1->__toString();
            } else {
                if (isset($attrs['display']) && (trim($attrs['display']) == 'custom')) {
                    $module_file = $l1->__get_vars();

                    return $module_file;
                } elseif (isset($attrs['format']) && (trim($attrs['format']) == 'json')) {
                    $module_file = $l1->__get_vars();
                    header('Content-type: application/json');
                    exit(json_encode($module_file));
                } else {
                    $module_file = $l1->__toString();
                }
            }
            //	$l1 = null;
            unset($l1);
            if ($lic != false and isset($lic['error']) and ($lic['error'] == 'no_license_found')) {
                $lic_l1_try_file1 = MW_ADMIN_VIEWS_DIR . 'activate_license.php';
                $lic_l1 = new \Microweber\View($lic_l1_try_file1);

                $lic_l1->config = $config;
                $lic_l1->params = $attrs;

                $lic_l1e_file = $lic_l1->__toString();
                unset($lic_l1);
                $module_file = $lic_l1e_file . $module_file;
            }

            // $mw_loaded_mod_memory[$function_cache_id] = $module_file;
            return $module_file;
        } else {
            //define($cache_content, FALSE);
            // $mw_loaded_mod_memory[$function_cache_id] = false;
            return false;
        }
    }

    public function module_name_decode($module_name)
    {
        $module_name = str_replace('__', '/', $module_name);

        return $module_name;
    }

    public function module_name_encode($module_name)
    {
        $module_name = str_replace('/', '__', $module_name);
        $module_name = str_replace('\\', '__', $module_name);

        return $module_name;
    }

    public function module_css_class($module_name)
    {
        $module_class = str_replace('/', '-', $module_name);
        $module_class = str_replace('\\', '-', $module_class);
        $module_class = str_replace(' ', '-', $module_class);
        $module_class = str_replace('%20', '-', $module_class);
        $module_class = str_replace('_', '-', $module_class);
        $module_class = 'module-' . $module_class;

        return $module_class;
    }

    private function _str_replace_first($search, $replace, $subject)
    {
        if ($search == false || $replace === false) {
            return $subject;
        }
        $pos = strpos($subject, $search);
        if ($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }

    private function _do_we_have_more_for_parse($mod_content)
    {
        $proceed_with_parse = false;
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
                    } else {
//                        preg_match_all('/<tag.*[^>]*>/', $mod_content, $modinner);
//                        if (!empty($modinner) and isset($modinner[0][0])) {
//                            $proceed_with_parse = true;
//                        }
                    }
                }
            }
        }
        return $proceed_with_parse;
    }

    private function _str_clean_mod_id($mod_id)
    {
        $mod_id = str_replace(' ', '-', $mod_id);
        $mod_id = str_replace('/', '-', $mod_id);
        $mod_id = str_replace('\\', '-', $mod_id);
        $mod_id = str_replace('_', '-', $mod_id);
        $mod_id = str_replace(';', '-', $mod_id);
        $mod_id = str_replace('.', '-', $mod_id);
        $mod_id = str_replace('#', '-', $mod_id);
        //   $mod_id = str_replace('--', '', $mod_id);
        $mod_id = strtolower($mod_id);
        $mod_id = trim($mod_id);
        return $mod_id;
    }
}
