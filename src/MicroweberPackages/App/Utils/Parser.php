<?php

namespace MicroweberPackages\App\Utils;

use MicroweberPackages\View\View;

$parser_cache_object = false; //global cache storage
$mw_replaced_edit_fields_vals = array();
$mw_replaced_edit_fields_vals_inner = array();
$mw_replaced_codes_tag = array();

$mw_replaced_textarea_tag = array();
$local_mw_replaced_modules_ids_grouped = array();
$local_mw_replaced_modules = array();

$mw_parser_nest_counter_level = 0;
$mw_parser_replace_inc = 0;
$mod_tag_replace_inc = 0;
$other_html_tag_replace_inc = 0;

class Parser
{
    public $page = array();
    public $params = array();

    private $mw_replaced_modules_tags = array();
    private $mw_replaced_modules_values = array();

    private $_mw_parser_passed_hashes = array();
    private $_mw_parser_passed_hashes_rel = array();
    private $_mw_parser_passed_replaces = array();

    private $_mw_parser_passed_replaces_inner = array();
    private $_mw_parser_replaced_tags = array();
    private $_mw_parser_replaced_html_comments = array();
    private $_replaced_modules_values = array();
    private $_replaced_modules = array();
    private $_replaced_codes = array();
    private $_replaced_input_tags = array();
    private $_replaced_input_tags_inner_loops = array();
    private $_existing_module_ids = array();
    private $_existing_module_ids_grouped = array();
    private $_existing_module_ids_map = array();
    private $_current_parser_rel = false;
    private $_current_parser_field = false;
    private $_current_parser_module_of_type = array();
    private $have_more = false;
    private $have_more_is_set = false;
    private $prev_module_data = array();
    private $iter_parent = array();
    private $_mw_edit_field_map = array();
    private $_additional_parsers = array();
    public $current_module_params = false;
    public $current_module = false;
    public $processor = false;


    public $debugbarEnabled = false;

    public function __construct()
    {
        $this->debugbarEnabled = false;
        if (class_exists('\Debugbar', false)) {
            $this->debugbarEnabled = \Debugbar::isEnabled();
        }

        require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';

        $this->processor = new ParserProcessor();

        $this->processor->debugbarEnabled = $this->debugbarEnabled;



    }

    public function register($callback, $type='module'){

        if(!isset($this->_additional_parsers[$type])){
            $this->_additional_parsers[$type] = array();
        }
        $this->_additional_parsers[$type][] = $callback;
    }


    public function process($layout, $options = [])
    {


        return $this->processor->process($layout, $options);;

    }

    public function load($module_name, $attrs = array())
    {
        return $this->processor->load($module_name, $attrs);
    }


    public $filter = array();

    public function filter($callback)
    {
        $this->filter[] = $callback;
    }


    public function replace_url_placeholders($layout)
    {
        if (defined('TEMPLATE_URL')) {
            $replaces = array(
                '{TEMPLATE_URL}',
                '{THIS_TEMPLATE_URL}',
                '{DEFAULT_TEMPLATE_URL}',
                '%7BTEMPLATE_URL%7D',
                '%7BTHIS_TEMPLATE_URL%7D',
                '%7BDEFAULT_TEMPLATE_URL%7D',
            );


            $replaces_vals = array(
                TEMPLATE_URL,
                THIS_TEMPLATE_URL,
                DEFAULT_TEMPLATE_URL,
                TEMPLATE_URL,
                THIS_TEMPLATE_URL,
                DEFAULT_TEMPLATE_URL
            );

            //        $layout = str_replace($replaces, $replaces_vals, $layout);
            $layout = str_replace_bulk($replaces, $replaces_vals, $layout);
        }
        return $layout;
    }

    public function make_tags($layout, $options = array())
    {

        if ($layout == '') {
            return $layout;
        }
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';

        $pq = \phpQuery::newDocument($layout);


        $remove_clases = ['changed', 'inaccessibleModule', 'module-over', 'currentDragMouseOver', 'mw-webkit-drag-hover-binded'];

        foreach ($pq ['.edit.changed'] as $elem) {
            $attrs = $elem->attributes;
            $tag = $elem->tagName;



            $module_html = '<' . $tag . ' ';
            if (!empty($attrs)) {
                foreach ($attrs as $attribute_name => $attribute_node) {
                    $v = $attribute_node->nodeValue;
                    if ($attribute_name == 'class') {
                        foreach ($remove_clases as $remove_class) {
                            $v = str_replace(' ' . $remove_class, '', $v);
                        }
                    }
                    $module_html .= " {$attribute_name}='{$v}'  ";
                }
            }
            $module_html .= '><inner-edit-tag>mw_saved_inner_edit_from_parent_edit_field</inner-edit-tag><' . $tag . '/>';
            pq($elem)->replaceWith($module_html);

        }

        $layout = $pq->htmlOuter();

        $pq = \phpQuery::newDocument($layout);

        foreach ($pq ['.module'] as $elem) {
            $name = pq($elem)->attr('module');
            $attrs = $elem->attributes;
            $module_html = '<module ';
            $attrs2 = [];
            if (!empty($attrs)) {
                foreach ($attrs as $attribute_name => $attribute_node) {
                    $attrs2[$attribute_name] = $attribute_node->nodeValue;
                }
            }
            if (!empty($attrs2)) {
                $attrs2 = array_unique($attrs2);
                foreach ($attrs2 as $attribute_name => $attribute_node) {
                    //$v = $attribute_node->nodeValue;
                    $v = $attribute_node;
                    if ($attribute_name == 'class') {
                        foreach ($remove_clases as $remove_class) {
                            $v = str_replace(' ' . $remove_class, '', $v);
                        }
                    }


                    $module_html .= " {$attribute_name}='{$v}'  ";
                }
            }
            $module_html .= ' />';


            $has_type_attribute = false;
            if(isset( $attrs2['type'] ) or isset( $attrs2['data-type'] ) or isset( $attrs2['module'] )){
                $has_type_attribute = true;

            }
             if(!$has_type_attribute){
                 $module_html = '';
             }
            pq($elem)->replaceWith($module_html);
        }
        $layout = $pq->htmlOuter();
        $layout = str_replace("\u00a0", ' ', $layout);
        $layout = str_replace('<?', '&lt;?', $layout);
        $layout = str_replace('?>', '?&gt;', $layout);


        if (isset($options['change_module_ids']) and $options['change_module_ids']) {
            $script_pattern = '/<module[^>]*>/Uis';
            preg_match_all($script_pattern, $layout, $mw_script_matches);
            if (!empty($mw_script_matches)) {
                $matches1 = $mw_script_matches[0];

                foreach ($matches1 as $key => $value) {
                    if ($value != '') {
                        $attrs = $this->_extract_module_tag_attrs($value);
                        $suffix = date("Ymdhis");
                        if (isset($attrs['parent-module-id'])) {
                            $attrs['parent-module-id'] = $attrs['parent-module-id'] . $suffix;
                        }
                        if (isset($attrs['id'])) {
                            $attrs['id'] = $attrs['id'] . $suffix;
                        }

                        if ($attrs) {
                            $attrs = array_unique($attrs);
                            $module_tags = '<module ';
                            foreach ($attrs as $nn => $nv) {
                                $module_tags .= " {$nn}='{$nv}' ";
                            }
                            $module_tags .= "/>";
                            $layout = $this->_str_replace_first($value, $module_tags, $layout);

                        }

                    }
                }
            }
        }

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

    public function get_by_id($html_element_id = false, $layout = false)
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
            foreach ($pq ['[field=content]:last'] as $elem) {
                $l = pq($elem)->htmlOuter();

                $found = true;
            }
        }

        if ($found == false) {
            foreach ($pq ['[field=content_body]:last'] as $elem) {
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
    public $module_registry = array();
    public $module_load_registry = array();

    public function loadOLLDD($module_name, $attrs = array())
    {




        $mod_id_value = 'load'.crc32($module_name . json_encode($attrs));
        $that = $this;
        if (isset($that->module_load_registry[$mod_id_value])) {
            return $that->module_load_registry[$mod_id_value];
        }

        if ($this->debugbarEnabled) {
            \Debugbar::startMeasure('render_module_'.$module_name, 'Rendering '.$module_name);
        }



        $that->module_load_registry[$mod_id_value] = $that->load_module_callback($module_name, $attrs);

        if ($this->debugbarEnabled) {
            \Debugbar::stopMeasure('render_module_'.$module_name,$attrs);
        }


        return $that->module_load_registry[$mod_id_value];


    }


    public function replace_non_cached_modules_with_placeholders($layout)
    {
        //   $non_cached
        $non_cached = app()->module_manager->get('allow_caching=0&ui=any');
        $has_changes = false;


        if (!$non_cached or $layout == '') {
            return $layout;
        }
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';

        $pq = \phpQuery::newDocument($layout);


        $remove_clases = ['changed', 'inaccessibleModule', 'module-over', 'currentDragMouseOver', 'mw-webkit-drag-hover-binded'];
        $found_mods = array();
        $found_mods_non_cached = array();
      //  foreach ($pq ['.module'] as $elem) {
        foreach ($pq->find('.module')as $elem) {
            $attrs = $elem->attributes;
            $tag = $elem->tagName;


            $module_html = '<' . $tag . ' ';
            if (!empty($attrs)) {
                $mod_name = false;
                $mod_name_is_cached = true;
                foreach ($attrs as $attribute_name => $attribute_node) {
                    $v = $attribute_node->nodeValue;

                    if ($attribute_name == 'type'
                        or $attribute_name == 'data-type'
                        or $attribute_name == 'type'
                    ) {
                        $mod_name = $v;
                        $found_mods[] = $mod_name;
                    }
                }
                foreach ($non_cached as $mod) {
                    if (isset($mod['module'])
                        and $mod_name
                        and $mod_name == $mod['module']
                    ) {
                        $has_changes = true;
                        $mod_name_is_cached = false;

                        $found_mods_non_cached[] = $mod_name;
                    }
                }

                if (!$mod_name_is_cached and $mod_name and $has_changes) {


                    foreach ($attrs as $attribute_name => $attribute_node) {

                        $v = $attribute_node->nodeValue;


                        if ($attribute_name == 'class') {
                            $v = str_replace('module ', 'mw-lazy-load-module module ', $v);
                        }


                        $module_html .= " {$attribute_name}='{$v}'  ";
                        $has_changes = true;


                    }

                    if ($has_changes) {
                        $module_html .= '><!-- Loading module ' . $mod_name . ' --><' . $tag . '/>';


                        $elem = pq($elem);

                        $elem->replaceWith($module_html);


                    }


                }

            }


        }


        if ($has_changes) {
            $layout = $pq->htmlOuter();
        }
        return $layout;

    }

    private function _process_additional_module_parsers($layout, $module, $params)
    {
        $type = 'module';
        if(isset($this->_additional_parsers[$type]) and $this->_additional_parsers[$type]){
            $parsers_callbacks = $this->_additional_parsers[$type];
            foreach($parsers_callbacks as $parser_callback){
                if (is_callable($parser_callback)) {
                    $res = call_user_func($parser_callback, $layout,$module, $params);
                    if($res){
                        $layout = $res;
                    }
                }
            }
        }
        return $layout;
    }

    public function optimize_asset_loading_order($layout)
    {

        return $layout;
        $replaced = array();
        $pq = \phpQuery::newDocument($layout);
        $srcs = array();
        $srcs_css = array();

        foreach ($pq ['script'] as $elem) {
            $src = pq($elem)->attr('src');
            // <script type="text/javascript/defer">

            if ($src and !strstr($src, 'apijs')) {

                //  pq($elem)->attr('type', 'text/javascript/defer');
                //pq($elem)->attr('type', 'text/delayscript');
                $srcs[] = $src;
                pq($elem)->replaceWith('');
            }
            if ($src) {
//                $replaced[] = pq($elem)->htmlOuter();
//                pq($elem)->replaceWith('');
            } else {

                //     pq($elem)->attr('defer', 'defer');

//                 $base = pq($elem)->html();
//                $base = base64_encode($base);
//                pq($elem)->attr('src', 'data:text/javascript;base64,'.$base);
//                pq($elem)->html('');
            }

        }
//        foreach ($pq ['link'] as $elem) {
//            $src = pq($elem)->attr('href');
//            if($src){
//                $srcs_css[] = $src;
//                pq($elem)->replaceWith('');
//            }
//        }


        $layout = $pq->htmlOuter();
        $load_deffered = "<script>
   $( document ).ready(function() {


	$('script[type=\"text/javascript/defer\"]').each(function(){
		$(this).clone().attr('type', 'application/javascript').insertAfter(this);
		$(this).remove();
	});
});
</script>";

        $load_deffered = "<script>
   $( document ).ready(function() {

	var scripts = document.getElementsByTagName(\"script\")

    for (var i = 0; i < scripts.length; i++) {
        var type = scripts[i].getAttribute(\"type\");
        if (type && type.toLowerCase() == 'text/delayscript') {
            scripts[i].parentNode.replaceChild((function (delayscript) {
                var script = document.createElement('script');
                script.type = 'text/javascript';
                script.innerHTML = delayscript.innerHTML;

                return script;
            })(scripts[i]), scripts[i]);
        }
    }
});
</script>";


        if ($srcs) {
            $srsc_str = '';
            foreach ($srcs as $src) {
                $srsc_str .= 'mw.require("' . $src . '")' . "\n";
            }
            $srsc_str = "<script>
$srsc_str
            </script>";
            $layout = str_ireplace('</body>', $srsc_str . '</body>', $layout, $c);

        }

//        if($srcs_css){
//            $srsc_str = '';
//            foreach ($srcs_css as $src){
//                $srsc_str .= 'mw.moduleCSS("'.$src.'")'."\n";
//            }
//            $srsc_str = "<script>
//$srsc_str
//            </script>";
//            $layout = str_ireplace('</body>', $srsc_str . '</body>', $layout, $c);
//
//        }

        $layout = str_ireplace('</head>', $load_deffered . '</head>', $layout, $c);

        // $replaced[] = $load_deffered;

        if ($replaced) {
            //$replaced = array_unique($replaced);


            $replaced_str = implode("\n", $replaced);
            $c = 1;
            // $layout = str_ireplace('</head>', $replaced_str . '</head>', $layout, $c);


            $layout = str_ireplace('</body>', $replaced_str . '</body>', $layout, $c);
            // $layout = str_ireplace('</body>', $load_deffered . '</body>', $layout, $c);

        }

        return $layout;

        $replaced = array();
        $pq = \phpQuery::newDocument($layout);
//        foreach ($pq ['script'] as $elem) {
//               $src = pq($elem)->attr('src');
//
//            $replaced[] = pq($elem)->htmlOuter();
//            pq($elem)->replaceWith('');
//
//
//        }
        foreach ($pq ['script'] as $elem) {
            $src = pq($elem)->attr('src');
            if ($src) {
                $replaced[] = pq($elem)->htmlOuter();
                pq($elem)->replaceWith('');
            } else {

                pq($elem)->attr('defer', 'defer');

                $base = pq($elem)->html();
                $base = base64_encode($base);
                pq($elem)->attr('src', 'data:text/javascript;base64,' . $base);
                pq($elem)->html('');
            }

        }


        $layout = $pq->htmlOuter();

        if ($replaced) {
            //$replaced = array_unique($replaced);
            $replaced_str = implode("\n", $replaced);
            $c = 1;
            // $layout = str_ireplace('</head>', $replaced_str . '</head>', $layout, $c);
            $layout = str_ireplace('</body>', $replaced_str . '</body>', $layout, $c);

        }
        return $layout;
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
        if ($search == false || $replace == false) {
            return $subject;
        }
        if (!is_string($search)) {
            return $subject;
        }

        $pos = strpos($subject, (string)$search);
        if ($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
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

    private function _extract_module_tag_attrs($module_tag)
    {
        $value = $module_tag;
        $attrs = array();
        $attribute_pattern = '@(?P<name>[a-z-_A-Z]+)\s*=\s*((?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)|(?P<value_unquoted>[^\s"\']+?)(?:\s+|$))@xsi';
        $mw_attrs_key_value_seperator = "__MW_PARSER_ATTR_VAL__";
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

        if ($attrs) {
            return $attrs;
        }
    }

}
