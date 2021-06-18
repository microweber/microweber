<?php
namespace MicroweberPackages\App\Utils;


use Illuminate\View\View;

class Parser
{
    public function process($layout, $options = false, $coming_from_parent = false, $coming_from_parent_id = false, $previous_attrs = false)
    {
        echo $this->recursive_parse_modules($layout);
    }

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

    public function _preg_match_module_tags($layout) {

        preg_match_all('/<module[^>]*>/Uis', $layout, $module_tags);

        if (empty($module_tags[0])) {
            return false;
        }

        return $module_tags[0];
    }

    public function execute_module($module_tag, $module_attributes)
    {
        if (!isset($module_attributes['type'])) {
            // If you want to run this module, you must set the attribute type
            return false;
        }

        $module_index_file = modules_path() . $module_attributes['type'] . DS . 'index.php';
        if (!is_file($module_index_file)) {
            return false;
        }

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

        $attributes = '';
        foreach($module_attributes as $attribute_key=>$attribute_value) {
            $attributes .= " ".$attribute_key . '="'.$attribute_value.'"';
        }

        $html_output = '<div'.$attributes.' mw_module="true">';
        $html_output .= trim($module_rendered);
        $html_output .= '</div>';

        return $html_output;
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

    private function _extract_tag_attributes($value)
    {
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

            $layout = str_replace_bulk($replaces, $replaces_vals, $layout);
        }

        return $layout;
    }
}
