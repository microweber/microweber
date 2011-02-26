<?php /* Smarty version 2.6.26, created on 2011-02-09 23:47:07
         compiled from Installation/templates/welcome.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'Installation/templates/welcome.tpl', 1, false),array('function', 'url', 'Installation/templates/welcome.tpl', 41, false),)), $this); ?>
<h2><?php echo ((is_array($_tmp='Installation_Welcome')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>

<?php if ($this->_tpl_vars['newInstall']): ?>
<?php echo ((is_array($_tmp='Installation_WelcomeHelp')) ? $this->_run_mod_handler('translate', true, $_tmp, $this->_tpl_vars['totalNumberOfSteps']) : smarty_modifier_translate($_tmp, $this->_tpl_vars['totalNumberOfSteps'])); ?>

<?php else: ?>
<p><?php echo ((is_array($_tmp='Installation_ConfigurationHelp')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
<br />
<div class="error">
<?php echo $this->_tpl_vars['errorMessage']; ?>

</div>
<?php endif; ?>

<?php echo '
<script type="text/javascript">
<!--
$(function() {
	// client-side test for https to handle the case where the server is behind a reverse proxy
	if (document.location.protocol === \'https:\') {
		$(\'p.nextStep a\').attr(\'href\', $(\'p.nextStep a\').attr(\'href\') + \'&clientProtocol=https\');
	}

	// client-side test for broken tracker (e.g., mod_security rule)
	$(\'p.nextStep\').hide();
	$.ajax({
		url: \'piwik.php\',
		data: \'url=http://example.com\',
		complete: function() {
			$(\'p.nextStep\').show();
		},
		error: function(req) {
			$(\'p.nextStep a\').attr(\'href\', $(\'p.nextStep a\').attr(\'href\') + \'&trackerStatus=\' + req.status);
		}
	});
});
//-->
</script>
'; ?>


<?php if (! $this->_tpl_vars['showNextStep']): ?>
<p class="nextStep">
	<a href="<?php echo smarty_function_url(array(), $this);?>
"><?php echo ((is_array($_tmp='General_Refresh')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 &raquo;</a>
</p>
<?php endif; ?>