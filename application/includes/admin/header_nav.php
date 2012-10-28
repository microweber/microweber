<div id="mw_toolbar_nav"> <a href="<?php print admin_url(); ?>view:dashboard" id="mw_toolbar_logo"></a>
  <? if(is_admin()): ?>
  <?   $active = url_param('view'); ?>
  <?php /* <ul id="mw_tabs">
  <li <?php if($active == 'dashboard'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:dashboard">Dashboard</a></li>
  <li <?php if($active == 'content'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:content">Content</a></li>
  <li <?php if($active == 'shop'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:shop">Online shop</a></li>
  <li <?php if($active == 'modules'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:modules">Modules</a></li>
  <li <?php if($active == 'elements'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:elements">Elements</a></li>
  <li <?php if($active == 'updates'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:updates">Updates</a></li>
  <li <?php if($active == 'settings'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:settings">Settings</a></li>
  <li <?php if($active == 'comments'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:comments">Comments</a></li>
</ul> */ ?>
  <ul id="mw_tabs">
    <li <?php if($active == 'dashboard'): ?>class="active"<? endif; ?>><a href="<?php print admin_url(); ?>view:dashboard">Dashboard</a></li>
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
  <a href="<?php print $past_page; ?>/editmode:y">go live edit</a> | <a href="<?php print api_url('logout'); ?>">logout</a>
  <? endif; ?>
</div>
