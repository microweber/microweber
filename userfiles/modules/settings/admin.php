<?php  $rand =  $params['id']; ?>
<?php
only_admin_access();?>
<?  $option_groups = array('website','users','template','email'); ?>
<script  type="text/javascript">



mw.require('forms.js');

</script>
<script  type="text/javascript">
_settingsSort = function(){

    var group = mw.url.windowHashParam('option_group');

if(group != undefined){
 mw.$('#settings_admin_<? print $rand; ?>').attr('option_group',group);
 
}
else{
 mw.$('#settings_admin_<? print $rand; ?>').attr('option_group','website');
}
mw.$('#settings_admin_<? print $rand; ?>').attr('is_system',1);

    mw.load_module('settings/system_settings','#settings_admin_<? print $rand; ?>', function(){
    
    });

}


mw.on.hashParam('ui', _settingsSort);

mw.on.hashParam('option_group', function(){

    if(this!=false){

    mw.$("#settings_admin_categories_<? print $rand; ?> a").removeClass("active");
    mw.$("#settings_admin_categories_<? print $rand; ?> a.item-" + this).addClass("active");
   }
   else{
     mw.$(".mw-admin-side-nav a").removeClass("active");
     mw.$(".mw-admin-side-nav .item-website").addClass("active");
   }

   _settingsSort()

});
mw.on.hashParam('installed', function(){

   _settingsSort();

});

$(document).ready(function(){
  var group = mw.url.windowHashParam('option_group');

  if(typeof group == 'undefined'){

    mw.$(".mw-admin-side-nav .item-website").addClass("active");
  }
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
          <li><a onclick="mw.url.windowHashParam('option_group', 'website');return false;" class="item-website" href="#option_group=website">Website</a></li>
          <li><a onclick="mw.url.windowHashParam('option_group', 'users');return false;" class="item-users" href="#option_group=users">Login & Register</a></li>
          <li><a onclick="mw.url.windowHashParam('option_group', 'template');return false;" class="item-template" href="#option_group=template">Template</a></li>
          <li><a onclick="mw.url.windowHashParam('option_group', 'email');return false;" class="item-email" href="#option_group=website">Email</a></li>
           <? exec_action('mw_admin_settings_menu'); ?>
           
           
            <li><a onclick="mw.url.windowHashParam('option_group', 'advanced');return false;" class="item-advanced" href="#option_group=advanced">Advanced</a></li>
        </ul>
      </div>
      <div style="padding-left: 46px">
        <div class="vSpace"></div>
        <!--<div class="vSpace">&nbsp;</div>
        <a href="javascript:;" class="mw-ui-btn" style="width: 147px;margin-left: -47px;"><span class="ico iplus"></span><span>
        <?php _e("Add new settings"); ?>
        </span></a>--> </div>
    </div>
  </div>
  <div class="mw_edit_page_right" style="padding: 20px;">
    <div class="vSpace"></div>
    <div id="settings_admin_<? print $rand; ?>" >
      <microweber module="settings/system_settings" option_group="website" is_system="1" id="options_list_<? print $rand; ?>">
    </div>
  </div>
</div>
