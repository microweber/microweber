<?php /* Smarty version 2.6.26, created on 2011-05-11 10:43:42
         compiled from C:%5Cxampp%5Cxampp%5Chtdocs%5Ccms%5CMicroweber%5Csystem%5Capplication%5Cstats%5Cplugins%5CUserCountryMap/templates/worldmap.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'C:\\xampp\\xampp\\htdocs\\cms\\Microweber\\system\\application\\stats\\plugins\\UserCountryMap/templates/worldmap.tpl', 2, false),)), $this); ?>
<div id="UserCountryMap_content" style="position:relative; overflow:hidden;">
	<div id="UserCountryMap_map"><?php echo ((is_array($_tmp='General_RequiresFlash')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</div>
	<div style="height:3px"></div>
	<select id="userCountryMapSelectMetrics" style="position:absolute; left: 5px; bottom: 0px;">
		<?php $_from = $this->_tpl_vars['metrics']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['metric']):
?>
			<option value="<?php echo $this->_tpl_vars['metric'][0]; ?>
" <?php if ($this->_tpl_vars['metric'][0] == $this->_tpl_vars['defaultMetric']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['metric'][1]; ?>
</option>
		<?php endforeach; endif; unset($_from); ?>
	</select>
</div>

<script type="text/javascript">
<?php echo '
	var fv = {};
	
	var params = {
		menu: "false",
		scale: "noScale",
		allowscriptaccess: "always",
		wmode: "opaque",
		bgcolor: "#FFFFFF",
		allowfullscreen: "true"
		
	};
	
'; ?>


		var isSafari = (navigator.userAgent.toLowerCase().indexOf("safari") != -1 && 
		navigator.userAgent.toLowerCase().indexOf("chrome") == -1 ? true : false); 
	
	fv.dataUrl = encodeURIComponent("<?php echo $this->_tpl_vars['dataUrl']; ?>
");
	fv.hueMin = <?php echo $this->_tpl_vars['hueMin']; ?>
;
	fv.hueMax = <?php echo $this->_tpl_vars['hueMax']; ?>
;
	fv.satMin = <?php echo $this->_tpl_vars['satMin']; ?>
;
	fv.satMax = <?php echo $this->_tpl_vars['satMax']; ?>
;
	fv.lgtMin = <?php echo $this->_tpl_vars['lgtMin']; ?>
;
	fv.lgtMax = <?php echo $this->_tpl_vars['lgtMax']; ?>
;
		fv.iconOffset = $('#userCountryMapSelectMetrics').width() + 22 + (isSafari ? 22 : 0);
	fv.defaultMetric = "<?php echo $this->_tpl_vars['defaultMetric']; ?>
";
	
	fv.txtLoading = encodeURIComponent("<?php echo ((is_array($_tmp='General_Loading_js')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
");
	fv.txtLoadingData = encodeURIComponent("<?php echo ((is_array($_tmp='General_LoadingData')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
");
	fv.txtToggleFullscreen = encodeURIComponent("<?php echo ((is_array($_tmp='UserCountryMap_toggleFullscreen')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
");
	fv.txtExportImage = encodeURIComponent("<?php echo ((is_array($_tmp='General_ExportAsImage_js')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
");

<?php echo '	
	
	var attr = { id:"UserCountryMap" };
'; ?>
	
	swfobject.embedSWF("plugins/UserCountryMap/PiwikMap.swf?piwik=<?php echo $this->_tpl_vars['piwik_version']; ?>
", "UserCountryMap_map", 
		"100%", Math.round($('#UserCountryMap_content').width() *.55), "10.0.0", 
		"libs/swfobject/expressInstall.swf", fv, params, attr
	);
<?php echo '	
	
	
	$("#userCountryMapSelectMetrics").change(function(el) {
		$("#UserCountryMap")[0].changeMode(el.currentTarget.value);
	});
	$("#userCountryMapSelectMetrics").keypress(function(e) { 
		var keyCode = e.keyCode || e.which; 
		if (keyCode == 38 || keyCode == 40) { // if up or down key is pressed
			$(this).change(); // trigger the change event
		} 
	});
	
	$(".userCountryMapFooterIcons a.tableIcon[var=fullscreen]").click(function() {
		$("#UserCountryMap")[0].setFullscreenMode();	
	});
	
	$(".userCountryMapFooterIcons a.tableIcon[var=export_png]").click(function() {
		$("#UserCountryMap")[0].exportPNG();
	});

	$(window).resize(function() {
		if($(\'#UserCountryMap\').length) {
			$("#UserCountryMap").height( Math.round($(\'#UserCountryMap\').width() *.55) );
		}
	});
'; ?>

</script>