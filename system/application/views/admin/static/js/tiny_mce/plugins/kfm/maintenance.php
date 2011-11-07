<?php
/**
 * KFM - Kae's File Manager - database maintenance
 *
 * @category None
 * @package  None
 * @author   Benjamin ter Kuile <bterkuile@gmail.com>
 * @license  docs/license.txt for licensing
 * @link     http://kfm.verens.com/
 */
require_once 'initialise.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>KFM-maintenance</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
<script type="text/javascript">
	var $j = jQuery.noConflict();
	$j(document).ready(function(){
	});
</script>
<style type="text/css">
body{
	background-color:#eeeeee;
}
</style>
</head>
<body>
<div id="maintenance_messages">
<?php
//Ghost file deletion
$kfmdb->query('DELETE FROM '.KFM_DB_PREFIX.'_files WHERE directory=0');
?>
<p>Maintenance done. <a href="index.php">Return to the filemanager</a></p>
</div>
<div id="maintenance_complete_html" style="display:none;">
<h2>Maintenance complete</h2>
</div>
</body>
</html>
