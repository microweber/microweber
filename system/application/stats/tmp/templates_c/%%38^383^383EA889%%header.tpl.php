<?php /* Smarty version 2.6.26, created on 2011-02-09 23:59:48
         compiled from CoreAdminHome/templates/header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'CoreAdminHome/templates/header.tpl', 5, false),array('function', 'loadJavascriptTranslations', 'CoreAdminHome/templates/header.tpl', 10, false),array('function', 'includeAssets', 'CoreAdminHome/templates/header.tpl', 14, false),array('function', 'ajaxRequestErrorDiv', 'CoreAdminHome/templates/header.tpl', 33, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Piwik &rsaquo; <?php echo ((is_array($_tmp='CoreAdminHome_Administration')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="generator" content="Piwik <?php echo $this->_tpl_vars['piwik_version']; ?>
" />
<link rel="shortcut icon" href="plugins/CoreHome/templates/images/favicon.ico" />

<?php echo smarty_function_loadJavascriptTranslations(array('plugins' => 'CoreAdminHome'), $this);?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/js_global_variables.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo smarty_function_includeAssets(array('type' => 'css'), $this);?>

<?php echo smarty_function_includeAssets(array('type' => 'js'), $this);?>


<!--[if IE]>
<link rel="stylesheet" type="text/css" href="themes/default/ieonly.css" />
<![endif]-->
</head>
<body>
<div id="root">
<?php if (! isset ( $this->_tpl_vars['showTopMenu'] ) || $this->_tpl_vars['showTopMenu']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/top_bar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<div id="header">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/logo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if ($this->_tpl_vars['showPeriodSelection']): ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/period_select.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/js_disabled_notice.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>

<?php echo smarty_function_ajaxRequestErrorDiv(array(), $this);?>

<?php if (! isset ( $this->_tpl_vars['showMenu'] ) || $this->_tpl_vars['showMenu']): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreAdminHome/templates/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>


<div id="content" class="admin">

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/header_message.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if (! empty ( $this->_tpl_vars['configFileNotWritable'] )): ?>
<div class="ajaxSuccess" style="display:normal">
	<?php echo ((is_array($_tmp='General_ConfigFileIsNotWritable')) ? $this->_run_mod_handler('translate', true, $_tmp, "(config/config.ini.php)", "<br/>") : smarty_modifier_translate($_tmp, "(config/config.ini.php)", "<br/>")); ?>

</div>
<?php elseif (strpos ( $this->_tpl_vars['url'] , 'updated=1' )): ?>	
<div class="ajaxSuccess" style="display:normal">
	<?php echo ((is_array($_tmp='General_YourChangesHaveBeenSaved')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

</div>
<?php endif; ?>
