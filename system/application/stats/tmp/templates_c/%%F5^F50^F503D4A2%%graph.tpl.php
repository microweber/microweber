<?php /* Smarty version 2.6.26, created on 2011-04-18 13:09:23
         compiled from CoreHome/templates/graph.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'CoreHome/templates/graph.tpl', 6, false),array('modifier', 'escape', 'CoreHome/templates/graph.tpl', 6, false),)), $this); ?>
<div id="<?php echo $this->_tpl_vars['properties']['uniqueId']; ?>
">
	<div class="<?php if ($this->_tpl_vars['graphType'] == 'evolution'): ?>dataTableGraphEvolutionWrapper<?php else: ?>dataTableGraphWrapper<?php endif; ?>">

	<?php if ($this->_tpl_vars['flashParameters']['isDataAvailable'] || ! $this->_tpl_vars['flashParameters']['includeData']): ?>
		<div><div id="<?php echo $this->_tpl_vars['chartDivId']; ?>
">
			<?php echo ((is_array($_tmp='General_RequiresFlash')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 >= <?php echo $this->_tpl_vars['flashParameters']['requiredFlashVersion']; ?>
. <a target="_blank" href="?module=Proxy&action=redirect&url=<?php echo ((is_array($_tmp='http://piwik.org/faq/troubleshooting/#faq_53')) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"><?php echo ((is_array($_tmp='General_GraphHelp')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
		</div></div>
		<script type="text/javascript">
<!--
			<?php if ($this->_tpl_vars['flashParameters']['includeData']): ?>
			piwikHelper.OFC.set("<?php echo $this->_tpl_vars['chartDivId']; ?>
", '<?php echo $this->_tpl_vars['flashParameters']['data']; ?>
');
			<?php endif; ?>
			swfobject.embedSWF(
				"<?php echo $this->_tpl_vars['flashParameters']['ofcLibraryPath']; ?>
open-flash-chart.swf?piwik=<?php echo $this->_tpl_vars['piwik_version']; ?>
",
				"<?php echo $this->_tpl_vars['chartDivId']; ?>
",
				"<?php echo $this->_tpl_vars['flashParameters']['width']; ?>
", "<?php echo $this->_tpl_vars['flashParameters']['height']; ?>
",
				"<?php echo $this->_tpl_vars['flashParameters']['requiredFlashVersion']; ?>
",
				"<?php echo $this->_tpl_vars['flashParameters']['swfLibraryPath']; ?>
expressInstall.swf",
				<?php echo '{'; ?>

					"<?php if ($this->_tpl_vars['flashParameters']['includeData']): ?>x-<?php endif; ?>data-file":"<?php echo ((is_array($_tmp=$this->_tpl_vars['urlGraphData'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
",
				<?php if ($this->_tpl_vars['flashParameters']['includeData']): ?>
					"id":"<?php echo $this->_tpl_vars['chartDivId']; ?>
",
				<?php endif; ?>
					"loading":"<?php echo ((is_array($_tmp=((is_array($_tmp='General_Loading_js')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"
				<?php echo '},
				{'; ?>

					"allowScriptAccess":"always",
					"wmode":"transparent"
				<?php echo '},
				{'; ?>

					"bgcolor":"#FFFFFF"
				<?php echo '}'; ?>

			);
//-->
		</script>
	<?php else: ?>
		<div><div id="<?php echo $this->_tpl_vars['chartDivId']; ?>
" class="pk-emptyGraph">
			<?php echo ((is_array($_tmp='General_NoDataForGraph')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

		</div></div>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['properties']['show_footer']): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/datatable_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/datatable_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
	
	</div>
</div>