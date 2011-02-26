<h1><?=t('Edit Task \'%s\'',$task['name'])?></h1>

<a class="with-icon done" title="<?=t('Mark Task as Done')?>" href="edit.php?task=<?=$task['id']?>&amp;action=done"><?=t('Mark Task as Done')?></a><br />
<a class="with-icon delete" title="<?=t('Delete Task')?>" href="delete.php?task=<?=$task['id']?>"><?=t('Delete Task')?></a><br />

<form action="" method="post" id="task_frm">
<?php include("_form.php"); ?>

<span class="item-name"><?=t('Created On')?></span><span class="item-value"><?=$task['created_on']?></span><br />
<?php if($task['completed_on']) { ?>
<span class="item-name"><?=t('Completed On')?></span><span class="item-value"><?=$task['completed_on']?></span><br />
<?php } ?>

<input name="task" value="<?=$task['id']?>" type='hidden' />
<input name="action" value="<?=t('Save')?>" type='submit' />
</form>

