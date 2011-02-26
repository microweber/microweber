<label><?=t('Name')?></label><input type="text" name="name" id="name" value="<?=$task['name']?>" /><br />
<label><?=t('Project')?></label><?php $html->buildDropDownArray($projects,'project_id',$task['project_id']); ?><br />
<label><?=t('Description')?></label><textarea name="description" rows="5" cols="35"><?=$task['description']?></textarea><br />
<label><?=t('Due Date')?></label><input type="text" name="due_on" id="due_on" value="<?=$task['due_on']?>" /><br />
<label><?=t('Url')?></label><input type="text" name="url" value="<?=$task['url']?>" /><br />
<label><?=t('Type')?></label><?php $html->buildDropDownArray($all_types,'type',$task['type']); ?><br />

<label><?=t('Contexts')?></label>
<div class="checkboxs">
<?php foreach($contexts as $id=>$name) { ?>
<span class="context-selector"><label for="context_<?=$id?>"><?=$name?></label>
<input type="checkbox" name="contexts[]" value="<?=$id?>" id="context_<?=$id?>"<?php
	if(isset($task['contexts']))
		if(in_array($id,$task['contexts'])) print ' checked="checked"';
?> /></span>

<?php } ?>
</div>

<?php if(isset($QUERY['project_id'])) { ?>
<input type="hidden" name="project" value="<?=$QUERY['project_id']?>" />
<?php } ?>

<br /><br /> 
