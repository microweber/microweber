<?php if(!isset($_REQUEST['no_toolbar'])): ?>
<?php

  if(isset($_COOKIE['last_page'])){
    $past_page = site_url($_COOKIE['last_page']);
    if($past_page != false){
        $cont_by_url = mw('content')->get_by_url($past_page );
    }
    if(isset($cont_by_url) and $cont_by_url == false){
        $past_page=get_content("order_by=updated_on desc&limit=1");
        $past_page = mw('content')->link($past_page[0]['id']);
    }
  }
  else {
  	$past_page=get_content("order_by=updated_on desc&limit=1");
      $past_page = mw('content')->link($past_page[0]['id']);
  }

?>

  <div class="mw-v-table" id="mw_toolbar_nav">
    <a class="mw-cube-holder" id="mw-admin-toolbar-cube" href="<?php print $past_page; ?>?editmode=y">
      <span class="mw-cube">
        <span class="mw-cube1"><span id="mw_toolbar_logo" href="<?php print admin_url(); ?>"></span></span>
        <span class="mw-cube2"><span class="ico ilive"></span><span>Go Live Edit</span></span>
      </span>
    </a>
    <div class="mw-v-cell" style="width: 100%">
  <?php if(is_admin()): ?>
  <?php   $active = mw('url')->param('view'); ?>
    <ul id="mw_tabs">
      <li <?php if($active == 'dashboard' or $active == false): ?>class="active"<?php endif; ?>><a href="<?php print admin_url(); ?>view:dashboard" title="<?php _e("Dashboard"); ?>"><i class="ico inavdashboard"></i><span><?php _e("Dashboard"); ?></span></a></li>
      <li <?php if($active == 'content'): ?> class="active" id="mw-admin-nav-website" <?php endif; ?>><a href="<?php print admin_url(); ?>view:content" title="<?php _e("Website"); ?>"><i class="ico inavwevsite"></i><span><?php _e("Website"); ?></span></a></li>
      <?php event_trigger('mw_admin_header_menu_start'); ?>
      <li <?php if($active == 'modules'): ?> class="active" <?php endif; ?>><a href="<?php print admin_url(); ?>view:modules" title="<?php _e("Modules"); ?>"><i class="ico inavmodules"></i><span><?php _e("Modules"); ?></span></a></li>
      <?php event_trigger('mw_admin_header_menu'); ?>
      <li <?php if($active == 'users'): ?> class="active" <?php endif; ?>><a href="<?php print admin_url(); ?>view:users" title="<?php _e("Users"); ?>"><i class="ico inavusers"></i><span><?php _e("Users"); ?></span></a></li>
      <?php event_trigger('mw_admin_header_menu_end'); ?>
    </ul>
    </div>
    <div class="mw-v-cell">

      <div id="mw-admin-toolbar-right">
        <a title="<?php _e("Logout"); ?>" class="ico ilogout"  href="<?php print mw('url')->api_link('logout'); ?>"><span></span></a>
        <a title="<?php _e("Go Live Edit"); ?>" id="mw-go_livebtn_admin" class="mw-ui-btn mw-ui-btn-blue right back-to-admin-cookie" href="<?php print $past_page; ?>?editmode=y"><span class="ico ilive"></span>
            <?php _e("Go Live Edit"); ?>
        </a>
        <div class="mw-toolbar-notification">
  	        <module type="admin/notifications" view="toolbar"  limit="5" id="mw-header-notif" />
        </div>
        <a href="javascript:;" class="right" id="helpbtn" onclick="mw.helpinfo.init(true);"><?php _e("Help"); ?></a>
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
});
</script>
  <?php endif; ?>
</div>
<?php endif; ?>
<div class="mw_clear" style="height: 0;">&nbsp;</div>
<div id="mw-admin-content">