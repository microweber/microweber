<?php /* Smarty version 2.6.26, created on 2011-02-09 23:50:05
         compiled from C:%5Cxampp%5Cxampp%5Chtdocs%5Ccms%5Csystem%5Capplication%5Cstats%5Cplugins%5CExampleFeedburner/templates/feedburner.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'C:\\xampp\\xampp\\htdocs\\cms\\system\\application\\stats\\plugins\\ExampleFeedburner/templates/feedburner.tpl', 41, false),)), $this); ?>

<script type="text/javascript">
	var idSite = <?php echo $this->_tpl_vars['idSite']; ?>
;
<?php echo '

function initFeedburner()
{

	function getName()
	{
		return $("#feedburnerName").val();
	}
	$("#feedburnerName").bind("keyup", function(e) {
		if(isEnterKey(e)) { 
			$("#feedburnerSubmit").click(); 
		} 
	}); 
	$("#feedburnerSubmit").click( function(){
		var feedburnerName = getName();
		$.get(\'?module=ExampleFeedburner&action=saveFeedburnerName&idSite=\'+idSite+\'&name=\'+feedburnerName);
		piwik.dashboardObject.reloadEnclosingWidget($(this));
		initFeedburner();
	});
}
$(document).ready(function(){
	initFeedburner();
});
</script>
<style>
.metric { font-weight:bold;text-align:left; }
.feedburner td { padding:0px 3px; } 
</style>
'; ?>


<?php if (! is_array ( $this->_tpl_vars['fbStats'] )): ?>
	<p style='margin-top:20px'><?php echo $this->_tpl_vars['fbStats']; ?>
</p>
<?php else: ?>
<table class='feedburner' align="center" cellpadding="2" style='text-align:center'>
	<tr>
		<td></td>
		<td style="text-decoration:underline;"><?php echo ((is_array($_tmp='General_Previous')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td style="text-decoration:underline;"><?php echo ((is_array($_tmp='General_Yesterday')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td></td>
	</tr>
	<tr>
		<td class='metric'>Circulation</td>
		<td><?php echo $this->_tpl_vars['fbStats'][0][0]; ?>
</td>
		<td><?php echo $this->_tpl_vars['fbStats'][0][1]; ?>
</td>
		<td><?php echo $this->_tpl_vars['fbStats'][0][2]; ?>
</td>
	</tr>
	<tr>
		<td class='metric'>Hits</td>
		<td><?php echo $this->_tpl_vars['fbStats'][1][0]; ?>
</td>
		<td><?php echo $this->_tpl_vars['fbStats'][1][1]; ?>
</td>
		<td><?php echo $this->_tpl_vars['fbStats'][1][2]; ?>
</td>
	</tr>
	<tr>
		<td class='metric'>Reach</td>
		<td><?php echo $this->_tpl_vars['fbStats'][2][0]; ?>
</td>
		<td><?php echo $this->_tpl_vars['fbStats'][2][1]; ?>
</td>
		<td><?php echo $this->_tpl_vars['fbStats'][2][2]; ?>
</td>
	</tr>
</table>
<?php endif; ?>

<div class='center entityContainer'>
	<input id="feedburnerName" type="text" value="<?php echo $this->_tpl_vars['feedburnerFeedName']; ?>
" />
	<input id="feedburnerSubmit" type="submit" value="<?php echo ((is_array($_tmp='General_Ok')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
</div>