<?php /* Smarty version 2.6.26, created on 2011-07-03 12:30:26
         compiled from /home/microweber/public_html/system/application/stats/plugins/Referers/templates/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', '/home/microweber/public_html/system/application/stats/plugins/Referers/templates/index.tpl', 2, false),array('function', 'sparkline', '/home/microweber/public_html/system/application/stats/plugins/Referers/templates/index.tpl', 9, false),)), $this); ?>
<a name="evolutionGraph" graphId="<?php echo $this->_tpl_vars['nameGraphEvolutionReferers']; ?>
"></a>
<h2><?php echo ((is_array($_tmp='Referers_Evolution')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
<?php echo $this->_tpl_vars['graphEvolutionReferers']; ?>


<br />
<div id='leftcolumn'>
	<h2><?php echo ((is_array($_tmp='Referers_Type')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
	<div id='leftcolumn'>
			<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineDirectEntry']), $this);?>

			<?php echo ((is_array($_tmp='Referers_TypeDirectEntries')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['visitorsFromDirectEntry'])."</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['visitorsFromDirectEntry'])."</strong>")); ?>
</div>
			<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineSearchEngines']), $this);?>

			<?php echo ((is_array($_tmp='Referers_TypeSearchEngines')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['visitorsFromSearchEngines'])."</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['visitorsFromSearchEngines'])."</strong>")); ?>
</div>
	</div>
	<div id='rightcolumn'>
			<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineWebsites']), $this);?>

			<?php echo ((is_array($_tmp='Referers_TypeWebsites')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['visitorsFromWebsites'])."</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['visitorsFromWebsites'])."</strong>")); ?>
</div>
			<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineCampaigns']), $this);?>

			<?php echo ((is_array($_tmp='Referers_TypeCampaigns')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['visitorsFromCampaigns'])."</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['visitorsFromCampaigns'])."</strong>")); ?>
</div>
	</div>
</div>

<div id='rightcolumn'>
	<h2><?php echo ((is_array($_tmp='Referers_DetailsByRefererType')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
	<?php echo $this->_tpl_vars['dataTableRefererType']; ?>

</div>

<div style="clear:both;"></div>

<p>View 
	<a href="javascript:broadcast.propagateAjax('module=Referers&action=getSearchEnginesAndKeywords')"><?php echo ((is_array($_tmp='Referers_SubmenuSearchEngines')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>,
	<a href="javascript:broadcast.propagateAjax('module=Referers&action=getWebsites')"><?php echo ((is_array($_tmp='Referers_SubmenuWebsites')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>,
	<a href="javascript:broadcast.propagateAjax('module=Referers&action=getCampaigns')"><?php echo ((is_array($_tmp='Referers_SubmenuCampaigns')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>.
</p>
	

<h2><?php echo ((is_array($_tmp='Referers_Distinct')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
<table cellpadding="15">
<tr><td style="padding-right:50px">
	<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineDistinctSearchEngines']), $this);?>

	<strong><?php echo $this->_tpl_vars['numberDistinctSearchEngines']; ?>
</strong> <?php echo ((is_array($_tmp='Referers_DistinctSearchEngines')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</div>
	<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineDistinctKeywords']), $this);?>

	<strong><?php echo $this->_tpl_vars['numberDistinctKeywords']; ?>
</strong> <?php echo ((is_array($_tmp='Referers_DistinctKeywords')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</div>
</td>
	<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineDistinctWebsites']), $this);?>

	<strong><?php echo $this->_tpl_vars['numberDistinctWebsites']; ?>
</strong> <?php echo ((is_array($_tmp='Referers_DistinctWebsites')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 <?php echo ((is_array($_tmp='Referers_UsingNDistinctUrls')) ? $this->_run_mod_handler('translate', true, $_tmp, "<strong>".($this->_tpl_vars['numberDistinctWebsitesUrls'])."</strong>") : smarty_modifier_translate($_tmp, "<strong>".($this->_tpl_vars['numberDistinctWebsitesUrls'])."</strong>")); ?>
</div>
	<div class="sparkline"><?php echo smarty_function_sparkline(array('src' => $this->_tpl_vars['urlSparklineDistinctCampaigns']), $this);?>
 
	<strong><?php echo $this->_tpl_vars['numberDistinctCampaigns']; ?>
</strong> <?php echo ((is_array($_tmp='Referers_DistinctCampaigns')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</div>
</td></tr>
</table>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "CoreHome/templates/sparkline_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
