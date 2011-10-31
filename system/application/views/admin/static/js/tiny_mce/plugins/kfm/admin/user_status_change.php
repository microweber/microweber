<?php
require_once('initialise.php');
if($kfm->user_status!=1)die ('error("No authorization aquired")');
if(!isset($_POST['uid']) || !isset($_POST['status'])) die ('error("Some parameters are missing")');
if($kfm->user_id == $_POST['uid']) die ('error("User cannot change their own status")');
$kfm->db->query('UPDATE '.KFM_DB_PREFIX.'users SET status='.((int)$_POST['status']).' WHERE id='.((int)$_POST['uid']));
?>
message('Status changed');
