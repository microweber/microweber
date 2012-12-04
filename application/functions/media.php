<?php

function get_picture($content_id, $for = 'post') {
	$arr = array();
	$arr['to_table'] = 'table_content';
	$arr['limit'] = '1';
	$arr['to_table_id'] = $content_id;
	$imgages = get_pictures($arr);
	//..p($imgages);
	if (isset($imgages[0])) {
		return $imgages[0];
	}
}

api_expose('upload_progress_check');

function upload_progress_check() {
	if (is_admin() == false) {

		error('not logged in as admin');
	}
	if (isset($_SERVER["HTTP_REFERER"])) {
		$ref_str = md5($_SERVER["HTTP_REFERER"]);
	} else {
		$ref_str = 'no_HTTP_REFERER';
	}
	$ref_str = 'no_HTTP_REFERER';
	$cache_id = 'upload_progress_' . $ref_str;
	$cache_group = 'media/global';

	$cache_content = cache_get_content($cache_id, $cache_group);
	if ($cache_content != false) {
		if (isset($cache_content["tmp_name"]) != false) {
			if (isset($cache_content["f"]) != false) {

				$filename = $cache_content["tmp_name"];
				if (is_file($filename)) {
					$filesize = filesize($filename);
				}

				$filename = $cache_content["f"];

				if (is_file($filename)) {
					$filesize = filesize($filename);
				}

				$perc = percent($filesize, $cache_content["size"]);
				return $perc;
				//  d($perc);
			}
		}
	}

	//ini_set("session.upload_progress.enabled", true);
	// return $_SESSION;
}

api_expose('upload');

function upload($data) {
	if (is_admin() == false) {

		error('not logged in as admin');
	}
	ini_set("upload_max_filesize", "2500M");
	ini_set("memory_limit", "256M");
	ini_set("max_execution_time", 0);
	ini_set("post_max_size", "2500M");
	ini_set("max_input_time", 9999999);

	// ini_set("session.upload_progress.enabled", 1);
	if (isset($_SERVER["HTTP_REFERER"])) {
		$ref_str = md5($_SERVER["HTTP_REFERER"]);
	} else {
		$ref_str = 'no_HTTP_REFERER';
	}
	$ref_str = 'no_HTTP_REFERER';
	$cache_id = 'upload_progress_' . $ref_str;
	$cache_group = 'media/global';

	$target_path = MEDIAFILES . 'uploaded' . DS;
	$target_path = normalize_path($target_path, 1);

	if (!is_dir($target_path)) {

		mkdir_recursive($target_path);
	}
	$rerturn = array();
	if (!isset($_FILES) and isset($data['file'])) {
		if (isset($data['name'])) {
			$f = $target_path . $data['name'];
			if (is_file($f)) {
				$f = $target_path . date('YmdHis') . $data['name'];
			}

			$df = strpos($data['file'], 'base64,');
			if ($df != false) {
				//   $df = substr($data['file'], 0, $df);
				$data['file'] = substr($data['file'], $df + 7);
				$data['file'] = str_replace(' ', '+', $data['file']);
				//    d($data['file']);
			}

			// base64_to_file($data['file'], $f);

			$rerturn['src'] = pathToURL($f);
			$rerturn['name'] = $data['name'];
		}
	} else {

		$allowedExts = array("jpg", "jpeg", "gif", "png", 'bmp');

		//$upl = cache_save($_FILES, $cache_id, $cache_group);
		foreach ($_FILES as $item) {

			$extension = end(explode(".", $item["name"]));
			if (in_array($extension, $allowedExts)) {
				if ($item["error"] > 0) {
					error("Error: " . $item["error"]);
				} else {
					$upl = cache_store_data($item, $cache_id, $cache_group);

					$f = $target_path . $item['name'];
					if (is_file($f)) {
						$f = $target_path . date('YmdHis') . $item['name'];
					}

					$progress = (array)$item;
					$progress['f'] = $f;
					$upl = cache_store_data($progress, $cache_id, $cache_group);

					if (move_uploaded_file($item['tmp_name'], $f)) {
						$rerturn['src'] = pathToURL($f);
						$rerturn['name'] = $item['name'];
					}
				}
			} else {
				error("Invalid file ext");
			}

			//
			//            $input = fopen("php://input", "r");
			//            $temp = tmpfile();
			//
			//            $realSize = stream_copy_to_stream($input, $temp);
			//            fclose($input);
			//
			//
			//
			//
			//            $target = fopen($f, "w");
			//            fseek($temp, 0, SEEK_SET);
			//            stream_copy_to_stream($temp, $target);
			//            $rerturn['src'] = pathToURL($f);
			//            $rerturn['name'] = $item['name'];
			//            fclose($target);
		}

		//   var_dump($_FILES);
	}

	exit(json_encode($rerturn));
	//var_dump($data);
	//var_dump($_FILES);
}

function base64_to_file($data, $target) {

	touch($target);
	if (is_writable($target) == false) {
		exit("$target is not writable");
	}
	$whandle = fopen($target, 'wb');
	stream_filter_append($whandle, 'convert.base64-decode', STREAM_FILTER_WRITE);
	fwrite($whandle, $data);

	// file_put_contents($target, base64_decode($data));
	fclose($whandle);
}

api_expose('reorder_media');

function reorder_media($data) {

	$adm = is_admin();
	if ($adm == false) {
		error('Error: not logged in as admin.');
	}
	$tables = c('db_tables');

	$table =  MW_TABLE_PREFIX . 'media';
	foreach ($data as $value) {
		if (is_arr($value)) {
			$indx = array();
			$i = 0;
			foreach ($value as $value2) {
				$indx[$i] = $value2;
				$i++;
			}

			db_update_position($table, $indx);
			return true;
			// d($indx);
		}
	}
}

api_expose('delete_media');

function delete_media($data) {

	$adm = is_admin();
	if ($adm == false) {
		error('Error: not logged in as admin.');
	}

	if (isset($data['id'])) {
		$c_id = intval($data['id']);
		db_delete_by_id('table_media', $c_id);

		//d($c_id);
	}
}

api_expose('save_media');

function save_media($data) {

	$s = array();
	if (isset($data['for'])) {
		$t = guess_table_name($data['for']);
		$t = db_get_assoc_table_name($t);
		$s['to_table'] = $t;
	}

	if (isset($data['for-id'])) {
		$t = intval($data['for-id']);
		$s['to_table_id'] = $t;
	}

	if (isset($data['id'])) {
		$t = intval($data['id']);
		$s['id'] = $t;
	}

	if (isset($data['title'])) {
		$t = ($data['title']);
		$s['title'] = $t;
	}

	if (isset($data['src'])) {

		$s['filename'] = $data['src'];
	}

	if (!isset($data['position']) and !isset($s['id'])) {
		$s['position'] = 9999999;
	}

	if (isset($data['for_id'])) {
		$t = intval($data['for_id']);
		$s['to_table_id'] = $t;
	}

	if (isset($data['media_type'])) {
		$t = db_escape_string($data['media_type']);
		$s['media_type'] = $t;
	}
	$tables = c('db_tables');

	// ->'table_content';
	if (isset($s['to_table']) and isset($s['to_table_id'])) {
		$table =  MW_TABLE_PREFIX . 'media';
		//$s['debug'] = $t;
		$s = save_data($table, $s);
		return ($s);
	} elseif (isset($s['id']) and isset($s['title'])) {
		$table =  MW_TABLE_PREFIX . 'media';
		//$s['debug'] = $t;
		$s = save_data($table, $s);
		return ($s);
	} else {
		error('Invalid data');
	}
}

function thumbnail($src, $width = 200, $height = 200) {

	//require_once ();
	$surl = site_url();
	$local = false;

	$media_url = MEDIA_URL;
	if (stristr($src, $surl)) {
		$src = str_ireplace($surl . '/', $surl, $src);

		$src = str_replace($media_url, '', $src);
		$src = str_ireplace($surl, '', $src);
		$src = ltrim($src, DS);
		$src = ltrim($src, '/');
		$src = rtrim($src, DS);
		$src = rtrim($src, '/');
		$src = MEDIAFILES . $src;
		$src = normalize_path($src, false);
		//d($src);
	} else {
		$dl_file = MEDIAFILES . crc32($src) . basename($src);
		url_download($src, false, $dl_file);
		$src = $dl_file;
	}
	$cd = CACHEDIR . 'thumbnail' . DS;
	if (!is_dir($cd)) {
		mkdir_recursive($cd);
	}

	$cache = crc32($src . $width . $height) . basename($src);

	$cache = str_replace(' ', '_', $cache);
	$cache_path = $cd . $cache;

	if (file_exists($cache_path)) {

	} else {
		if (file_exists($src)) {

			$tn = new Thumbnailer($src);
			$thumbOptions = array('maxLength' => $height, 'width' => $width);
			$tn -> createThumb($thumbOptions, $cache_path);
		}
	}

	$cache_path = pathToURL($cache_path);
	return $cache_path;
	//d($src);
}

function get_pictures($params) {
	$tables = c('db_tables');
	$table =  MW_TABLE_PREFIX . 'media';
	$params = parse_params($params);
/*
	// if (is_string($params)) {
		// $params = parse_str($params, $params2);
		// $params = $params2;
	// }*/


	if (isset($params['for'])) {
		$params['for'] = db_get_assoc_table_name($params['for']);

	}

	$params['table'] = $table;
	$params['orderby'] = 'position ASC';
	$data = get($params);

	return $data;
}
