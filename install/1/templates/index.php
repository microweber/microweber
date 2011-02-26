<h1><?=t('Stuff to do')?></h1>

<?php if(count($tasks)) { ?>
<ul>
<?php foreach($tasks as $task) { ?>
<li><a href="<?=getLink("tasks/edit.php",array('task'=>$task['id']), true)?>"><?=$task['name']?></a> 
(<a href="<?=getLink("tasks/list.php",array('project'=>$task['project_id']), true)?>"><?=$task['project_name']?></a>)
<a href="<?=getLink("tasks/edit.php",array('task'=>$task['id'],'action'=>'done'),true)?>" class="icon done"><?=t('Done')?></a> 
</li>

<?php } ?>
</ul>

<?php } else { ?>
<p><?=t('Nothin\' to do')?>...<br />
<?=t('Ya-hooo!')?></p>
<?php } ?>

<ul id="task-actions">
<li><a href="<?=getLink("tasks/inbox.php",array(),true)?>" class="with-icon inbox"><?=t('Inbox')?>...</a></li>
<li><a href="<?=getLink("tasks/new.php",array(),true)?>" class="with-icon tasks"><?=t('New Task')?>...</a></li>
<li><a href="<?=getLink("tasks/next.php",array(),true)?>" class="with-icon next-task"><?=t('Next Action')?>...</a></li>
</ul>