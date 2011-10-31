<?php /* Smarty version 2.6.26, created on 2011-07-03 11:41:45
         compiled from /home/microweber/public_html/system/application/stats/plugins/Goals/templates/add_new_goal.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', '/home/microweber/public_html/system/application/stats/plugins/Goals/templates/add_new_goal.tpl', 5, false),)), $this); ?>

<?php if ($this->_tpl_vars['userCanEditGoals']): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Goals/templates/add_edit_goal.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
<h2><?php echo ((is_array($_tmp='Goals_CreateNewGOal')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
<p>
<?php echo ((is_array($_tmp='Goals_NoGoalsNeedAccess')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

</p>
<p><?php echo ((is_array($_tmp='Goals_LearnMoreAboutGoalTrackingDocumentation')) ? $this->_run_mod_handler('translate', true, $_tmp, "<a href='?module=Proxy&action=redirect&url=http://piwik.org/docs/tracking-goals-web-analytics/' target='_blank'>", "</a>") : smarty_modifier_translate($_tmp, "<a href='?module=Proxy&action=redirect&url=http://piwik.org/docs/tracking-goals-web-analytics/' target='_blank'>", "</a>")); ?>

</p>
<?php endif; ?>