<?php

class Masterdebate_model extends Model {
	
	function __construct() {
		parent::Model ();
		//$this->db_setup();
	}
	
	function downloadRemoteVid($vid) {
		$table = TABLE_PREFIX . 'masterdebate_videos';
		//$vid = 'asdasda';
		@mkdir ( BASEPATHSTATIC . 'videos/' );
		$savedir = BASEPATHSTATIC . 'videos/';
		
		$filename1 = $vid . '.flv';
		$filename2 = $vid . '.flv.meta';
		$url = MASTER_DEBATE_RED5_SAVE_STREAMS_URL . $filename1;
		$url_meta = MASTER_DEBATE_RED5_SAVE_STREAMS_URL . $filename2;
		//print $url;
		if (@fopen ( $url, "r" )) {
			$command = " wget -o /dev/null  -c  -q $url -O $savedir$filename1	";
			$command3 = " flvtool2 -U -c -a -v  $savedir$filename1 ";
			//print $command . "    ;   " . $command3 ;
			//exit;
			
			exec ( $command . "    ;   " . $command3 );
			// exec ( $command3 );
			//print $command;
			//exit;
			if (@fopen ( $url_meta, "r" )) {
				$command2 = " wget -o /dev/null  -c -b -q $url_meta -O $savedir$filename2	";
				exec ( $command2 );
			}
			
			//exec ( $command3 );
			

			$data_to_save = false;
			$data_to_save ['vid'] = $vid;
			$data_to_save ['saved_to_local'] = '1';
			$data_to_save ['is_active'] = '1';
			$save = CI::model('core')->saveData ( $table, $data_to_save );
			exec ( $command3 );
			return true;
			//print $command;
		//echo "File Exists";
		

		} else {
			return false;
		
		}
	}
	
	function getVideos($criteria = false) {
		$table = TABLE_PREFIX . 'masterdebate_videos';
		$q = " SELECT * FROM $table order by id DESC ";
		//print $q ;
		$query = CI::db()->query ( $q );
		$query = $query->result_array ();
		return $query;
	}

}
?>