<?php
namespace Microweber;
// Handles working with PHP output templates
class View
{

    var $v;

    function __construct($v)
    {
        $this->v = $v;
    }

    function set($a)
    {
        foreach ($a as $k => $v)
            $this->$k = $v;
    }

    function assign($var, $val)
    {
        $this->$var = $val;
    }

    function __get_vars()
    {

        ob_start();
        extract((array)$this);


        $file_dir = dirname($this->v) . DS;


        require($this->v);

        $content = ob_get_clean();
        unset($content);

        $defined_vars = array();
        $var_names = array_keys(get_defined_vars());

        foreach ($var_names as $var_name) {
            if ($var_name != 'defined_vars' and $var_name != 'this') {
                $defined_vars[$var_name] = $$var_name;
            }
        }
        return $defined_vars;
    }

    function display()
    {
        print $this->__toString();
    }

    function __toString()
    {
        extract((array)$this);

        ob_start();


        if (is_array($this->v)) {
            foreach ($this->v as $item) {
                if (is_file($item)) {
                    include($item);
                }
            }
        } elseif (is_string($this->v)) {
            if (is_file($this->v)) {
                include($this->v);
            }
        }


        $content = ob_get_clean();

        return $content;
    }

}
