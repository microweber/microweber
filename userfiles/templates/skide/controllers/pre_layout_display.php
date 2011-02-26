<?php


/*if (url_param('cdn') == true) {*/
	$old = site_url ( 'userfiles' );
	$new = str_replace ( 'skidekids.com', 'skidekids.ooyes.netdna-cdn.com', $old );
	$layout = str_replace ( $old, $new, $layout );

	

/*}*/
 




?>