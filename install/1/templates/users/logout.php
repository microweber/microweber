<h1><?=t('Logged Out')?></h1>

<p><a href="register.php"><?=t('Register')?></a></p>

<form action="login.php" method="post">
<label for="username"><?=t('User name')?></label>
<input type="text" name="username" id="username" value="<?=$_REQUEST['username']?>" /><br />

<label for="password"><?=t('Password')?></label>
<input type="password" name="password" id="password" value="" /><br />

<label for="remember"><?=t('Remember Me')?></label>
<input type="checkbox" name="remember" id="remember" value="1" checked="checked" /><br />

<input type="submit" name="action" value="<?=t('Login')?>" />
</form>

<a href="register.php"><?=t('Sign up')?></a> | <a href="forget.php"><?=t('Forgot Password?')?></a>