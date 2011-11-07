<?php
require_once('initialise.php');
/** This function sets a usersetting 
  * Only the values of the admin user are taken into account for this
 */
function change_usersetting($sn, $value, $is, $uid){
  global $kfm;
  $s=db_fetch_row('SELECT id FROM '.KFM_DB_PREFIX.'settings WHERE name="'.sql_escape($sn).'" and user_id='.$uid);
  if($s && count($s)){
    $kfm->db->query('UPDATE '.KFM_DB_PREFIX.'settings SET value="'.sql_escape($value).'", usersetting='.$is.' WHERE name="'.sql_escape($sn).'" AND user_id='.$uid);
  }else{
    $sql = 'INSERT INTO '.KFM_DB_PREFIX.'settings (name, value, user_id, usersetting) VALUES ("'.sql_escape($sn).'","'.sql_escape($value).'", '.$uid.','.sql_escape($is).')';
    $kfm->db->query($sql);
  }
}
if(!isset($_POST['name']) || !isset($_POST['value'])) die ('error("post value missing");');
if(!isset($_POST['usersetting'])) die ('error("Cannot determine if setting is usersetting")');
$sn=$_POST['name'];
$value=$_POST['value'];
$usersetting = (int)$_POST['usersetting'];
/* Next section to create a proper value for the database */
if($kfm->sdef[$sn]['type']=='select_list'){
	if(!isset($_POST['checked']))die ('error("property checked must be given for a select list");');
	$ch=$_POST['checked'];
	$sval=implode(',',$kfm->setting($sn));
	if(isset($_POST['clean']) && $_POST['clean'])$sval='';
	if($ch){
		$sval.=','.$value;
	}else{
		$sval=preg_replace('/'.$value.',*/','',$sval);
	}
	$sval=trim($sval, ' ,');
	$value=$sval;
}
// Only allow administrator users to give a custom uid
$uid = ($kfm->isAdmin() && isset($_POST['uid']) && is_numeric($_POST['uid'])) ? $_POST['uid'] : $kfm->user_id;
if($uid == 1){
  change_usersetting($sn, $value, $usersetting, 1); 
}else{
    // Check if admin setting exist, add it if not
    $s=db_fetch_row('SELECT id FROM '.KFM_DB_PREFIX.'settings WHERE name="'.sql_escape($sn).'" and user_id=1');
    if($s && count($s)){
    }else{
      // Add the setting for the admin user. The setting of the user with administrator rights will become the default setting
      // I (Benjamin) think this is a feature and not a bug :)
      change_usersetting($sn, $value, $usersetting, 1);
    }
  if(!$kfm->isAdmin()){
    list($settings, $usersettings) = get_settings($uid); // $settings as database values
    if(!in_array($sn, $usersettings)) die('error("Only admins can change non usersettings");'); // Security
    $usersetting = 0; // Only admin users can make a setting a usersetting
  }
  change_usersetting($sn, $value, $usersetting, $uid); 
	echo '$("#todefault_'.$sn.'_'.$uid.'").fadeIn();';
}
// Change theme in current session if is changed
if($sn=='theme' && $uid == $kfm->user_id )$kfm_session->set('theme',$value);
echo 'style_usersetting("'.$sn.'",'.$uid.');';
?>
message('setting changed');
