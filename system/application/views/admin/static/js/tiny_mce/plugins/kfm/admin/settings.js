
function change_setting(name,value,uid){var usersetting=$('#'+sprefix+name+'_'+uid+'_usersetting').val();usersetting=usersetting?usersetting:0;$('.'+sprefix+name+'_usersetting').val(usersetting);$.post('setting_change.php',{uid:uid,name:name,value:value,usersetting:usersetting},function(res){eval(res);});}
function style_usersetting(name,uid){$('#desc_'+sprefix+name+'_'+uid).removeClass().addClass('user_setting');}
function style_defaultsetting(name,uid){$('#desc_'+sprefix+name+'_'+uid).removeClass().addClass('default_setting');}
function change_is_user_setting(name,is,uid){if(!parseInt(is)){}
var value=$('#'+sprefix+name+'_'+uid).val();$.post('setting_change.php',{uid:uid,name:name,value:value,usersetting:is},function(res){eval(res);});}
function setting_default_value(name,uid){$.post('setting_make_default.php',{sname:name,uid:uid},function(res){eval(res);});}
function setting_select_list(name,option,checked,uid){checked=checked?1:0;var usersetting=$('#'+sprefix+name+'_'+uid+'_usersetting').val();usersetting=usersetting?usersetting:0;$('.'+sprefix+name+'_usersetting').val(usersetting);$.post('setting_change.php',{uid:uid,name:name,value:option,checked:checked,usersetting:usersetting},function(res){eval(res);});}
function setting_help(setting_name,caller,uid){$.post('setting_get_help.php',{name:setting_name},function(res){eval(res);});}
function setting_help_close(setting_name){$("#"+setting_name+"_help").remove();}