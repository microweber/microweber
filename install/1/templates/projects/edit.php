<h1><?=t('Edit Project \'%s\'',$project['name'])?></h1>

<form action="" method="post">
<label><?=t('Project')?></label><input type="text" name='name' value="<?=$project['name']?>" /><br />
<input type="hidden" name='id' value="<?=$project['id']?>" />
<input name="action" value="<?=t('Edit')?>" type='submit' />
</form>