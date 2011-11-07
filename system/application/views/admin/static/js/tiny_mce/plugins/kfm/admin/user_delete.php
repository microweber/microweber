<?php
require_once('initialise.php');
if($kfm->user_status!=1)die ('error("No authorization aquired")');
if(!isset($_POST['uid'])) die ('error("Some parameters are missing")');
if($kfm->user_id == $_POST['uid']) die ('error("User cannot delete their own accounts")');
$kfm->db->query('DELETE FROM '.KFM_DB_PREFIX.'users WHERE id='.((int)$_POST['uid']));
$kfm->db->query('DELETE FROM '.KFM_DB_PREFIX.'settings WHERE user_id='.((int)$_POST['uid']));
echo '$("#user_row_'.$_POST['uid'].'").fadeOut();';
?>
message('User removed');
