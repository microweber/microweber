<?php
require_once('initialise.php');
if(!isset($_POST['aid']))die('error("Post values are missing");');
$kfm->db->query('DELETE FROM '.KFM_DB_PREFIX.'plugin_extensions WHERE id='.$_POST['aid']);
echo '$("#association_row_'.$_POST['aid'].'").remove();';
?>
