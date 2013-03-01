<?php  $rand = uniqid(); ?>
<?  $option_groups = array('website','users','template'); ?>
<script  type="text/javascript">



mw.require('forms.js');

</script>
<script  type="text/javascript">
_settingsSort = function(){

    var hash = mw.url.getHashParams(window.location.hash);

if(hash.option_group != undefined){
 mw.$('#settings_admin_<? print $rand; ?>').attr('option_group',hash.option_group);	
 
}
mw.$('#settings_admin_<? print $rand; ?>').attr('is_system',1);	

    mw.load_module('settings/system_settings','#settings_admin_<? print $rand; ?>', function(){
    
    });

}


mw.on.hashParam('ui', _settingsSort);

mw.on.hashParam('option_group', function(){
  _settingsSort();
});
mw.on.hashParam('installed', function(){

   _settingsSort();

});
</script>
<script type="text/javascript">
	mw.require("options.js");
	mw.require("<?php print $config['url_to_module']; ?>settings.css");
	</script>

<div id="mw_index_settings">
  <div class="mw_edit_page_left" id="mw_edit_page_left" style="width: 195px;">
    <h2 style="padding:30px 0 0 25px;"><span class="ico imanage-module"></span>&nbsp;
      <?php _e("Settings"); ?>
    </h2>
    <div class="mw-admin-side-nav" id="settings_categories_tree_<? print $rand; ?>" >
      <div id="settings_admin_categories_<? print $rand; ?>">
        <ul>
          <? foreach($option_groups as $item): ?>
          <li><a onclick="mw.url.windowHashParam('option_group', '<? print $item ?>');return false;" href="#option_group=<? print $item ?>"><? print ucwords($item) ?></a></li>
          <? endforeach; ?>
        </ul>
      </div>
      <div style="padding-left: 46px">
        <div class="vSpace"></div>
        <div class="vSpace">&nbsp;</div>
        <a href="javascript:;" class="mw-ui-btn" style="width: 147px;margin-left: -47px;"><span class="ico iplus"></span><span>
        <?php _e("Add new settings"); ?>
        </span></a> </div>
    </div>
  </div>
  <div class="mw_edit_page_right" style="padding: 20px;">
    <div class="vSpace"></div>
    <div id="settings_admin_<? print $rand; ?>" >
      <microweber module="settings/system_settings" option_group="website" is_system="1" id="options_list_<? print $rand; ?>">
    </div>
  </div>
</div>
