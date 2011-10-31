<?php
require_once('initialise.php');
if($kfm->user_status!=1)die ('error("No authorization aquired")');
if(!isset($_POST['sname']) || !isset($_POST['isuser'])) die ('error("Some parameters are missing")');
$isuser=$_POST['isuser']?1:0;
$sn=$_POST['sname'];
$sql='UPDATE '.KFM_DB_PREFIX.'settings SET usersetting='.$isuser.' WHERE name="'.sql_escape($sn).'"';
$kfm->db->query($sql);
if($kfm->user_id==1 && $_POST['isuser']){
	$_POST['name']=$sn;
	if($kfm->sdev[$sn]['type']=='array' || $kfm->sdef[$sn]['type']=='select_list'){
		$_POST['value']==implode(',',$kfm->setting($sn));
		$_POST['checked']=1;
		$_POST['clean']=1;
	}else{
		$_POST['value']=$kfm->setting($sn);
	}
	include('setting_change.php');
	exit;
}
echo 'message("'.$sn.' is '.($isuser?'a':'no').' usersetting");';
?>
