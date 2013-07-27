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

		// $ext = strtolower(get_file_extension($this -> v));

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
        extract((array)$this);

        ob_start();


		//	set_include_path(dirname($this -> v) . DS . PATH_SEPARATOR . get_include_path());
		//$old_dir = getcwd();
		$file_dir = dirname($this -> v) . DS;

		$ext = strtolower(get_file_extension($this -> v));

		//	set_include_path($file_dir . PATH_SEPARATOR . get_include_path());
		//chdir($file_dir);

		if ($ext == 'md') {
			$content = file_get_contents($this -> v);
			$rel_url = dir2url(dirname($this -> v)) . '/';
			//$content = \mw\content\Markdown::defaultTransform($content);
			$parser = new \mw\content\MarkdownExtra;

			$parser -> no_markup = true;
			 $parser -> no_entities = true;

			$content = $parser -> transform($content);

			$content = preg_replace("#(<\s*a\s+[^>]*href\s*=\s*[\"'])(?!http)([^\"'>]+)([\"'>]+)#", '$1' . $rel_url . '$2$3', $content);
			//$content = preg_replace("#(<\s*a\s+[^>]*src\s*=\s*[\"'])(?!src)([^\"'>]+)([\"'>]+)#", '$1'.$rel_url.'$2$3', $content);

			$dom_md = new DOMDocument;
			$dom_md -> loadHTML($content);

			$imgs = $dom_md -> getElementsByTagName('img');
			foreach ($imgs as $img) {
				$src = $img -> getAttribute('src');
				if (strpos($src, site_url()) !== 0) {
					$img -> setAttribute('src', $rel_url . $src);
				}
			}

			$content = $dom_md -> saveHTML();

			unset($dom_md);
			unset($parser);

		} else {
			require ($this -> v);
			$content = ob_get_clean();
		}

		//ob_end_clean();
		//chdir($old_dir);
		return $content;
	}

}
