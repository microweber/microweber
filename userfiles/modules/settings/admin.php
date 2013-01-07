<?php $rand = $params['id']; ?>
<?  $option_groups = get_option_groups(); ?>
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
    mw.load_module('settings/list','#settings_admin_<? print $rand  ?>', function(){
      mw.$('#module_keyword').removeClass('loading');
    });

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
    <h2 style="padding:30px 0 0 25px;"><span class="ico imanage-module"></span>&nbsp;<?php _e("Settings"); ?></h2>
    <div class="mw-admin-side-nav" id="settings_categories_tree_<? print $rand  ?>" >
      <div id="settings_admin_categories_<? print $rand  ?>">
        <ul>
          <? foreach($option_groups as $item): ?>
          <li><a onclick="mw.url.windowHashParam('data-option_group', '<? print $item ?>');return false;" href="#data-option_group=<? print $item ?>"><? print $item ?></a></li>
          <? endforeach; ?>
        </ul>
      </div>
      <div style="padding-left: 46px">
        <div class="vSpace"></div>
        <label class="mw-ui-label">Show:</label>
        <div onmousedown="mw.switcher._switch(this);" class="mw-switcher unselectable installed_switcher"> <span class="mw-switch-handle"></span>
          <label>Installed
            <input type="radio" name="installed" checked="checked" onchange="mw.url.windowHashParam('installed', 1);" id="installed_1" />
          </label>
          <label>Uninstalled
            <input type="radio" name="installed" onchange="mw.url.windowHashParam('installed', 0);" id="installed_0"  />
          </label>
        </div>
        <div class="vSpace">&nbsp;</div>
        <a href="javascript:;" class="mw-ui-btn-rect" style="width: 147px;margin-left: -47px;"><span class="ico iplus"></span><span><?php _e("Add new settings"); ?></span></a> </div>
    </div>
  </div>
  <div class="mw_edit_page_right" style="padding: 20px;">
    <div class="settings-index-bar"> <span class="mw-ui-label-help font-11 left"><?php _e("Sort settings:"); ?></span>
      <?php $def = _e("Search for settings", true); ?>
      <input name="module_keyword" id="module_keyword" class="mw-ui-searchfield right" type="text" value="<?php print $def; ?>" data-default='<?php print $def; ?>' onfocus='mw.form.dstatic(event);' onblur='mw.form.dstatic(event);'  onkeyup="mw.form.dstatic(event);mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"     />
      <div class="mw_clear"></div>
      <ul class="mw-ui-inline-selector">
        <li>
          <label class="mw-ui-check">
            <input name="module_show" class="mw_settings_filter_show" type="radio" value="admin" checked="checked" onchange="mw.url.windowHashParam('ui', this.value);" />
            <span></span><span><?php _e("Admin settings"); ?></span></label>
        </li>
        <li>
          <label class="mw-ui-check">
            <input name="module_show"  class="mw_settings_filter_show"  type="radio" value="live_edit" onchange="mw.url.windowHashParam('ui', this.value);" />
            <span></span><span><?php _e("Live edit settings"); ?></span></label>
        </li>
        <li>
          <label class="mw-ui-check">
            <input name="module_show"  class="mw_settings_filter_show"  type="radio" value="advanced"  onchange="mw.url.windowHashParam('ui', this.value);" />
            <span></span><span><?php _e("Advanced"); ?></span></label>
        </li>
      </ul>
    </div>
    <div class="vSpace"></div>
    <div id="settings_admin_<? print $rand  ?>" >

       <microweber module="settings/list" data-option_group="website" id="options_list_<? print $rand  ?>">


      <!-- START THEME BROWSER TAB -->
      <br /><br /><br /><br /><br /><br />
      <br /><br /><br /><br /><br /><br />
      <br /><br /><br /><hr /><br /><br /><br />
      <br /><br /><br /><br /><br /><br />
      <br /><br /><br /><br /><br /><br />

      <script>

      $(document).ready(function(){

          mw.on.scrollBarOnBottom(window, 50, function(){
            var el = this;
            el.pauseScrollCallback();
            $.post("", function(){
                var a = '<li><a title="Nominate"href="javascript:;"><img contenteditable="false"alt="Nominate screenshot."src="http://prothemedesign.com/wp-content/uploads/2010/07/nominate-wordpress-theme-screenshot.jpg"><span class="mw-overlay"></span><span class="mw-ui-theme-list-description"><span class="mw-theme-browser-list-title">Nominate</span><span class="mw_clear"></span><span style="margin-right: 10px;"class="mw-ui-btn mw-ui-btn-medium">Get Started</span><span class="mw-ui-btn mw-ui-btn-medium">Live Demo</span></span></a></li><li><a title="Byline"href="javascript:;"><img contenteditable="false"alt="Byline screenshot."src="http://prothemedesign.com/wp-content/uploads/2011/06/byline-wordpress-theme-screenshot.jpg"><span class="mw-overlay"></span><span class="mw-ui-theme-list-description"><span class="mw-theme-browser-list-title">Byline</span><span class="mw_clear"></span><span style="margin-right: 10px;"class="mw-ui-btn mw-ui-btn-medium">Get Started</span><span class="mw-ui-btn mw-ui-btn-medium">Live Demo</span></span></a></li>';
                $("#mw-theme-browser-list").append(a);
                $("#mw-theme-browser-list").append(a);
                el.continueScrollCallback();
            });
          });


      });

      </script>

      <?php

        $themes = array(
            'Nominate' => 'http://prothemedesign.com/wp-content/uploads/2010/07/nominate-wordpress-theme-screenshot.jpg',
            'Byline' => 'http://prothemedesign.com/wp-content/uploads/2011/06/byline-wordpress-theme-screenshot.jpg',
            'uDesign' => 'http://www.themesmafia.org/wp-content/uploads/2012/09/u-design-theme-screenshot-400.jpg',
            'Impact'=>'http://1.s3.envato.com/files/44039565/Screenshots/01_main_screenshot.__large_preview.png'
        );

       ?>

      <div class="mw-theme-browser">
        <ul id="mw-theme-browser-list">
            <?php foreach($themes as $title=>$url): ?>
            <li>
                <a href="javascript:;" title="<?php print $title; ?>">
                    <img src="<?php print $url; ?>" alt="<?php print $title; ?> <?php _e("screenshot"); ?>." />
                    <span class="mw-overlay"></span>
                    <span class="mw-ui-theme-list-description">
                      <span class="mw-theme-browser-list-title"><?php print $title; ?></span>
                      <span class="mw_clear"></span>
                      <span class="mw-ui-btn mw-ui-btn-medium" style="margin-right: 10px;">Get Started</span>
                      <span class="mw-ui-btn mw-ui-btn-medium">Live Demo</span>
                    </span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
      </div>
      <!-- END THEME BROWSER TAB -->

    </div>
  </div>
</div>
