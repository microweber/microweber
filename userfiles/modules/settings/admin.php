<?php  $rand =  $params['id']; ?>
<?php
only_admin_access();?>
<?php  $option_groups = array('website','users','template','email'); ?>
<script  type="text/javascript">



mw.require('forms.js');

</script>
<style>
  .mw_edit_page_right{
    padding: 20px;
  }

  @media (max-width:768px){
    .mw_edit_page_right{
      padding-left: 0;
      padding-right: 0;
    }
  }

</style>
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

});
if(!mw.url.windowHashParam('option_group') ){
  mw.url.windowHashParam('option_group', 'admin__modules');
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

    mw.on.hashParam('option_group', function(){


        $(".active-parent li.active").removeClass('active');
        var link = $('a[href*="?option_group='+this+'"]');

        link
            .parent()
            .addClass('active');
    })
</script>

<div id="edit-content-row" class="mw-ui-row">

  <div class="mw_edit_page_right">
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
