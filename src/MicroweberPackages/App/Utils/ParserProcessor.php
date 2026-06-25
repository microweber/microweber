<?php


namespace MicroweberPackages\App\Utils;


use Doctrine\DBAL\Connection;
use Illuminate\Support\Str;
use MicroweberPackages\App\Utils\ParserHelpers\AttributeParser;
use MicroweberPackages\App\Utils\ParserHelpers\ContentProtector;
use MicroweberPackages\App\Utils\ParserHelpers\EditFieldExtractor;
use MicroweberPackages\App\Utils\ParserHelpers\LayoutProcessor;
use MicroweberPackages\App\Utils\ParserHelpers\ModuleIdAllocator;
use MicroweberPackages\App\Utils\ParserHelpers\ModuleRenderer;
use MicroweberPackages\App\Utils\ParserHelpers\ParserLayoutItem;
use MicroweberPackages\App\Utils\ParserHelpers\ParserModuleItem;
use MicroweberPackages\App\Utils\ParserHelpers\ParserModuleItemCollection;
use MicroweberPackages\App\Utils\ParserHelpers\TagLexer;
use MicroweberPackages\View\MicroweberModuleTagCompiler;

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

    /**
     * New helper instances (refactored from the monolithic code).
     */
    public TagLexer $tagLexer;
    public AttributeParser $attributeParser;
    public ContentProtector $contentProtector;
    public ModuleIdAllocator $moduleIdAllocator;
    public ModuleRenderer $moduleRenderer;
    public EditFieldExtractor $editFieldExtractor;
    public LayoutProcessor $layoutProcessor;

    /** Re-entrancy guard for the experimental LayoutProcessor pipeline. */
    private bool $_layoutProcessorActive = false;


    public function __construct()
    {

        $this->utils = new ParserUtils();
        $this->registry = new ParserRegistry();
        $this->parser_modules_collection = new ParserModuleItemCollection();

        // Initialize new helper classes
        $this->tagLexer = new TagLexer();
        $this->attributeParser = new AttributeParser();
        $this->contentProtector = new ContentProtector();
        $this->moduleIdAllocator = new ModuleIdAllocator();
        $this->moduleRenderer = new ModuleRenderer();
        $this->editFieldExtractor = new EditFieldExtractor($this->attributeParser);
        $this->layoutProcessor = new LayoutProcessor(
            $this->tagLexer,
            $this->attributeParser,
            $this->contentProtector,
            $this->moduleIdAllocator,
            $this->moduleRenderer,
            $this->editFieldExtractor
        );

        require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';

    }

    /*
     * cycle-N (post-cycle-116 OOM hunt): converted from `public static`
     * to `public` instance property. The static accumulator never got
     * reset between phpunit tests, so every `process()` call seeded
     * entries (~1KB each) into the same in-memory hash for the
     * lifetime of the PHP process — directly contributing to the
     * ~6MB-per-test leak documented in project memory
     * `project_test_architecture`. As an instance property it lives
     * only as long as the ParserProcessor instance (owned by the
     * per-request Parser, GC'd when LaravelApplication tears down
     * between tests). No external caller reads it via the
     * `ParserProcessor::$...` syntax (verified with grep) — the only
     * touches are `self::$...` inside the class, now `$this->...`.
     */
    public $process_layouts_loop = [];

    /**
     * Stack of ParserModuleItem objects pushed while a layout Blade template
     * is being rendered via $this->load(). When non-empty, any bare
     * single-<module> tag that reaches process() with no parent context
     * (i.e. from an @module() Blade directive compiled inline) is returned
     * unchanged so the outer _replace_editable_fields + recursive process()
     * flow can assign the correct rel/field/parent context.
     */
    public array $_deferred_blade_module_stack = [];

    public function process($layout, $options = false, $coming_from_parent = false, $coming_from_parent_id = false, $previous_attrs = false, $prevous_mod_obj = false, $prevous_layout_obj = false)
    {
        if ($layout == '') {
            return;
        }

        // Route a top-level, context-free layout through the default
        // ParserHelpers\LayoutProcessor pipeline (unless legacy is opted in via
        // use_legacy_parser). Only the simple top-level case is routed — never
        // recursive / parent-driven / per-module sub-calls, which legacy owns.
        if ($this->shouldUseLayoutProcessor($coming_from_parent, $prevous_mod_obj, $previous_attrs)) {
            return $this->processWithLayoutProcessor($layout);
        }

        // Deferred Blade module processing: if we're currently rendering a
        // layout's Blade template (stack non-empty) and this call is a bare
        // single-module tag with no parent context, return the tag unchanged
        // so the outer _replace_editable_fields + recursive process() flow
        // can assign the correct rel/field/parent context before rendering.
        if (!empty($this->_deferred_blade_module_stack)
            && !$prevous_mod_obj
            && preg_match('/^\s*<module\s[^>]*\/>\s*$/s', $layout)) {
            return $layout;
        }
        /*
         * cycle-N (post-cycle-116 OOM hunt): the original code declared
         * `static $first_known_mod;` and `static $it_loop2;`. PHP
         * function-level statics persist for the entire PHP process
         * lifetime — including across every phpunit test iteration —
         * so any state that bled into `$it_loop2` from a previous test
         * carried forward. Inspection shows nothing actually READS the
         * cross-call value; both vars are effectively per-call counters
         * that get reset at function entry. Promoted to plain locals
         * so no cross-test bleed is possible.
         */
        $first_known_mod = null;
        $it_loop2 = 0;

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
        MicroweberModuleTagCompiler::disableModuleProcessing();


     //   dd(app('blade.compiler'));


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
        if (!isset($this->process_layouts_loop[$parser_modules_crc])) {
            $is_first_loop = true;
            $this->process_layouts_loop[$parser_modules_crc] = true;
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


        global $global_mw_replaced_modules;


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
         //       dd($layout);
                //      $layout = $this->_edit_field_add_modules_for_processing_first_pass($layout);

            }



            $layout = $this->_replace_editable_fields($layout, false, $layout, $coming_from_parent_id);
            //  $layout = $this->_replace_tags_with_placeholders_back($layout);
            if ($this->debugbarEnabled && class_exists('Barryvdh\Debugbar\Facades\Debugbar')) {
                \Barryvdh\Debugbar\Facades\Debugbar::info($layout);
            }




            $layout = $this->_edit_field_add_modules_for_processing($layout, 'mwnoedit', 'mwnoedit', false, $prevous_mod_obj);

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
//
            // if ($coming_from_parent) {
            $more = $this->_do_we_have_more_edit_fields_for_parse($layout);
            if ($more) {
                // bug ?

                $layout = $this->_replace_editable_fields($layout, false, $layout, $coming_from_parent_id);
                //    $layout = $this->_replace_tags_with_placeholders_back($layout);

            }



            // if($is_first_loop) {
            $layout = $this->_edit_field_add_modules_for_processing($layout, 'mwnoedit', 'mwnoedit', false, $prevous_mod_obj);
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


            if (is_array($local_mw_replaced_modules) and !empty($local_mw_replaced_modules)) {


                $attrs = array();
                foreach ($local_mw_replaced_modules as $parse_key => $parse_item) {


                    $parent_of_iteration = false;

                    //$parse_item  = array_reverse($parse_item);
                    foreach ($parse_item as $key => $mod_obj) {


                        /**
                         * @var $mod_obj ParserModuleItem
                         */

                        $value = $mod_obj->getReplaceValue();
                        //  $value = $parse_item_obj->get
                        $replace_key = $key;
                        $replace_key2 = $key;
                        //   $replace_key2 = $parse_key . $key . $parser_mem_crc;
                        if (isset($this->mw_replaced_modules_values[$replace_key2])) {


                            continue;
                        }

                        if ($mod_obj->isProcessing()) {
                            continue;

                        }

                        if ($mod_obj->isProcessed()) {

                            $module_html = $mod_obj->getOutput();
                            // $layout = $this->_str_replace_first($value, $module_html, $layout);
                            $layout = $this->_str_replace_first($replace_key, $module_html, $layout);


                            continue;
                        }


                        //   $mod_obj->setIsProcessed(true);

                        $mod_obj->setIsProcessing(true);


                        if ($value != '') {


                            $attrs = $this->attributeParser->parse($value);


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

                                if (isset($attrs['type']) and $attrs['type']) {
                                    $attrs['data-type'] = $attrs['type'];
                                    unset($attrs['type']);
                                }

                                if (!isset($attrs['parent-module-id'])) {
                                    $check_mod_obj_parent = ($mod_obj->getParent());

                                    if ($check_mod_obj_parent) {

                                        $attrs['parent-module-id'] = $check_mod_obj_parent->getId();
                                        $attrs['parent-module'] = $check_mod_obj_parent->getModuleName();
                                        $this->prev_module_data = $check_mod_obj_parent->getAttributes();

                                        $coming_from_parent = $attrs['parent-module'];
                                        $coming_from_parent_id = $attrs['parent-module-id'];


                                    } else  if (isset($attrs['id']) and isset($attrs['data-type'])){
//                                        $attrs['parent-module-id'] = $attrs['id'];
//                                        $attrs['parent-module'] = $attrs['data-type'];
//                                        $coming_from_parent = $attrs['parent-module'];
//                                        $coming_from_parent_id = $attrs['parent-module-id'];


                                    }
                                }

                                if (!$coming_from_parent_id) {
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

                                $z = 0;
                                $mod_as_element = false;
                                $mod_no_wrapper = false;
                                $module_name = false;

                                if (isset($options['no_wrap']) and $options['no_wrap']) {
                                    $mod_no_wrapper = true;
                                }

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
                                    $module_title = Str::headline($module_name);


                                    if (!isset($attrs['id'])) {

                                        global $mw_mod_counter;
                                        ++$mw_mod_counter;


                                        $mod_id = '';
                                        $mod_id_was_not_found = false;
                                        $mod_id2 = '';

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


//                                        if(!$coming_from_parent){
//                                            continue;
//                                        }
                                        static $last_content_id = null;


                                        $append_to_id = false;
                                        $append_to_content_id = false;


                                        $curent_mod_rel = $mod_obj->getEditFieldRel();
                                        $curent_mod_field = $mod_obj->getEditField();

// check this again if repeating modules ids appear again, uncomment this
                                        if($curent_mod_rel == 'module' and $curent_mod_field){
                                            $mod_id =  $curent_mod_field . '-' . $mod_id;
                                        }


                                        $mod_id = $this->_str_clean_mod_id($mod_id);

//                                        if($module_name == 'btn') {
//Debugbar::info($mod_id);
//                                          //  dump($layout,$more,$coming_from_parent_id);
//                                            //  dd(debug_backtrace(1));
//                                          //  dd($curent_mod_field,$mod_id,$attrs);
//                                        }

                                        if ($curent_mod_rel) {


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
                                        if (defined('MW_FRONTEND')) {
                                            if ($append_to_content_id) {
                                                if (content_id() == 0) {
                                                    if ($last_content_id == null) {
                                                        $last_content_id = app()->database_manager->last_id('content');
                                                    }
                                                    $last_content_id = intval($last_content_id) + 1;
                                                    $append_to_id = $last_content_id;
                                                } elseif (content_id()) {
                                                    $append_to_id = content_id();
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


                                        } else if

                                        (
                                            $this->registry->isModuleIdFromDatabase($mod_id) ||

                                            isset($this->_existing_module_ids_grouped[$par_id_mod_count][$mod_id])
                                        ) {

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
                                                if ($this->registry->isModuleIdFromDatabase($mod_id)) {
                                                    $inc_mod_num = 1;
                                                }
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


                                        if ($this->registry->isParsedModule($module_name, $mod_id)) {

                                            continue;
//                                            if($mod_obj and $mod_obj->isProcessing() and !$mod_obj->isProcessed()){
//                                                // do nothing
//                                            }  else {
//                                                continue;
//                                            }

                                        }


                                        $this->_existing_module_ids[$mod_id] = $mod_id;

                                        $this->registry->registerParsedModule($module_name, $mod_id);

                                        $this->_existing_module_ids_grouped[$par_id_mod_count][$mod_id] = $mod_id;

                                        //                            $attrs = $this->utils->parseAttributes($value);

                                        $local_mw_replaced_modules_ids_grouped[$par_id_mod_count][$module_name]++;

                                        // $this->_existing_module_ids_map[$parse_key.$replace_key] = $mod_id;
                                        //  $this->_current_parser_module_of_type[$par_id_mod_count][$module_name] = $mod_id;

                                        $attrs['id'] = $mod_id;
                                        if (!strpos($module_html, ' id=')) {
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
                                        if (!strpos($module_html, ' data-mw-title=')) {
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
                                        if (!$coming_from_parent_id and (isset($options['module_as_element']) and !isset($options['populate_module_ids_in_elements']))
                                            or ($module_name == 'text' or $module_name == 'title' or $module_name == 'text/empty_element' or $module_name == 'text/multiple_columns')) {
                                            $module_html = str_replace('__MODULE_CLASS__', 'layout-element ' . $module_name_url, $module_html);

                                        } else if ((isset($options['module_as_element']) and isset($options['populate_module_ids_in_elements']))) {
                                            $module_html = str_replace('__MODULE_CLASS__', 'layout-element ' . $module_name_url, $module_html);

                                        } else {
                                            $module_html = str_replace('__MODULE_CLASS__', 'module ' . $module_class, $module_html);
                                        }


                                        if (isset($options['module_as_element'])) {
                                            unset($options['module_as_element']);
                                        }

                                        if (isset($options['populate_module_ids_in_elements'])) {
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

                                    if (!isset($attrs['parent-module-id'])) {
                                        $check_mod_obj_parent = ($mod_obj->getParent());

                                        if ($check_mod_obj_parent) {


                                            //
                                            $attrs['parent-module-id'] = $check_mod_obj_parent->getId();
                                            $attrs['parent-module'] = $check_mod_obj_parent->getModuleName();

                                            $this->prev_module_data = $check_mod_obj_parent->getAttributes();


                                           //    $coming_from_parent = $attrs['parent-module'];


                                           //    $coming_from_parent_id = $attrs['parent-module-id'];


                                        } else if ($prevous_mod_obj) {

                                            $attrs['parent-module'] = $module_name;
                                            $attrs['parent-module-id'] = $attrs['id'];
                                            $this->prev_module_data = $attrs;
                                            //      $attrs['parent-module-id'] = $prevous_mod_obj->getId();
                                            //     $attrs['parent-module'] = $prevous_mod_obj->getModuleName();

                                            //    $this->prev_module_data = $prevous_mod_obj->getAttributes();
                                        } else {

                                            $attrs['parent-module'] = $module_name;
                                            $attrs['parent-module-id'] = $attrs['id'];
                                            $this->prev_module_data = $attrs;
                                        }
                                    }
                                    $prevous_mod_obj = $mod_obj;

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


                                    $this->_deferred_blade_module_stack[] = $mod_obj;
                                    $mod_content = $this->load($module_name, $attrs);


                                    if ($this->current_module and isset($this->current_module['settings']) and isset($this->current_module['settings']['html_tag']) and $this->current_module['settings']['html_tag']) {
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
                                                if (!strpos($module_html, ' ' . $nn . '=')) {

                                                    // $module_html .= " {$nn}='{$nv}'  ";
                                                    $module_html .= " {$nn}=\"{$nv}\"  ";
                                                    // $module_html .= " {$nn}={$nv}  ";
                                                }
                                            }
                                        }
                                    }

                                    $plain_modules = false;
                                    unset($local_mw_replaced_modules[$parse_key][$key]);

                                    if(is_object($mod_content) and method_exists($mod_content, 'render')){
                                        $mod_content = $mod_content->render();
                                     }
                                    array_pop($this->_deferred_blade_module_stack);


                                    if ($this->current_module /*and isset($this->current_module['module_type']) and $this->current_module['module_type']*/) {
                                        $mod_content = $this->_process_additional_module_parsers($mod_content, $this->current_module, $this->current_module_params);
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
                                                $mod_content = $this->_replace_editable_fields($mod_content, false, $mod_content, $coming_from_parent_id, $mod_obj);
                                            }


                                        }
                                        //  $mod_content2 = $mod_content;
                                        $proceed_with_parse = $this->_do_we_have_more_for_parse($mod_content);
//                                        $debug = [];
//                                        $debug['mod_content'] = $mod_content;
//                                        $debug['proceed_with_parse'] = $proceed_with_parse;
//                                        $debug['coming_from_parent_id'] = $coming_from_parent_id;
//                                        $debug['mod_obj'] = $mod_obj;
//                                        file_put_contents(base_path('debug.txt'),print_r($debug,true),FILE_APPEND);

                                        if ($proceed_with_parse == true) {


//                                            if (!empty($global_mw_replaced_modules)) {
//                                                foreach ($global_mw_replaced_modules as $key => $value) {
//                                                    if ($value != '') {
//                                                        $mod_content = str_replace($key, $value, $layout);
//                                                    }
//                                                    //unset($this->_mw_parser_replaced_html_comments[$key]);
//                                                }
//                                            }


                                            $mod_content = $this->process($mod_content, $options, $coming_from_parentz, $coming_from_parent_strz1, $previous_attrs2, $mod_obj);
                                        }

                                        $mod_content = $this->_replace_tags_with_placeholders($mod_content);


                                        if (strpos($mod_content, '<inner-edit-tag>mw_saved_inner_edit_from_parent_edit_field</inner-edit-tag>') !== false) {

                                            if (!isset($this->_mw_parser_passed_replaces_inner[$parse_key])) {
                                                $mod_content = $this->_replace_editable_fields($mod_content, false, $mod_content, $coming_from_parent_id, $mod_obj);
                                                $proceed_with_parse = $this->_do_we_have_more_for_parse($mod_content);
                                                if ($proceed_with_parse == true) {
                                                    $mod_content = $this->process($mod_content, $options, $coming_from_parentz, $coming_from_parent_strz1, $previous_attrs2, $mod_obj);
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
        if (!$coming_from_parent or !$this->have_more or $it_loop == 0) {

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
        MicroweberModuleTagCompiler::enableModuleProcessing();
        return $layout;
    }


    /**
     * Whether the experimental LayoutProcessor pipeline should handle this call.
     *
     * Engaged only when the config flag is on AND this is a top-level call with
     * no parent/module/attribute context (the legacy flow keeps every recursive
     * and per-module sub-call).
     */
    private function shouldUseLayoutProcessor($coming_from_parent, $prevous_mod_obj, $previous_attrs): bool
    {
        // The LayoutProcessor pipeline is the default; only opt OUT to legacy.
        if ($this->useLegacyParser()) {
            return false;
        }
        // Re-entrancy guard: while a top-level LayoutProcessor run is active,
        // any nested process() call (e.g. triggered from inside load() when a
        // module renders its own sub-layout) must fall through to the legacy
        // flow. Re-entering here would reset the shared id allocator / protector
        // mid-render → duplicate module ids → load-cache collisions (every
        // layout rendering the first one's content).
        if ($this->_layoutProcessorActive) {
            return false;
        }
        return !$coming_from_parent && !$prevous_mod_obj && !$previous_attrs;
    }

    /**
     * Whether to fall back to the legacy phpQuery parser. The LayoutProcessor
     * pipeline is the default; legacy is opt-in via config/env
     * (microweber.use_legacy_parser / MW_USE_LEGACY_PARSER) or the admin option
     * `use_legacy_parser`. The option lookup is cached per request.
     *
     * @deprecated The legacy phpQuery parser path is deprecated. The default and
     *             validated parser is the ParserHelpers\LayoutProcessor pipeline
     *             ({@see processWithLayoutProcessor()}); the `use_legacy_parser`
     *             opt-out — and the legacy branch in {@see process()} it selects —
     *             is retained only as a temporary fallback and is scheduled for
     *             removal once the new parser is fully signed off.
     */
    private function useLegacyParser(): bool
    {
        if (config('microweber.use_legacy_parser', false)) {
            return true;
        }

        static $optionCache = null;
        if ($optionCache === null) {
            $optionCache = false;
            if (function_exists('get_option')) {
                try {
                    $opt = get_option('use_legacy_parser', 'website');
                    $optionCache = in_array($opt, ['y', 'yes', '1', 1, true, 'true'], true);
                } catch (\Throwable $e) {
                    $optionCache = false;
                }
            }
        }

        return $optionCache;
    }

    /**
     * Run the new ParserHelpers\LayoutProcessor over a layout, bridging module
     * rendering back to the real module system via $this->load(). State is reset
     * per top-level call so module ids restart deterministically.
     */
    /**
     * Reset the shared LayoutProcessor state (module-id allocator + content
     * protector) that is otherwise scoped to a single top-level
     * {@see processWithLayoutProcessor()} run.
     *
     * In normal operation a request renders ONE top-level layout and then the
     * process ends, so this state never needs explicit clearing between
     * renders. But a single PHP process that performs MANY independent
     * top-level layout renders WITHOUT going through process()'s reset path
     * (e.g. a test that loops `load_module('layouts', …)` over hundreds of
     * skins, or a batch/CLI renderer) would otherwise accumulate allocator
     * counters and protected-content placeholders across renders, eventually
     * leaking a stale module id / `&quot;` fragment from an earlier render into
     * a later one. Call this between such independent renders to restore the
     * same clean, isolated state each web request gets for free.
     */
    public function resetLayoutState(): void
    {
        $this->moduleIdAllocator->reset();
        $this->contentProtector->reset();

        // The nested legacy flow (run via the re-entrancy guard while a
        // LayoutProcessor render is active) parses through phpQuery, which keeps
        // every parsed DOM in a process-global static registry
        // (phpQuery::$documents). The legacy parser unloads them at the end of
        // its own render (see Parser.php), but the LayoutProcessor short-circuit
        // never did — so across many independent top-level renders in one PHP
        // process the registry grows unbounded (the historical OOM source) and a
        // stale document can bleed a fragment (e.g. a Livewire `wire:snapshot`
        // carrying `&quot;`) into a later, otherwise-clean render. Unload them at
        // each top-level render boundary, matching the legacy path. A real
        // request renders one layout then ends, so this is a no-op there.
        if (class_exists(\phpQuery::class, false)) {
            \phpQuery::unloadDocuments();
        }
    }

    private function processWithLayoutProcessor($layout)
    {
        $this->_layoutProcessorActive = true;
        $this->resetLayoutState();

        // content_id() is 0/empty when there is no current content — treat that
        // as "no content scope" (null) so ids stay bare (module-btn, not -0).
        $contentId = function_exists('content_id') ? (int) content_id() : 0;
        $contentId = $contentId > 0 ? $contentId : null;

        $moduleLoader = function ($moduleName, $attrs) {
            return $this->load($moduleName, $attrs);
        };

        // Bridge to the real edit-field store so saved region content replaces
        // each .edit default — the piece that lets real pages render their
        // authored content through the new pipeline.
        $editFieldLoader = function ($field, $rel, $relId, $cid) {
            if (!$field || !$rel) {
                return null;
            }

            // Native content-table fields (content / content_body / description
            // / title) live on the content ROW, not in content_fields — this is
            // where a page's MAIN edited content actually sits. Mirror the legacy
            // loader (_is_native_content_table_field): read the row column first
            // for content/page/post/inherit, and only fall back to edit_field.
            $isNative = in_array($field, ['content', 'content_body', 'description', 'title'], true);
            if ($isNative
                && $cid
                && in_array($rel, ['content', 'page', 'post', 'inherit'], true)) {
                $row = app()->content_manager->get_by_id((int) $cid);
                if (is_array($row) && isset($row[$field]) && is_string($row[$field]) && $row[$field] !== '') {
                    return $row[$field];
                }
                // fall through to edit_field for content stored that way instead
            }

            $query = 'rel_type=' . rawurlencode((string) $rel)
                . '&field=' . rawurlencode((string) $field);
            if ($cid) {
                $query .= '&rel_id=' . (int) $cid;
            }
            $value = app()->content_manager->edit_field($query);
            return is_string($value) ? $value : null;
        };

        // Match the legacy flow: tell the Blade MicroweberModuleTagCompiler to
        // leave <module> tags raw so load()'s rendered output contains real
        // <module> markup for LayoutProcessor to tokenize/render itself (instead
        // of the compiler rendering them its own way → empty/duplicated output).
        // rel="inherit" fields resolve to the inherited (master) content — e.g.
        // a post/product inheriting a region from its blog/shop master page.
        $inheritedParentResolver = function ($id) {
            $parent = app()->content_manager->get_inherited_parent((int) $id);
            return ($parent && (int) $parent > 0) ? (int) $parent : null;
        };

        MicroweberModuleTagCompiler::disableModuleProcessing();
        try {
            $result = $this->layoutProcessor->process(
                $layout, $contentId, $moduleLoader, $editFieldLoader, $inheritedParentResolver
            );

            // Nested load() calls run the LEGACY flow (re-entrancy guard), which
            // shields <style>/<textarea>/comments/urls behind its own
            // placeholders and normally unwinds them at the end of its own
            // process(). Those restores don't run on this short-circuit path, so
            // replay them here or the placeholders leak into the page.
            global $mw_replaced_textarea_tag;
            if (is_string($result)) {
                if (!empty($mw_replaced_textarea_tag)) {
                    foreach ($mw_replaced_textarea_tag as $k => $v) {
                        if ($v !== '') {
                            $result = str_replace($k, $v, $result);
                        }
                    }
                }
                $result = $this->_replace_tags_with_placeholders_back($result);
                if (method_exists($this, 'replace_url_placeholders')) {
                    $result = $this->replace_url_placeholders($result);
                }
            }

            return $result;
        } finally {
            MicroweberModuleTagCompiler::enableModuleProcessing();
            $this->_layoutProcessorActive = false;
        }
    }


    private function _do_we_have_more_for_parse($mod_content)
    {
        // cycle-N: PHP 8.4 deprecates passing null to preg_match_all's
        // $subject. Coerce at function entry — null/false content
        // simply means "no more to parse" (returns 0 matches).
        if ($mod_content === null) {
            $mod_content = '';
        }
        $mod_content = (string) $mod_content;

        $proceed_with_parse = false;

        if ($this->_do_we_have_more_edit_fields_for_parse($mod_content)) {
            $proceed_with_parse = true;
        } else {
            // Use TagLexer instead of brittle regex for module tag detection
            if ($this->tagLexer->hasModuleTags($mod_content)) {
                $proceed_with_parse = true;
            } else {
                // Also check for unprocessed module placeholders
                if (strpos($mod_content, '<mw-unprocessed-module-tag') !== false) {
                    $proceed_with_parse = true;
                }
            }
        }
        return $proceed_with_parse;
    }


}
