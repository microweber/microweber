<?php /* Smarty version 2.6.26, created on 2011-02-09 23:47:58
         compiled from default/genericForm.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'default/genericForm.tpl', 4, false),)), $this); ?>
<?php if ($this->_tpl_vars['form_data']['errors']): ?>
	<div class="warning">
		<img src="themes/default/images/warning_medium.png">
		<strong><?php echo ((is_array($_tmp='Installation_PleaseFixTheFollowingErrors')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</strong>
		<ul>
			<?php $_from = $this->_tpl_vars['form_data']['errors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
				<li><?php echo $this->_tpl_vars['data']; ?>
</li>
			<?php endforeach; endif; unset($_from); ?>
		</ul>	
	</div>
<?php endif; ?>

<form <?php echo $this->_tpl_vars['form_data']['attributes']; ?>
>
	<div class="centrer">
		<table class="centrer">
			<?php $_from = $this->_tpl_vars['element_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['fieldname']):
?>
				<?php if ($this->_tpl_vars['form_data'][$this->_tpl_vars['fieldname']]['type'] == 'checkbox'): ?>
					<tr>
						<td colspan=2><?php echo $this->_tpl_vars['form_data'][$this->_tpl_vars['fieldname']]['html']; ?>
</td>
					</tr>
				<?php elseif ($this->_tpl_vars['form_data'][$this->_tpl_vars['fieldname']]['label']): ?>
					<tr>
						<td><?php echo $this->_tpl_vars['form_data'][$this->_tpl_vars['fieldname']]['label']; ?>
</td>
						<td><?php echo $this->_tpl_vars['form_data'][$this->_tpl_vars['fieldname']]['html']; ?>
</td>
					</tr>
				<?php elseif ($this->_tpl_vars['form_data'][$this->_tpl_vars['fieldname']]['type'] == 'hidden'): ?>
					<tr>
						<td colspan=2><?php echo $this->_tpl_vars['form_data'][$this->_tpl_vars['fieldname']]['html']; ?>
</td>
					</tr>
				<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
		</table>
	</div>

	<?php echo $this->_tpl_vars['form_data']['submit']['html']; ?>

</form>