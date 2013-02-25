<?php //$rand = $params['id']; ?>
<?  $option_groups = get_option_groups(1); ?>
<script  type="text/javascript">



mw.require('forms.js');

</script>
<script  type="text/javascript">
_settingsSort = function(){

    var hash = mw.url.getHashParams(window.location.hash);

if(hash.option_group != undefined){
 mw.$('#settings_admin_{rand}').attr('option_group',hash.option_group);	
 
}
mw.$('#settings_admin_{rand}').attr('is_system',1);	

    mw.load_module('settings/list','#settings_admin_{rand}', function(){
    
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

<div id="mw_index_settings">
  <div class="mw_edit_page_left" id="mw_edit_page_left" style="width: 195px;">
    <h2 style="padding:30px 0 0 25px;"><span class="ico imanage-module"></span>&nbsp;
      <?php _e("Settings"); ?>
    </h2>
    <div class="mw-admin-side-nav" id="settings_categories_tree_{rand}" >
      <div id="settings_admin_categories_{rand}">
        <ul>
          <? foreach($option_groups as $item): ?>
          <li><a onclick="mw.url.windowHashParam('option_group', '<? print $item ?>');return false;" href="#option_group=<? print $item ?>"><? print $item ?></a></li>
          <? endforeach; ?>
        </ul>
      </div>
      <div style="padding-left: 46px">
        <div class="vSpace"></div>
        <!--<label class="mw-ui-label">Show:</label>
        <div onmousedown="mw.switcher._switch(this);" class="mw-switcher unselectable installed_switcher"> <span class="mw-switch-handle"></span>
          <label>Installed
            <input type="radio" name="installed" checked="checked" onchange="mw.url.windowHashParam('installed', 1);" id="installed_1" />
          </label>
          <label>Uninstalled
            <input type="radio" name="installed" onchange="mw.url.windowHashParam('installed', 0);" id="installed_0"  />
          </label>
        </div>-->
        <div class="vSpace">&nbsp;</div>
        <a href="javascript:;" class="mw-ui-btn-rect" style="width: 147px;margin-left: -47px;"><span class="ico iplus"></span><span>
        <?php _e("Add new settings"); ?>
        </span></a> </div>
    </div>
  </div>
  <div class="mw_edit_page_right" style="padding: 20px;">
     
    <div class="vSpace"></div>
    <div id="settings_admin_{rand}" >
      <microweber module="settings/list" option_group="website" is_system="1" id="options_list_{rand}">
    </div>
  </div>
</div>
