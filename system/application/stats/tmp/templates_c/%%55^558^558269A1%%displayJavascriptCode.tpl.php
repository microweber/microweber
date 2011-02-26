<?php /* Smarty version 2.6.26, created on 2011-02-09 23:49:33
         compiled from Installation/templates/displayJavascriptCode.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'Installation/templates/displayJavascriptCode.tpl', 6, false),)), $this); ?>


<?php if (isset ( $this->_tpl_vars['displayfirstWebsiteSetupSuccess'] )): ?>

<span id="toFade" class="success">
	<?php echo ((is_array($_tmp='Installation_SetupWebsiteSetupSuccess')) ? $this->_run_mod_handler('translate', true, $_tmp, $this->_tpl_vars['displaySiteName']) : smarty_modifier_translate($_tmp, $this->_tpl_vars['displaySiteName'])); ?>

	<img src="themes/default/images/success_medium.png" />
</span>
<?php endif; ?>

<?php echo $this->_tpl_vars['trackingHelp']; ?>

<br/><br/>
<h2><?php echo ((is_array($_tmp='Installation_LargePiwikInstances')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
<?php echo ((is_array($_tmp='Installation_JsTagArchivingHelp')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>


<?php echo '
<style>
code {
	font-size:80%;
}
</style>
<script>
$(document).ready( function(){
	$(\'code\').click( function(){ $(this).select(); });
});
</script>

'; ?>