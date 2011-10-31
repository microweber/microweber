<?php /* Smarty version 2.6.26, created on 2011-07-02 14:04:25
         compiled from Live/templates/visitorLog.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'Live/templates/visitorLog.tpl', 3, false),array('modifier', 'capitalize', 'Live/templates/visitorLog.tpl', 79, false),array('modifier', 'escape', 'Live/templates/visitorLog.tpl', 88, false),array('modifier', 'count', 'Live/templates/visitorLog.tpl', 118, false),array('modifier', 'truncate', 'Live/templates/visitorLog.tpl', 133, false),array('function', 'cycle', 'Live/templates/visitorLog.tpl', 37, false),)), $this); ?>
 <div class="home" id="content" style="display: block;">
<a graphid="VisitsSummarygetEvolutionGraph" name="evolutionGraph"></a>
<h2><?php echo ((is_array($_tmp='Live_VisitorLog')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
<div id="<?php echo $this->_tpl_vars['properties']['uniqueId']; ?>
" class="visitorLog">

<?php $this->assign('maxIdVisit', 0); ?>
<?php if (isset ( $this->_tpl_vars['arrayDataTable']['result'] ) && $this->_tpl_vars['arrayDataTable']['result'] == 'error'): ?>
		<?php echo $this->_tpl_vars['arrayDataTable']['message']; ?>

	<?php else: ?>
		<?php if (count ( $this->_tpl_vars['arrayDataTable'] ) == 0): ?>
		<a name="<?php echo $this->_tpl_vars['properties']['uniqueId']; ?>
"></a>
		<div class="pk-emptyDataTable"><?php echo ((is_array($_tmp='CoreHome_ThereIsNoDataForThisReport')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</div>
		<?php else: ?>
			<a name="<?php echo $this->_tpl_vars['properties']['uniqueId']; ?>
"></a>

	<table class="dataTable" cellspacing="0" width="100%" style="width:100%;">
	<thead>
	<tr>
	<th style="display:none"></th>
	<th id="label" class="sortable label" style="cursor: auto;width:12%" width="12%">
	<div id="thDIV"><?php echo ((is_array($_tmp='General_Date')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
<div></th>
	<th id="label" class="sortable label" style="cursor: auto;width:13%" width="13%">
	<div id="thDIV"><?php echo ((is_array($_tmp='General_Visitors')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
<div></th>
	<th id="label" class="sortable label" style="cursor: auto;width:15%" width="15%">
	<div id="thDIV"><?php echo ((is_array($_tmp='Live_Referrer_URL')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
<div></th>
	<th id="label" class="sortable label" style="cursor: auto;width:62%" width="62%">
	<div id="thDIV"><?php echo ((is_array($_tmp='General_ColumnNbActions')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
<div></th>
	</tr>
	</thead>
	<tbody>

<?php $_from = $this->_tpl_vars['arrayDataTable']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['visitor']):
?>
<?php if ($this->_tpl_vars['maxIdVisit'] == 0 || $this->_tpl_vars['visitor']['columns']['idVisit'] < $this->_tpl_vars['maxIdVisit']): ?>
<?php $this->assign('maxIdVisit', $this->_tpl_vars['visitor']['columns']['idVisit']); ?>
<?php endif; ?>

	<tr class="label<?php echo smarty_function_cycle(array('values' => 'odd,even'), $this);?>
">
	<td style="display:none;"></td>
	<td class="label" style="width:12%" width="12%">

				<strong><?php echo $this->_tpl_vars['visitor']['columns']['serverDatePrettyFirstAction']; ?>
 - <?php echo $this->_tpl_vars['visitor']['columns']['serverTimePrettyFirstAction']; ?>
</strong>
				<?php if (! empty ( $this->_tpl_vars['visitor']['columns']['visitIp'] )): ?> <br/><span title="<?php if (! empty ( $this->_tpl_vars['visitor']['columns']['visitorId'] )): ?><?php echo ((is_array($_tmp='General_VisitorID')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <?php echo $this->_tpl_vars['visitor']['columns']['visitorId']; ?>
<?php endif; ?>">IP: <?php echo $this->_tpl_vars['visitor']['columns']['visitIp']; ?>
</span><?php endif; ?>
				
				<?php if (( isset ( $this->_tpl_vars['visitor']['columns']['provider'] ) && $this->_tpl_vars['visitor']['columns']['provider'] != 'IP' )): ?> 
					<br />
					<?php echo ((is_array($_tmp='Provider_ColumnProvider')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: 
					<a href="<?php echo $this->_tpl_vars['visitor']['columns']['providerUrl']; ?>
" target="_blank" title="<?php echo $this->_tpl_vars['visitor']['columns']['providerUrl']; ?>
" style="text-decoration:underline;">
						<?php echo $this->_tpl_vars['visitor']['columns']['provider']; ?>

					</a>
				<?php endif; ?>
				<?php if (! empty ( $this->_tpl_vars['visitor']['columns']['customVariables'] )): ?>
					<br/>
					<?php $_from = $this->_tpl_vars['visitor']['columns']['customVariables']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['customVariable']):
?>
						<?php ob_start(); ?>customVariableName<?php echo $this->_tpl_vars['id']; ?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('name', ob_get_contents());ob_end_clean(); ?>
						<?php ob_start(); ?>customVariableValue<?php echo $this->_tpl_vars['id']; ?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('value', ob_get_contents());ob_end_clean(); ?>
						<br/><acronym title="<?php echo ((is_array($_tmp='CustomVariables_CustomVariables')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 (index <?php echo $this->_tpl_vars['id']; ?>
)"><?php echo $this->_tpl_vars['customVariable'][$this->_tpl_vars['name']]; ?>
</acronym>: <?php echo $this->_tpl_vars['customVariable'][$this->_tpl_vars['value']]; ?>

					<?php endforeach; endif; unset($_from); ?>
				<?php endif; ?>
				
	</td>
	<td class="label" style="width:13%" width="13%">
		&nbsp;<img src="<?php echo $this->_tpl_vars['visitor']['columns']['countryFlag']; ?>
" title="<?php echo $this->_tpl_vars['visitor']['columns']['country']; ?>
, Provider <?php echo $this->_tpl_vars['visitor']['columns']['provider']; ?>
" />
		&nbsp;<img src="<?php echo $this->_tpl_vars['visitor']['columns']['browserIcon']; ?>
" title="<?php echo $this->_tpl_vars['visitor']['columns']['browserName']; ?>
 with plugins <?php echo $this->_tpl_vars['visitor']['columns']['plugins']; ?>
 enabled" />
		&nbsp;<img src="<?php echo $this->_tpl_vars['visitor']['columns']['operatingSystemIcon']; ?>
" title="<?php echo $this->_tpl_vars['visitor']['columns']['operatingSystem']; ?>
, <?php echo $this->_tpl_vars['visitor']['columns']['resolution']; ?>
 (<?php echo $this->_tpl_vars['visitor']['columns']['screenType']; ?>
)" />
		<?php if ($this->_tpl_vars['visitor']['columns']['visitorType'] == 'returning'): ?>
			&nbsp;<img src="plugins/Live/templates/images/returningVisitor.gif" title="<?php echo ((is_array($_tmp='General_ReturningVisitor')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
		<?php endif; ?>
		&nbsp;<?php if ($this->_tpl_vars['visitor']['columns']['visitConverted']): ?>
		<span title="<?php echo ((is_array($_tmp='General_VisitConvertedNGoals')) ? $this->_run_mod_handler('translate', true, $_tmp, $this->_tpl_vars['visitor']['columns']['goalConversions']) : smarty_modifier_translate($_tmp, $this->_tpl_vars['visitor']['columns']['goalConversions'])); ?>
" class='visitorRank'>
		<img src="themes/default/images/goal.png" />
		<span class='hash'>#</span><?php echo $this->_tpl_vars['visitor']['columns']['goalConversions']; ?>

		</span><?php endif; ?>
		
		<br/>
		<?php if (count ( $this->_tpl_vars['visitor']['columns']['pluginsIcons'] ) > 0): ?>
			<hr />
			<?php echo ((is_array($_tmp='UserSettings_Plugins')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:
				<?php $_from = $this->_tpl_vars['visitor']['columns']['pluginsIcons']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pluginIcon']):
?>
					<img src="<?php echo $this->_tpl_vars['pluginIcon']['pluginIcon']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['pluginIcon']['pluginName'])) ? $this->_run_mod_handler('capitalize', true, $_tmp, true) : smarty_modifier_capitalize($_tmp, true)); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['pluginIcon']['pluginName'])) ? $this->_run_mod_handler('capitalize', true, $_tmp, true) : smarty_modifier_capitalize($_tmp, true)); ?>
" />
				<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
	</td>

	<td class="column" style="width:20%" width="20%">
		<div class="referer">
			<?php if ($this->_tpl_vars['visitor']['columns']['referrerType'] == 'website'): ?>
				<?php echo ((is_array($_tmp='Referers_ColumnWebsite')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:
				<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['visitor']['columns']['referrerUrl'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" target="_blank" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['visitor']['columns']['referrerUrl'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" style="text-decoration:underline;">
					<?php echo ((is_array($_tmp=$this->_tpl_vars['visitor']['columns']['referrerName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

				</a>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['visitor']['columns']['referrerType'] == 'campaign'): ?>
				<?php echo ((is_array($_tmp='Referers_ColumnCampaign')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

				<br />
				<?php echo ((is_array($_tmp=$this->_tpl_vars['visitor']['columns']['referrerName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

				<?php if (! empty ( $this->_tpl_vars['visitor']['columns']['referrerKeyword'] )): ?> - <?php echo ((is_array($_tmp=$this->_tpl_vars['visitor']['columns']['referrerKeyword'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['visitor']['columns']['referrerType'] == 'search'): ?>
				<?php if (! empty ( $this->_tpl_vars['visitor']['columns']['searchEngineIcon'] )): ?>
					<img src="<?php echo $this->_tpl_vars['visitor']['columns']['searchEngineIcon']; ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['visitor']['columns']['referrerName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /> 
				<?php endif; ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['visitor']['columns']['referrerName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

				<br />
				<?php if (! empty ( $this->_tpl_vars['visitor']['columns']['referrerKeyword'] )): ?><?php echo ((is_array($_tmp='Referers_Keywords')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:<?php endif; ?>
				<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['visitor']['columns']['referrerUrl'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" target="_blank" style="text-decoration:underline;">
					<?php if (! empty ( $this->_tpl_vars['visitor']['columns']['referrerKeyword'] )): ?>
						"<?php echo ((is_array($_tmp=$this->_tpl_vars['visitor']['columns']['referrerKeyword'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"<?php endif; ?></a>
				<?php ob_start(); ?><?php echo ((is_array($_tmp=$this->_tpl_vars['visitor']['columns']['referrerKeyword'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('keyword', ob_get_contents());ob_end_clean(); ?>
				<?php ob_start(); ?><?php echo ((is_array($_tmp=$this->_tpl_vars['visitor']['columns']['referrerName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('searchName', ob_get_contents());ob_end_clean(); ?>
				<?php ob_start(); ?>#<?php echo $this->_tpl_vars['visitor']['columns']['referrerKeywordPosition']; ?>
<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('position', ob_get_contents());ob_end_clean(); ?>
				<?php if (! empty ( $this->_tpl_vars['visitor']['columns']['referrerKeywordPosition'] )): ?><span title='<?php echo ((is_array($_tmp='Live_KeywordRankedOnSearchResultForThisVisitor')) ? $this->_run_mod_handler('translate', true, $_tmp, $this->_tpl_vars['keyword'], $this->_tpl_vars['position'], $this->_tpl_vars['searchName']) : smarty_modifier_translate($_tmp, $this->_tpl_vars['keyword'], $this->_tpl_vars['position'], $this->_tpl_vars['searchName'])); ?>
' class='visitorRank'><span class='hash'>#</span><?php echo $this->_tpl_vars['visitor']['columns']['referrerKeywordPosition']; ?>
</span><?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['visitor']['columns']['referrerType'] == 'direct'): ?><?php echo ((is_array($_tmp='Referers_DirectEntry')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
<?php endif; ?>
		</div>
	</td>
	<td class="column <?php if ($this->_tpl_vars['visitor']['columns']['visitConverted']): ?>highlightField<?php endif; ?>" style="width:55%" width="55%">
			<strong>
				<?php echo count($this->_tpl_vars['visitor']['columns']['actionDetails']); ?>

				<?php if (count($this->_tpl_vars['visitor']['columns']['actionDetails']) <= 1): ?>
					<?php echo ((is_array($_tmp='Live_Action')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 
				<?php else: ?>
					<?php echo ((is_array($_tmp='Live_Actions')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

				<?php endif; ?>
				- <?php echo $this->_tpl_vars['visitor']['columns']['visitDurationPretty']; ?>

			</strong>
			<br />
			<ol class='visitorLog'>
			<?php $_from = $this->_tpl_vars['visitor']['columns']['actionDetails']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['action']):
?>
				<li class="<?php if (! empty ( $this->_tpl_vars['action']['goalName'] )): ?>goal<?php else: ?>action<?php endif; ?>" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['serverTimePretty'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
 - <?php echo ((is_array($_tmp=$this->_tpl_vars['action']['url'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
				<?php if (empty ( $this->_tpl_vars['action']['goalName'] )): ?>
										<?php if (strlen ( trim ( $this->_tpl_vars['action']['pageTitle'] ) ) > 0): ?>
						 	<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['pageTitle'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 80, "...", true) : smarty_modifier_truncate($_tmp, 80, "...", true)); ?>

							<br/>
						<?php endif; ?>
						<?php if ($this->_tpl_vars['action']['type'] == 'download'): ?>
							<img src='themes/default/images/download.png'>
						<?php elseif ($this->_tpl_vars['action']['type'] == 'outlink'): ?>
							<img src='themes/default/images/link.gif'>
						<?php endif; ?>
						<?php if (! empty ( $this->_tpl_vars['action']['url'] )): ?>
						 	<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['url'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" target="_blank" style="<?php if ($this->_tpl_vars['action']['type'] == 'action' && ! empty ( $this->_tpl_vars['action']['pageTitle'] )): ?>margin-left: 25px;<?php endif; ?>text-decoration:underline;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['action']['url'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('truncate', true, $_tmp, 80, "...", true) : smarty_modifier_truncate($_tmp, 80, "...", true)); ?>
</a>
						<?php else: ?>
							<?php echo $this->_tpl_vars['javascriptVariablesToSet']['pageUrlNotDefined']; ?>

						<?php endif; ?>
				<?php else: ?>
									<img src="themes/default/images/goal.png" /> 
					<strong><?php echo $this->_tpl_vars['action']['goalName']; ?>
</strong>
					<?php if ($this->_tpl_vars['action']['revenue'] > 0): ?>, <?php echo ((is_array($_tmp='Live_GoalRevenue')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <strong><?php echo $this->_tpl_vars['action']['revenue']; ?>
 <?php echo $this->_tpl_vars['visitor']['columns']['siteCurrency']; ?>
</strong><?php endif; ?>
				<?php endif; ?>
				</li>
			<?php endforeach; endif; unset($_from); ?>
			</ol>
	</td>
	</tr>
<?php endforeach; endif; unset($_from); ?>
	</tbody>
	</table>

	<?php endif; ?>
	<?php if (count ( $this->_tpl_vars['arrayDataTable'] ) == 20): ?>
		<?php $this->_tpl_vars['javascriptVariablesToSet']['totalRows'] = 100000;  ?>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['properties']['show_footer']): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/datatable_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/datatable_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<script type="text/javascript" defer="defer">
		dataTables['<?php echo $this->_tpl_vars['properties']['uniqueId']; ?>
'].param.maxIdVisit = <?php echo $this->_tpl_vars['maxIdVisit']; ?>
;
	</script>
<?php endif; ?>
</div>

<?php echo '
<style type="text/css">
 hr {
	background:none repeat scroll 0 0 transparent;
	border-color:-moz-use-text-color -moz-use-text-color #EEEEEE;
	border-style:none none solid;
	border-width:0 0 1px;
	color:#CCCCCC;
	margin:0 2em 0.5em;
	padding:0 0 0.5em;
 }

</style>
'; ?>

</div>