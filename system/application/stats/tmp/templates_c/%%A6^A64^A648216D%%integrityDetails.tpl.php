<?php /* Smarty version 2.6.26, created on 2011-02-09 23:47:37
         compiled from Installation/templates/integrityDetails.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'Installation/templates/integrityDetails.tpl', 4, false),)), $this); ?>
<?php if (! isset ( $this->_tpl_vars['warningMessages'] )): ?>
<?php $this->assign('warningMessages', $this->_tpl_vars['infos']['integrityErrorMessages']); ?>
<?php endif; ?>
<div id="integrity-results" title="<?php echo ((is_array($_tmp='Installation_SystemCheckFileIntegrity')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" style="display:none; font-size: 62.5%;">
	<table>
	<?php $_from = $this->_tpl_vars['warningMessages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['msg']):
?>
		<tr><td><?php echo $this->_tpl_vars['msg']; ?>
</td></tr>
	<?php endforeach; endif; unset($_from); ?>
	</table>
</div>
<script type="text/javascript">
<?php echo '<!--
$(function() {
	$("#integrity-results").dialog({
		bgiframe: true,
		modal: true,
		autoOpen: false,
		width: 600,
		buttons: {
			Ok: function() {
			$(this).dialog(\'close\');
			}
		}
	});
});
$(\'#more-results\').click(function() {
	$(\'#integrity-results\').dialog(\'open\');
})
.hover(
	function(){ 
		$(this).addClass("ui-state-hover"); 
	},
	function(){ 
		$(this).removeClass("ui-state-hover"); 
	}
).mousedown(function(){
	$(this).addClass("ui-state-active"); 
})
.mouseup(function(){
		$(this).removeClass("ui-state-active");
});
//-->'; ?>

</script>