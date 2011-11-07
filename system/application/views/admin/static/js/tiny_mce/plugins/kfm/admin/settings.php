<?php
require_once('initialise.php');
require_once('functions.php');
$uid = ($kfm->isAdmin() && isset($_POST['uid']) && is_numeric($_POST['uid'])) ? $_POST['uid'] : $kfm->user_id;
$mysets=db_fetch_all('SELECT name, value FROM '.KFM_DB_PREFIX.'settings WHERE user_id='.$uid);
$ismodal = isset($_REQUEST['ismodal']) ? $_REQUEST['ismodal'] : 0;
$my_settings = array();
foreach($mysets as $myset) $my_settings[$myset['name']] = $myset['value']; // Convert array to Hash
list($settings, $usersettings) = get_settings($uid); // $settings as database values

// Needed to hide usersetting combo in modal view for non admin users when setting is usersetting anyway
if($ismodal) list($default_db_settings, $default_db_usersettings) = get_settings(1);
//foreach($mysets as &$myset)$myset=$myset['name'];
?>
<?php
$js='';
$sprefix='kfm_setting_'; // Until now a dummy prefix for settings. Maybe needed for future things. Also in index.php
$str='<div id="settings_container_'.$uid.'" class="settings_container ui-widget ui-widget-content"><table class="settings_table">
<tbody>';
$user_is_administrator = $kfm->isAdmin($uid);
foreach($kfm->sdef as $sname=>$sdef){
  $is_usersetting = in_array($sname, $usersettings);
	if(!$kfm->isAdmin() && !$is_usersetting) continue; // Do not process settings the user has no rights to
  // Comment below to let administrators change all user values
  //if(!$user_is_administrator && !$kfm->isUserSetting($sname) && !isset($my_settings[$sname])) continue; // Only show non user setting for user if it is explicitly defined
	//$svalue=$kfm->setting($sname);
  $svalue = isset($my_settings[$sname]) ? $my_settings[$sname] : $kfm->setting($sname);
  //print isset($my_settings[$sname]) ? "Test my setting $sname: ".$my_settings[$sname]."<br/>\n" : "Test default setting $sname: ".$kfm->setting($sname)."<br/>\n";
	$sprops=isset($sdef->properties)?$sdef['properties']:array();
	$sunit=isset($sdef['unit'])?$sdef['unit']:'';
	$gh=$sdef['type']=='group_header'?1:0;
	
	//$ismyset=in_array($sname,$mysets);
  $ismyset = isset($my_settings[$sname]);
	$str.="\n\t<tr>";
	if($gh){
		$str.='
		<td colspan="5"><span class="ui-widget-header">'.$sname.'</span></td></tr>';
	}else{
		$str.='
		<td><span id="desc_'.$sprefix.$sname.'_'.$uid.'" class="'.($ismyset?'user_setting':'default_setting').'">'.$sname.'</span></td>
		<td>';
		switch($sdef['type']){
			case 'text':
			case 'integer':
				$str.=form_input($uid,'text',$sname, $svalue, $sprops);	
				break;
			case 'bool':
				$str.=form_bool($uid,$sname, $svalue);
				break;
			case 'array':
				$str.=form_array($uid,$sname,$svalue,$sprops);
				break;
			case 'choice_list':
				$str.=form_choice_list($uid,$sname, $svalue, $sdef['options']);
				break;
			case 'select_list':
				$str.='<div id="select_list_'.$sname.'_container_'.$uid.'" class="select_list_container">';
				$str.=form_select_list($uid, $sname, $svalue, $sdef['options']);
				$str.='</div>';
				break;
			default:
				die('Error with setting '.$sname.'. Type '.$sdef['type'].' does not exist');
				break;
		}
		$str.=' '.$sunit.'
		</td>';
	  $str.='<td>';
		if(!$ismodal && $kfm->user_status==1){
			$str.=form_user_setting($uid,$sname,$is_usersetting);
		}elseif($ismodal && !$user_is_administrator){

			$str.=in_array($sname, $default_db_usersettings) ? 'yes' : form_user_setting($uid,$sname,$is_usersetting);
    }

	  $str.='</td>';
		$str.='
		<td>';
			if($uid != 1)$str.='
				<div id="todefault_'.$sname.'_'.$uid.'" class="ui-state-default button" '.($ismyset?'':' style="display:none;"').'>
            <span onclick="setting_default_value(\''.$sname.'\', '.$uid.')" class="ui-icon ui-icon-arrowreturnthick-1-w"></span>
        </span>';
		$str.='
		</td>
		<td>';
    if(!$ismodal) $str.='
			<div class="ui-state-default ui-corner-all button"><span class="ui-icon ui-icon-help" onclick="setting_help(\''.$sname.'\',this, '.$uid.')"></span></div>';
    $str.='
		</td>
		</tr>';
	}
}
$str.='</table></div><br>';
print $str;
?>
