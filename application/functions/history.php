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

 