<?php /* Smarty version 2.6.26, created on 2011-02-09 23:48:29
         compiled from Installation/templates/generalSetup.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'Installation/templates/generalSetup.tpl', 1, false),)), $this); ?>
<h2><?php echo ((is_array($_tmp='Installation_GeneralSetup')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>

<?php if (isset ( $this->_tpl_vars['form_data'] )): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "default/genericForm.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>