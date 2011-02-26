<?php
include('../common.php');
include('../includes/classes/SqlPager.php');

//Handle all the requested features here.
$wheres = array();
$params = array();
$special= '';
if(isset($QUERY['project']) and $QUERY['project']) {
	$wheres[]	= "project_id=$QUERY[project]";
	$special	.= ' '.t('in project').' <span class="title-project">"' . $projects[$QUERY['project']] . '"</span>';
	$params['project'] = $_REQUEST['project'];
	$project_name = $projects[$QUERY['project']];
}

//Contexts - this has a many to many relation - so it is the most complicated
if(isset($QUERY['contexts'])) {
	$all_context_ids = array();
	$all_contexts = array();
	//Get all the contexts from the url and find the tasks is included in all the given contexts.
	//That is - the more contexts you provide the lesser projects you will find
	$params['contexts'] = $QUERY['contexts'];
	foreach($QUERY['contexts'] as $context) {
		$all_contexts[] = $contexts[$context];
		$context_ids = $sql->getCol("SELECT task_id FROM ${config['db_prefix']}TaskContext WHERE context_id=$context");
	
		if(!$all_context_ids)
			$all_context_ids = $context_ids;
		else
			$all_context_ids = array_intersect($all_context_ids,$context_ids);
	}

	$tasks_in_context = array_values($all_context_ids);
	$special .= ' '.t('with context(s)').' <span class="title-context">"' . implode(', ',$all_contexts) . '"</span>';
	if(count($tasks_in_context)) //If some tasks are found with intersecting contexts, well and good.
		$wheres[]	= "id IN (" . implode(',',$tasks_in_context) . ")";
	else //If not, short-circut the query - giving a 0 will make sure that nothing is found
		$wheres[]	= "0";
}

//If the type is specified.
if(isset($QUERY['done'])) $QUERY['type'] = 'Done';
if(isset($QUERY['type'])) {
	$wheres[] = "type='$QUERY[type]'";
	if($QUERY['type'] == 'Done') $special	.= t('Tasks that are').' <span class="title-type">'.t('Completed').'</span>';
	else $special	.= t('Tasks in section').' <span class="title-type">'.t($QUERY['type']).'</span>';
	$params['type']	= $_REQUEST['type'];
}

//Date based choice
//Some extra things that I have added - not implmented yet
if(isset($QUERY['after']))		$wheres[] = "created_on>'$QUERY[after]'";
if(isset($QUERY['before']))		$wheres[] = "created_on<'$QUERY[before]'";

//Some more touchups needed
$special = trim($special);
$new_task_link	=	'<a class="with-icon tasks" href="new.php">'.t('Create a new task').' ' . strip_tags($special) . '</a>';
if(count($params)) {
	if(isset($params['project'])) $params['project_id'] = $params['project'];
	$new_task_link = '<a class="with-icon tasks" href="'. getLink('new.php',$params) .'">'.t('Add a new task').' '. strip_tags($special) .'</a>';
}

//Create the Query
$qry_tasks = "SELECT id,name,description,url,project_id,type,sort_order, "
				. "DATE_FORMAT(due_on,'$config[date_format]') AS due_on, "
				. "DATE_FORMAT(edited_on,'$config[date_format]') AS time "
				. "FROM ${config['db_prefix']}Task WHERE user_id='$_SESSION[user]'";
if(count($wheres)) {
	$qry_tasks.= " AND ";
	$qry_tasks.= implode(' AND ',$wheres); //Only 'and' supported right now
}
if(isset($QUERY['type']) and $QUERY['type'] =='Done') {
	$qry_tasks .= " ORDER BY completed_on DESC"; //The done stuff must be sorted a specific way
} else {
	$qry_tasks .= " ORDER BY type='Immediately' DESC, type='Someday/Maybe' DESC, type='Waiting' DESC, type='Idea' DESC, type='Done' DESC, sort_order ASC, due_on DESC, edited_on DESC";
}

//Save new order
if(isset($QUERY['action']) and $QUERY['action'] == 'save_order') {
	$order = array();
	for($i=0; $i<count($QUERY['task_id']); $i++) {
		$order[$QUERY['task_id'][$i]] = $QUERY['sort_order'][$i];
	}
	$Task->reOrder($order);
	$QUERY['action'] = 'change_order';
}

//We bring in the pager.
$options = array();
if(isset($_REQUEST['project'])) $options['project'] = $_REQUEST['project'];
if(isset($_REQUEST['contexts'])) $options['contexts'] = $_REQUEST['contexts'];

$pager = new SqlPager($qry_tasks,10);
$tasks = $pager->getPage();

$bbcode = new BBcode();

//Get the project name and context names into the $tasks array
for($i=0; $i<count($tasks); $i++) {
	$tasks[$i]['project_name'] = $projects[$tasks[$i]['project_id']];
	$tasks[$i]['contexts'] = $sql->getCol("SELECT context_id FROM ${config['db_prefix']}TaskContext WHERE task_id=".$tasks[$i]['id']);
	$tasks[$i]['description'] = $bbcode->Parse($tasks[$i]['description']);
}


//Change Order Settings
$change_order = false;
$colspan = 6;
if(isset($QUERY['action']) and $QUERY['action'] == 'change_order') {
	$colspan = 7;
	$change_order = true;
}

$template->addResource('bbcode.css','css');
$template->addResource('bbcode.js','js');

$template->addResource('_list.js','js');
render();