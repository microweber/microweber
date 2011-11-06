<?php /* Smarty version 2.6.26, created on 2011-11-04 10:34:20
         compiled from VisitsSummary/templates/sparklines.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'sparkline', 'VisitsSummary/templates/sparklines.tpl', 2, false),array('modifier', 'translate', 'VisitsSummary/templates/sparklines.tpl', 2, false),array('modifier', 'sumtime', 'VisitsSummary/templates/sparklines.tpl', 10, false),)), $this); ?>
<div id='leftcolumn'>
	<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineNbVisits']), $this);?>
 <?php echo ((is_array($_tmp='VisitsSummary_NbVisits')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['nbVisits'])."</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['nbVisits'])."</strong>")); ?>
</div>
<?php if ($this->_tpl_vars['displayUniqueVisitors']): ?>
	<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineNbUniqVisitors']), $this);?>
 <?php echo ((is_array($_tmp='VisitsSummary_NbUniqueVisitors')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['nbUniqVisitors'])."</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['nbUniqVisitors'])."</strong>")); ?>
</div>
<?php endif; ?>
	<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineNbActions']), $this);?>
 <?php echo ((is_array($_tmp='VisitsSummary_NbActionsDescription')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['nbActions'])."</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['nbActions'])."</strong>")); ?>
</div>
	<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineActionsPerVisit']), $this);?>
 <?php echo ((is_array($_tmp='VisitsSummary_NbActionsPerVisit')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['nbActionsPerVisit'])."</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['nbActionsPerVisit'])."</strong>")); ?>
</div>
</div>
<div id='rightcolumn'>
	<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineAvgVisitDuration']), $this);?>
 <?php $this->assign('averageVisitDuration', ((is_array($_tmp=$this->_tpl_vars['averageVisitDuration'])) ? $this->_run_mod_handler('sumtime', true, $_tmp) : smarty_modifier_sumtime($_tmp))); ?> <?php echo ((is_array($_tmp='VisitsSummary_AverageVisitDuration')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['averageVisitDuration'])."</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['averageVisitDuration'])."</strong>")); ?>
</div>
	<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineBounceRate']), $this);?>
 <?php echo ((is_array($_tmp='VisitsSummary_NbVisitsBounced')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['bounceRate'])."%</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['bounceRate'])."%</strong>")); ?>
</div>
	<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineMaxActions']), $this);?>
 <?php echo ((is_array($_tmp='VisitsSummary_MaxNbActions')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['maxActions'])."</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['maxActions'])."</strong>")); ?>
</div>
</div>
<div style="clear:both;"></div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/sparkline_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
