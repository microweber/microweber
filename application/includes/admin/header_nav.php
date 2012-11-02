<div id="mw_toolbar_nav"> <a href="<?php print admin_url(); ?>view:dashboard" id="mw_toolbar_logo"></a>
  <? if(is_admin()): ?>
  <?   $active = url_param('view'); ?>

  <ul id="mw_tabs">
    <li <?php if($active == 'dashboard' or $active == false): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:dashboard">Dashboard</a></li>
    <li <?php if($active == 'content'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:content">Website</a></li>
    <li <?php if($active == 'shop'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:shop">Online Shop</a></li>
    <li <?php if($active == 'settings'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:settings">Settings</a></li>
    <li><a href="#">Users</a></li>
    <li><a href="#">Help</a></li>
  </ul>
  <?

if(isset($_COOKIE['last_page'])){
	$past_page = site_url($_COOKIE['last_page']);
} else {
	$past_page=get_content("limit=1");
$past_page = content_link($past_page[0]['id']);
}


//d($past_page);
 ?>

   <a title="<?php _e("Logout"); ?>" class="ico ilogout right" style="margin: 11px 20px 0 5px;" <?php /* class="mw-ui-btn right" */ ?> href="<?php print api_url('logout'); ?>"><span></span></a>
  <a title="<?php _e("Go Live Edit"); ?>" class="mw-ui-btn right" href="<?php print $past_page; ?>/editmode:y"><span class="ico ilive"></span><?php _e("Go Live Edit"); ?></a>




  <? endif; ?>
</div>
