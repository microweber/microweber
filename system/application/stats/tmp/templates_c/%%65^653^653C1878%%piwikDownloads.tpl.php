<?php /* Smarty version 2.6.26, created on 2011-04-18 13:28:27
         compiled from C:%5Cxampp%5Cxampp%5Chtdocs%5Cmicroweber%5Csystem%5Capplication%5Cstats%5Cplugins%5CExamplePlugin/templates/piwikDownloads.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'C:\\xampp\\xampp\\htdocs\\microweber\\system\\application\\stats\\plugins\\ExamplePlugin/templates/piwikDownloads.tpl', 2, false),)), $this); ?>
<div style="padding:1.5em;text-align:center">
	<?php echo ((is_array($_tmp='ExamplePlugin_PiwikForumReceivedVisits')) ? $this->_run_mod_handler('translate', true, $_tmp, $this->_tpl_vars['prettyDate'], '<b class="piwikDownloadCount_cnt" >...</b>') : smarty_modifier_translate($_tmp, $this->_tpl_vars['prettyDate'], '<b class="piwikDownloadCount_cnt" >...</b>')); ?>

</div>
<script type="text/javascript">
<?php echo '
	$.ajax({
		url: "http://demo.piwik.org/?module=API&method=VisitsSummary.getVisits"
				+"&idSite=7&period="+piwik.period+"&date="+broadcast.getValueFromUrl(\'date\')
				+"&token_auth=anonymous&format=json",
		dataType: \'jsonp\', 
		jsonp: \'jsoncallback\',
		success: function(data) {
			$(\'.piwikDownloadCount_cnt\').html(data.value);
		}
	});
'; ?>

</script>