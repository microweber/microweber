<?php /* Smarty version 2.6.26, created on 2011-02-09 23:47:07
         compiled from Installation/templates/allSteps.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'Installation/templates/allSteps.tpl', 4, false),)), $this); ?>
<ul>
<?php $_from = $this->_tpl_vars['allStepsTitle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['stepId'] => $this->_tpl_vars['stepName']):
?>
	<?php if ($this->_tpl_vars['currentStepId'] > $this->_tpl_vars['stepId']): ?>
	<li class="pastStep"><?php echo ((is_array($_tmp=$this->_tpl_vars['stepName'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</li>
	<?php elseif ($this->_tpl_vars['currentStepId'] == $this->_tpl_vars['stepId']): ?>
	<li class="actualStep"><?php echo ((is_array($_tmp=$this->_tpl_vars['stepName'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</li>
	<?php else: ?>
	<li class="futureStep"><?php echo ((is_array($_tmp=$this->_tpl_vars['stepName'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</li>
	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</ul>