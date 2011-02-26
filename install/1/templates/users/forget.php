<h1><?=t('Retrieve Password')?></h1>
 
<p><?=t('You should have specified an email address in your profile for this feature to work.')?></p>

<p><?=t('Please enter your Username OR your email address.')?></p>

<form action="" method="post">
<label for="username"><?=t('Username')?></label>
<input type="text" name="username" id="username" value="" />
<input type="submit" name="action" value="<?=t('Get Password')?>" />
</form>
<br /><br />

<form action="" method="post" id="fetch-by-email">
<label for="email"><?=t('Email')?></label>
<input type="text" name="email" id="email" value="" />
<input type="submit" name="action" value="<?=t('Get Password')?>" />
</form>

<p><?=t('The password will be send to the email you have specified in your profile.')?></p>