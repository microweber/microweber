<h1><?=t('Added Tasks')?></h1>

<ul>
<?php foreach($added_tasks as $at) { ?>
<li><a href="edit.php?task=<?=$at['task_id']?>"><?=$at['task']?></a> 
<strong><?=t('in')?></strong> <a href="list.php?project=<?=$at['project_id']?>"><?=$projects[$at['project_id']]?></a></li>

<?php } ?>
</ul>

<a href="inbox.php"><?=t('Add More')?>...</a>
