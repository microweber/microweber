<?php /* Smarty version 2.6.26, created on 2011-04-18 13:09:14
         compiled from CoreHome/templates/js_css_includes.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'includeAssets', 'CoreHome/templates/js_css_includes.tpl', 1, false),array('modifier', 'translate', 'CoreHome/templates/js_css_includes.tpl', 3, false),)), $this); ?>
<?php echo smarty_function_includeAssets(array('type' => 'css'), $this);?>

<?php echo smarty_function_includeAssets(array('type' => 'js'), $this);?>

<?php if (((is_array($_tmp='General_LayoutDirection')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)) == 'rtl'): ?>
<link rel="stylesheet" type="text/css" href="themes/default/rtl.css" />
<?php endif; ?>