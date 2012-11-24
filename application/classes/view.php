<?php

// Handles working with HTML output templates
class View {

    var $v;

    function __construct($v) {
        // d($v);
        $this->v = $v;
        // $this->v = load_file ( "views/$v" );
    }

    function set($a) {
        foreach ($a as $k => $v)
            $this->$k = $v;
    }

    function __get_vars() {

        ob_start();
        // write content
        extract((array) $this);

      

        require ($this->v);
        $content = ob_get_contents();
        ob_end_clean();

        $defined_vars = array();
        $var_names = array_keys(get_defined_vars());

        foreach ($var_names as $var_name) {
        	if($var_name != 'defined_vars' and $var_name != 'this'){
            $defined_vars[$var_name] = $$var_name;
			}
        }

        return $defined_vars;
    }

    function __toString() {
        ob_start();
        // write content
        extract((array) $this);

        foreach ($this as $k => $v) {
            if (is_array($v)) {
                //d($v);
                //extract ( ( array ) $v );
            }
        }

        require ($this->v);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

}
