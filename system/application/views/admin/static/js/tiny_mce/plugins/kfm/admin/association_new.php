<?php
require_once('initialise.php');
require_once('functions.php');
if(!isset($_POST['extension']) || !isset($_POST['plugin']))die('error("Post values are missing");');
$uid=$kfm->user_id;
$sql='INSERT INTO '.KFM_DB_PREFIX.'plugin_extensions (extension, plugin, user_id) VALUES (';
$sql.='"'.sql_escape($_POST['extension']).'",';
$sql.='"'.sql_escape($_POST['plugin']).'",';
$sql.=$uid;
$sql.=')';
$kfm->db->query($sql);
$ahtml=get_association_row($_POST['extension'],$_POST['plugin'],$kfm->db->lastInsertId());
echo '$("#association_table tbody").append(\''.$ahtml.'\');';
?>
message("new association");
