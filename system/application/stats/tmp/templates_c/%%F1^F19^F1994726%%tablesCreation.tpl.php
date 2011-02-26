<?php /* Smarty version 2.6.26, created on 2011-02-09 23:48:25
         compiled from Installation/templates/tablesCreation.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'Installation/templates/tablesCreation.tpl', 1, false),array('function', 'url', 'Installation/templates/tablesCreation.tpl', 12, false),)), $this); ?>
<h2><?php echo ((is_array($_tmp='Installation_Tables')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>

<?php if (isset ( $this->_tpl_vars['someTablesInstalled'] )): ?>
	<div class="warning"><?php echo ((is_array($_tmp='Installation_TablesWithSameNamesFound')) ? $this->_run_mod_handler('translate', true, $_tmp, "<span id='linkToggle'>", "</span>") : smarty_modifier_translate($_tmp, "<span id='linkToggle'>", "</span>")); ?>

	<img src="themes/default/images/warning_medium.png" />
	</div>
	<div id="toggle" style="display:none;color:#4F2410"><small><i><?php echo ((is_array($_tmp='Installation_TablesFound')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:
		<br /><?php echo $this->_tpl_vars['tablesInstalled']; ?>
 </i></small></div>
	
	<?php if (isset ( $this->_tpl_vars['showReuseExistingTables'] )): ?>
		<p><?php echo ((is_array($_tmp='Installation_TablesWarningHelp')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
		<p class="nextStep"><a href="<?php echo smarty_function_url(array('action' => $this->_tpl_vars['nextModuleName']), $this);?>
"><?php echo ((is_array($_tmp='Installation_TablesReuse')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 &raquo;</a></p>
	<?php else: ?>
		<p class="nextStep"><a href="<?php echo smarty_function_url(array('action' => $this->_tpl_vars['previousPreviousModuleName']), $this);?>
">&laquo; <?php echo ((is_array($_tmp='Installation_GoBackAndDefinePrefix')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a></p>
	<?php endif; ?>
	
	<p class="nextStep"><a href="<?php echo smarty_function_url(array('deleteTables' => 1), $this);?>
" id="eraseAllTables"><?php echo ((is_array($_tmp='Installation_TablesDelete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 &raquo;</a></p>
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['existingTablesDeleted'] )): ?>
	<div class="success"> <?php echo ((is_array($_tmp='Installation_TablesDeletedSuccess')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 
	<img src="themes/default/images/success_medium.png" /></div>
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['tablesCreated'] )): ?>
	<div class="success"> <?php echo ((is_array($_tmp='Installation_TablesCreatedSuccess')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 
	<img src="themes/default/images/success_medium.png" /></div>
<?php endif; ?>

<?php echo '
<script>
$(document).ready( function(){
	'; ?>

	var strConfirmEraseTables = "<?php echo ((is_array($_tmp='Installation_ConfirmDeleteExistingTables')) ? $this->_run_mod_handler('translate', true, $_tmp, "[".($this->_tpl_vars['tablesInstalled'])."]", "<br />") : smarty_modifier_translate($_tmp, "[".($this->_tpl_vars['tablesInstalled'])."]", "<br />")); ?>
 ";
	<?php echo '	
	
	// toggle the display of the tables detected during the installation when clicking
	// on the span "linkToggle"
	$("#linkToggle")
		.css("border-bottom","thin dotted #ff5502")
		
		.hover( function() {  
			 	 $(this).css({ cursor: "pointer"}); 
			  	},
			  	function() {  
			 	 $(this).css({ cursor: "auto"}); 
			  	})
		.css("border-bottom","thin dotted #ff5502")
		.click(function(){
			$("#toggle").toggle();} );
			
	$("#eraseAllTables")
		.click( function(){ 
			if(!confirm( strConfirmEraseTables ) ) 
			{ 
				return false; 
			}
		});
			
	;
});
</script>
'; ?>
