<?php /* Smarty version 2.6.26, created on 2011-02-09 23:49:44
         compiled from Login/templates/header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'Login/templates/header.tpl', 5, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
	<title>Piwik &rsaquo; <?php echo ((is_array($_tmp='Login_LogIn')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="plugins/CoreHome/templates/images/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="plugins/Login/templates/login.css" />
<?php if (! $this->_tpl_vars['enableFramedLogins']): ?>
<?php echo '
	<style>body { display : none; }</style>
'; ?>

<?php endif; ?>
<?php if ($this->_tpl_vars['forceSslLogin']): ?>
<?php echo '
	<script>
		if(window.location.protocol !== \'https:\') {
			var url = window.location.toString();
			url = url.replace(/^http:/, \'https:\');
			window.location.replace(url);
		}
	</script>
'; ?>

<?php endif; ?>
<?php echo '
	<script type="text/javascript">
		function focusit() {
			var formLogin = document.getElementById(\'form_login\');
			if(formLogin)
			{
				formLogin.focus();
			}
		}
		window.onload = focusit;
	</script>
'; ?>

	<script type="text/javascript" src="libs/jquery/jquery.js"></script>
</head>
<body class="login">
<?php if (! $this->_tpl_vars['enableFramedLogins']): ?>
<?php echo '
	<script type="text/javascript">
		if(self == top) {
			var theBody = document.getElementsByTagName(\'body\')[0];
			theBody.style.display = \'block\';
		} else {
			top.location = self.location;
		}
	</script>
'; ?>

<?php endif; ?>
	<div id="logo">
		<a href="http://piwik.org" title="<?php echo $this->_tpl_vars['linkTitle']; ?>
"><span class="h1"><span style="color: rgb(245, 223, 114);">P</span><span style="color: rgb(241, 175, 108);">i</span><span style="color: rgb(241, 117, 117);">w</span><span style="color: rgb(155, 106, 58);">i</span><span style="color: rgb(107, 50, 11);">k</span> <span class="description"># <?php echo ((is_array($_tmp='General_OpenSourceWebAnalytics')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</span></span></a>
	</div>