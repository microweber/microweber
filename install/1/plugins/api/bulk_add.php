<?php
if(isset($QUERY['action']) and $QUERY['action']==t('Create')) {
	include('controllers/bulk_add.php');
} else {
if(isset($task) and count($task)) { ?>
<form action="" method="post">

<?php foreach($new_tasks as $task) { ?>
<fieldset>
<legend><?=$task['name']?></legend>

<label>Name</label><input type="text" name="name[]" value="<?=$task['name']?>" /><br />
<label>Project</label><?php buildDropDownArray($projects,'project_id[]',$task['project_id']); ?><br />
<label>Description</label><textarea name="description[]" rows="5" cols="35"><?=$task['description']?></textarea><br />
<label>Url</label><input type="text" name="url[]" value="<?=$task['url']?>" /><br />
<label>Type</label><?php buildDropDownArray($all_types,'type[]',$task['type']); ?><br />

</fieldset>
<?php } ?>
<input name="action" value="Create" type='submit' />
</form>
<?php } 

}
?>