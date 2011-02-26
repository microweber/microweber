<h1><?=t('User Modes')?></h1>

<p><?=t('There are two user modes')?>...</p>

<dl>
<dt><?=t('Single User Mode')?></dt>
<dd><?=t('No login - so minimal fuss involved. Perfect if you have installed this software at home.')?></dd>

<dt><?=t('Multi User Mode')?></dt>
<dd><?=t('Choose this if there are multiple users for the system. This mode enables user authentication - so you will have to login to the system. If you have installed this software in a website, this should be the way to go.')?></dd>
</dl>

<p><?=t('Current Mode is')?> : <?php if($single_user['status']) { ?>
<u><?=t('Single User Mode')?></u><br /><br />

<a href="#" onclick="$('user-info').style.display='block';"><?=t('Change to Multi User Mode.')?></a>

<form action="" method="post" id="user-info" style="display:none;">
<p><?=t('Enter the details for the first user. Make sure that you remember these details as you will need this information to login.')?></p>
<label for="username"><?=t('User name')?></label><input type="text" name="username" id="username" value="<?=$users[$first_user]?>" /><br />
<label for="password"><?=t('Password')?></label><input type="password" name="password" id="password" value="" /><br />
<label for="password"><?=t('Confirm Password')?></label><input type="password" name="confirm_password" id="confirm_password" value="" /><br />

<input type="hidden" name="mode" value="multi" />
<input type="submit" name="action" value="<?=t('Continue')?>" />
</form>

<?php } else { ?>
<u><?=t('Multi User Mode')?></u><br /><br />
<a href="?mode=single"><?=t('Change to Single User Mode.')?></a>
<?php } ?>
