<?php
require_once('initialise.php');
require_once('functions.php');
if($kfm->user_status!=1)die ('error("No authorization aquired")');
if(!(isset($_POST['username'])&&isset($_POST['password'])))die('error("error with new user request");');
$res=db_fetch_all('SELECT id FROM '.KFM_DB_PREFIX.'users WHERE username="'.sql_escape($_POST['username']).'"');
if($res && count($res))die('error("Name already exists.");');
$kfm->db->query('INSERT INTO '.KFM_DB_PREFIX.'users (username, password,status) VALUES ("'.sql_escape($_POST['username']).'", "'.sha1($_POST['password']).'", 2)');
if(PEAR::isError($kfmdb))die('error("error with database interactions");');
$uhtml=user_row($kfm->db->lastInsertId(),$_POST['username'],2);
$uhtml=preg_replace('/\n/','',$uhtml);
$uhtml=str_replace("'","\'",$uhtml);
echo '$("#kfm_admin_users_table tbody").append(\''.$uhtml.'\');';
?>
message('user created');
