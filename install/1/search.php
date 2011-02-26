<?php
include('./common.php');
include('includes/classes/ORM.php');
if(!isset($QUERY['search']) or !$QUERY['search']) showMessage("Please provide a search query", "index.php", 'search-status');

$search = $QUERY['search'];

$keywords = array_unique(preg_split("/[\s,\.\-]+/", $search)); //Split the search query to its individual keywords
$stop_words = array('I','a','about','an','are','as','at','be','by','for','from','how','in','is','it','la','of','on','or','that','the','this','to','was','what','when','where','who','will','with','the');
$keywords = array_diff($keywords, $stop_words); //We don't need the frequently used words in the keywords

if(!$keywords) showMessage('Please be more specific in your query','index.php','search-status');

//Make the necessary query fragments
$name = array();
$description = array();
foreach($keywords as $key) {
	$name[] = "name LIKE '%$key%'";
	$description[] = "description LIKE '%$key%'";
}

$wheres  = '((' . implode(' AND ', $name) . ') OR ';
$wheres .= '(' . implode(' AND ', $description) . ")) ";
$wheres .= "AND user_id='$_SESSION[user]'";

//Search for all Task with our keywords
$Task = new DBTable($config['db_prefix'].'Task');
$all_tasks = $Task->find(array(
	'select'	=>	array("id","name","type"),
	'where'		=>	$wheres,
	'order'		=>	'type, created_on DESC'
));

//Search for all Projects
$Project = new DBTable($config['db_prefix'].'Project');
$all_projects = $Project->find(array(
	'select'	=>	array("id","name"),
	'where'		=>	$wheres,
	'order'		=>	'created_on DESC'
));

//Search among all Reminders
$Reminder = new DBTable($config['db_prefix'].'Reminder');
$all_reminders= $Reminder->find(array(
	'select'	=>	array("id","name"),
	'where'		=>	$wheres,
	'order'		=>	'day DESC'
));

render();
