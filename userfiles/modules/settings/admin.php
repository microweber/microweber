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
 mw.$('#settings_admin_<?php print $rand; ?>').attr('option_group','admin__modules');
}
  mw.$('#settings_admin_<?php print $rand; ?>').attr('is_system',1);

    mw.tools.loading(mwd.querySelector('#edit-content-row'), true)

    mw.load_module('settings/system_settings','#settings_admin_<?php print $rand; ?>', function(){
      mw.tools.loading(mwd.querySelector('#edit-content-row'), false)
    });

}


mw.on.hashParam('ui', _settingsSort);

mw.on.hashParam('option_group', function(){

    if(this!=false){

    mw.$("#settings_admin_categories_<?php print $rand; ?> a").removeClass("active");
    mw.$("#settings_admin_categories_<?php print $rand; ?> a.item-" + this).addClass("active");
   }
   else{
     mw.url.windowHashParam('option_group', 'admin__modules');
   }

   _settingsSort()

    $(".active-parent li.active, #mw-admin-main-menu .active .active").removeClass('active');
    var link = $('a[href*="option_group='+this+'"]');

    link
        .parent()
        .addClass('active');

});
if(!mw.url.windowHashParam('option_group') ){
  //mw.url.windowHashParam('option_group', 'admin__modules');
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

    <div id="settings_admin_<?php print $rand; ?>" >

    </div>

</div>
<?php  show_help('settings');  ?>
