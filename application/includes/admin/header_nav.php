<div id="mw_toolbar_nav"> <a href="<?php print admin_url(); ?>view:dashboard" id="mw_toolbar_logo"></a>
  <? if(is_admin()): ?>
  <?   $active = url_param('view'); ?>
  <div id="mw-menu-liquify">
    <ul id="mw_tabs">
      <li <?php if($active == 'dashboard' or $active == false): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:dashboard">Dashboard</a></li>
      <li <?php if($active == 'content'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:content">Website</a></li>
      <? exec_action('mw_admin_header_menu_start'); ?>
       <li <?php if($active == 'modules'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:modules">Modules</a></li>
      <? exec_action('mw_admin_header_menu'); ?>
       <li <?php if($active == 'users'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:users">Users</a></li>
      <? //exec_action('mw_admin_header_menu'); ?>

       <li><a href="#">Help</a></li>
       <? exec_action('mw_admin_header_menu_end'); ?>
    </ul>

  </div>
  <div id="menu-dropdown" class="unselectable" onclick="mw.tools.toggle('#menu-dropdown-nav', this);"><div id="menu-dropdown-nav"></div></div>
  <?

if(isset($_COOKIE['last_page'])){
	$past_page = site_url($_COOKIE['last_page']);
} else {
	$past_page=get_content("order_by=updated_on desc&limit=1");
$past_page = content_link($past_page[0]['id']);
}


// d($past_page);
 ?>
 <div id="mw-toolbar-right">
  <a title="<?php _e("Logout"); ?>" class="ico ilogout right" style="margin: 13px 20px 0 5px;" <?php /* class="mw-ui-btn right" */ ?> href="<?php print api_url('logout'); ?>"><span></span></a>
  <a title="<?php _e("Go Live Edit"); ?>" class="mw-ui-btn right" href="<?php print $past_page; ?>/editmode:y"><span class="ico ilive"></span><?php _e("Go Live Edit"); ?></a>
 </div>
  <? endif; ?>
</div>
<div class="mw_clear" style="height: 0;">&nbsp;</div>
<div id="mw-admin-content">
