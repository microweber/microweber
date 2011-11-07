<?php
require_once('initialise.php');
foreach($kfm->plugins as $plugin){
	foreach($plugin->admin_tabs as $tab){
		if(!empty($tab['requirements']['user_ids']) && !in_array($kfm->user_id, $tab['requirements']['user_ids'])) continue;
		$kfm->addAdminTab(isset($tab['title'])? $tab['title'] : $plugin->title, $plugin->url().$tab['file'], isset($tab['stylesheet'])? $plugin->url.$tab['stylesheet'] : false);
	}
}
$getparams='?';
foreach($_GET as $key => $value)$getparams.=urlencode($key).'='.urlencode($value).'&';
$getparams=rtrim($getparams,'& ');
$sprefix='kfm_setting_'; // Until now a dummy prefix for settings. Maybe needed for future things. Also in the settings.php
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>KFM admin</title>

<link type="text/css" rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/ui-lightness/jquery-ui.css" />
<script src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load('jquery', '1.4');
google.load('jqueryui', '1.7');
</script>
<script type="text/javascript" src="themeswitchertool.js"></script>
<script type="text/javascript">
$(function(){
  $('.button').live('mouseover', function(){$(this).addClass('ui-state-hover')});
  $('.button').live('mouseout', function(){$(this).removeClass('ui-state-hover')});
  $('#tabscontainer').tabs();
  $('#switcher').themeswitcher({width:180});
});
function message(msg){
	jobj=$('#messages');
	jobj.html(msg);
  fadingMsg(jobj);
}
function error(msg){
  jobj = $('#errors');
  jobj.html(msg);
  fadingMsg(jobj);
	//message('Error: '+msg);
}
function fadingMsg(jobj){
	jobj.fadeIn();
	//jobj.animate({fontSize:"16px"},2500);
  setTimeout(function(){jobj.fadeOut()}, 2500);
	//jobj.fadeOut();
}

</script>
<script type="text/javascript">
//$ = jQuery; // Normal notation from now
</script>
<?php
foreach($kfm->admin_tabs as $tab){
	if($tab['stylesheet'])echo '<link rel="stylesheet" href="'.$tab['stylesheet'].'" type="text/css" />'."\n";
}
?>
<style type="text/css">
#general_info{
	float:right;
	width:300px;
	background-color:#ddd;
	text-align:right;
	padding-right:4px;
}
.admin_button{
	color:black;
	font-weight:bold;
}
#messages, #errors{
	display:none;
	position:absolute;
	width:270px;
	top:30px;
	right:30px;
  padding: 4px;
}
#password_div{
	margin:30px auto;
	padding:15px;
	width:650px;
}
#password_div label { position: absolute; text-align:left; width:222px; }
#password_div input, textarea { margin-left: 222px; }

.settings_container{
	margin-left:60px;
	margin-right:60px;
  padding: 10px;
}
.button{
	cursor:pointer;
	width:auto;
  padding: 3px;
}
#kfm_admin_users_table{
	margin-left:60px;
	margin-right:60px;
  width:auto;
}
.group_header{
	font-size:24px;
	font-weight:bold;
}
.user_setting{
}
.default_setting{
	color:#777;
}
.left{float:left;}
.right{float:right;}
.clearfix{clear:both;}
</style>
<style type="text/css">
#associations_container{
	margin-left:60px;
	margin-right:60px;
	padding:10px;
}
</style>
<style type="text/css">
.help_container{
	position:absolute;
	display:none;
	border:2px solid #bbb;
	background-color:#444;
	width:300px;
	padding:5px;
}
.help_title{
	margin:0 25px 0 0;
	background-color:#777;
	color:white;
}
.help_title h1{
	font-sze:10px;
	cursor:pointer;
	background-color:inherit;
}
.help_body{
	padding:10px;
	background-color:#777;
	color:white;
	margin-top:5px;
}
.help_body pre{
	display:block;
	margin: 3px 0px 3px 15px;
	padding: 2px 4px;
	font-family: verdana;
	background-color:#888;
}
.help_close{
	position:absolute;
  display:block;
  right: 5px;
	top:5px;
	border:1px solid #bbb;
	background-color:#777;
	color:white;
	padding: 0 2px;
	cursor:pointer;
}
</style>
<script type="text/javascript">
var sprefix='<?php echo $sprefix;?>';
</script>
<script type="text/javascript" src="settings.js"></script>
</head>
<body>
<div id="tabscontainer">
	<ul>
		<?php if($kfm->user_status==1) echo '<li><a href="users.php" title="Users tab"><span>Users</span></a></li>'; ?>
		<li><a href="settings.php" title="Settings tab"><span>Settings</span></a></li>
		<li><a href="password.php" title="Password tab"><span>Change password</span></a></li>
		<?php 
		if($kfm->user_status==1) echo '<li><a href="associations.php" title="File associations"><span>File associations</span></a></li>';
		foreach($kfm->admin_tabs as $tab){
			$active=isset($_GET['tab'])&&$_GET['tab']==$tab['title']?' class="ui-tabs-selected"':'';
			echo '<li'.$active.'><a href="'.$tab['page'].$getparams.'" title="'.$tab['title'].'"><span>'.$tab['title'].'</span></a></li>'."\n";
		}
		?>
	</ul>
</div>
<div id="switcher" class="right"></div>
<div id="general_info">
<?php echo $kfm->username;?> <a class="admin_button" href="<?php echo $kfm->setting('kfm_url');?>">To the File Manager</a>
</div>
<div id="messages" class="ui-state-highlight ui-corner-all"></div>
<div id="errors" class="ui-state-error ui-corner-all"></div>
</body>
</html>
