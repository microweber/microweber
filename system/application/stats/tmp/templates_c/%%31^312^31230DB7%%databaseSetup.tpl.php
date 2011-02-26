<?php /* Smarty version 2.6.26, created on 2011-02-09 23:47:58
         compiled from Installation/templates/databaseSetup.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'Installation/templates/databaseSetup.tpl', 1, false),)), $this); ?>
<h2><?php echo ((is_array($_tmp='Installation_DatabaseSetup')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>

<?php if (isset ( $this->_tpl_vars['errorMessage'] )): ?>
	<div class="error">
		<img src="themes/default/images/error_medium.png" />
		<?php echo ((is_array($_tmp='Installation_DatabaseErrorConnect')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:
		<br /><?php echo $this->_tpl_vars['errorMessage']; ?>

		
	</div>
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['form_data'] )): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "default/genericForm.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>