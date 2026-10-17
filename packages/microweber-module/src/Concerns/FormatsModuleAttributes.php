<?php

namespace MicroweberPackages\Module\Concerns;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait FormatsModuleAttributes
{
    public function format_attr($attr_value)
    {
        $attr_value = str_replace('"', '&quot;', $attr_value);
        $attr_value = str_replace("'", '&#39;', $attr_value);
        $attr_value = str_replace('<', '&lt;', $attr_value);
        $attr_value = str_replace('>', '&gt;', $attr_value);
        $attr_value = str_replace('&', '&amp;', $attr_value);
        $attr_value = str_replace(']', '&#93;', $attr_value);
        $attr_value = str_replace('[', '&#91;', $attr_value);
        $attr_value = str_replace('{', '&#123;', $attr_value);
        $attr_value = str_replace('}', '&#125;', $attr_value);
        $attr_value = str_replace('`', '&#96;', $attr_value);
        $attr_value = str_replace(';', '&#59;', $attr_value);
        return $attr_value;
    }

    public function css_class($module_name)
    {
        global $mw_defined_module_classes;

        if (isset($mw_defined_module_classes[$module_name]) != false) {
            return $mw_defined_module_classes[$module_name];
        } else {
            $module_class = str_replace('/', '-', $module_name);
            $module_class = str_replace('\\', '-', $module_class);
            $module_class = str_replace(' ', '-', $module_class);
            $module_class = str_replace('%20', '-', $module_class);
            $module_class = str_replace('_', '-', $module_class);
            $module_class = 'module-' . $module_class;

            $mw_defined_module_classes[$module_name] = $module_class;

            return $module_class;
        }
    }
}
