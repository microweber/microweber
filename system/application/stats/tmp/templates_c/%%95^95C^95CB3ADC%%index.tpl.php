<?php /* Smarty version 2.6.26, created on 2011-02-09 23:50:06
         compiled from C:%5Cxampp%5Cxampp%5Chtdocs%5Ccms%5Csystem%5Capplication%5Cstats%5Cplugins%5CLive/templates/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'C:\\xampp\\xampp\\htdocs\\cms\\system\\application\\stats\\plugins\\Live/templates/index.tpl', 48, false),)), $this); ?>
<?php echo '
<script type="text/javascript" charset="utf-8">

$(document).ready(function() {
	initSpy();
});

function initSpy()
{
	if($(\'#_spyTmp\').size() == 0) {
		$(\'#visitsLive > div:gt(2)\').fadeEachDown(); // initial fade
		$(\'#visitsLive\').spy({
			limit: 10,
			ajax: \'index.php?module=Live&idSite='; ?>
<?php echo $this->_tpl_vars['idSite']; ?>
<?php echo '&action=getLastVisitsStart\',
			fadeLast: 2,
			isDupe: check_for_dupe,
			timeout: 8000,
			customParameterName: \'minIdVisit\',
			customParameterValueCallback: lastIdVisit,
			fadeInSpeed: 600,
			appendTo: \'div#content\'
		});
	}
}

//updates the numbers of total visits in startbox
function updateTotalVisits()
{
	$("#visitsTotal").load("index.php?module=Live&idSite='; ?>
<?php echo $this->_tpl_vars['idSite']; ?>
<?php echo '&action=ajaxTotalVisitors");
}
//updates the visit table, to refresh the already presented visitors pages
function updateVisitBox()
{
	$("#visitsLive").load("index.php?module=Live&idSite='; ?>
<?php echo $this->_tpl_vars['idSite']; ?>
<?php echo '&action=getLastVisitsStart");
}
</script>
'; ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Live/templates/totalVisits.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id='visitsLive'>
<?php echo $this->_tpl_vars['visitors']; ?>

</div>

<div class="visitsLiveFooter">
	<a href="javascript:void(0);" onclick="onClickPause();"><img id="pauseImage" border="0" src="plugins/Live/templates/images/pause_disabled.gif" /></a>
	<a href="javascript:void(0);" onclick="onClickPlay();"><img id="playImage" border="0" src="plugins/Live/templates/images/play.gif" /></a>
	&nbsp; <a class="rightLink" href="javascript:broadcast.propagateAjax('module=Live&action=getVisitorLog')"><?php echo ((is_array($_tmp='Live_LinkVisitorLog')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
</div>