<?php $rand = uniqid(); ?>
<script  type="text/javascript">


 


 

 
 




</script>
<?  $option_groups = get_option_groups(); ?>
<?

 $rand = uniqid(); ?>
<script  type="text/javascript">



mw.require('forms.js');

</script>
<script  type="text/javascript">
_settingsSort = function(){

    var hash = mw.url.getHashParams(window.location.hash);

    hash.ui === undefined ? mw.url.windowHashParam('ui', 'admin') : '' ;
    hash.category === undefined ? mw.url.windowHashParam('category', '0') : '' ;

    var attrs  = mw.url.getHashParams(window.location.hash);
    var holder = mw.$('#settings_admin_<? print $rand  ?>');

    var arr = ['data-show-ui','data-search-keyword','data-option_group','data-installed'], i=0, l=arr.length;

    var sync = ['ui','search','data-option_group','installed'];

    for(;i<l;i++){
      holder.removeAttr(arr[i]);
    }
    for (var x in attrs){
        if(x==='data-option_group' && (attrs[x]==='0' || attrs[x]===undefined)) continue;
        holder.attr(arr[sync.indexOf(x)], attrs[x]);
    }
    mw.load_module('settings/list','#settings_admin_<? print $rand  ?>');

}


mw.on.hashParam('ui', _settingsSort);
mw.on.hashParam('search', function(){
  _settingsSort();

  var field = mwd.getElementById('module_keyword');

  if(!field.focused){
    field.value = this;
  }

});
mw.on.hashParam('data-option_group', function(){
  _settingsSort();
 // $("#mw_index_settings .category_tree a").removeClass('active');
 // $("#mw_index_settings .cat-"+this).addClass('active');
});
mw.on.hashParam('installed', function(){

    _settingsSort();

});
</script>
<div id="mw_index_settings">
  <div class="mw_edit_page_left" id="mw_edit_page_left" style="width: 195px;">
    <h2 style="padding:30px 0 0 25px;"><span class="ico imanage-module"></span>&nbsp;settings</h2>
    <div class="mw-admin-side-nav" id="settings_categories_tree_<? print $rand  ?>" >
      <div id="settings_admin_categories_<? print $rand  ?>">
        <ul>
          <? foreach($option_groups as $item): ?>
          <li><a href="#data-option_group=<? print $item ?>"><? print $item ?></a></li>
          <? endforeach; ?>
        </ul>
      </div>
      <div style="padding-left: 46px">
        <div class="vSpace"></div>
        <label class="mw-ui-label">Show: </label>
        <div onmousedown="mw.switcher._switch(this);" class="mw-switcher unselectable installed_switcher"> <span class="mw-switch-handle"></span>
          <label>Installed
            <input type="radio" name="installed" checked="checked" onchange="mw.url.windowHashParam('installed', 1);" id="installed_1" />
          </label>
          <label>Uninstalled
            <input type="radio" name="installed" onchange="mw.url.windowHashParam('installed', 0);" id="installed_0"  />
          </label>
        </div>
        <div class="vSpace">&nbsp;</div>
        <a href="javascript:;" class="mw-ui-btn-rect" style="width: 147px;margin-left: -47px;"><span class="ico iplus"></span><span>Add new settings</span></a> </div>
    </div>
  </div>
  <div class="mw_edit_page_right" style="padding: 20px;width: 750px;">
    <div class="settings-index-bar"> <span class="mw-ui-label-help font-11 left">Sort settings:</span>
      <input name="module_keyword" id="module_keyword" class="mw-ui-searchfield right" type="text" value="Search for settings"  onkeyup="mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"     />
      <div class="mw_clear"></div>
      <ul class="mw-ui-inline-selector">
        <li>
          <label class="mw-ui-check">
            <input name="module_show" class="mw_settings_filter_show" type="radio" value="admin" checked="checked" onchange="mw.url.windowHashParam('ui', this.value)" />
            <span></span><span>Admin settings</span></label>
        </li>
        <li>
          <label class="mw-ui-check">
            <input name="module_show"  class="mw_settings_filter_show"  type="radio" value="live_edit" onchange="mw.url.windowHashParam('ui', this.value)" />
            <span></span><span>Live edit settings</span></label>
        </li>
        <li>
          <label class="mw-ui-check">
            <input name="module_show"  class="mw_settings_filter_show"  type="radio" value="advanced"  onchange="mw.url.windowHashParam('ui', this.value)" />
            <span></span><span>Advanced</span></label>
        </li>
      </ul>
    </div>
    <div class="vSpace"></div>
    <div id="settings_admin_<? print $rand  ?>" >
      <microweber module="settings/list" data-option_group="website" id="options_list_<? print $rand  ?>">
    </div>
  </div>
</div>
