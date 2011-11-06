<?php /* Smarty version 2.6.26, created on 2011-07-03 13:52:27
         compiled from VisitFrequency/templates/sparklines.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'sparkline', 'VisitFrequency/templates/sparklines.tpl', 2, false),array('modifier', 'translate', 'VisitFrequency/templates/sparklines.tpl', 3, false),array('modifier', 'sumtime', 'VisitFrequency/templates/sparklines.tpl', 9, false),)), $this); ?>

<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineNbVisitsReturning']), $this);?>

<?php echo ((is_array($_tmp='VisitFrequency_ReturnVisits')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['nbVisitsReturning'])."</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['nbVisitsReturning'])."</strong>")); ?>
</div>
<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineNbActionsReturning']), $this);?>

<?php echo ((is_array($_tmp='VisitFrequency_ReturnActions')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['nbActionsReturning'])."</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['nbActionsReturning'])."</strong>")); ?>
</div>
<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineActionsPerVisitReturning']), $this);?>

 <?php echo ((is_array($_tmp='VisitFrequency_ReturnAvgActions')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['nbActionsPerVisitReturning'])."</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['nbActionsPerVisitReturning'])."</strong>")); ?>
</div>
<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineAvgVisitDurationReturning']), $this);?>

 <?php $this->assign('avgVisitDurationReturning', ((is_array($_tmp=$this->_tpl_vars['avgVisitDurationReturning'])) ? $this->_run_mod_handler('sumtime', true, $_tmp) : smarty_modifier_sumtime($_tmp))); ?>
 <?php echo ((is_array($_tmp='VisitFrequency_ReturnAverageVisitDuration')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['avgVisitDurationReturning'])."</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['avgVisitDurationReturning'])."</strong>")); ?>
</div>
<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineBounceRateReturning']), $this);?>

 <?php echo ((is_array($_tmp='VisitFrequency_ReturnBounceRate')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['bounceRateReturning'])."%</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['bounceRateReturning'])."%</strong>")); ?>
 </div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/sparkline_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>