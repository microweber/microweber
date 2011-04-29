<?php /* Smarty version 2.6.26, created on 2011-04-19 10:38:53
         compiled from C:%5Cxampp%5Cxampp%5Chtdocs%5Cmicroweber%5Csystem%5Capplication%5Cstats%5Cplugins%5CSitesManager/templates/SitesManager.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'loadJavascriptTranslations', 'C:\\xampp\\xampp\\htdocs\\microweber\\system\\application\\stats\\plugins\\SitesManager/templates/SitesManager.tpl', 4, false),array('function', 'ajaxErrorDiv', 'C:\\xampp\\xampp\\htdocs\\microweber\\system\\application\\stats\\plugins\\SitesManager/templates/SitesManager.tpl', 87, false),array('function', 'ajaxLoadingDiv', 'C:\\xampp\\xampp\\htdocs\\microweber\\system\\application\\stats\\plugins\\SitesManager/templates/SitesManager.tpl', 88, false),array('function', 'url', 'C:\\xampp\\xampp\\htdocs\\microweber\\system\\application\\stats\\plugins\\SitesManager/templates/SitesManager.tpl', 128, false),array('modifier', 'translate', 'C:\\xampp\\xampp\\htdocs\\microweber\\system\\application\\stats\\plugins\\SitesManager/templates/SitesManager.tpl', 7, false),array('modifier', 'inlineHelp', 'C:\\xampp\\xampp\\htdocs\\microweber\\system\\application\\stats\\plugins\\SitesManager/templates/SitesManager.tpl', 8, false),array('modifier', 'escape', 'C:\\xampp\\xampp\\htdocs\\microweber\\system\\application\\stats\\plugins\\SitesManager/templates/SitesManager.tpl', 9, false),array('modifier', 'count', 'C:\\xampp\\xampp\\htdocs\\microweber\\system\\application\\stats\\plugins\\SitesManager/templates/SitesManager.tpl', 94, false),array('modifier', 'replace', 'C:\\xampp\\xampp\\htdocs\\microweber\\system\\application\\stats\\plugins\\SitesManager/templates/SitesManager.tpl', 108, false),)), $this); ?>
<?php $this->assign('showSitesSelection', false); ?>
<?php $this->assign('showPeriodSelection', false); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreAdminHome/templates/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo smarty_function_loadJavascriptTranslations(array('plugins' => 'SitesManager'), $this);?>


<script type="text/javascript">
<?php ob_start(); ?><?php echo ((is_array($_tmp='SitesManager_HelpExcludedIps')) ? $this->_run_mod_handler('translate', true, $_tmp, "1.2.3.*", "1.2.*.*") : smarty_modifier_translate($_tmp, "1.2.3.*", "1.2.*.*")); ?>
<br /><br /> <?php echo ((is_array($_tmp='SitesManager_YourCurrentIpAddressIs')) ? $this->_run_mod_handler('translate', true, $_tmp, "<i>".($this->_tpl_vars['currentIpAddress'])."</i>") : smarty_modifier_translate($_tmp, "<i>".($this->_tpl_vars['currentIpAddress'])."</i>")); ?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('excludedIpHelpPlain', ob_get_contents());ob_end_clean(); ?>
<?php $this->assign('excludedIpHelp', ((is_array($_tmp=$this->_tpl_vars['excludedIpHelpPlain'])) ? $this->_run_mod_handler('inlineHelp', true, $_tmp) : smarty_modifier_inlineHelp($_tmp))); ?>
var excludedIpHelp = '<?php echo ((is_array($_tmp=$this->_tpl_vars['excludedIpHelp'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
';
var aliasUrlsHelp = '<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp='SitesManager_AliasUrlHelp')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('inlineHelp', true, $_tmp) : smarty_modifier_inlineHelp($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
';
<?php ob_start(); ?>
	<?php if ($this->_tpl_vars['timezoneSupported']): ?>
		<?php echo ((is_array($_tmp='SitesManager_ChooseCityInSameTimezoneAsYou')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

	<?php else: ?>
		<?php echo ((is_array($_tmp='SitesManager_AdvancedTimezoneSupportNotFound')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

	<?php endif; ?> <br /><br /><?php echo ((is_array($_tmp='SitesManager_UTCTimeIs')) ? $this->_run_mod_handler('translate', true, $_tmp, $this->_tpl_vars['utcTime']) : smarty_modifier_translate($_tmp, $this->_tpl_vars['utcTime'])); ?>

<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('defaultTimezoneHelpPlain', ob_get_contents());ob_end_clean(); ?>

<?php ob_start(); ?>
	<?php echo $this->_tpl_vars['defaultTimezoneHelpPlain']; ?>

	<br /><br /><?php echo ((is_array($_tmp='SitesManager_ChangingYourTimezoneWillOnlyAffectDataForward')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('timezoneHelpPlain', ob_get_contents());ob_end_clean(); ?>

<?php ob_start(); ?>
	<?php echo ((is_array($_tmp=((is_array($_tmp='SitesManager_CurrencySymbolWillBeUsedForGoals')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('inlineHelp', true, $_tmp) : smarty_modifier_inlineHelp($_tmp)); ?>

<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('currencyHelpPlain', ob_get_contents());ob_end_clean(); ?>

<?php ob_start(); ?>
	<?php echo ((is_array($_tmp='SitesManager_ListOfQueryParametersToExclude')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

	<br /><br />
	<?php echo ((is_array($_tmp='SitesManager_PiwikWillAutomaticallyExcludeCommonSessionParameters')) ? $this->_run_mod_handler('translate', true, $_tmp, "phpsessid, sessionid, etc.") : smarty_modifier_translate($_tmp, "phpsessid, sessionid, etc.")); ?>

<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('excludedQueryParametersHelp', ob_get_contents());ob_end_clean(); ?>
<?php $this->assign('excludedQueryParametersHelp', ((is_array($_tmp=$this->_tpl_vars['excludedQueryParametersHelp'])) ? $this->_run_mod_handler('inlineHelp', true, $_tmp) : smarty_modifier_inlineHelp($_tmp))); ?>
var excludedQueryParametersHelp = '<?php echo ((is_array($_tmp=$this->_tpl_vars['excludedQueryParametersHelp'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
';
var timezoneHelp = '<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['timezoneHelpPlain'])) ? $this->_run_mod_handler('inlineHelp', true, $_tmp) : smarty_modifier_inlineHelp($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
';
var currencyHelp = '<?php echo ((is_array($_tmp=$this->_tpl_vars['currencyHelpPlain'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
';
<?php $this->assign('defaultTimezoneHelp', ((is_array($_tmp=$this->_tpl_vars['defaultTimezoneHelpPlain'])) ? $this->_run_mod_handler('inlineHelp', true, $_tmp) : smarty_modifier_inlineHelp($_tmp))); ?>;

var sitesManager = new SitesManager ( <?php echo $this->_tpl_vars['timezones']; ?>
, <?php echo $this->_tpl_vars['currencies']; ?>
, '<?php echo $this->_tpl_vars['defaultTimezone']; ?>
', '<?php echo $this->_tpl_vars['defaultCurrency']; ?>
');

<?php echo '
$(document).ready( function() {
	sitesManager.init();
});
</script>

<style type="text/css">
.entityTable tr td {
    vertical-align: top;
    padding-top:7px;
}

.addRowSite:hover, .editableSite:hover, .addsite:hover, .cancel:hover, .deleteSite:hover, .editSite:hover, .updateSite:hover{
	cursor: pointer;
}
.addRowSite a {
	text-decoration: none;
}
.addRowSite {
	padding:1em;
	font-color:#3A477B;
	padding:1em;
	font-weight:bold;
}
#editSites {
	valign: top;
}
option, select {
	font-size:11px;
}
textarea {
font-size:9pt;
}
.admin thead th {
vertical-align:middle;
}
</style>
'; ?>


<h2><?php echo ((is_array($_tmp='SitesManager_WebsitesManagement')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
<p><?php echo ((is_array($_tmp='SitesManager_MainDescription')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

<?php echo ((is_array($_tmp='SitesManager_YouCurrentlyHaveAccessToNWebsites')) ? $this->_run_mod_handler('translate', true, $_tmp, "<b>".($this->_tpl_vars['adminSitesCount'])."</b>") : smarty_modifier_translate($_tmp, "<b>".($this->_tpl_vars['adminSitesCount'])."</b>")); ?>

<?php if ($this->_tpl_vars['isSuperUser']): ?>
<br /><?php echo ((is_array($_tmp='SitesManager_SuperUserCan')) ? $this->_run_mod_handler('translate', true, $_tmp, "<a href='#globalSettings'>", "</a>") : smarty_modifier_translate($_tmp, "<a href='#globalSettings'>", "</a>")); ?>

<?php endif; ?>
</p>
<?php echo smarty_function_ajaxErrorDiv(array(), $this);?>

<?php echo smarty_function_ajaxLoadingDiv(array(), $this);?>


<?php ob_start(); ?>
	<div class="addRowSite"><img src='plugins/UsersManager/images/add.png' alt="" /> <?php echo ((is_array($_tmp='SitesManager_AddSite')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</div>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('createNewWebsite', ob_get_contents());ob_end_clean(); ?>

<?php if (count($this->_tpl_vars['adminSites']) == 0): ?>
	<?php echo ((is_array($_tmp='SitesManager_NoWebsites')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

<?php else: ?>
	<div class="entityContainer">
	<?php if ($this->_tpl_vars['isSuperUser']): ?> 
		<?php echo $this->_tpl_vars['createNewWebsite']; ?>
	
	<?php endif; ?>
	<table class="entityTable dataTable" id="editSites">
		<thead>
			<tr>
			<th><?php echo ((is_array($_tmp='General_Id')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
			<th><?php echo ((is_array($_tmp='General_Name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
			<th><?php echo ((is_array($_tmp='SitesManager_Urls')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
			<th><?php echo ((is_array($_tmp='SitesManager_ExcludedIps')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
			<th><?php echo ((is_array($_tmp=((is_array($_tmp='SitesManager_ExcludedParameters')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', "<br />") : smarty_modifier_replace($_tmp, ' ', "<br />")); ?>
</th>
			<th><?php echo ((is_array($_tmp='SitesManager_Timezone')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
			<th><?php echo ((is_array($_tmp='SitesManager_Currency')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</th>
			<th> </th>
			<th> </th>
			<th> <?php echo ((is_array($_tmp='SitesManager_JsTrackingTag')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 </th>
			</tr>
		</thead>
		<tbody>
			<?php $_from = $this->_tpl_vars['adminSites']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['site']):
?>
			<tr id="row<?php echo $this->_tpl_vars['i']; ?>
">
				<td id="idSite"><?php echo $this->_tpl_vars['site']['idsite']; ?>
</td>
				<td id="siteName" class="editableSite"><?php echo $this->_tpl_vars['site']['name']; ?>
</td>
				<td id="urls" class="editableSite"><?php $_from = $this->_tpl_vars['site']['alias_urls']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['url']):
?><?php echo ((is_array($_tmp=$this->_tpl_vars['url'])) ? $this->_run_mod_handler('replace', true, $_tmp, "http://", "") : smarty_modifier_replace($_tmp, "http://", "")); ?>
<br /><?php endforeach; endif; unset($_from); ?></td>       
				<td id="excludedIps" class="editableSite"><?php $_from = $this->_tpl_vars['site']['excluded_ips']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ip']):
?><?php echo $this->_tpl_vars['ip']; ?>
<br /><?php endforeach; endif; unset($_from); ?></td>       
				<td id="excludedQueryParameters" class="editableSite"><?php $_from = $this->_tpl_vars['site']['excluded_parameters']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['parameter']):
?><?php echo $this->_tpl_vars['parameter']; ?>
<br /><?php endforeach; endif; unset($_from); ?></td>       
				<td id="timezone" class="editableSite"><?php echo $this->_tpl_vars['site']['timezone']; ?>
</td>
				<td id="currency" class="editableSite"><?php echo $this->_tpl_vars['site']['currency']; ?>
</td>
				<td><span id="row<?php echo $this->_tpl_vars['i']; ?>
" class='editSite link_but'><img src='themes/default/images/ico_edit.png' title="<?php echo ((is_array($_tmp='General_Edit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" border="0"/> <?php echo ((is_array($_tmp='General_Edit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</span></td>
				<td><span id="row<?php echo $this->_tpl_vars['i']; ?>
" class="deleteSite link_but"><img src='themes/default/images/ico_delete.png' title="<?php echo ((is_array($_tmp='General_Delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" border="0" /> <?php echo ((is_array($_tmp='General_Delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</span></td>
				<td><a href='<?php echo smarty_function_url(array('action' => 'displayJavascriptCode','idSite' => $this->_tpl_vars['site']['idsite'],'updated' => false), $this);?>
'><?php echo ((is_array($_tmp='SitesManager_ShowTrackingTag')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a></td>
			</tr>
			<?php endforeach; endif; unset($_from); ?>
		</tbody>
	</table>
	<?php if ($this->_tpl_vars['isSuperUser']): ?>	
		<?php echo $this->_tpl_vars['createNewWebsite']; ?>
	
	<?php endif; ?>
	</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['isSuperUser']): ?>	
<br />
	<a name='globalSettings'></a>
	<h2><?php echo ((is_array($_tmp='SitesManager_GlobalWebsitesSettings')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
	<br />
	<table style='width:600px' class="adminTable" >
		
		<tr><td colspan="2">
				<b><?php echo ((is_array($_tmp='SitesManager_GlobalListExcludedIps')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b>
				<p><?php echo ((is_array($_tmp='SitesManager_ListOfIpsToBeExcludedOnAllWebsites')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 </p>
			</td></tr>
			<tr><td>
			<textarea cols="30" rows="3" id="globalExcludedIps"><?php echo $this->_tpl_vars['globalExcludedIps']; ?>

</textarea>
			</td><td>
				<?php echo $this->_tpl_vars['excludedIpHelp']; ?>

		</td></tr>
		
		<tr><td colspan="2">
				<b><?php echo ((is_array($_tmp='SitesManager_GlobalListExcludedQueryParameters')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b>
				<p><?php echo ((is_array($_tmp='SitesManager_ListOfQueryParametersToBeExcludedOnAllWebsites')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 </p>
			</td></tr>
			<tr><td>
			<textarea cols="30" rows="3" id="globalExcludedQueryParameters"><?php echo $this->_tpl_vars['globalExcludedQueryParameters']; ?>

</textarea>
			</td><td>
				<?php echo $this->_tpl_vars['excludedQueryParametersHelp']; ?>

		</td></tr>
		
		<tr><td colspan="2">
				<b><?php echo ((is_array($_tmp='SitesManager_DefaultTimezoneForNewWebsites')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b>
				<p><?php echo ((is_array($_tmp='SitesManager_SelectDefaultTimezone')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 </p>
			</td></tr>
			<tr><td>
				<div id='defaultTimezone'></div>
			</td><td>
				<?php echo $this->_tpl_vars['defaultTimezoneHelp']; ?>

		</td></tr>
		
		<tr><td colspan="2">
				<b><?php echo ((is_array($_tmp='SitesManager_DefaultCurrencyForNewWebsites')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b>
				<p><?php echo ((is_array($_tmp='SitesManager_SelectDefaultCurrency')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 </p>
			</td></tr>
			<tr><td>
				<div id='defaultCurrency'></div>
			</td><td>
				<?php echo $this->_tpl_vars['currencyHelpPlain']; ?>

		</td></tr>
	</table>
	<span style='margin-left:20px'>
		<input type="submit" class="submit" id='globalSettingsSubmit' value="<?php echo ((is_array($_tmp='General_Save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
	</span>
	<?php echo smarty_function_ajaxErrorDiv(array('id' => 'ajaxErrorGlobalSettings'), $this);?>

	<?php echo smarty_function_ajaxLoadingDiv(array('id' => 'ajaxLoadingGlobalSettings'), $this);?>

<?php endif; ?>

<br /><br /><br /><br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreAdminHome/templates/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>