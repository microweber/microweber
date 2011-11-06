<?php /* Smarty version 2.6.26, created on 2011-07-03 13:52:27
         compiled from /home/microweber/public_html/system/application/stats/plugins/VisitFrequency/templates/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'postEvent', '/home/microweber/public_html/system/application/stats/plugins/VisitFrequency/templates/index.tpl', 1, false),array('modifier', 'translate', '/home/microweber/public_html/system/application/stats/plugins/VisitFrequency/templates/index.tpl', 4, false),)), $this); ?>
<?php echo smarty_function_postEvent(array('name' => 'template_headerVisitsFrequency'), $this);?>


<a name="evolutionGraph" graphId="VisitFrequencygetEvolutionGraph"></a>
<h2><?php echo ((is_array($_tmp='VisitFrequency_ColumnReturningVisits')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
<?php echo $this->_tpl_vars['graphEvolutionVisitFrequency']; ?>

<br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "VisitFrequency/templates/sparklines.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	
<?php echo smarty_function_postEvent(array('name' => 'template_footerVisitsFrequency'), $this);?>
