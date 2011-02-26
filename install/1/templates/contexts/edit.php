<h1><?=t('Edit Context \'%s\'',$context_data['name'])?>'</h1>

<form action="" method="post">
<label><?=t('Context')?></label><input type="text" name='name' value="<?=$context_data['name']?>" /><br />
<input type="hidden" name='id' value="<?=$context_data['id']?>" />
<input name="action" value="<?=t('Edit')?>" type='submit' />
</form>