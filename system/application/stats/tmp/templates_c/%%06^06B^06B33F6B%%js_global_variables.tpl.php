<?php /* Smarty version 2.6.26, created on 2011-04-18 13:09:14
         compiled from CoreHome/templates/js_global_variables.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'CoreHome/templates/js_global_variables.tpl', 5, false),)), $this); ?>
<script type="text/javascript">
	var piwik = <?php echo '{}'; ?>
;
	piwik.token_auth = "<?php echo $this->_tpl_vars['token_auth']; ?>
";
	piwik.piwik_url = "<?php echo $this->_tpl_vars['piwikUrl']; ?>
";
	<?php if (isset ( $this->_tpl_vars['userLogin'] )): ?>piwik.userLogin = "<?php echo ((is_array($_tmp=$this->_tpl_vars['userLogin'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";<?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['idSite'] )): ?>piwik.idSite = "<?php echo $this->_tpl_vars['idSite']; ?>
";<?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['siteName'] )): ?>piwik.siteName = "<?php echo ((is_array($_tmp=$this->_tpl_vars['siteName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";<?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['siteMainUrl'] )): ?>piwik.siteMainUrl = "<?php echo ((is_array($_tmp=$this->_tpl_vars['siteMainUrl'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";<?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['period'] )): ?>piwik.period = "<?php echo $this->_tpl_vars['period']; ?>
";<?php endif; ?>
		piwik.currentDateString = "<?php if (isset ( $this->_tpl_vars['date'] )): ?><?php echo $this->_tpl_vars['date']; ?>
<?php elseif (isset ( $this->_tpl_vars['endDate'] )): ?><?php echo $this->_tpl_vars['endDate']; ?>
<?php endif; ?>";
	<?php if (isset ( $this->_tpl_vars['startDate'] )): ?>piwik.startDateString = "<?php echo $this->_tpl_vars['startDate']; ?>
";<?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['endDate'] )): ?>piwik.endDateString = "<?php echo $this->_tpl_vars['endDate']; ?>
";<?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['minDateYear'] )): ?>piwik.minDateYear = <?php echo $this->_tpl_vars['minDateYear']; ?>
;<?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['minDateMonth'] )): ?>piwik.minDateMonth = parseInt("<?php echo $this->_tpl_vars['minDateMonth']; ?>
", 10);<?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['minDateDay'] )): ?>piwik.minDateDay = parseInt("<?php echo $this->_tpl_vars['minDateDay']; ?>
", 10);<?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['maxDateYear'] )): ?>piwik.maxDateYear = <?php echo $this->_tpl_vars['maxDateYear']; ?>
;<?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['maxDateMonth'] )): ?>piwik.maxDateMonth = parseInt("<?php echo $this->_tpl_vars['maxDateMonth']; ?>
", 10);<?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['maxDateDay'] )): ?>piwik.maxDateDay = parseInt("<?php echo $this->_tpl_vars['maxDateDay']; ?>
", 10);<?php endif; ?>
</script>