<?php /* Smarty version 2.6.26, created on 2011-07-02 13:40:41
         compiled from /home/microweber/public_html/system/application/stats/plugins/VisitsSummary/templates/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', '/home/microweber/public_html/system/application/stats/plugins/VisitsSummary/templates/index.tpl', 4, false),)), $this); ?>

<a name="evolutionGraph" graphId="VisitsSummarygetEvolutionGraph"></a>

<h2><?php if ($this->_tpl_vars['period'] == 'range'): ?><?php echo ((is_array($_tmp='Referers_Evolution')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

	<?php else: ?><?php echo ((is_array($_tmp='VisitsSummary_EvolutionOverLastPeriods')) ? $this->_run_mod_handler('translate', true, $_tmp, $this->_tpl_vars['periodsNames'][$this->_tpl_vars['period']]['plural']) : smarty_modifier_translate($_tmp, $this->_tpl_vars['periodsNames'][$this->_tpl_vars['period']]['plural'])); ?>
<?php endif; ?>
</h2>
<?php echo $this->_tpl_vars['graphEvolutionVisitsSummary']; ?>


<h2><?php echo ((is_array($_tmp='General_Report')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "VisitsSummary/templates/sparklines.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>