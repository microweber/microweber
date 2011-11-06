<?php /* Smarty version 2.6.26, created on 2011-11-04 10:34:20
         compiled from CoreHome/templates/datatable_cell.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'CoreHome/templates/datatable_cell.tpl', 2, false),array('function', 'logoHtml', 'CoreHome/templates/datatable_cell.tpl', 8, false),)), $this); ?>
<?php if (! $this->_tpl_vars['row']['idsubdatatable'] && $this->_tpl_vars['column'] == 'label' && ! empty ( $this->_tpl_vars['row']['metadata']['url'] )): ?>
<a target="_blank" href='<?php if (! in_array ( substr ( $this->_tpl_vars['row']['metadata']['url'] , 0 , 4 ) , array ( 'http' , 'ftp:' ) )): ?>http://<?php endif; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['metadata']['url'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
'>
	<?php if (empty ( $this->_tpl_vars['row']['metadata']['logo'] )): ?>
		<img class="link" width="10" height="9" src="themes/default/images/link.gif" />
	<?php endif; ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['column'] == 'label'): ?>
	<?php echo smarty_function_logoHtml(array('metadata' => $this->_tpl_vars['row']['metadata'],'alt' => $this->_tpl_vars['row']['columns']['label']), $this);?>

	<span class='label'>	
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['row']['columns'][$this->_tpl_vars['column']] )): ?><?php echo $this->_tpl_vars['row']['columns'][$this->_tpl_vars['column']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['defaultWhenColumnValueNotDefined']; ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['column'] == 'label'): ?>
</span>
<?php endif; ?>
<?php if (! $this->_tpl_vars['row']['idsubdatatable'] && $this->_tpl_vars['column'] == 'label' && ! empty ( $this->_tpl_vars['row']['metadata']['url'] )): ?>
	</a>
<?php endif; ?>