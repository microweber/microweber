<?php
function form_input($uid, $type, $name, $value, $props){
	global $sprefix;
	$str='<input type="'.$type.'" name="'.$sprefix.$name.'_'.$uid.'" id="'.$sprefix.$name.'_'.$uid.'" value="'.$value.'"';
	foreach($props as $prop_name=>$prop_value) $str.=' '.$prop_name.'="'.$prop_value.'"';
	$str.=' onblur="change_setting(\''.$name.'\',this.value, '.$uid.')"';
	$str.=' />';
	return $str;
}
function form_array($uid, $name, $value, $props){
	global $sprefix;
	if(gettype($value)=='array')$value=implode(',',$value);
	$str='<input type="text" name="'.$sprefix.$name.'_'.$uid.'" id="'.$sprefix.$name.'_'.$uid.'" value="'.$value.'"';
	$str.=' onblur="change_setting(\''.$name.'\',this.value, '.$uid.')"';
	foreach($props as $prop_name=>$prop_value) $str.=' '.$prop_name.'="'.$prop_value.'"';
	$str.=' />';
	return $str;
}
function form_bool($uid,$name,$value){
	global $sprefix;
	//$checked=$value?'checked="checked"':''; TODO remove this line
	//$str='<input name="'.$sprefix.$name.'" id="'.$sprefix.$name.'" type="checkbox" '.$checked.' />'; TODO remove this line
	$str='<select name="'.$sprefix.$name.'_'.$uid.'" id="'.$sprefix.$name.'_'.$uid.'" onchange="change_setting(\''.$name.'\',this.value,'.$uid.')">';
	$str.='<option value="0"'.($value?'':' selected="selected"').'>no</option>';
	$str.='<option value="1"'.($value?' selected="selected"':'').'>yes</option>';
	$str.='</select>';
	return $str;
}
function form_choice_list($uid, $name,$value,$options){
	global $sprefix;
	$str='<select name="'.$sprefix.$name.'_'.$uid.'" id="'.$sprefix.$name.'_'.$uid.'" onchange="change_setting(\''.$name.'\',this.value,'.$uid.')">';
	foreach($options as $option=>$ovalue){
		$str.='<option value="'.$ovalue.'"'.($ovalue==$value?' selected="selected"':'').'>'.$option.'</option>';
	}
	$str.='</select>';
	return $str;
}
function form_select_list($uid,$name,$values,$options){
	global $sprefix;
	$str='';
  if(is_string($values)) $values = explode(',', $values);
	foreach($options as $option){
		$ch=in_array($option,$values)?'checked="checked"':'';
		$str.='<input type="checkbox" onclick="setting_select_list(\''.$name.'\',\''.$option.'\',this.checked,'.$uid.')" '.$ch.'/>'.$option.'<br />';
	}
	return $str;
}
function form_user_setting($uid,$name, $is){
	global $sprefix;
	$checked=$is?'checked="checked"':'';
  // Name and id not required
	$str='<select name="'.$sprefix.$name.'_'.$uid.'_usersetting" id="'.$sprefix.$name.'_'.$uid.'_usersetting" class="'.$sprefix.$name.'_usersetting" onchange="change_is_user_setting(\''.$name.'\',this.value,'.$uid.')">';
	$str.='<option value="0"'.($is?'':' selected="selected"').'>no</option>';
	$str.='<option value="1"'.($is?' selected="selected"':'').'>yes</option>';
	$str.='</select>';
	return $str;
}
function user_row($id, $username, $status){
	$html='<tr id="user_row_'.$id.'">
		<td>'.$username.'</td>
		<td>
			<select onchange="user_status_change('.$id.',this.value)">
				<option value="1" '.(($status==1)?'selected="selected"':'').'>admin</option>
				<option value="2" '.(($status==2)?'selected="selected"':'').'>user</option>
				<option value="3" '.(($status==3)?'selected="selected"':'').'>blocked</option>
			</select>
		</td>
		<td>
			<span class="ui-state-default button" onclick="password_reset('.$id.',\''.$username.'\')">Reset password</span>
      <span class="ui-state-default button" onclick="edit_user_settings('.$id.', \''.$username.'\')">Settings</span>
    </td>
    <td>
			<div class="ui-state-default ui-corner-all button right"><span class="ui-icon ui-icon-closethick" onclick="delete_user('.$id.',\''.$username.'\')"></span></div>
		</td>
	</tr>';
	return $html;
}
function get_association_row($ext, $plugin,$id){
	$str= '
	<tr id="association_row_'.$id.'">
		<td><input type="text" id="association_extension_'.$id.'" value="'.$ext.'" onblur="association_extension_change('.$id.')"/></td>
		<td>'.get_plugin_list($plugin,$id).'</td>
		<td><div class="ui-state-default ui-corner-all button"><span class="ui-icon ui-icon-closethick" onclick="association_delete('.$id.');"></span></td>
	</tr>';
	$str=preg_replace('/\n/','',$str);
	return $str;
}
function get_plugin_list($selected_plugin, $unique){
	global $kfm;
	$str='<select name="plugin_selector_'.$unique.'" id="plugin_selector_'.$unique.'"';
	if($unique!='0222')$str.=' onchange="change_association_plugin('.$unique.')"';
	$str.='>';
	foreach($kfm->plugins as $plugin){
		$str.='<option value="'.$plugin->name.'" '.($selected_plugin==$plugin->name?'selected="selected"':'').'>'.$plugin->title.'</option>';
	}
	$str.='</select>';
	$str=preg_replace('/\n/','',$str);
	return $str;
}
?>
