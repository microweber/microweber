<? if(is_admin() != false): ?>

<a href="<?php print admin_url(); ?>view:dashboard">dashboard</a> <a href="<?php print admin_url(); ?>view:content">content</a> <a href="<?php print admin_url(); ?>view:shop">Online shop</a> <a href="<?php print admin_url(); ?>view:modules">modules</a> <a href="<?php print admin_url(); ?>view:elements">elements</a> <a href="<?php print admin_url(); ?>view:updates">updates</a> <a href="<?php print admin_url(); ?>view:settings">settings</a> |
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
