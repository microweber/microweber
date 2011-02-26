<h1><?=t('Login')?></h1>

<form action="" method="post">
<label for="username"><?=t('User name')?></label>
<input type="text" name="username" id="username" value="<?=isset($_REQUEST['username'])?$_REQUEST['username']:''?>" /><br />

<label for="password"><?=t('Password')?></label>
<input type="password" name="password" id="password" value="" /><br />

<label for="remember"><?=t('Remember Me')?></label>
<input type="checkbox" name="remember" id="remember" value="1" checked="checked" /><br />

<input type="submit" name="action" value="<?=t('Login')?>" />
</form>

<a href="register.php"><?=t('Sign up')?></a> | <a href="forget.php"><?=t('Forgot Password?')?></a> | <a href="../about.php"><?=t('About Nexty')?>...</a>