<?

namespace mw;
 
class update {

	private $remote_api_url = 'http://serv.microweber.net/service/update/';

	function get_modules() {

	}

	function check() {
		$a = is_admin();
		if ($a == false) {
			error('Must be admin!');
		}

		$data = array();
		$data['mw_version'] = MW_VERSION;

		$t = templates_list();
		$data['templates'] = $t;

		$t = modules_list();
		$data['modules'] = $t;

		$t = get_elements();
		$data['elements'] = $t;
		$result = $this -> call('check_for_update', $data);
		return $result;
	}

	function apply_updates($updates) {
		$to_be_unzipped = array();
		$a = is_admin();
		if ($a == false) {
			error('Must be admin!');
		}

		d($updates);
		print 1;
		return $updates;
		$down_dir = CACHEDIR_ROOT . 'downloads' . DS;
		if (!is_dir($down_dir)) {
			mkdir_recursive($down_dir);
		}
		if (isset($updates['mw_new_version_download'])) {
			$loc_fn = url_title($updates['mw_new_version_download']) . '.zip';
			$loc_fn_d = $down_dir . $loc_fn;
			if (!is_file($loc_fn_d)) {
				$loc_fn_d1 = url_download($updates['mw_new_version_download'], false, $loc_fn_d);
			}
			if (is_file($loc_fn_d)) {
				$to_be_unzipped['root'][] = $loc_fn_d;
			}
			// d($loc_fn_d);
		}

		$what_next = array('modules', 'elements');

		foreach ($what_next as $what_nex) {
			$down_dir2 = $down_dir . $what_nex . DS;
			if (!is_dir($down_dir2)) {
				mkdir_recursive($down_dir2);
			}
			// d($updates);
			// d($what_nex);
			if (isset($updates[$what_nex])) {

				foreach ($updates[$what_nex] as $key => $value) {

					$loc_fn = url_title($value) . '.zip';
					$loc_fn_d = $down_dir2 . $loc_fn;

					if (!is_file($loc_fn_d)) {
						$loc_fn_d1 = url_download($value, false, $loc_fn_d);
					}
					if (is_file($loc_fn_d)) {
						$to_be_unzipped[$what_nex][$key] = $loc_fn_d;
					}
				}
			}
		}
		$unzipped = array();
		if (!empty($to_be_unzipped)) {
			set_time_limit(0);
			foreach ($to_be_unzipped as $key => $value) {
				$unzip_loc = false;
				if ($key == 'root') {
					$unzip_loc = MW_ROOTPATH;
				}

				if ($key == 'modules') {
					$unzip_loc = MODULES_DIR;
				}

				if ($key == 'elements') {
					$unzip_loc = ELEMENTS_DIR;
				}
				// $unzip_loc = CACHEDIR_ROOT;

				if ($unzip_loc != false and is_array($value) and !empty($value)) {
					$unzip_loc = normalize_path($unzip_loc);
					if (!is_dir($unzip_loc)) {
						mkdir_recursive($unzip_loc);
					}

					foreach ($value as $key2 => $value2) {
						$value2 = normalize_path($value2, 0);

						$unzip = new Unzip();
						$a = $unzip -> extract($value2, $unzip_loc);
						$unzip -> close();
						$unzipped = array_merge($a, $unzipped);
						//  d($unzipped);
						//  d($unzip_loc);
						// d($value2);

						if ($key == 'modules') {
							install_module($key2);
						}
					}
				}
			}
		}
		//clearcache();
		return $unzipped;
	}

	function install_module($module_name) {

		$params = array();

		$params['module'] = $module_name;
		$result = $this -> call('get_download_link', $params);
		if (isset($result["modules"])) {
			foreach ($result["modules"] as $mod_k => $value) {

				$fname = basename($value);
				$dir_c = CACHEDIR . 'downloads' . DS;
				if (!is_dir($dir_c)) {
					mkdir_recursive($dir_c);
				}
				$dl_file = $dir_c . $fname;
				if (!is_file($dl_file)) {
					$get = url_download($value, $post_params = false, $save_to_file = $dl_file);
				}
				if (is_file($dl_file)) {
					$unzip = new \mw\utils\unzip();
					$target_dir = MODULES_DIR . $mod_k;
					$result = $unzip -> extract($dl_file, $target_dir, $preserve_filepath = TRUE);
					// skip_cache
				}
			}
			$params = array();
			$params['skip_cache'] = true;

			$data = modules_list($params);
			//d($data);

		}
		return $result;

	}

	function call($method = false, $post_params = false) {
		$cookie = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cookies' . DIRECTORY_SEPARATOR;
		if (!is_dir($cookie)) {
			mkdir($cookie);
		}
		$cookie_file = $cookie . 'cookie.txt';
		$requestUrl = $this -> remote_api_url;
		if ($method != false) {
			$requestUrl = $requestUrl . '?api_function=' . $method;
		}
		$ch = curl_init($requestUrl);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_USERAGENT, "Microweber");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
		if (!is_array($post_params)) {
			$post_params = array();
		}
		$post_params['site_url'] = site_url();

		if ($post_params != false and is_array($post_params)) {

			$post_params = $this -> http_build_query_for_curl($post_params);

			curl_setopt($ch, CURLOPT_POST, count($post_params));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
		}
		$result = curl_exec($ch);

		curl_close($ch);
		if ($result != false) {
			$result = json_decode($result, 1);
		}
		return $result;
	}

	function http_build_query_for_curl($arrays, &$new = array(), $prefix = null) {

		if (is_object($arrays)) {
			$arrays = get_object_vars($arrays);
		}

		foreach ($arrays AS $key => $value) {
			$k = isset($prefix) ? $prefix . '[' . $key . ']' : $key;
			if (is_array($value) OR is_object($value)) {
				$this -> http_build_query_for_curl($value, $new, $k);
			} else {
				$new[$k] = $value;
			}
		}
		return $new;
	}

}
