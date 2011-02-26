<h1><?=t('Edit Profile for %s',$user['username'])?></h1>

<form action="" method="post" id="user-details">
<fieldset>
<legend><?=t('Profile')?></legend>

<label for="username"><?=t('User name')?></label><strong><?=$user['username']?></strong><br />
<label for="password"><?=t('Password')?></label><a id="change_password_question" href="javascript:changePassword();"><?=t('Change password?')?></a>
<input style="display:none;" type="password" name="password" id="password" value="" /><br />

<br />
<label for="name"><?=t('Name')?></label><input type="text" name="name" id="name" value="<?=$user['name']?>" /><?=t('[Optional]')?><br />
<label for="email"><?=t('Email')?></label><input type="text" name="email" id="email" value="<?=$user['email']?>" /><?=t('[Optional]')?><br />

<input type="submit" name="action" value="<?=t('Edit Profile')?>" />
</fieldset>
</form>

<form action="" method="post">
<fieldset>
<legend><?=t('Delete User')?></legend>

<p class="warning with-icon"><?=t('Warning : Deleting this user will remove the profile, projects, contexts and the tasks of the current user from the system. This action is irreversable.')?></p>

<input type="submit" name="action" value="<?=t('Delete This User')?>" id="delete" />
</fieldset>
</form>
