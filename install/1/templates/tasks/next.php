<h1><?=t('Next Action %s',$action_title)?>...</h1>

<div id="container">
<?php
$count = 1;
foreach($task_data as $tsk) {
	$class = 'hidden';
	if($count == 1) $class = 'active';
?>
<div id="task-<?=$tsk['id']?>" class="<?=$class?>">
<h2><?=$tsk['name']?></h2>

<?php if($tsk['description'] or $tsk['url']) { ?>
<p><?=$tsk['description']?>

<?php if($tsk['url']) { print '<br /><a href="' . $tsk['url'] .'">Link</a>'; } ?></p>
<?php } ?>

<p><a href="edit.php?task=<?=$tsk['id']?>&amp;action=done" class="with-icon done"><?=t('Done')?></a></p>

<ul>
<li><?=t('Created On')?> : <?=date('d M, Y h:i a', strtotime($tsk['created_on']))?></li>
<li><?=t('Project')?> : <a href="list.php?project=<?=$tsk['project_id']?>"><?=$projects[$tsk['project_id']]?></a></li>
<li><?=t('Actions')?> : <a href="edit.php?task=<?=$tsk['id']?>" class="with-icon edit"><?=t('Edit')?></a> |
<a href="delete.php?task=<?=$tsk['id']?>" class="with-icon delete"><?=t('Delete')?></a> |
<a href="<?=getLink('list.php',array('action'=>'change_order'), true)?>"><?=t('Change Order')?></a>
</li> 
</ul>
</div>
<?php
	$count++;
}
?>

<div id="controls">
<a href="#" id="previous-task" class="with-icon previous"><?=t('Previous Task')?></a> | 
<a href="#" id="next-task" class="with-icon next"><?=t('Next Task')?></a>
</div>
</div>