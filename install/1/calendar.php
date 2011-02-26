<?php
include('./common.php');
include('./includes/classes/Calendar.php');

$calendar = new Calendar('day');
$calendar->limit = array();

$curmonth = ($calendar->month < 10) ? "0$calendar->month" : $calendar->month;
//Get all the tasks and reminders for a whole month
$all_tasks = $sql->getAll("SELECT Task.id AS task_id,Task.name AS task_name,Project.id AS project_id,Project.name AS project_name, "
				. "DATE_FORMAT(Task.created_on,'%Y-%m-%d') AS created_on, DATE_FORMAT(Task.due_on,'%Y-%m-%d') AS due_on "
				. "FROM ${config['db_prefix']}Task As Task INNER JOIN ${config['db_prefix']}Project AS Project ON Task.project_id=Project.id "
				. "WHERE DATE_FORMAT(Task.created_on,'%Y-%m')='{$calendar->year}-$curmonth' AND Project.user_id='$_SESSION[user]'");
$all_reminders = $sql->getAll("SELECT id,name,day FROM ${config['db_prefix']}Reminder WHERE DATE_FORMAT(day,'%Y-%m')='{$calendar->year}-$curmonth' AND user_id='$_SESSION[user]'");

function day($year, $curmonth, $da) {
	global $all_tasks, $all_reminders;
	
	$d = intval($da);
	$month = intval($curmonth);
	
	//Find what all will happen on that day.
	$this_day = "$year-$curmonth-$da";
	
	$tasks_created_on_this_day = getThisDay($all_tasks, 'created_on', $this_day);
	$tasks_due_on_this_day = getThisDay($all_tasks, 'due_on', $this_day);
	$reminders_for_this_day = getThisDay($all_reminders, 'day', $this_day);

	if($tasks_created_on_this_day or $tasks_due_on_this_day or $reminders_for_this_day) {
		if($tasks_created_on_this_day) {
			foreach($tasks_created_on_this_day as $task) {
				$name = stripslashes($task['task_name']);
				print "<span class='calendar-row task created-on'><a href='tasks/edit.php?task=$task[task_id]'>$name</a> (<a href='tasks/list.php?project=$task[project_id]'>$task[project_name]</a>)</span><br />";
			}
		}
		if($tasks_due_on_this_day) {
			foreach($tasks_due_on_this_day as $task) {
				$name = stripslashes($task['task_name']);
				print "<span class='calendar-row task due-on'><a href='tasks/edit.php?task=$task[task_id]'>$name</a> (<a href='tasks/list.php?project=$task[project_id]'>$task[project_name]</a>)</span><br />";
			}
		}
		if($reminders_for_this_day) {
			foreach($reminders_for_this_day as $reminder) {
				$name = stripslashes($reminder['name']);
				print "<span class='calendar-row'><a class='with-icon reminder' href='reminders/edit.php?reminder=$reminder[id]'>$name</a></span><br />";
			}
		}
	}
}

function getThisDay($all, $index, $day) {
	$stuff_for_this_day = array();
	foreach($all as $item) {
		if($item[$index] === $day) {
			array_push($stuff_for_this_day, $item);
		}
	}
	return $stuff_for_this_day;
}

render();