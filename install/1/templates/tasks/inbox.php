<h1><?=t('Inbox')?></h1>

<form action="add.php" method="post">
<textarea id="task" name="task" rows="10" cols="70"></textarea><br />
<?=t('Project')?> <?php $html->buildDropDownArray($project_names, 'project'); ?><br />

<input name="action" value="<?=t('Parse')?>" type='submit' />
<?php if(isset($QUERY['layout'])) { ?>
<input name="layout" value="<?=$PARAM['layout']?>" type='hidden' />
<?php } ?>
</form>

<p><?=t('Just enter your tasks as pure text. I will try to find some structure in them. Some general rules...')?></p>
<ul id="inbox-rules">
<li><?=t('Seperate each task with a new line(Enter key)')?></li>
<li><?=t('If you select a project in the dropdown, all the tasks will go to that project.')?></li>
<li><?=t('If you select "Auto Select" as the project and include the name of the project inside the task, I will put the task in that project.')?></li>
<li><?=t('If I cannot find a project, it will be in the default project(Misc).')?></li>
</ul>
