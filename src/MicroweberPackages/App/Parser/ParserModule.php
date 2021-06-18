<?php
namespace MicroweberPackages\App\Parser;

class ParserModule {

    use ParserHelperTrait;

    public function recursive_parse_modules($layout)
    {
        // Check first block to parse
        $module_tags = $this->_preg_match_module_tags($layout);
        if (!$module_tags) {
            return false;
        }

        // Execute modules
        foreach($module_tags as $module_tag) {
            $module_attributes = $this->_extract_tag_attributes($module_tag);
            $render_module = $this->execute_module($module_tag, $module_attributes);
            $layout = $this->_str_replace_first($module_tag, $render_module, $layout);
        }

        // Check next block to parse
        $module_tags = $this->_preg_match_module_tags($layout);
        if ($module_tags) {
            $layout = $this->recursive_parse_modules($layout);
        }

        return $layout;
    }

    public function execute_module($module_tag, $module_attributes)
    {
        if (!isset($module_attributes['type'])) {
            // If you want to run this module, you must set the attribute type
            return false;
        }

        // Find the index of the module file
        $module_index_file = modules_path() . $module_attributes['type'] . DS . 'index.php';
        if (!is_file($module_index_file)) {
            return false;
        }

        // Execute module in ob start
        ob_start();

        $config = [];
        $config['module'] = $module_attributes['type'];

        $params = $module_attributes;
        $params['id'] = uniqid();
        $params['module'] = $module_attributes['type'];

        if (isset($module_attributes['id'])) {
            $params['id'] = $module_attributes['id'];
        }

        include($module_index_file);

        $module_rendered = ob_get_clean();
        // Module is rendered

        // Append executed module in html
        $attributes = '';
        foreach($module_attributes as $attribute_key=>$attribute_value) {
            $attributes .= " ".$attribute_key . '="'.$attribute_value.'"';
        }

        $html_output = '<div'.$attributes.' mw_module="true">';
        $html_output .= trim($module_rendered);
        $html_output .= '</div>';

        return $html_output;
    }
}
