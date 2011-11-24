<?php /* Smarty version 2.6.26, created on 2011-11-22 17:56:26
         compiled from CoreHome/templates/sites_selection.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'CoreHome/templates/sites_selection.tpl', 3, false),array('function', 'url', 'CoreHome/templates/sites_selection.tpl', 5, false),array('function', 'hiddenurl', 'CoreHome/templates/sites_selection.tpl', 11, false),)), $this); ?>
<?php if (! $this->_tpl_vars['show_autocompleter']): ?>
<div class="sites_selection">
	<label><?php echo ((is_array($_tmp='General_Website')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label><span id="selectedSiteName" style="display:none"><?php echo $this->_tpl_vars['siteName']; ?>
</span>
	<span id="sitesSelection">
		<form action="<?php echo smarty_function_url(array('idSite' => null), $this);?>
" method="get">
		<select name="idSite">
		   <?php $_from = $this->_tpl_vars['sites']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
		   		<option value="<?php echo $this->_tpl_vars['info']['idsite']; ?>
" <?php if ($this->_tpl_vars['idSite'] == $this->_tpl_vars['info']['idsite']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['info']['name']; ?>
</option>
		   <?php endforeach; endif; unset($_from); ?>
		</select>
		<?php echo smarty_function_hiddenurl(array('idSite' => null), $this);?>

		<input type="submit" value="go" />
		</form>
	</span>

	<?php echo '<script type="text/javascript">
	$(document).ready(function() {
		var inlinePaddingWidth=22;
		var staticPaddingWidth=34;
		$("#sitesSelection").fdd2div({CssClassName:"custom_select"});
		$("#sitesSelectionWrapper").show();
		if($("#sitesSelection ul")[0]){
			var widthSitesSelection = Math.max($("#sitesSelection ul").width()+inlinePaddingWidth, $("#selectedSiteName").width()+staticPaddingWidth);
			$("#sitesSelectionWrapper").css(\'padding-right\', widthSitesSelection);
			$("#sitesSelection").css(\'width\', widthSitesSelection);
	
			// this will put the anchor after the url before proceed to different site.
			$("#sitesSelection ul li").bind(\'click\',function (e) {
				e.preventDefault();               
				var request_URL = $(e.target).attr("href");
					var new_idSite = broadcast.getValueFromUrl(\'idSite\',request_URL);
					broadcast.propagateNewPage( \'idSite=\'+new_idSite );
			});
		}else{
			var widthSitesSelection = Math.max($("#sitesSelection").width()+inlinePaddingWidth);
			$("#sitesSelectionWrapper").css(\'padding-right\', widthSitesSelection);
		}
	});</script>
	'; ?>

</div>
<?php else: ?>
<div class="sites_autocomplete">
    <label><?php echo ((is_array($_tmp='General_Website')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label>
    <div id="sitesSelectionSearch" class="custom_select">
    
        <a href="javascript:broadcast.propagateNewPage( 'idSite=<?php echo $this->_tpl_vars['idSite']; ?>
' );" class="custom_select_main_link custom_select_collapsed"><?php echo $this->_tpl_vars['siteName']; ?>
</a>
        
        <div class="custom_select_block">
            <div id="custom_select_container">
            <ul class="custom_select_ul_list" >
                <?php $_from = $this->_tpl_vars['sites']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
                    <li><a <?php if ($this->_tpl_vars['idSite'] == $this->_tpl_vars['info']['idsite']): ?> style="display: none"<?php endif; ?> href="javascript:broadcast.propagateNewPage( 'idSite=<?php echo $this->_tpl_vars['info']['idsite']; ?>
');"><?php echo $this->_tpl_vars['info']['name']; ?>
</a></li>
				<?php endforeach; endif; unset($_from); ?>
            </ul>
            </div>
            <div class="custom_select_all" style="clear: both">
				<br />
				<a href="index.php?module=MultiSites&amp;action=index&amp;period=<?php echo $this->_tpl_vars['period']; ?>
&amp;date=<?php echo $this->_tpl_vars['date']; ?>
&amp;idSite=<?php echo $this->_tpl_vars['idSite']; ?>
"><?php echo ((is_array($_tmp='General_MultiSitesSummary')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
			</div>
            
            <div class="custom_select_search">
                <input type="text" length="15" id="websiteSearch" class="inp">
                <input type="hidden" class="max_sitename_width" id="max_sitename_width" value="130" />
                <input type="submit" value="Search" class="but">
				<img title="Clear" id="reset" style="position: relative; top: 4px; left: -44px; cursor: pointer; display: none;" src="plugins/CoreHome/templates/images/reset_search.png"/>
            </div>
        </div>
	</div>
    
	<?php echo '<script type="text/javascript">
$("#sitesSelectionSearch .custom_select_main_link").click(function(){
	$("#sitesSelectionSearch .custom_select_block").toggleClass("custom_select_block_show");
	
		$(\'#websiteSearch\').focus();
	return false;
});
    </script>'; ?>

</div>
<?php endif; ?>