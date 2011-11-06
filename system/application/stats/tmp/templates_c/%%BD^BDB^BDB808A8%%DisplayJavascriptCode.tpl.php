<?php /* Smarty version 2.6.26, created on 2011-04-19 10:38:57
         compiled from SitesManager/templates/DisplayJavascriptCode.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'SitesManager/templates/DisplayJavascriptCode.tpl', 23, false),)), $this); ?>

<?php echo '
<style type="text/css">
.trackingHelp ul { 
	padding-left:40px;
	list-style-type:square;
}
.trackingHelp ul li {
	margin-bottom:10px;
}
.trackingHelp h2 {
	margin-top:20px;
}
.trackingHelp .toggleHelp {
	display:none;
}
p {
	text-align:justify;
}
</style>
'; ?>


<h2><?php echo ((is_array($_tmp='SitesManager_TrackingTags')) ? $this->_run_mod_handler('translate', true, $_tmp, $this->_tpl_vars['displaySiteName']) : smarty_modifier_translate($_tmp, $this->_tpl_vars['displaySiteName'])); ?>
</h2>

<div class='trackingHelp'>
To record visitors, visits and page views in Piwik, you must add a Tracking code in all your pages. 
We recommend to use the standard Javascript Tracking code.

<h3>Standard JavaScript Tracking code</h3>
Copy and paste the following code in all the pages you want to track with Piwik. 
<br />In most websites, blogs, CMS, etc. you can edit your website templates and add this code in a "footer" file.

<p><?php echo ((is_array($_tmp='SitesManager_JsTrackingTagHelp')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
, just before the &lt;/body&gt; tag.</p>

<code><?php echo $this->_tpl_vars['jsTag']; ?>
</code>

<br />
If you want to do more than tracking a page view,  
please check out the <a target="_blank" href="?module=Proxy&action=redirect&url=http://piwik.org/docs/javascript-tracking/">
Piwik Javascript Tracking documentation</a> for the list of available functions.

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'SitesManager/templates/DisplayAlternativeTags.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

</div>


<?php echo '
<script type=\'text/javascript\'>
$(document).ready( function() {
	$(\'.toggleHelp\').each(function() {
		var id = $(this).attr(\'id\');
		// show \'display\' link
		$(this).show(); 
		// hide help block
		$(\'.\'+id).hide();
	});

	// click on Display links will toggle help text
	$(\'.toggleHelp\').click( function() {
		// on click, show help block, hide link
		$(\'.\'+ $(this).attr(\'id\')).show();
		$(this).hide();
	});
});
</script>
'; ?>
