<h1><?=t('Projects')?></h1>

<table>
<?php 
$row = 0;
foreach($projects as $id=>$name) {
$class = ($row++ % 2) ? 'even' : 'odd';
?>
<tr class="<?=$class?>"><td id="cell_<?=$id?>"><a class="with-icon project" href="../tasks/list.php?project=<?=$id?>"><?=$name?></a></td>
<td class="action"><a class="icon add" href="<?=$abs?>tasks/new.php?project_id=<?=$id?>"><?=t('New Task')?></a></td>
<td class="action"><a class="icon edit" href="edit.php?project=<?=$id?>"><?=t('Rename')?></a></td>
<td class="action"><?php if($id != $default_project) { ?>
<a class="icon delete" href="delete.php?project=<?=$id?>"><?=t('Delete')?></a>
<?php } else { echo "&nbsp;"; } ?></td></tr>
<?php } ?>
</table>
<?php showPager(); ?>

<form action="new.php" id="new_item" method="post">
<label><?=t('New Project')?></label><input type="text" id="name" name='name' />
<input name="action" value="<?=t('Create')?>" type='submit' />
</form>
