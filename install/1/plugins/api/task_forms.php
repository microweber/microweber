<?php 
if(isset($task) and count($task)) {
	foreach($new_tasks as $task) { ?>
<form action="<?=$rel?>tasks/new.php" method="post">
<fieldset>
<legend><?=$task['name']?></legend>

<?php include('../../templates/tasks/_form.php'); ?>

<?php if(isset($task['file'])) { ?><input name="file" value="<?=$task['file']?>" type='hidden' /><?php } ?>
<input name="action" value="Create" type='submit' />
</fieldset>
</form>
<?php }
}
?>