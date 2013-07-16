<?php if(!isset($_REQUEST['no_toolbar'])): ?>
<div>
  <div class="mw-v-table" id="mw_toolbar_nav">
    <div class="mw-v-cell">
       <a href="<?php print admin_url(); ?>view:dashboard" id="mw_toolbar_logo"></a>
    </div>
    <div class="mw-v-cell" style="width: 100%">
  <?php if(is_admin()): ?>
  <?php   $active = url_param('view'); ?>
    <ul id="mw_tabs">
      <li <?php if($active == 'dashboard' or $active == false): ?>class="active"<?php endif; ?>><a href="<?php print admin_url(); ?>view:dashboard"><?php _e("Dashboard"); ?></a></li>
      <li <?php if($active == 'content'): ?> class="active" id="mw-admin-nav-website" <?php endif; ?>><a href="<?php print admin_url(); ?>view:content"><?php _e("Website"); ?></a></li>
      <?php exec_action('mw_admin_header_menu_start'); ?>
      <li <?php if($active == 'modules'): ?> class="active" <?php endif; ?>><a href="<?php print admin_url(); ?>view:modules"><?php _e("Modules"); ?></a></li>
      <?php exec_action('mw_admin_header_menu'); ?>
      <li <?php if($active == 'users'): ?> class="active" <?php endif; ?>><a href="<?php print admin_url(); ?>view:users"><?php _e("Users"); ?></a></li>

    <li><a href="javascript:;" id="helpbtn" onclick="mw.helpinfo.init(true);"><?php _e("Help"); ?></a></li>

  <?php


  /*
      <?php if(is_module('help')): ?>
      <li <?php if($active == 'help'): ?> class="active" <?php endif; ?> ><a href="<?php print admin_url(); ?>view:help"><?php _e("Help"); ?></a></li>
      <?php endif; ?>

*/


?>

      <?php exec_action('mw_admin_header_menu_end'); ?>
    </ul>
    </div>
    <div class="mw-v-cell">
<?php
if(isset($_COOKIE['last_page'])){
	$past_page = site_url($_COOKIE['last_page']);
  if($past_page != false){
    $cont_by_url = get_content_by_url($past_page );
   }
if(isset($cont_by_url) and $cont_by_url == false){
  $past_page=get_content("order_by=updated_on desc&limit=1");
$past_page = content_link($past_page[0]['id']);
}


} else {
	$past_page=get_content("order_by=updated_on desc&limit=1");
$past_page = content_link($past_page[0]['id']);
}  ?>
  <div id="mw-admin-toolbar-right"> <a title="<?php _e("Logout"); ?>" class="ico ilogout"  href="<?php print api_url('logout'); ?>"><span></span></a> <a title="<?php _e("Go Live Edit"); ?>" id="mw-go_livebtn_admin" class="mw-ui-btn mw-ui-btn-blue right back-to-admin-cookie" href="<?php print $past_page; ?>?editmode=y"><span class="ico ilive"></span>
    <?php _e("Go Live Edit"); ?>
    </a>
    <div class="mw-toolbar-notification">
      <?php $notif_count = \mw\Notifications::get('is_read=n&count=1'); ?>
      <span class="mw-ui-btn mw-btn-single-ico mw-ui-btn-hover<?php if( $notif_count == 0): ?> faded<?php endif; ?>"> <span class="ico inotification" id="toolbar_notifications">
      <?php if( $notif_count > 0): ?>
      <sup class="mw-notif-bubble"><?php print  $notif_count ?></sup>
      <?php endif; ?>
      </span> </span>
      <div class="mw-toolbar-notif-items-wrap mw-o-box">
        <div class="mw-o-box-header">
          <h5><?php _e("Latest activity:"); ?></h5>
        </div>
        <module type="admin/notifications" view="toolbar"  limit="5" />
        <a  class="mw-ui-link sell-all-notifications" href="<?php print admin_url('view:admin__notifications'); ?>"><?php _e("See all"); ?></a></div>
    </div>
  </div>
    </div>
  </div>
<script type="text/javascript">

   try{
    mwd.querySelector('#mw_tabs li.active').previousElementSibling.className = 'active-prev';
  }catch(e){}

  $(document).ready(function() {
       var navli = mw.$("#mw_tabs li");
       navli.click(function(){
         if(!$(this).hasClass('active')){
         navli.removeClass('active');
         $(this).addClass('active');}
       });
    var go_livebtn_admin = mw.$("#mw-go_livebtn_admin");
    go_livebtn_admin.click(function(event){
      var ex = mw.$(".mw_admin_edit_content_form").length;
      if(ex > 0){
        mw.tools.confirm("<?php _e("Do you want to save the changes?"); ?>", function(){
          mw.$(".mw_admin_edit_content_form").find('.go-live').click();
          event.stopPropagation();
          event.preventDefault()
          return false;
        });
      }
    });
  mw.$("#toolbar_notifications").click(function(){
     var el = $(this.parentNode);
    if(el.hasClass("active")){
       el.removeClass("active");
       mw.$(".mw-toolbar-notif-items-wrap").invisible();
    }
    else{
       el.addClass("active");
       mw.$(".mw-toolbar-notif-items-wrap").visible();
    }
    $(mwd.body).click(function(e){
      if(!mw.tools.hasParentsWithClass(e.target, 'mw-toolbar-notification')){
          var toolbar_notifications = $(mwd.getElementById('toolbar_notifications').parentNode);
          if(toolbar_notifications.hasClass("active")){
             toolbar_notifications.removeClass("active");
             mw.$(".mw-toolbar-notif-items-wrap").invisible();
          }
      }
    });
  });
});
</script>
  <?php endif; ?>
</div>
<?php endif; ?>
<div class="mw_clear" style="height: 0;">&nbsp;</div>
<div id="mw-admin-content">
