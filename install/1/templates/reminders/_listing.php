<?php if($reminders) { ?>
<table class="reminder-listing">
<tr class="row-info"><th class="reminder-name"><?=t('Name')?></th><th class="reminder-project"><?=t('Created On')?></th><th class="reminder-date"><?=t('Reminder Date')?></th>
<th class="action-actions" colspan="2"><?=t('Actions')?></th></tr>
<tr class="row-description"><th class="reminder-description" colspan="6"><?=t('Description')?></th></tr>
<tr class="spacer"><td colspan="6">&nbsp;</td></tr>

<?php
for($i=0;$i<count($reminders); $i++) {
	$reminder = $reminders[$i];
	$date = strtotime($reminder['day']);
	if(date('Y-m-d') == date('Y-m-d',$date)) $class='today';
	if(date('Y-m-d') <  date('Y-m-d',$date)) $class='future';
	if(date('Y-m-d') >  date('Y-m-d',$date)) $class='past';
?>
<tr class="row-info reminder-<?=$class?>"><td class="reminder-name"><a href="edit.php?reminder=<?=$reminder['id']?>"><?=$reminder['name']?></a></td>
<td class="reminder-project"><?=$reminder['created_on']?></td>
<td class="reminder-date"><?=$reminder['day']?></td>

<td class="action"><a class="icon edit" href="edit.php?reminder=<?=$reminder['id']?>"><?=t('Edit')?></a></td>
<td class="action action-delete"><a class="icon delete" href="delete.php?reminder=<?=$reminder['id']?>"><?=t('Delete')?></a></td></tr>

<?php if($reminder['description']) { ?>
<tr class="row-description reminder-<?=$class?>"><td class="reminder-description" colspan="6"><?=$reminder['description']?><br />

<?php }

if($i<count($reminders)) { ?>
<tr class="spacer"><td colspan="6">&nbsp;</td></tr>
<?php }
} ?>

<tr class="row-info"><th class="reminder-name"><?=t('Name')?></th><th class="reminder-project"><?=t('Created On')?></th><th class="reminder-date"><?=t('Reminder Date')?></th>
<th class="action-actions" colspan="2"><?=t('Actions')?></th></tr>
<tr class="row-description"><th class="reminder-description" colspan="6"><?=t('Description')?></th></tr>

</table>

<?php 
	showPager();

} else { ?>
<div class="info-message"><?=t('No reminders were found.')?></div>
<?php } ?>

<br />

<a href="new.php" class="with-icon reminder"><?=t('Create a new Reminder')?>...</a>