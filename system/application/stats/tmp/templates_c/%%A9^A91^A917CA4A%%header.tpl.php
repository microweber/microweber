<?php /* Smarty version 2.6.26, created on 2011-04-18 13:14:55
         compiled from Login/templates/header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'Login/templates/header.tpl', 5, false),array('modifier', 'escape', 'Login/templates/header.tpl', 9, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
	<title>Piwik &rsaquo; <?php echo ((is_array($_tmp='Login_LogIn')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="plugins/CoreHome/templates/images/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="plugins/Login/templates/login.css" />
	<meta name="description" content="<?php echo ((is_array($_tmp=((is_array($_tmp='General_OpenSourceWebAnalytics')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
	
<?php if (isset ( $this->_tpl_vars['enableFrames'] ) && ! $this->_tpl_vars['enableFrames']): ?>
<?php echo '
	<style>body { display : none; }</style>
'; ?>

<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['forceSslLogin'] ) && $this->_tpl_vars['forceSslLogin']): ?>
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
<?php if (((is_array($_tmp='General_LayoutDirection')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)) == 'rtl'): ?>
<link rel="stylesheet" type="text/css" href="themes/default/rtl.css" />
<?php endif; ?>
</head>
<body class="login">
<?php if (isset ( $this->_tpl_vars['enableFrames'] ) && ! $this->_tpl_vars['enableFrames']): ?>
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
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "default/ie6.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div id="logo">
	<a href="http://piwik.org" title="<?php echo $this->_tpl_vars['linkTitle']; ?>
">
		<img src='themes/default/images/logo.png' width='200' style='margin-right:20px'>
		<div class="description"># <?php echo $this->_tpl_vars['linkTitle']; ?>
</div>
	</a>
	</div>