<h1><?=t('Edit Reminder \'%s\'',$reminder_data['name'])?></h1>

<form action="" method="post" id="reminder_frm">
<?php include("_form.php"); ?>

<input name="reminder" value="<?=$reminder_data['id']?>" type='hidden' />
<input name="action" value="<?=t('Edit')?>" type='submit' />
</form>