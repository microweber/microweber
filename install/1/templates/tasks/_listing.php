<?php if($tasks) { 

if(isset($QUERY['project']) and $QUERY['project']) { ?>
<a class="with-icon next-task" href="<?=getLink("next.php", array("project"=>$QUERY['project']), false)?>"><?=t('Next Task in').' '.$project_name ?></a><spacer type="block" width="20px"/>
<a class="with-icon tasks" href="<?=$abs?>tasks/new.php?project_id=<?=$QUERY['project']?>"><?=t("New Task in %s",$project_name)?></a><spacer type="block" width="20px"/>
<?php }
if(isset($QUERY['contexts']) and $QUERY['contexts'][0]) { ?>
<a class="with-icon next-task" href="next.php?context=<?=$QUERY['contexts'][0]?>"><?=t('Next Task in %s Context', $all_contexts[0])?></a><spacer type="block" width="20px"/>
<a class="with-icon tasks" href="<?=$abs?>tasks/new.php?context=<?=$QUERY['contexts'][0]?>"><?=t("New Task in %s",$all_contexts[0])?></a><spacer type="block" width="20px"/>
<?php }
if(isset($QUERY['type']) and $QUERY['type']) { ?>
<a class="with-icon tasks" href="<?=$abs?>tasks/new.php?type=<?=$QUERY['type']?>"><?=t("New Task in '%s' section",t($QUERY['type']))?></a><spacer type="block" width="20px"/>
<?php } ?>

<form action="" method="post">
<table class="task-listing">
<tr class="row-info"><th class="task-name"><?=t('Task')?></th><th class="task-project"><?=t('Project')?></th>
<?php if($change_order) { ?><th class="task-order">
<input type="image" name="action" value="save_order" src="../images/save.png" alt="<?=t('Save Sort Order')?>" title="<?=t('Save Sort Order')?>" /> 
<?=t('Order')?></th><?php } ?>

<th class="task-date"><?=t('Last Edit')?></th>
<th class="action-actions" colspan="3"><?=t('Actions')?></th></tr>
<tr class="row-description"><th class="task-description" colspan="7"><?=t('Description')?></th></tr>
<tr class="spacer"><td colspan="<?=$colspan?>">&nbsp;</td></tr>

<?php 
for($i=0;$i<count($tasks); $i++) { 
	$task = $tasks[$i];
?>
<tr class="row-info type-<?=strtolower($task['type'])?>"><td class="task-name"><a href="edit.php?task=<?=$task['id']?>"><?=stripslashes($task['name'])?></a></td>
<td class="task-project"><a href="list.php?project=<?=$task['project_id']?>"><?=$task['project_name']?></a></td>
<?php if($change_order) { ?><td class="task-order"><input type="text" name="sort_order[]" value="<?=$task['sort_order']?>" size="2" />
<input type="hidden" name="task_id[]" value="<?=$task['id']?>" /></td><?php } ?>

<td class="task-date"><?=$task['time']?></td>

<td class="action"><a class="icon done" title="<?=t('Mark Task as Done')?>" <?php if($task['type'] != 'Done') { ?> href="edit.php?task=<?=$task['id']?>&amp;action=done"<?php } ?>><?=t('Done')?></a></td>
<td class="action"><a class="icon edit" title="<?=t('Edit Task')?>" href="edit.php?task=<?=$task['id']?>"><?=t('Edit')?></a></td>
<td class="action action-delete"><a class="icon delete" title="<?=t('Delete Task')?>" href="delete.php?task=<?=$task['id']?>"><?=t('Delete')?></a></td></tr>

<tr class="row-description type-<?=strtolower(stripslashes($task['type']))?>"><td class="task-description" colspan="<?=$colspan?>"><?=stripslashes($task['description'])?>
<?php if($task['url']) print '<br /><a href="'.$task['url'].'">Link...</a>'; ?></td></tr>

<?php
if($i<count($tasks)) { ?>
<tr class="spacer"><td colspan="<?=$colspan?>">&nbsp;</td></tr>
<?php }
} ?>

<tr class="row-info"><th class="task-name"><?=t('Task')?></th><th class="task-project"><?=t('Project')?></th>
<?php if($change_order) { ?><th class="task-order">
<input type="image" name="action" value="save_order" src="../images/save.png" alt="<?=t('Save Sort Order')?>" title="<?=t('Save Sort Order')?>" />
<?=t('Order')?></th><?php } ?><th class="task-date"><?=t('Last Edit')?></th>
<th class="action-actions" colspan="3"><?=t('Actions')?></th></tr>
<tr class="row-description"><th class="task-description" colspan="<?=$colspan?>"><?=t('Description')?></th></tr>
</table>
</form>
<?php
	showPager();
} else { ?>
<div class="info-message"><?=t('No tasks found. Lucky you!')?></div>
<?php
if(isset($QUERY['project']) and $QUERY['project']) { ?>
<br/><a class="with-icon tasks" href="<?=$abs?>tasks/new.php?project_id=<?=$QUERY['project']?>"><?=t("New Task in %s",$project_name)?></a><spacer type="block" width="20px"/>
<?php }
if(isset($QUERY['type']) and $QUERY['type']) { ?>
<br><a class="with-icon tasks" href="<?=$abs?>tasks/new.php?type=<?=$QUERY['type']?>"><?=t("New Task in '%s' section",t($QUERY['type']))?></a><spacer type="block" width="20px"/>
<?php }
if(isset($QUERY['contexts']) and $QUERY['contexts'][0]) { ?>
<br><a class="with-icon tasks" href="<?=$abs?>tasks/new.php?context=<?=$QUERY['contexts'][0]?>"><?=t("New Task in %s",$all_contexts[0])?></a><spacer type="block" width="20px"/>
<?php }
} ?>
<br />