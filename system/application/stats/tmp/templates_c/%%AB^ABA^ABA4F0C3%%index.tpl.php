<?php /* Smarty version 2.6.26, created on 2011-02-09 23:50:00
         compiled from C:%5Cxampp%5Cxampp%5Chtdocs%5Ccms%5Csystem%5Capplication%5Cstats%5Cplugins%5CDashboard/templates/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'loadJavascriptTranslations', 'C:\\xampp\\xampp\\htdocs\\cms\\system\\application\\stats\\plugins\\Dashboard/templates/index.tpl', 1, false),array('modifier', 'translate', 'C:\\xampp\\xampp\\htdocs\\cms\\system\\application\\stats\\plugins\\Dashboard/templates/index.tpl', 37, false),)), $this); ?>
<?php echo smarty_function_loadJavascriptTranslations(array('plugins' => 'CoreHome Dashboard'), $this);?>


<script type="text/javascript">
	piwik.dashboardLayout = <?php echo $this->_tpl_vars['layout']; ?>
;
		piwik.availableWidgets = <?php echo $this->_tpl_vars['availableWidgets']; ?>
;
</script>

<?php echo '
<script type="text/javascript">
$(document).ready( function() {
        // Standard dashboard
		if($(\'#periodString\').length) 
		{
		$(\'#addWidget\').css({left:$(\'#periodString\')[0].offsetWidth+25});
        }
		// Embed dashboard
		else 
		{
        	$(\'#addWidget\').css({left:7, top:42});
        }
		piwik.dashboardObject = new dashboard();
		var widgetMenuObject = new widgetMenu(piwik.dashboardObject);
		piwik.dashboardObject.init(piwik.dashboardLayout);
		widgetMenuObject.init();
		$(\'#addWidget .widget_button\').click(function(e){widgetMenuObject.toggle(e);});
		//$(\'#addWidget\').mouseout(function(){});
});
</script>
'; ?>

<div id="dashboard">
 
	<div class="dialog" id="confirm">
	        <h2><?php echo ((is_array($_tmp='Dashboard_DeleteWidgetConfirm')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
			<input id="yes" type="button" value="<?php echo ((is_array($_tmp='General_Yes')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
			<input id="no" type="button" value="<?php echo ((is_array($_tmp='General_No')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
	</div> 
	
	<div id="addWidget">
		<div class="widget_button"><?php echo ((is_array($_tmp='Dashboard_AddWidget')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 <img height="16" width="16" class="arr" src="themes/default/images/sortdesc.png"></div>
	
		<div class="menu" id="widgetChooser">	
			<div class="subMenu" id="sub1"></div>
			<div class="subMenu" id="sub2"></div>
			<div class="subMenu" id="sub3"></div>
			<div class="menuClear"> </div>
		</div>	
	</div>
	
	<div class="clear"></div>
	
	<div id="dashboardWidgetsArea">
		<div class="col" id="1"></div>
		<div class="col" id="2"></div>
		<div class="col" id="3"></div>
		<div class="clear"></div>
	</div>
</div>