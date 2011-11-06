<?php /* Smarty version 2.6.26, created on 2011-06-14 13:11:53
         compiled from CoreHome/templates/datatable_actions.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'CoreHome/templates/datatable_actions.tpl', 7, false),array('modifier', 'escape', 'CoreHome/templates/datatable_actions.tpl', 13, false),)), $this); ?>
<div id="<?php echo $this->_tpl_vars['properties']['uniqueId']; ?>
">
	<div class="dataTableActionsWrapper">
	<?php if (isset ( $this->_tpl_vars['arrayDataTable']['result'] ) && $this->_tpl_vars['arrayDataTable']['result'] == 'error'): ?>
		<?php echo $this->_tpl_vars['arrayDataTable']['message']; ?>
 
	<?php else: ?>
		<?php if (count ( $this->_tpl_vars['arrayDataTable'] ) == 0): ?>
			<div class="pk-emptyDataTable"><?php echo ((is_array($_tmp='CoreHome_ThereIsNoDataForThisReport')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</div>
		<?php else: ?>
			<table cellspacing="0" class="dataTable dataTableActions"> 
			<thead>
			<tr>
			<?php $_from = $this->_tpl_vars['dataTableColumns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['head'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['head']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['column']):
        $this->_foreach['head']['iteration']++;
?>
				<th class="sortable <?php if (($this->_foreach['head']['iteration'] <= 1)): ?>first<?php elseif (($this->_foreach['head']['iteration'] == $this->_foreach['head']['total'])): ?>last<?php endif; ?>" id="<?php echo $this->_tpl_vars['column']; ?>
"><div id="thDIV"><?php if (! empty ( $this->_tpl_vars['columnDescriptions'][$this->_tpl_vars['column']] )): ?><label title='<?php echo ((is_array($_tmp=$this->_tpl_vars['columnDescriptions'][$this->_tpl_vars['column']])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
'><?php endif; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['columnTranslations'][$this->_tpl_vars['column']])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
<?php if (! empty ( $this->_tpl_vars['columnDescriptions'][$this->_tpl_vars['column']] )): ?></label><?php endif; ?></div></td>
			<?php endforeach; endif; unset($_from); ?>
			</tr>
			</thead>
			
			<tbody>
			<?php $_from = $this->_tpl_vars['arrayDataTable']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
			<tr <?php if ($this->_tpl_vars['row']['idsubdatatable']): ?>class="rowToProcess subActionsDataTable" id="<?php echo $this->_tpl_vars['row']['idsubdatatable']; ?>
"<?php else: ?> class="actionsDataTable rowToProcess"<?php endif; ?>>
			<?php $_from = $this->_tpl_vars['dataTableColumns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['column']):
?>
			<td>
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/datatable_cell.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			</td>
			<?php endforeach; endif; unset($_from); ?>
			</tr>
			<?php endforeach; endif; unset($_from); ?>
			</tbody>
		</table>
		<?php endif; ?>
	
		<?php if ($this->_tpl_vars['properties']['show_footer']): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/datatable_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/datatable_actions_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
	</div>
</div>