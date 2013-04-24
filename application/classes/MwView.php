<?php

// Handles working with HTML output templates
class MwView {

	var $v;

	function __construct($v) {
		$this -> v = $v;
	}

	function set($a) {
		foreach ($a as $k => $v)
			$this -> $k = $v;
	}

	function assign($var, $val) {
 			$this -> $var = $val;
	}

	function __get_vars() {

		ob_start();
		// write content
		extract((array)$this);

		//$old_dir = getcwd();
		$file_dir = dirname($this -> v) . DS;
		//	set_include_path($file_dir . PATH_SEPARATOR . get_include_path());
		//chdir($file_dir);
		require ($this -> v);
		$content = ob_get_clean();
		unset($content);
		//ob_end_clean();

		$defined_vars = array();
		$var_names = array_keys(get_defined_vars());

		foreach ($var_names as $var_name) {
			if ($var_name != 'defined_vars' and $var_name != 'this') {
				$defined_vars[$var_name] = $$var_name;
			}
		}
		//chdir($old_dir);
		return $defined_vars;
	}

	function __toString() {
		ob_start();
		// write content
		extract((array)$this);

		foreach ($this as $k => $v) {
			if (is_array($v)) {
				//d($v);
				//extract ( ( array ) $v );
			}
		}
		//	set_include_path(dirname($this -> v) . DS . PATH_SEPARATOR . get_include_path());
		//$old_dir = getcwd();
		$file_dir = dirname($this -> v) . DS;
		//	set_include_path($file_dir . PATH_SEPARATOR . get_include_path());
		//chdir($file_dir);
		require ($this -> v);
		$content = ob_get_clean();

		//ob_end_clean();
		//chdir($old_dir);
		return $content;
	}

}
