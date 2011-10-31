<?php
require_once('initialise.php');
if(isset($_POST['uid'])){
	if($kfm->user_status==1){
		$uid=(int)$_POST['uid'];
	}else{
		die('error("Unauthorized attempt to change a users password");');
	}
}else{
	$uid=$kfm->user_id;
}
if(!isset($_POST['npw'])||!isset($_POST['npw2']))die('error("Error: no new passwords given.");');
if(isset($_POST['reset']) && $_POST['reset'] && $kfm->user_status==1){
  // No check required. For admin users only
}else{ // Check if old password is correct
  $sql='SELECT id FROM '.KFM_DB_PREFIX.'users WHERE id="'.$uid.'" AND password="'.sha1($_POST['opw']).'"';
  $r=db_fetch_all($sql);
  if(!$r || !count($r))die('$(\'.npw_field\').removeClass("ui-state-error");$(\'#password_old\').addClass("ui-state-error");error("Old password is not correct");');
}
if($_POST['npw']!=$_POST['npw2'])die('$(\'.npw_field\').removeClass("ui-state-error");$(\'#password_new2\').addClass("ui-state-error");error("The passwords are not equal");');
$kfmdb->query('UPDATE '.KFM_DB_PREFIX.'users SET password="'.sha1($_POST['npw']).'" WHERE id='.$uid);
if($uid==$kfm->user_id)$kfm_session->set('password',$_POST['npw']);
?>
$('.npw_field').val('').removeClass('ui-state-error');
message('Password changed');
