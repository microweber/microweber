<?php  $rand =  $params['id']; ?>
<?php
only_admin_access();?>
<?php  $option_groups = array('website','users','template','email'); ?>
<script  type="text/javascript">



mw.require('forms.js');

</script>
<script  type="text/javascript">
_settingsSort = function(){

    var group = mw.url.windowHashParam('option_group');

if(group != undefined){
 mw.$('#settings_admin_<?php print $rand; ?>').attr('option_group',group);
 
}
else{
 mw.$('#settings_admin_<?php print $rand; ?>').attr('option_group','website');
}
mw.$('#settings_admin_<?php print $rand; ?>').attr('is_system',1);

    mw.load_module('settings/system_settings','#settings_admin_<?php print $rand; ?>', function(){
    
    });

}


mw.on.hashParam('ui', _settingsSort);

mw.on.hashParam('option_group', function(){

    if(this!=false){

    mw.$("#settings_admin_categories_<?php print $rand; ?> a").removeClass("active");
    mw.$("#settings_admin_categories_<?php print $rand; ?> a.item-" + this).addClass("active");
   }
   else{
     mw.url.windowHashParam('option_group', 'website');
   }

   _settingsSort()

});
if(!mw.url.windowHashParam('option_group') ){
  mw.url.windowHashParam('option_group', 'website');
}


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

<div id="edit-content-row" class="mw-ui-row">
    <div class="mw-ui-col tree-column">

    <div class="tree-column-holder">
    <div class="mw-admin-sidebar">
    <div class="fixed-side-column scroll-height-exception-master">

            <div class="admin-side-box">
                <h2 class="mw-side-main-title scroll-height-exception"><span class="mw-icon-gear"></span><?php _e("Settings"); ?></h2>
            </div>
              <div class="fixed-side-column-container">
              <div class="mw-admin-side-nav" id="settings_categories_tree_<?php print $rand; ?>" >
                <div id="settings_admin_categories_<?php print $rand; ?>">
                  <ul class="mw-ui-sidenav">
                    <li><a onclick="mw.url.windowHashParam('option_group', 'website');return false;" class="item-website" href="#option_group=website"><?php _e("Website"); ?></a></li>
          		    <li><a onclick="mw.url.windowHashParam('option_group', 'template');return false;" class="item-template" href="#option_group=template"><?php _e("Template"); ?></a></li>
                    <li><a onclick="mw.url.windowHashParam('option_group', 'users');return false;" class="item-users" href="#option_group=users"><?php _e("Login & Register"); ?></a></li>
                    <li><a onclick="mw.url.windowHashParam('option_group', 'email');return false;" class="item-email" href="#option_group=website"><?php _e("Email"); ?></a></li>
<?php event_trigger('mw_admin_settings_menu'); ?>
<?php $settings_menu =  mw()->modules->ui('admin.settings.menu'); ?>
<?php if(is_array($settings_menu) and !empty($settings_menu)): ?>
<?php foreach($settings_menu as $item): ?>
<?php $module = ( isset( $item['module'])) ? module_name_encode($item['module']) : false ; ?>
<?php $title = ( isset( $item['title'])) ? ($item['title']) : false ; ?>
<?php $class = ( isset( $item['class'])) ? ($item['class']) : false ; ?>
<?php if($module != 'xxshop__settings') { ?>


<li><a onclick="mw.url.windowHashParam('option_group', '<?php print $module ?>');return false;" class="<?php print $class ?>" href="#option_group=<?php print $module ?>"><?php print $title ?></a></li>

<?php } ?>

<?php endforeach; ?>
<?php endif; ?>
                    
<?php $got_lic = mw()->update->get_licenses('count=1') ?>
<?php if(($got_lic) > 0): ?>
   <li><a onclick="mw.url.windowHashParam('option_group', 'licenses');return false;" class="item-advanced" href="#option_group=licenses"><?php _e("Licenses"); ?></a></li>
<?php endif; ?>
 
                    <li><a onclick="mw.url.windowHashParam('option_group', 'advanced');return false;" class="item-advanced" href="#option_group=advanced"><?php _e("Advanced"); ?></a></li>
                    <li><a onclick="mw.url.windowHashParam('option_group', 'language');return false;" class="item-language" href="#option_group=language"><?php _e("Language"); ?></a></li>
                  </ul>
                </div>
              </div>
              </div>



    </div>
    </div>
    </div>
  </div>
  <div class="mw_edit_page_right" style="padding: 20px;">
    
    <div id="settings_admin_<?php print $rand; ?>" >
        <?php
        /*

         <module type="settings/system_settings" option_group="website" is_system="1" id="options_list_<?php print $rand; ?>">

        */

        ?>
    </div>
  </div>
</div>



  <?php  show_help('settings');  ?>

