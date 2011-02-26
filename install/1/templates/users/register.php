<h1><?=t('User Registration')?></h1>

<form action="" method="post">
<label for="username"><?=t('User name')?></label><input type="text" name="username" id="username" value="<?=$user['username']?>" /><br />
<label for="password"><?=t('Password')?></label><input type="password" name="password" id="password" value="" /><br />
<label for="password"><?=t('Confirm Password')?></label><input type="password" name="confirm_password" id="confirm_password" value="" /><br />

<br />
<label for="name"><?=t('Name')?></label><input type="text" name="name" id="name" value="<?=$user['name']?>" /><?=t('[Optional]')?><br />
<label for="email"><?=t('Email')?></label><input type="text" name="email" id="email" value="<?=$user['email']?>" /><?=t('[Optional]')?><br />
 
<p><?=t('We will not give away your email address to any one. We just need it in case you forgot your password.')?></p>

<input type="submit" name="action" value="<?=t('Register')?>" />
</form>
