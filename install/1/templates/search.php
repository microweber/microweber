<h1><?=t('Search')?></h1>
 
<?php if(isset($QUERY['search'])) print t('Search Results for \'%s\'', $QUERY['search']); ?>

<?php if($all_tasks) { ?>
<h2><?=t('Tasks')?></h2>

<ul>
<?php // Show tasks selected
foreach($all_tasks as $task) { ?>
<li class="type-<?=strtolower(stripslashes($task['type']))?>"><a href="tasks/edit.php?task=<?=$task['id']?>"><?=$task['name']?></a>
<a href="<?=getLink("tasks/edit.php",array('task'=>$task['id'],'action'=>'done'),true)?>" class="icon done"><?=t('Done')?></a></li>
<?php } ?>
</ul>
<?php } ?>

<?php if($all_projects) { ?>
<h2><?=t('Projects')?></h2>

<ul>
<?php foreach($all_projects as $proj) { ?>
<li><a href="tasks/list.php?project=<?=$proj['id']?>"><?=$proj['name']?></a>&nbsp;
<a class="icon add" href="<?=$abs?>tasks/new.php?project_id=<?=$proj['id']?>"><?=t("New Task in %s",$proj['name'])?></a></li>
<?php } ?>
</ul>
<?php } ?>

<?php if($all_reminders) { ?>
<h2><?=t('Reminders')?></h2>

<ul>
<?php foreach($all_reminders as $rem) { ?>
<li><a href="reminders/edit.php?reminder=<?=$rem['id']?>"><?=$rem['name']?></a></li>
<?php } ?>
</ul>
<?php } ?>
