<?php
function get_history_files($data = array()) {
	$table = $data ['table'];
	$id = $data ['id'];
	$value = $data ['value'];
	$field = $data ['field'];
	if ($table == false) {
		$table = 'global';
	}
	$field = str_replace ( ':', '_', $field );
	$id = str_replace ( ':', '_', $id );
	
	// copy for hiustory
	$today = date ( 'Y-m-d H-i-s' );
	$history_dir = HISTORY_DIR . $table . '/' . $id . '/' . $field . '/';
	$history_dir = normalize_path ( $history_dir );
	$history_dir = str_replace ( '..', '_', $history_dir );
	
	// $history_dir = str_replace(':', '_', $history_dir);
	$history_dir = str_replace ( '^', '_', $history_dir );
	if ($history_dir == false) {
		mkdir_recursive ( $history_dir );
	}
	
	// p($history_dir);
	$his = array ();
	$file_counter = 0;
	
	$filez = glob ( $history_dir . "*.php" );
	if (! empty ( $filez )) {
		$filez = array_reverse ( $filez );
		foreach ( $filez as $filename ) {
			
			if ($file_counter < 200) {
				
				$size = filesize ( $filename );
				// p($size);
				if (intval ( $size ) != 0) {
					$his [] = $filename;
				} else {
					print $filename;
					@unlink ( $filename );
				}
			} else {
				@unlink ( $filename );
			}
			
			$file_counter ++;
		}
	}
	if (! empty ( $his )) {
		// $his = array_reverse($his);
	}
	return $his;
}
function save_history($data = array()) {
	/*
	 * $table = $data ['table']; $id = $data ['id']; $value = $data ['value'];
	 * $field = $data ['field']; $full_path = $data ['full_path'];
	 */
	extract ( $data );
	
	$field = str_replace ( ':', '_', $field );
	$id = str_replace ( ':', '_', $id );
	
	// copy for hiustory
	$today = date ( 'Y-m-d H-i-s' );
	
	if (isset ( $full_path ) == false) {
		$history_dir = HISTORY_DIR . $table . '/' . $id . '/' . $field . '/';
		$history_dir = normalize_path ( $history_dir );
	} else {
		
		$history_dir = dirname ( $full_path );
		$history_dir = normalize_path ( $history_dir );
	}
	
	$history_dir = str_replace ( '..', '_', $history_dir );
	$dir = $history_dir;
	$pattern = '\.(php)$';
	
	if (is_dir ( $history_dir ) == false) {
		mkdir_recursive ( $history_dir );
	}
	
	$newstamp = 0;
	$newname = "";
	
	$file_counter_to_keep = 0;
	
	$dc = opendir ( $dir );
	while ( $fn = readdir ( $dc ) ) {
 
		$timedat = filemtime ( "$dir/$fn" );
		if ($timedat > $newstamp) {
			$newstamp = $timedat;
			$newname = $fn;
		}
		
		// }
	}
	// $timedat is the time for the latest file
	// $newname is the name of the latest file
	// p($newname);
	if (is_file ( "$dir/$newname" )) {
		$newest = @file_get_contents ( "$dir/$newname" );
	} else {
		$newest = false;
	}
	if (trim ( $value ) != '') {
		if ($newest != $value) {
			
			touch ( HISTORY_DIR . 'index.php' );
			
			$hf = $history_dir . $today . '.php';
			// p($hf);
			$value = html_entity_decode ( $value );
			
			// $data = mb_convert_encoding ( $value, 'UTF-8', 'OLD-ENCODING' );
			file_put_contents ( $hf, $value );
			
			// file_put_contents ( $hf, $value );
		} else {
			// print 'skip';
		}
	}
}