<h1><?=t('Contexts')?></h1>

<table>
<?php
$row = 0;
foreach($contexts as $id=>$name) {
$class = ($row++ % 2) ? 'even' : 'odd';
?>
<tr class="<?=$class?>"><td id="cell_<?=$id?>"><a class="with-icon context" href="../tasks/list.php?contexts[]=<?=$id?>"><?=$name?></a></td>
<td class="action"><a class="icon add" href="<?=$abs?>tasks/new.php?context=<?=$id?>"><?=t('New Task')?></a></td>
<td class="action"><a class="icon edit" href="edit.php?context=<?=$id?>"><?=t('Rename')?></a></td>
<td class="action"><a class="icon delete" href="delete.php?context=<?=$id?>"><?=t('Delete')?></a></td></tr>
<?php } ?>
</table>

<form action="new.php" id="new_item" method="post">
<label><?=t('New Context')?></label><input type="text" id="name" name='name' />
<input name="action" value="<?=t('Create')?>" type='submit' />
</form>
