<?php


namespace MicroweberPackages\App\Utils;


use Doctrine\DBAL\Connection;
use MicroweberPackages\App\Utils\ParserHelpers\ParserLayoutItem;
use MicroweberPackages\App\Utils\ParserHelpers\ParserModuleItem;
use MicroweberPackages\App\Utils\ParserHelpers\ParserModuleItemCollection;

class ParserProcessor
{
    use ParserEditFieldsTrait;
    use ParserLoadModuleTrait;

    public $utils;
    public $registry;

    public $page = array();
    public $params = array();



    private $mw_replaced_modules_tags = array();
    private $mw_replaced_modules_values = array();



    public $current_module_params = false;

    public $have_more = false;
    public $current_module = false;

    public $debugbarEnabled = false;

    public $processor = false;


    /**
     * @var ParserModuleItemCollection
     */
   public $parser_modules_collection;


    public function __construct()
    {

        $this->utils = new ParserUtils();
        $this->registry = new ParserRegistry();
        $this->parser_modules_collection = new ParserModuleItemCollection();

        require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';

    }

    public static $process_layouts_loop = [];

    public function process($layout, $options = false, $coming_from_parent = false, $coming_from_parent_id = false, $previous_attrs = false,$prevous_mod_obj=false,$prevous_layout_obj=false)
    {

        static $first_known_mod;
        static $it_loop2;


        if (!$it_loop2) {
            $it_loop2 = 0;
        }

        global $mw_replaced_edit_fields_vals;
        // global $mod_tag_replace_inc;
        global $other_html_tag_replace_inc;
        global $mw_replaced_codes_tag;
        global $mw_replaced_textarea_tag;
        //global $local_mw_replaced_modules_ids_grouped;
        //  global $local_mw_replaced_modules;
        $coming_from_parent_strz1 = false;
        $root_module_id = false;
        $coming_from_parentz = false;
        $par_id_mod_count = 'global';
        $static_parser_mem_crc = 'global';


        /**
         * @var $layout_obj ParserLayoutItem
         */


        $it = 0;
        $it_loop = 0;
        $it_loop1 = 0;

//d('have_more'.$this->have_more);

        $mod_tag_replace_inc = 0;
        $previous_attrs2 = $previous_attrs;
        if (!isset($parser_mem_crc)) {
            $parser_mem_crc = 'parser_' . md5($layout) . content_id();
            if ($coming_from_parent_id) {
                $parser_modules_crc = 'parser_modules' . md5($coming_from_parent_id . content_id() . json_encode($previous_attrs));

            } else if ($previous_attrs) {
                $parser_modules_crc = 'parser_modules' . md5($layout . content_id() . json_encode($previous_attrs));

            } else {
                $parser_modules_crc = 'parser_modules' . md5($layout . content_id());

            }

        }

        $static_parser_mem_crc = $parser_mem_crc;

        if (!$static_parser_mem_crc) {
            //    $static_parser_mem_crc = $parser_mem_crc;
        }
        $is_first_loop = false;
        //$this->layout = $layout;
       // static $process_started;
        $local_mw_replaced_modules = array();
        $local_mw_replaced_modules_ids_grouped = array();
        if(!isset(self::$process_layouts_loop[$parser_modules_crc])){
            $is_first_loop = true;
            self::$process_layouts_loop[$parser_modules_crc] = true;
            app()->event_manager->trigger('parser.process', $layout);
        }
//        if (!$process_started) {
//            $process_started = true;
//            $is_first_loop = true;
//            app()->event_manager->trigger('parser.process', $layout);
//        }

        if (isset($mw_replaced_edit_fields_vals[$parser_mem_crc])) {
            //d($parser_mem_crc);
            return $mw_replaced_edit_fields_vals[$parser_mem_crc];
        }


        $script_pattern = "/<textarea[^>]*>(.*)<\/textarea>/Uis";
        preg_match_all($script_pattern, $layout, $mw_script_matches);

        if (!empty($mw_script_matches)) {
            foreach ($mw_script_matches [0] as $key => $value) {
                if ($value != '') {
                    $v1 = md5($value);
                    $v1 = '<tag-textarea>mw_replace_back_this_textarea_' . $v1 . '</tag-textarea>';
                    $layout = str_replace($value, $v1, $layout);

                        $mw_replaced_textarea_tag[$v1] = $value;


                }
            }
        }




        global $mw_parser_replace_inc;
        $layout = str_replace('<?', '&lt;?', $layout);


        $layout = $this->_replace_tags_with_placeholders($layout);





        global  $global_mw_replaced_modules;


        $should_parse_only_vars = false;
        if (isset($options['parse_only_vars']) and $options['parse_only_vars']) {
            $should_parse_only_vars = true;
        }

        if (!$should_parse_only_vars) {
            $layout = str_replace('<mw ', '<module ', $layout);
            $layout = str_replace('<editable ', '<div class="edit" ', $layout);
            $layout = str_replace('</editable>', '</div>', $layout);

            $layout = str_replace('<microweber module=', '<module data-type=', $layout);
            $layout = str_replace('</microweber>', '', $layout);
            $layout = str_replace('></module>', '/>', $layout);
            $replaced_scripts = array();

            $replaced_scripts = array();

            $script_pattern = "/<script[^>]*>(.*)<\/script>/Uis";

            preg_match_all($script_pattern, $layout, $mw_script_matches);

            if (!empty($mw_script_matches)) {
                foreach ($mw_script_matches [0] as $key => $value) {
                    if ($value != '') {
                        $v1 = md5($value);

                        $v1 = '<x-tag> mw_replace_back_this_script_' . $v1 . ' </x-tag>';
                        $layout = str_replace($value, $v1, $layout);
                        if (!isset($replaced_scripts[$v1])) {
                            $replaced_scripts[$v1] = $value;
                        }
                    }
                }
            }




            if ($is_first_loop) {
          //      $layout = $this->_edit_field_add_modules_for_processing_first_pass($layout);

            }



//
            // if ($coming_from_parent) {
            $more = $this->_do_we_have_more_edit_fields_for_parse($layout);
            if ($more  ) {
                // bug ?
                $layout = $this->_replace_editable_fields($layout,false,$layout,$coming_from_parent_id);
            //    $layout = $this->_replace_tags_with_placeholders_back($layout);

            }





           // if($is_first_loop) {
                $layout = $this->_edit_field_add_modules_for_processing($layout, 'mwnoedit', 'mwnoedit',false,$prevous_mod_obj);
            //}
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
            $local_mw_replaced_modules = array();


           $local_mw_replaced_modules[$static_parser_mem_crc] = $this->parser_modules_collection->getItems();

            //$local_mw_replaced_modules[$static_parser_mem_crc] = $this->parser_modules_collection->getItemsForProcessing();


            if (is_array( $local_mw_replaced_modules) and !empty( $local_mw_replaced_modules)) {


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
                    foreach ($parse_item as $key => $mod_obj) {


                        /**
                         * @var $mod_obj ParserModuleItem
                         */

                        $value =   $mod_obj->getReplaceValue();
                      //  $value = $parse_item_obj->get
                        $replace_key = $key;
                        $replace_key2 = $key;
                     //   $replace_key2 = $parse_key . $key . $parser_mem_crc;
                        if (isset($this->mw_replaced_modules_values[$replace_key2])) {


                            continue;
                        }

                        if($mod_obj->isProcessing()){
                            continue;

                        }

                        if($mod_obj->isProcessed()){

                            $module_html = $mod_obj->getOutput();
                           // $layout = $this->_str_replace_first($value, $module_html, $layout);
                            $layout = $this->_str_replace_first($replace_key, $module_html, $layout);


                            continue;
                        }


                     //   $mod_obj->setIsProcessed(true);

                        $mod_obj->setIsProcessing(true);


                        if ($value != '') {


                            $attrs = $this->utils->parseAttributes($value);


                            $m_tag = ltrim($value, '<module');

                            $m_tag = rtrim($m_tag, '/>');
                            $m_tag = rtrim($m_tag);
                            $userclass = '';

                            $module_html_tag = 'div';

                            $module_html = "<__MODULE_HTML_TAG__ class='__USER_DEFINED_CLASS__ __MODULE_CLASS__ __WRAP_NO_WRAP__' __MODULE_ID__ __MODULE_NAME__";

                            $module_has_class = false;
                            if (!empty($attrs)) {
                                if (isset($attrs['module']) and $attrs['module']) {
                                    $attrs['data-type'] = $attrs['module'];
                                    unset($attrs['module']);
                                }
                                if (isset($attrs['parent-module'])) {
                                    $coming_from_parent = $attrs['parent-module'];
                                }
                                if (isset($attrs['parent-module-id'])) {
                                   $coming_from_parent_id = $attrs['parent-module-id'];
                                }





                                if (!isset($attrs['parent-module-id'])) {
                                    $check_mod_obj_parent = ($mod_obj->getParent());

                                    if ($check_mod_obj_parent) {

                                        $attrs['parent-module-id'] = $check_mod_obj_parent->getId();
                                        $attrs['parent-module'] = $check_mod_obj_parent->getModuleName();
                                        $this->prev_module_data = $check_mod_obj_parent->getAttributes();

                                        $coming_from_parent = $attrs['parent-module'];
                                        $coming_from_parent_id = $attrs['parent-module-id'];


                                    }
                                }

                                if(!$coming_from_parent_id){
                                    $par_id_mod_count = 'global';

                                } else {
                                    $par_id_mod_count = $coming_from_parent_id;

                                }



//                                if (isset($attrs['module-id']) and $attrs['module-id'] != false) {
//                                    $attrs['id'] = $attrs['module-id'];
//                                }


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
                                $module_name = false;

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
                                        $attrs['data-type'] = $module_name;
                                    }
                                    if ($nn == 'data-module') {
                                        $attrs['data-type'] = $module_name;
                                        $module_name = $nv;
                                    }
                                    ++$z;
                                }
                                $module_title = false;
                                if (!isset($module_name) or !$module_name) {
                                    $module_html = false;
                                    continue;
                                } else if (isset($module_name)) {
                                    $module_class = $this->module_css_class($module_name);
                                    $module_title = module_info($module_name);


                                    if (!isset($attrs['id'])) {

                                        global $mw_mod_counter;
                                        ++$mw_mod_counter;


                                        $mod_id = '';
                                        $mod_id_was_not_found = false;
                                        $mod_id2 = '';


//                                        if (!defined('CONTENT_ID')) {
//                                            //   $mod_id = $mod_id . '-uid-fixme-' . uniqid();
//                                        //    $mod_id = $mod_id . '-'.url_string(true);
//
//                                        }


                                        if (!$mod_id) {
                                            $mod_id = $module_class;

                                            $mod_id_was_not_found = true;
                                            if ($coming_from_parent_id and $coming_from_parent) {
                                                //  $mod_id = $module_name;

                                            }
                                            if ($coming_from_parent_id and !$coming_from_parent) {
                                                $mod_id = $mod_id . '-should-not-get-here-' . $coming_from_parent_id;

                                            }

                                        }
                                        if ($coming_from_parent_id and $coming_from_parent) {
                                            $mod_id = $coming_from_parent_id . '-' . $module_name;

                                        }


                                        $mod_id = $this->_str_clean_mod_id($mod_id);

//                                        if(!$coming_from_parent){
//                                            continue;
//                                        }
                                        static $last_content_id = null;


                                        $append_to_id = false;
                                        $append_to_content_id = false;


                                        $curent_mod_rel = $mod_obj->getEditFieldRel();
                                        $curent_mod_field = $mod_obj->getEditField();







                                        if($curent_mod_rel) {


                                            switch ($curent_mod_rel) {
                                                case 'content':
                                                case 'page':
                                                case 'post':
                                                case 'product':
                                                    $append_to_content_id = true;
                                                    break;
                                                case 'global':
                                                case 'module':
                                                    $append_to_content_id = false;
                                                    break;

                                                default:
                                                    $append_to_content_id = false;
                                            }

                                            if ($mod_id_was_not_found and isset($options['populate_module_ids_in_elements']) and $options['populate_module_ids_in_elements']) {
                                                $append_to_id = date('YmdHis') . '-' . $mw_mod_counter;
                                            }




//                                            if ($curent_mod_rel == 'global') {
//                                                $append_to_content_id = false;
//
//                                            } else if ($curent_mod_rel == 'module') {
//                                                $append_to_content_id = false;
//
//
//                                            } else if ($curent_mod_rel == 'page'
//                                            or $curent_mod_rel == 'post'
//                                            or $curent_mod_rel == 'content') {
//                                                $append_to_content_id = true;
//                                            } else {
//                                                //if (!$coming_from_parent    ) {
//                                                $append_to_content_id = false;
//
//                                                if ($mod_id_was_not_found and isset($options['populate_module_ids_in_elements']) and $options['populate_module_ids_in_elements']) {
//                                                    $append_to_id = date('YmdHis') . '-' . $mw_mod_counter;
//                                                }
//                                                //}
//
//                                            }
                                        }
//
//
//
 if(defined('MW_FRONTEND')){
                                        if($append_to_content_id){
                                            if (content_id() == 0) {
                                                if ($last_content_id == null) {
                                                    $last_content_id = app()->database_manager->last_id('content');
                                                }
                                                $last_content_id = intval($last_content_id) + 1;
                                                $append_to_id =  $last_content_id;
                                            } elseif (content_id()) {
                                                $append_to_id =  content_id();
                                            }
                                        }
 }


                                        if ($append_to_id) {
                                            $mod_id = $mod_id . '-' . $append_to_id;
                                        }



                                        $it++;

//
//                                        $skip= 0;
//
//                                        if(!isset($this->_existing_module_ids_grouped[$coming_from_parent_id])){
//                                            $this->_existing_module_ids_grouped[$coming_from_parent_id] = array();
//                                        }
//                                        if(isset($this->_existing_module_ids_grouped[$coming_from_parent_id]) and isset($this->_existing_module_ids_grouped[$coming_from_parent_id][$mod_id])){
//                                            $skip= 1;
//                                        }
//                                         $skip= 0;


//                                        if ($coming_from_parent_id != false) {
//                                            $par_id_mod_count = $parse_key;
//                                            //$par_id_mod_count =$parser_mem_crc. $parse_key.$key. $coming_from_parent.$coming_from_parent_id;
//                                            //$par_id_mod_count = $coming_from_parent.$coming_from_parent_id;
//                                            //   $par_id_mod_count = $static_parser_mem_crc;
//                                            //    $par_id_mod_count = $parser_mem_crc;
//                                            //    $par_id_mod_count = $parser_modules_crc;
//                                            $par_id_mod_count = $coming_from_parent_id;
//
//
//                                        }
                                        //   $par_id_mod_count = $parser_mem_crc;
                                  //      $par_id_mod_count = $parse_key;




//                                        if ($this->_current_parser_rel  ) {
//                                            dump($this->_current_parser_rel);
//                                             //  $par_id_mod_count = $coming_from_parent_id.'ed-'.$this->_current_parser_rel.$this->_current_parser_rel;
//                                            //    $par_id_mod_count = $par_id_mod_count.$this->_current_parser_rel.$this->_current_parser_rel;
//
//                                        }


                                        if (!isset($local_mw_replaced_modules_ids_grouped[$par_id_mod_count])) {
                                            $local_mw_replaced_modules_ids_grouped[$par_id_mod_count] = array();
                                        }
                                        if (!isset($local_mw_replaced_modules_ids_grouped[$par_id_mod_count][$module_name])) {
                                            $local_mw_replaced_modules_ids_grouped[$par_id_mod_count][$module_name] = 0;
                                        }

                                        if (!isset($this->_existing_module_ids_grouped[$par_id_mod_count])) {
                                            $this->_existing_module_ids_grouped[$par_id_mod_count] = array();
                                        }

                                        // if (isset($this->_existing_module_ids[$mod_id])) {


                                        if (

                                            !isset($this->_existing_module_ids_grouped[$par_id_mod_count][$mod_id]) and
                                            isset($local_mw_replaced_modules_ids_grouped[$par_id_mod_count])
                                            and $local_mw_replaced_modules_ids_grouped[$par_id_mod_count][$module_name]) {

                                            $inc_mod_num = $local_mw_replaced_modules_ids_grouped[$par_id_mod_count][$module_name];



                                            $mod_id = $mod_id . '--' . $inc_mod_num;








                                        } else if (isset($this->_existing_module_ids_grouped[$par_id_mod_count][$mod_id])) {
                                            //    if ( !$skip) {
                                            ++$it_loop;
                                            $inc_mod_num = 0;


                                            if (isset($this->_current_parser_module_of_type[$par_id_mod_count])) {
                                                if (isset($this->_current_parser_module_of_type[$par_id_mod_count][$module_name])) {
                                                    $inc_mod_num = $this->_current_parser_module_of_type[$par_id_mod_count][$module_name];
//
                                                }

                                            } else {
                                                // $inc_mod_num = 1;
                                                //    $inc_mod_num = $it_loop;

                                            }

                                            if ($coming_from_parent_id) {
                                                //$mod_id =  $mod_id . '-' .$coming_from_parent_id;
                                            }

                                            //$mod_id = $mod_id . '--' . ++$it;
                                            if ($inc_mod_num) {
                                                $mod_id = $mod_id . '--' . $inc_mod_num;
                                            }

                                            // $mod_id = $mod_id . '--' . ++$this->_current_parser_module_of_type[$module_name];
                                            //  if (isset($this->_existing_module_ids[$mod_id])) {

                                            // if (isset($this->_existing_module_ids[$mod_id])) {
                                            if (isset($this->_existing_module_ids_grouped[$par_id_mod_count][$mod_id])) {
                                                // if (isset($this->_existing_module_ids_grouped[$coming_from_parent_id][$mod_id])) {


                                                $mod_id_probe = false;
                                                if ($coming_from_parent_id) {

                                                    $mod_id_probe = $mod_id;
                                                    //$mod_id_probe =  $mod_id . '-coming_from_parent_id' .$coming_from_parent_id;
                                                    //   $mod_id_probe = $coming_from_parent_id . '-' . $mod_id;
                                                    //   $mod_id_probe = str_replace('module-', '', $mod_id_probe);

                                                } else {

                                                    //   $mod_id = $mod_id . '-' . $last_content_id;
                                                }

                                                //if ($mod_id_probe and !isset($this->_existing_module_ids[$mod_id_probe])) {
                                                if ($mod_id_probe and !isset($this->_existing_module_ids_grouped[$par_id_mod_count][$mod_id])) {
                                                    $mod_id = $mod_id_probe;


                                                } else {
                                                    //  $mod_id = $mod_id . '--' .$it_loop;
                                                    //  $mod_id = $mod_id . '-' . $last_content_id;

                                                    if ($mod_id_probe and !isset($this->_existing_module_ids_grouped[$par_id_mod_count][$mod_id])) {
                                                        $mod_id = $mod_id_probe;
                                                    } else {


                                                        if (!$inc_mod_num and isset($local_mw_replaced_modules_ids_grouped[$par_id_mod_count]) and $local_mw_replaced_modules_ids_grouped[$par_id_mod_count][$module_name]) {

                                                            $inc_mod_num = $local_mw_replaced_modules_ids_grouped[$par_id_mod_count][$module_name];


                                                            $mod_id = $mod_id . '--' . $inc_mod_num;

                                                        } else {

                                                        }


                                                    }

                                                }


                                            }

                                        } else {



                                        // bug 3


                                          //  $mod_id = $mod_id . '--' . $inc_mod_num;


                                            if (!$it_loop and $coming_from_parent_id) {
                                                //  $mod_id = $mod_id . '-1asdds';
                                            }


//                                            if ($this->_current_parser_rel  and $this->_current_parser_rel  == 'module' ) {
//                                                $mod_id_probe = $mod_id;
//
//                                            }
                                            //  $mod_id = $mod_id . '-1asdds';
                                        }



                                        if( $this->registry->isParsedModule($module_name,$mod_id)){

                                            continue;
//                                            if($mod_obj and $mod_obj->isProcessing() and !$mod_obj->isProcessed()){
//                                                // do nothing
//                                            }  else {
//                                                continue;
//                                            }

                                        }



                                        $this->_existing_module_ids[$mod_id] = $mod_id;

                                        $this->registry->registerParsedModule($module_name,$mod_id);

                                        $this->_existing_module_ids_grouped[$par_id_mod_count][$mod_id] = $mod_id;

                                        //                            $attrs = $this->utils->parseAttributes($value);

                                        $local_mw_replaced_modules_ids_grouped[$par_id_mod_count][$module_name]++;

                                        // $this->_existing_module_ids_map[$parse_key.$replace_key] = $mod_id;
                                        //  $this->_current_parser_module_of_type[$par_id_mod_count][$module_name] = $mod_id;

                                        $attrs['id'] = $mod_id;
                                        if(!strpos($module_html,' id=')) {
                                            $module_html = str_replace('__MODULE_ID__', "id='{$attrs['id']}'", $module_html);
                                        } else {
                                            $module_html = str_replace('__MODULE_ID__', '', $module_html);
                                        }

                                    } else {
                                        $module_html = str_replace('__MODULE_ID__', '', $module_html);
                                    }


                                    $attrs2 = array();
                                    if (is_array($module_title) and isset($module_title['name'])) {
                                        $module_title['name'] = addslashes($module_title['name']);
                                        if(!strpos($module_html,' data-mw-title=')){
                                            $module_html = str_replace('__MODULE_NAME__', ' data-mw-title="' . $module_title['name'] . '"', $module_html);
                                        } else {
                                            $module_html = str_replace('__MODULE_NAME__', '', $module_html);
                                        }
                                    } else {
                                        $module_html = str_replace('__MODULE_NAME__', '', $module_html);
                                    }


                                    if (strstr($module_name, 'admin')) {
                                        $module_html = str_replace('__WRAP_NO_WRAP__', '', $module_html);
                                    } else {
                                        $module_html = str_replace('__WRAP_NO_WRAP__', '', $module_html);
                                    }
                                    $module_name_url = app()->url_manager->slug($module_name);



                                    if ($mod_as_element == false) {
                                        if (!$coming_from_parent_id and (isset($options['module_as_element']) and !isset($options['populate_module_ids_in_elements'])) or ($module_name == 'text' or $module_name == 'title' or $module_name == 'text/empty_element' or $module_name == 'text/multiple_columns')) {
                                            $module_html = str_replace('__MODULE_CLASS__', 'layout-element ' . $module_name_url, $module_html);
                                        } else {
                                            $module_html = str_replace('__MODULE_CLASS__', 'module ' . $module_class, $module_html);
                                        }

                                        if(isset($options['module_as_element'])){
                                            unset($options['module_as_element']);
                                        }

                                        if(isset($options['populate_module_ids_in_elements'])){
                                            unset($options['populate_module_ids_in_elements']);
                                        }

                                        $userclass = str_replace(trim($module_class), '', $userclass);
                                        $userclass = trim(str_replace(' -module ', 'module ', $userclass));
                                        $userclass = trim(str_replace(' module ', ' ', $userclass));
                                        $userclass = trim(str_replace(' disabled module ', ' module ', $userclass));
                                        $module_class = trim(str_replace(' disabled module ', ' module ', $module_class));
                                        $userclass = trim(str_replace(' module module ', ' module ', $userclass));
                                        $userclass = trim(str_replace('module module ', 'module ', $userclass));
                                        $module_html = str_replace('__MODULE_CLASS_NAME__', '' . $module_class, $module_html);
                                        // $module_html = str_replace('__USER_DEFINED_CLASS__', $userclass, $module_html);

                                    } else {
                                        $userclass = trim(str_replace(' -module ', '', $userclass));

                                        $module_html = str_replace('__MODULE_CLASS__', 'element ' . $module_name_url, $module_html);
                                        $mod_no_wrapper = true;
                                    }
                                    $module_html = str_replace('__USER_DEFINED_CLASS__', $userclass, $module_html);





//                                    if ($coming_from_parent == false) {
//
//                                        $coming_from_parentz = $module_name;
//                                        $coming_from_parent_strz1 = $attrs['id'];
//                                        $previous_attrs2 = $attrs;
//                                        $attrs['parent-module'] = $coming_from_parentz;
//                                        $attrs['parent-module-id'] = $coming_from_parent_strz1;
//                                        $this->prev_module_data = $attrs;
//
//                                    } else {
//                                        $par_id_mod_count = $coming_from_parent_id;
//                                        $attrs['parent-module-id'] = $coming_from_parent_id;
//                                        $attrs['parent-module'] = $coming_from_parent;
//                                        $this->prev_module_data = $attrs;
//
//                                        $coming_from_parentz = $module_name;
//                                        $coming_from_parent_strz1 = $attrs['id'];
//                                    }





//                                    if (!isset($attrs['id'])) {
//                                        $getId = $mod_obj->getId();
//                                        if ($getId) {
//                                            $attrs['id'] = $getId;
//                                        } else {
//                                            $mod_obj->setId($attrs['id']);
//                                        }
//                                    }






                                    $mod_obj->setId($attrs['id']);
                                    $mod_obj->setModuleName($module_name);
                                    $mod_obj->setAttributes($attrs);

                                 if(!isset($attrs['parent-module-id'])) {
                                        $check_mod_obj_parent = ($mod_obj->getParent());

                                        if ($check_mod_obj_parent) {


                                       //
                                            $attrs['parent-module-id'] = $check_mod_obj_parent->getId();
                                            $attrs['parent-module'] = $check_mod_obj_parent->getModuleName();

                                            $this->prev_module_data = $check_mod_obj_parent->getAttributes();



                                              //  $coming_from_parent = $attrs['parent-module'];


                                              //  $coming_from_parent_id = $attrs['parent-module-id'];






                                        } else if($prevous_mod_obj){
                                            $attrs['parent-module'] = $module_name;
                                            $attrs['parent-module-id'] =  $attrs['id'];
                                            $this->prev_module_data = $attrs;
                                      //      $attrs['parent-module-id'] = $prevous_mod_obj->getId();
                                       //     $attrs['parent-module'] = $prevous_mod_obj->getModuleName();

                                        //    $this->prev_module_data = $prevous_mod_obj->getAttributes();
                                        } else {

                                                  $attrs['parent-module'] = $module_name;
                                                 $attrs['parent-module-id'] =  $attrs['id'];
                                            $this->prev_module_data = $attrs;
                                        }
                                  }


                           //         $attrs['parent-module'] = $module_name;
                              //      $attrs['parent-module-id'] =  $attrs['id'];



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

                                    // }


                                    if (!isset($this->_current_parser_module_of_type[$par_id_mod_count])) {
                                        $this->_current_parser_module_of_type[$par_id_mod_count] = array();
                                    }
                                    if (!isset($this->_current_parser_module_of_type[$par_id_mod_count])) {
                                        $this->_current_parser_module_of_type[$par_id_mod_count] = array();
                                    }
                                    if (!isset($this->_current_parser_module_of_type[$par_id_mod_count][$module_name])) {
                                        $this->_current_parser_module_of_type[$par_id_mod_count][$module_name] = 0;
                                    }
                                    $this->_current_parser_module_of_type[$par_id_mod_count][$module_name]++;



                                    $mod_content =$this->load($module_name, $attrs);


                                    if($this->current_module and isset($this->current_module['settings'] ) and isset($this->current_module['settings']['html_tag']) and $this->current_module['settings']['html_tag']){
                                        $module_html_tag = $this->current_module['settings']['html_tag'];
                                    }



                                    $plain_modules = mw_var('plain_modules');

                                    if ($plain_modules != false) {
                                        if (!defined('MW_PLAIN_MODULES')) {
                                            define('MW_PLAIN_MODULES', true);
                                        }
                                    }
                                    foreach ($attrs as $nn => $nv) {

                                        if ($nn != 'class') {
                                            $pass = true;
                                            if ($mod_no_wrapper) {
                                                if ($nn == 'id') {
                                                    $pass = false;

                                                }
                                            }

                                            if ($pass /*and $nv*/) {
                                                if(!strpos($module_html,' '.$nn.'=')) {

                                                    // $module_html .= " {$nn}='{$nv}'  ";
                                                    $module_html .= " {$nn}=\"{$nv}\"  ";
                                                    // $module_html .= " {$nn}={$nv}  ";
                                                }
                                            }
                                        }
                                    }

                                    $plain_modules = false;
                                    unset($local_mw_replaced_modules[$parse_key][$key]);


                                    if ($this->current_module /*and isset($this->current_module['module_type']) and $this->current_module['module_type']*/) {
                                        $mod_content = $this->_process_additional_module_parsers($mod_content, $this->current_module,$this->current_module_params);
                                    }


                                    $mod_content = $this->_replace_tags_with_placeholders($mod_content);


                                    $proceed_with_parse = $this->_do_we_have_more_for_parse($mod_content);
//
//                                                                        $mod_id_value = $module_name.$coming_from_parent_strz1.$par_id_mod_count;
//                                    $that = $this;
//                                    $mod_content = tap( $mod_id_value , function () use ($attrs,$module_name,$that) {
//                                      return $that->load($module_name, $attrs);
//                                    });

                                    if ($proceed_with_parse == true) {
                                        $this->have_more = true;
                                        preg_match_all('/.*?class=..*?edit.*?.[^>]*>/', $mod_content, $layoutmatches);
                                        if (!empty($layoutmatches) and isset($layoutmatches[0][0])) {



                                                $proceed_with_parse = $this->_do_we_have_more_for_parse($mod_content);
                                                if ($proceed_with_parse == true) {
                                                    $mod_content = $this->_replace_editable_fields($mod_content, false, $mod_content,$coming_from_parent_id,$mod_obj);
                                                }



                                        }
                                        //  $mod_content2 = $mod_content;
                                        $proceed_with_parse = $this->_do_we_have_more_for_parse($mod_content);





                                        if ($proceed_with_parse == true) {






//                                            if (!empty($global_mw_replaced_modules)) {
//                                                foreach ($global_mw_replaced_modules as $key => $value) {
//                                                    if ($value != '') {
//                                                        $mod_content = str_replace($key, $value, $layout);
//                                                    }
//                                                    //unset($this->_mw_parser_replaced_html_comments[$key]);
//                                                }
//                                            }



                                            $mod_content = $this->process($mod_content, $options, $coming_from_parentz, $coming_from_parent_strz1, $previous_attrs2,$mod_obj);
                                        }

                                        $mod_content = $this->_replace_tags_with_placeholders($mod_content);


                                        if (strpos($mod_content, '<inner-edit-tag>mw_saved_inner_edit_from_parent_edit_field</inner-edit-tag>') !== false) {

                                            if (!isset($this->_mw_parser_passed_replaces_inner[$parse_key])) {
                                                $mod_content = $this->_replace_editable_fields($mod_content, false, $mod_content,$coming_from_parent_id,$mod_obj);
                                                $proceed_with_parse = $this->_do_we_have_more_for_parse($mod_content);
                                                if ($proceed_with_parse == true) {
                                                    $mod_content = $this->process($mod_content, $options, $coming_from_parentz, $coming_from_parent_strz1, $previous_attrs2,$mod_obj);
                                                }
                                                $this->_mw_parser_passed_replaces_inner[$parse_key] = $mod_content;
                                            } else {
                                                $mod_content = $this->_mw_parser_passed_replaces_inner[$parse_key];
                                            }

                                        }
                                        $mod_content = $this->_replace_tags_with_placeholders($mod_content);


                                    } else {
                                        $this->have_more = false;
                                        $this->prev_module_data = array();
                                        $it_loop2 = 0;
                                        $coming_from_parent_str = '';

                                    }


                                    global $other_html_tag_replace_inc;

                                    if ($mod_no_wrapper == false) {
                                        $coming_from_parent_str = '';

                                        $module_html .= $coming_from_parent_str . '>' . $mod_content . '</__MODULE_HTML_TAG__>';


                                        $module_html = str_replace('__MODULE_HTML_TAG__', $module_html_tag, $module_html);


                                    } else {


                                        $module_html = $mod_content;
                                    }
                                }
                                $it_loop1++;
                                $it_loop2++;


//                                if (!isset($this->_current_parser_module_of_type[$par_id_mod_count])) {
//                                    $this->_current_parser_module_of_type[$par_id_mod_count] = array();
//                                }
//                                if (!isset($this->_current_parser_module_of_type[$par_id_mod_count])) {
//                                    $this->_current_parser_module_of_type[$par_id_mod_count] = array();
//                                }
//                                if (!isset($this->_current_parser_module_of_type[$par_id_mod_count][$module_name])) {
//                                    $this->_current_parser_module_of_type[$par_id_mod_count][$module_name] = 0;
//                                }
//                                $this->_current_parser_module_of_type[$par_id_mod_count][$module_name]++;
//                                //$this->_current_parser_module_of_type[$par_id_mod_count][$module_name]++;

                              //  $module_html = $this->_replace_tags_with_placeholders_back($module_html);
                                $mod_obj->setOutput($module_html);
                                $mod_obj->setIsProcessed(true);

                                $this->mw_replaced_modules_values[$parser_mem_crc] = $module_html;
                                $layout = $this->_str_replace_first($value, $module_html, $layout);
                                $layout = $this->_str_replace_first($replace_key, $module_html, $layout);


                            }
                        }
                        $mod_obj->setIsProcessing(false);
                        $mod_obj->setIsProcessed(true);

                        //$value=$this->process_module_item_from_loop($key, $value, $layout);
                        $layout = $this->_str_replace_first($key, $value, $layout);
                    }
                }
            }






        } else {
            $this->have_more = false;
            $this->prev_module_data = array();
            $it_loop2 = 0;
        }
        if ($is_first_loop) {
            if (!empty($mw_replaced_textarea_tag)) {
                foreach ($mw_replaced_textarea_tag as $key => $value) {
                    if ($value != '') {
                        $layout = str_replace($key, $value, $layout);
                    }
                    //  unset($mw_replaced_textarea_tag[$key]);
                }
            }

            $layout = $this->_replace_tags_with_placeholders_back($layout);
            $layout = $this->replace_url_placeholders($layout);
        }
        if (!$coming_from_parent or   !$this->have_more or $it_loop == 0 ) {

            if (!empty($mw_replaced_textarea_tag)) {
                foreach ($mw_replaced_textarea_tag as $key => $value) {
                    if ($value != '') {
                        $layout = str_replace($key, $value, $layout);
                    }
                    //  unset($mw_replaced_textarea_tag[$key]);
                }
            }

            if ($is_first_loop) {
                 $layout = $this->_replace_tags_with_placeholders_back($layout);
            }
        //  $layout = $this->_replace_tags_with_placeholders_back($layout);

           // $layout = $this->replace_url_placeholders($layout);
        } else {
            if ($layout and is_string($layout) and str_contains($layout, 'mw_replace_back_this_module_')) {
                if (!empty($global_mw_replaced_modules[$static_parser_mem_crc])) {
                    foreach ($global_mw_replaced_modules[$static_parser_mem_crc] as $key => $value) {
                        if ($value != '') {
                            $layout = str_replace($key, $value, $layout);
                        }
                    }
                }
            }
        }



//
//        if (!empty($mw_replaced_textarea_tag)) {
//            foreach ($mw_replaced_textarea_tag as $key => $value) {
//                if ($value != '') {
//                    $layout = str_replace($key, $value, $layout);
//                }
//                //unset($mw_replaced_textarea_tag[$key]);
//            }
//        }
     //   $layout = $this->_replace_tags_with_placeholders_back($layout);




        $layout = str_replace('{rand}', uniqid() . rand(), $layout);
        $layout = str_replace('{SITE_URL}', app()->url_manager->site(), $layout);
        $layout = str_replace('{MW_SITE_URL}', app()->url_manager->site(), $layout);
        $layout = str_replace('%7BSITE_URL%7D', app()->url_manager->site(), $layout);
//        //  $mw_replaced_edit_fields_vals[$parser_mem_crc] = $layout;

        return $layout;
    }


    private function _do_we_have_more_for_parse($mod_content)
    {


        $proceed_with_parse = false;

        if ($this->_do_we_have_more_edit_fields_for_parse($mod_content)) {
            $proceed_with_parse = true;
        } else {
            $has_not_found = true;
            $has_found = true;

            preg_match_all('/<module.*[^>]*>/', $mod_content, $modinner);
            if (!empty($modinner) and isset($modinner[0][0])) {

                $proceed_with_parse = true;


            } else {


                preg_match_all('/<mw-unprocessed-module-tag.*[^>]*>/', $mod_content, $modinner);
                if (!empty($modinner) and isset($modinner[0][0])) {

                    $proceed_with_parse = true;


                }
            }

        }
        return $proceed_with_parse;
    }


}
