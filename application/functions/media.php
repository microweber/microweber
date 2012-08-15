<?php 

function get_picture($content_id, $for = 'post') {
	print "get_picture must be finished!";
	return false;
	$imgages = get_pictures ( $content_id, $for );
	//..p($imgages);
	return $imgages [0];
}
?>