<?php include (__DIR__.DS . 'header.php'); ?>
<h2>
	<a href="<?php echo api_url(); ?>user_social_login?provider=microweber">
		Use your Microweber account
	</a>
</h2>
<h3>or create a local account</h3>
<form action="<?php echo admin_url('mw_install_create_user') ?>" method="post">
	<div>
		<div class="label">Username</div>
		<input name="admin_username" />
	</div>
	<div>
		<div class="label">Email</div>
		<input name="admin_email" />
	</div>
	<div>
		<div class="label">Password</div>
		<input name="admin_password" type="password" />
	</div>
	<div>
		<input type="submit" value="Create Account" />
	</div>
</form>
<?php	include (__DIR__.DS . 'footer.php'); ?>