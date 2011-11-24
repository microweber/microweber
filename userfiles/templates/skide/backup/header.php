<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:v='urn:schemas-microsoft-com:vml'>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="googlebot" content="index,follow" />
<meta name="robots" content="index,follow" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="rating" content="GENERAL" />
<meta name="MSSmartTagsPreventParsing" content="TRUE" />
<link rel="start" href="<?php print site_url(); ?>" />
<link rel="home" type="text/html" href="<?php print site_url(); ?>"  />
<link rel="index" type="text/html" href="<?php print site_url(); ?>" />
<meta name="generator" content="Microweber" />
<title>{content_meta_title}</title>
<meta name="keywords" content="{content_meta_keywords}" />
<meta name="description" content="{content_meta_description}" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php print site_url('main/rss'); ?>" />
<link rel="sitemap" type="application/rss+xml" title="Sitemap" href="<?php print site_url('main/sitemaps'); ?>" />
<meta name="reply-to" content="<?php print CI::model('core')->optionsGetByKey ( 'creator_email' ); ?>" />
<link rev="made" href="mailto:<?php print CI::model('core')->optionsGetByKey ( 'creator_email' ); ?>" />
<meta name="author" content="http://ooyes.net | Mass Media Group Ltd" />
<meta name="language" content="en" />
<meta name="distribution" content="global" />
<?php include (ACTIVE_TEMPLATE_DIR.'header_scripts_and_css.php') ?>
</head>
<body class="body <?php if($user_id == false): ?>user_not_logged<?php else: ?>user_logged<?php endif; ?>">
<div id="container">
<div class="wrap">
  <div id="header"> <a href="<?php print (site_url()); ?>" id="logo">SKID-E-KIDS - Facebook and Myspace for kids, Safe, Fun and very educational</a>
    <?php 
  $user_id = CI::model('core')->userId();
  if($user_id == false): ?>
    <script type="text/javascript">
 function logIn(){
   $.post("<?php print site_url('api/user'); ?>", { username: $("#username").val(), password: $("#password").val() , back_to: $("#redirect_after_login").val() },
   function(data){

     if(data.indexOf("{") !=-1){
    	 var data = eval('(' + data + ')');
     }


	 if(data.fail){
		alert(data.fail);
	 } else {
		 if(data.redirect){
			 window.location =data.redirect;
		 } else {
			window.location.reload(); 
		 }
	 }
   }); 
	return false;
 }
</script>



<?php if($user_id == false): ?>

<a href="#" id="kids_login">Kids Login</a>
<a href="#" id="fb_login">Facebook Login</a>


<?php endif; ?>


    <form  onsubmit="return logIn();" id="header_login_form">
      <label>Username</label>

      <input name="username" id="username" type="text" />

      <label>Password</label>

      <input name="password" id="password" type="password" />

      <input  id="redirect_after_login" type="hidden" value="<?php print base64_encode(site_url()); ?>" />
      <input name="Login" type="submit" value="Login"/>
    </form>
    <?php else: ?>
    Welcome, <?php print CI::model('users')->getPrintableName($user_id); ?>
    <ul id="userlog">
      <li id="my"><a href="<?php print (site_url()); ?>">My Skid-e-kids</a></li>
      <li id="logout"><a href="javascript:mw.users.LogOut();">Log out</a></li>
    </ul>
    <div class="c">&nbsp;</div>
    <ul id="nav">
      <?php $menu_items = CI::model('content')->getMenuItemsByMenuUnuqueId('header_menu');	?>
      <?php foreach($menu_items as $item): ?>
      <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
      <?php endforeach ;  ?>
    </ul>
    
    <?php $unread_messages =  CI::model('messages')->messagesGetUnreadCountForUser($user_id);  ?>
    
    <div id="usernav"> <a href="#" id="msg" title="Messages (<?php print $unread_messages; ?>)"><?php if($unread_messages > 0) : ?><span><?php print $unread_messages; ?></span> <?php endif; ?></a> <a href="#" id="smile" title="Friends Requests (2)"> <span>2</span> </a> <a href="#" id="video" title="My Videos"> </a> <a href="#" id="game" title="My Games"> </a> <a href="#" id="net" title="My Friends"> </a>
      <?php endif; ?>
    </div>
  </div>
  <!-- /#header -->
</div>
<!-- /.wrap -->
<div id="content">
