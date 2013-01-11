<?php
api_expose('load_history_file');
function load_history_file() {
	if ((trim(strval($_POST['history_file'])) != '') and strval($_POST['history_file']) != 'false') {
		//	p ( $_POST );
		$id = is_admin();
		if ($id == false) {
			exit('Error: not logged in as admin.');
		} else {
			$history_file = base64_decode($_POST['history_file']);
			//p($history_file);
			// p($history_file);
			//p(HISTORY_DIR);
			$d = normalize_path(HISTORY_DIR, 1);
			$history_file = normalize_path($history_file, false);
			//   p($d);
			//   p($history_file);
			if (strpos($history_file, $d) === 0) {
				$history_file = str_replace('..', '', $history_file);
			} else {
				exit('Error: invalid history dir.');
			}
			//print $history_file;
			//$history_file = $this->load->file ( $history_file, true );
			$history_file = file_get_contents($history_file);
			//$for_history = base64_decode ( $history_file );
			//$for_history = unserialize ( $for_history );
			//$history_file = $this->template_model->parseMicrwoberTags ( $history_file );
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json; charset=utf-8');
			//header ( "Content-type: text/html");
			//$history_file = preg_replace('/[^(\x20-\x7F)]*/','', $history_file);
			$history_file = preg_replace("/[�]/", "", $history_file);
			//$history_file =  ( $history_file );
			print $history_file;
			exit();
			//
		}
	}
}

function get_history_files($data = array()) {
 
	$table = $data['table'];
	$id = $data['id'];
	$value = $data['value'];
	$field = $data['field'];
	if ($table == false) {
		$table = 'global';
	}
	$field = str_replace(':', '_', $field);
	$id = str_replace(':', '_', $id);

	// copy for hiustory
	$today = date('Y-m-d H-i-s');
	$history_dir = HISTORY_DIR . $table . '/' . $id . '/' . $field . '/';
	$history_dir = normalize_path($history_dir);
	$history_dir = str_replace('..', '_', $history_dir);

	// $history_dir = str_replace(':', '_', $history_dir);
	$history_dir = str_replace('^', '_', $history_dir);
	if ($history_dir == false) {
		mkdir_recursive($history_dir);
	}

	// p($history_dir);
	$his = array();
	$file_counter = 0;

	$filez = glob($history_dir . "*.php");
	if (!empty($filez)) {
		$filez = array_reverse($filez);
		foreach ($filez as $filename) {

			if ($file_counter < 200) {

				$size = filesize($filename);
				// p($size);
				if (intval($size) != 0) {
					$his[] = $filename;
				} else {
				 
					@unlink($filename);
				}
			} else {
				@unlink($filename);
			}

			$file_counter++;
		}
	}
	if (!empty($his)) {
		// $his = array_reverse($his);
	}
	return $his;
}

function save_history($data = array()) {
	/*
	 * $table = $data ['table']; $id = $data ['id']; $value = $data ['value'];
	 * $field = $data ['field']; $full_path = $data ['full_path'];
	 */
	extract($data);
	if (!isset($id)) {
		$id = '0';
	}
	$field = str_replace(':', '_', $field);
	$id = str_replace(':', '_', $id);

	// copy for hiustory
	$today = date('Y-m-d H-i-s');

	if (isset($full_path) == false) {
		$history_dir = HISTORY_DIR . $table . '/' . $id . '/' . $field . '/';
		$history_dir = normalize_path($history_dir);
	} else {

		$history_dir = dirname($full_path);
		$history_dir = normalize_path($history_dir);
	}

	$history_dir = str_replace('..', '_', $history_dir);
	$dir = $history_dir;
	$pattern = '\.(php)$';
	//d//($history_dir);
	if (is_dir($history_dir) == false) {
		mkdir_recursive($history_dir);
	}

	$newstamp = 0;
	$newname = "";

	$file_counter_to_keep = 0;

	$dc = opendir($dir);
	while ($fn = readdir($dc)) {

		$timedat = filemtime("$dir/$fn");
		if ($timedat > $newstamp) {
			$newstamp = $timedat;
			$newname = $fn;
		}

		// }
	}
	// $timedat is the time for the latest file
	// $newname is the name of the latest file
	// p($newname);
	if (is_file("$dir/$newname")) {
		$newest = @file_get_contents("$dir/$newname");
	} else {
		$newest = false;
	}
	if (trim($value) != '') {
		if ($newest != $value) {

			touch(HISTORY_DIR . 'index.php');
			touch($history_dir . 'index.php');

			$hf = $history_dir . $today . '.php';
			// p($hf);
			$value = html_entity_decode($value);

			// $data = mb_convert_encoding ( $value, 'UTF-8', 'OLD-ENCODING' );
			file_put_contents($hf, $value);

			// file_put_contents ( $hf, $value );
		} else {
			// print 'skip';
		}
	}
}
