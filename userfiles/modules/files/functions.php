<?

/**
 *
 * Files module api
 *
 * @package		modules
 * @subpackage		files
 * @since		Version 0.567
 */

// ------------------------------------------------------------------------

/**
 * get_files
 *
 *  Get an array that represents directory and files
 *
 * @package		modules
 * @subpackage	files
 * @subpackage	files\api
 * @category	files module api
 * @version 1.0
 * @since 0.320
 * @return mixed Array with files
 *
 * @param array $params = array()     the params
 * @param string $params['directory']       The directory
 * @param string $params['keyword']       If set it will seach the dir and subdirs
 */
function get_files($params) {
	if (is_admin() == false) {
		error("Must be admin");
	}

	$params = parse_params($params);
	if (!isset($params['directory'])) {
		error("You must define directory");
	} else {
		$directory = $params['directory'];
	}
	$arrayItems = array();
	if (isset($params['search']) and strval($params['search']) != '') {
		$arrayItems_search = rglob($pattern = DS . '*' . $params['search'] . '*', $flags = 0, $directory);

	} else {

		$paths = glob($directory . DS . '*', GLOB_ONLYDIR | GLOB_NOSORT);
		$files = glob($directory . DS . '*', 0);
		$arrayItems_search = array_merge($paths, $files);

	}

	if (!empty($arrayItems_search)) {
		if (isset($params['sort_by']) and strval($params['sort_by']) != '') {
			if (isset($params['sort_order']) and strval($params['sort_order']) != '') {

				$ord = SORT_DESC;
				if (strtolower($params['sort_order']) == 'asc') {
					$ord = SORT_ASC;
				}

				array_multisort(array_map($params['sort_by'], $arrayItems_search), SORT_NUMERIC, $ord, $arrayItems_search);
				//	d($arrayItems_search);
			}
		}
		//usort($myarray, create_function('$a,$b', 'return filemtime($a) - filemtime($b);'));

		$arrayItems_f = array();
		$arrayItems_d = array();
		foreach ($arrayItems_search as $file) {
			if (is_file($file)) {
				$df  = normalize_path($file, false);
				if(!in_array($df,$arrayItems_f)){
				$arrayItems_f[] = $df;
				}
			} else {
				$df  = normalize_path($file, 1);
				if(!in_array($df,$arrayItems_d)){
				$arrayItems_d[] = $df; 
				}
			}
		}
		$arrayItems['files'] = $arrayItems_f;
		$arrayItems['dirs'] = $arrayItems_d;
	}

	return $arrayItems;
	$arrayItems = array();
	$skipByExclude = false;
	$directory = rtrim($directory, DIRECTORY_SEPARATOR);
	$handle = opendir($directory);
	if ($handle) {
		while (false !== ($file = readdir($handle))) {
			preg_match("/(^(([\.]){1,2})$|(\.(svn|git|md))|(Thumbs\.db|\.DS_STORE))$/iu", $file, $skip);
			if ($exclude) {
				preg_match($exclude, $file, $skipByExclude);
			}
			if (!$skip && !$skipByExclude) {
				if (is_dir($directory . DIRECTORY_SEPARATOR . $file)) {
					if ($listDirs) {
						$file = $directory . DIRECTORY_SEPARATOR . $file;
						$arrayItems['dirs'][] = $file;
					}
					if ($recursive) {
						$arrayItems = array_merge($arrayItems, dir_to_array($directory . DIRECTORY_SEPARATOR . $file, $recursive, $listDirs, $listFiles, $exclude));
					}

				} else {
					if ($listFiles) {
						$file = $directory . DIRECTORY_SEPARATOR . $file;
						$arrayItems['files'][] = $file;
					}
				}
			}
		}
		closedir($handle);
	}
	array_unique($arrayItems);
	return $arrayItems;
}




action_hook('rte_image_editor_image_search', 'mw_print_rte_image_editor_image_search');

function mw_print_rte_image_editor_image_search() {
  $active = url_param('view');
  $cls = '';
  if($active == 'shop'){
	   $cls = ' class="active" ';
  }
	print '<module type="files/admin" />';
}
