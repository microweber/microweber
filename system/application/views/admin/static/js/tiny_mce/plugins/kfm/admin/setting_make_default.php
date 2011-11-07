<?php
require_once('initialise.php');
if(!isset($_REQUEST['sname'])) die ('error("Postvalues are not correct")');
$uid = ($kfm->isAdmin() && isset($_REQUEST['uid']) && is_numeric($_REQUEST['uid'])) ? $_REQUEST['uid'] : $kfm->user_id;
if($uid == 1) die('error(Admin user cannot revert to original)');
$sn=$_REQUEST['sname'];
$kfm->db->query('DELETE FROM '.KFM_DB_PREFIX.'settings WHERE name="'.sql_escape($sn).'" AND user_id='.$uid);
$a=db_fetch_row('SELECT value, usersetting FROM '.KFM_DB_PREFIX.'settings WHERE name="'.sql_escape($sn).'" AND user_id=1');
$value= $a['value'];
$usersetting = $a['usersetting'];
if($kfm->sdef[$sn]['type']=='select_list'){
	require_once('functions.php');
	$newhtml=form_select_list($sn, explode(',',$value),$kfm->sdef[$sn]['options']);
	$newhtml=str_replace("'","\'",$newhtml);
	echo '$("#select_list_'.$sn.'_container_'.$uid.'").html(\''.$newhtml.'\');';
}else{
  echo '$("#kfm_setting_'.$sn.'_'.$uid.'").val("'.$value.'");';
}
if($value=='theme'){
	$kfm_session->set('theme',$value);
}
?>
style_defaultsetting("<?php echo $sn ?>", <?php echo $uid ?>);
$("#todefault_<?php echo $sn ?>_<?php echo $uid ?>").fadeOut();
$('#kfm_setting_<?php echo $sn ?>_<?php echo $uid ?>_usersetting').val(<?php echo $usersetting ?>);
