<?php
namespace MicroweberPackages\App\Parser;

use Illuminate\Support\Facades\Cache;
use MicroweberPackages\App\Parser\Traits\ParserHelperTrait;

class ParserModule {

    use ParserHelperTrait;

    public function recursive_parse_modules($layout, $parent = false)
    {
        // Check first block to parse
        $module_tags = $this->_preg_match_module_tags($layout);
        if (!$module_tags) {
            return false;
        }

        // Execute modules
        foreach($module_tags as $module_tag) {

            // Get html tags as array
            $module_attributes = $this->_extract_tag_attributes($module_tag);

            // If this module is called from another we append parent module tag
            if ($parent) {
                $module_attributes['parent_module'] = $parent['parent_module'];
            }

            // Try to parse module tag block
            $render_module = $this->parse_module($module_tag, $module_attributes);

            // if the output of module has a new module tags we must to recursive parse again
            $render_module_tags = $this->_preg_match_module_tags($render_module);
            if ($render_module_tags) {
                // Set the parent module tag for the new module taggs
                $render_module = $this->recursive_parse_modules($render_module, [
                    'parent_module'=>$module_attributes['type']
                ]);
            }
            
            // Replace the first module tag block with the rendered module output
            $layout = $this->_str_replace_first($module_tag, $render_module, $layout);
        }

        return $layout;
    }

    public function parse_module($module_tag, $module_attributes)
    {
        $cache_id = crc32(serialize($module_attributes));

        $cache_get = Cache::tags([$module_attributes['type']])->get($cache_id);
        if ($cache_get) {
            return $cache_get;
        }

        if (!isset($module_attributes['type'])) {
            // If you want to run this module, you must set the attribute type
            return $this->_execute_module_fail($module_attributes);
        }

        // Find the index of the module file
        $module_index_file_found = false;
        $module_index_file = modules_path() . $module_attributes['type'] . DS . 'index.php';
        if (is_file($module_index_file)) {
            $module_index_file_found = $module_index_file;
        }

        // Find the index of the module in current template
        $template_module_index_file = template_dir() . DS .  'userfiles' .  DS . 'modules' . DS. $module_attributes['type'] . DS . 'index.php';
        if (is_file($template_module_index_file)) {
            $module_index_file_found = $template_module_index_file;
        }
        if (!$module_index_file_found) {
            return $this->_execute_module_fail($module_attributes);
        }

        $html_output = $this->execute_module($module_index_file_found, $module_attributes);

        Cache::tags([$module_attributes['type']])->put($cache_id, $html_output);

        return $html_output;
    }

    public function execute_module($indexFile, $module_attributes)
    {
        // Execute module in ob start
        ob_start();

        $config = [];
        $config['module'] = $module_attributes['type'];
        $config['module_class'] = uniqid();
        $config['module_api'] = app()->url_manager->site('api/' . $module_attributes['type']);
        $config['module_view'] = app()->url_manager->site('module/' . $module_attributes['type']);

        $params = $module_attributes;
        $params['id'] = uniqid();
        $params['module'] = $module_attributes['type'];

        if (isset($module_attributes['id'])) {
            $params['id'] = $module_attributes['id'];
        }

        $this->app = app();

        include($indexFile);

        // Module is rendered
        $module_rendered = ob_get_clean();

        // Append executed module in html
        $html_output = '<div'.$this->_arrayToHtmlAttributes($module_attributes).' mw_module="true">';
        $html_output .= trim($module_rendered);
        $html_output .= '</div>';

        return $html_output;
    }

    private function _execute_module_fail($attributes) {

        $html_output = '<div'.$this->_arrayToHtmlAttributes($attributes).' mw_module="true" mw_module_executed="false">';
        $html_output .= '<!-- The module cant be executed. -->';
        $html_output .= '</div>';

        return $html_output;
    }

    private function _arrayToHtmlAttributes($array)
    {
        $attributes = '';
        foreach($array as $attribute_key=>$attribute_value) {
            $attributes .= " ".$attribute_key . '="'.$attribute_value.'"';
        }
        return $attributes;
    }
}
