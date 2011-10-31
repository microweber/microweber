<?php /* Smarty version 2.6.26, created on 2011-06-14 12:50:35
         compiled from CoreHome/templates/datatable_js.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'CoreHome/templates/datatable_js.tpl', 7, false),array('modifier', 'implode', 'CoreHome/templates/datatable_js.tpl', 7, false),)), $this); ?>
<?php if (! isset ( $this->_tpl_vars['dataTableClassName'] )): ?><?php $this->assign('dataTableClassName', 'dataTable'); ?><?php endif; ?>
<script type="text/javascript" defer="defer">
$(document).ready(function()<?php echo '{'; ?>
 
	dataTables['<?php echo $this->_tpl_vars['properties']['uniqueId']; ?>
'] = new <?php echo $this->_tpl_vars['dataTableClassName']; ?>
();
	dataTables['<?php echo $this->_tpl_vars['properties']['uniqueId']; ?>
'].param = <?php echo '{'; ?>
 
	<?php $_from = $this->_tpl_vars['javascriptVariablesToSet']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['value']):
        $this->_foreach['loop']['iteration']++;
?>
		'<?php echo ((is_array($_tmp=$this->_tpl_vars['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
': <?php if (is_array ( $this->_tpl_vars['value'] )): ?>'<?php echo ((is_array($_tmp=',')) ? $this->_run_mod_handler('implode', true, $_tmp, $this->_tpl_vars['value']) : implode($_tmp, $this->_tpl_vars['value'])); ?>
'<?php else: ?>'<?php echo $this->_tpl_vars['value']; ?>
'<?php endif; ?> <?php if (! ($this->_foreach['loop']['iteration'] == $this->_foreach['loop']['total'])): ?>,<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	<?php echo '};'; ?>

	dataTables['<?php echo $this->_tpl_vars['properties']['uniqueId']; ?>
'].init('<?php echo $this->_tpl_vars['properties']['uniqueId']; ?>
');
<?php echo '}'; ?>
);
</script>