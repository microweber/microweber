<?php /* Smarty version 2.6.26, created on 2011-02-09 23:50:03
         compiled from CoreHome/templates/datatable_footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'CoreHome/templates/datatable_footer.tpl', 16, false),)), $this); ?>
<div class="dataTableFeatures">

<?php if (! empty ( $this->_tpl_vars['properties']['show_footer_message'] )): ?>
	<div class='datatableFooterMessage'><?php echo $this->_tpl_vars['properties']['show_footer_message']; ?>
</div>
<?php endif; ?>


<?php if ($this->_tpl_vars['properties']['show_offset_information']): ?>
<span>
	<span class="dataTablePages"></span>
</span>
<?php endif; ?>

<?php if ($this->_tpl_vars['properties']['show_pagination_control']): ?>
<span>
	<span class="dataTablePrevious">&lsaquo; <?php echo ((is_array($_tmp='General_Previous')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</span>
	<span class="dataTableNext"><?php echo ((is_array($_tmp='General_Next')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 &rsaquo;</span>
</span>
<?php endif; ?>

<?php if ($this->_tpl_vars['properties']['show_search']): ?>
<span class="dataTableSearchPattern">
	<input id="keyword" type="text" length="15" />
	<input type="submit" value="<?php echo ((is_array($_tmp='General_Search')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
</span>
<?php endif; ?>

<?php if ($this->_tpl_vars['properties']['show_footer_icons']): ?>
	<div class="dataTableFooterIcons">
		<div class="dataTableFooterWrap" var="<?php echo $this->_tpl_vars['javascriptVariablesToSet']['viewDataTable']; ?>
">
			<img src="themes/default/images/data_table_footer_active_item.png" class="dataTableFooterActiveItem" />
			<div class="tableIconsGroup">
            	<span class="tableAllColumnsSwitch">
                    <?php if ($this->_tpl_vars['properties']['show_table']): ?>
                    <a class="tableIcon" format="table" var="table"><img title="<?php echo ((is_array($_tmp='General_DisplaySimpleTable')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" src="themes/default/images/table.png" /></a>
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['properties']['show_table_all_columns']): ?>
                    <a class="tableIcon" format="tableAllColumns" var="tableAllColumns"><img title="<?php echo ((is_array($_tmp='General_DisplayTableWithMoreMetrics')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" src="themes/default/images/table_more.png" /></a>
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['properties']['show_goals']): ?>
					<a class="tableIcon" format="tableGoals" var="tableGoals"><img title="<?php echo ((is_array($_tmp='General_DisplayTableWithGoalMetrics')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" src="themes/default/images/goal.png" /></a>
                    <?php endif; ?>
                </span>
           </div>
           
            <?php if ($this->_tpl_vars['properties']['show_all_views_icons']): ?>
			<div class="tableIconsGroup">
            	<span class="tableGraphViews tableGraphCollapsed">
                    <a class="tableIcon" format="graphVerticalBar" var="generateDataChartVerticalBar"><img width="16" height="16" src="themes/default/images/chart_bar.png" title="<?php echo ((is_array($_tmp='General_VBarGraph')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" /></a>
                    <a class="tableIcon" format="graphPie" var="generateDataChartPie"><img width="16" height="16" src="themes/default/images/chart_pie.png" title="<?php echo ((is_array($_tmp='General_Piechart')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" /></a>
                    <a class="tableIcon" format="cloud" var="cloud"><img width="16" height="16" src="themes/default/images/tagcloud.png" title="<?php echo ((is_array($_tmp='General_TagCloud')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" /></a>
				</span>
           </div>
           <?php elseif ($this->_tpl_vars['javascriptVariablesToSet']['viewDataTable'] == 'generateDataChartEvolution'): ?>
			<div class="tableIconsGroup">
            	<span class="tableGraphViews">
                    <a class="tableIcon" format="graphEvolution" var="generateDataChartEvolution"><img width="16" height="16" src="themes/default/images/chart_bar.png" title="<?php echo ((is_array($_tmp='General_VBarGraph')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" /></a>
				</span>
           </div>
           
           <?php endif; ?>			
           
			<div class="tableIconsGroup">
				<span class="exportToFormatIcons"><a class="tableIcon" var="export"><img width="16" height="16" src="themes/default/images/export.png" title="<?php echo ((is_array($_tmp='General_ExportThisReport')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" /></a></span>
				<span class="exportToFormatItems" style="display:none"> 
					Export: 
					<a target="_blank" methodToCall="<?php echo $this->_tpl_vars['properties']['apiMethodToRequestDataTable']; ?>
" format="CSV" filter_limit="100">CSV</a> | 
					<a target="_blank" methodToCall="<?php echo $this->_tpl_vars['properties']['apiMethodToRequestDataTable']; ?>
" format="TSV" filter_limit="100">TSV (Excel)</a> | 
					<a target="_blank" methodToCall="<?php echo $this->_tpl_vars['properties']['apiMethodToRequestDataTable']; ?>
" format="XML" filter_limit="100">XML</a> |
					<a target="_blank" methodToCall="<?php echo $this->_tpl_vars['properties']['apiMethodToRequestDataTable']; ?>
" format="JSON" filter_limit="100">Json</a> |
					<a target="_blank" methodToCall="<?php echo $this->_tpl_vars['properties']['apiMethodToRequestDataTable']; ?>
" format="PHP" filter_limit="100">Php</a>
					<?php if ($this->_tpl_vars['properties']['show_export_as_rss_feed']): ?>
						| <a target="_blank" methodToCall="<?php echo $this->_tpl_vars['properties']['apiMethodToRequestDataTable']; ?>
" format="RSS" filter_limit="100" date="last10"><img border="0" src="themes/default/images/feed.png" /></a>
					<?php endif; ?>
				</span>
				<?php if ($this->_tpl_vars['properties']['show_export_as_image_icon']): ?>
					<span id="dataTableFooterExportAsImageIcon">
						<a class="tableIcon" href="javascript:piwikHelper.OFC.jquery.popup('<?php echo $this->_tpl_vars['chartDivId']; ?>
');"><img title="<?php echo ((is_array($_tmp='General_ExportAsImage_js')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" src="themes/default/images/image.png" /></a>
					</span>
				<?php endif; ?>
			</div>
			
		</div>
			
		<?php if ($this->_tpl_vars['properties']['show_exclude_low_population']): ?>
			<span class="dataTableExcludeLowPopulation"></span>
		<?php endif; ?>
	</div>
<?php endif; ?>

<span class="loadingPiwik" style='display:none'><img src="themes/default/images/loading-blue.gif" /> <?php echo ((is_array($_tmp='General_LoadingData')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</span>
</div>

<div class="dataTableSpacer"></div>