<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:v='urn:schemas-microsoft-com:vml'>
<head profile="http://gmpg.org/xfn/11">
<script>
function css_browser_selector(u){var ua=u.toLowerCase(),is=function(t){return ua.indexOf(t)>-1},g='gecko',w='webkit',s='safari',o='opera',m='mobile',h=document.documentElement,b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+RegExp.$1):is('firefox/2')?g+' ff2':is('firefox/3.5')?g+' ff3 ff3_5':is('firefox/3.6')?g+' ff3 ff3_6':is('firefox/3')?g+' ff3':is('gecko/')?g:is('opera')?o+(/version\/(\d+)/.test(ua)?' '+o+RegExp.$1:(/opera(\s|\/)(\d+)/.test(ua)?' '+o+RegExp.$2:'')):is('konqueror')?'konqueror':is('blackberry')?m+' blackberry':is('android')?m+' android':is('chrome')?w+' chrome':is('iron')?w+' iron':is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:''):is('mozilla/')?g:'',is('j2me')?m+' j2me':is('iphone')?m+' iphone':is('ipod')?m+' ipod':is('ipad')?m+' ipad':is('mac')?'mac':is('darwin')?'mac':is('webtv')?'webtv':is('win')?'win'+(is('windows nt 6.0')?' vista':''):is('freebsd')?'freebsd':(is('x11')||is('linux'))?'linux':'','js']; c = b.join(' '); h.className += ' '+c; return c;};
css_browser_selector(navigator.userAgent);
if(window.location.hash=='#debug'){
  var s = document.createElement('script');
  s.src = 'https://getfirebug.com/firebug-lite.js';
  document.getElementsByTagName('head')[0].appendChild(s);
}
window.onhashchange = function(){
if(window.location.hash=='#debug'){
  var s = document.createElement('script');
  s.src = 'https://getfirebug.com/firebug-lite.js';
  document.getElementsByTagName('head')[0].appendChild(s);
}
} 
</script>
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
<body  class="body <?php if($user_id == false): ?>user_not_logged<?php else: ?>user_logged<?php endif; ?>">




<? $is_user_on_extend_account_page = url_param('action'); ?>

   <?
$user = user_id ();
if (intval ( $user ) != 0) {
	$user  = get_user($user );
}

?>


<? if( $user != 0 and ($is_user_on_extend_account_page != 'extend-account')) : ?>
<div class="xpire">
  <div class="xpirec"> <span class="xpclose"></span>
 
    <?  $expires = $user['expires_on'];
  $expires = strtotime($expires);
   $expires2 = strtotime('now');

  if($expires < $expires2)  :?>
  
  
<script type="text/javascript">
  $(document).ready(function() {
  
    var html = $('#extend_account_header');

    var width =   460;
    var height = 170;
    modal.init({
       html:html,
       width:width,
       height:height,
       oninit:function(){
            $(this).find(".modalclose").hide();
            $(this).find(".modalclose").remove();
       }
    });
    modal.overlay();
})
</script>




  
  
  
  
  
  
  
  
  
  <div id="extend_account_header">
  
  
    <a href="<? print site_url('dashboard/action:extend-account/ns:true') ?>" class="btn_orange right"><span>Enable Account</span></a>
    <h2>Your account has expired</h2>


    </div>
    <? else : ?>
    <?  $expires = $user['expires_on'];
  $expires = strtotime($expires);
   $expires2 = strtotime('+15 days');

  if($expires < $expires2)  :?>

    <a href="<? print site_url('dashboard/action:extend-account/ns:true') ?>" class="btn right"><span>Extend Account</span></a>

    <h2>Your account will expire on <? print ($user['expires_on']) ?></h2>

  <? endif; ?>
    <? endif; ?>
  </div>
</div>
<? endif; ?>


<div id="container">
<div class="wrap">
  <div id="header"> 
  
  <?php if($user_id == false): ?>
  <a href="<?php print (site_url()); ?>" id="logo">SKID-E-KIDS - Facebook and Myspace for kids, Safe, Fun and very educational</a>
  <?php else: ?>
  <a href="<?php print (site_url('dashboard')); ?>" id="logo">SKID-E-KIDS - Facebook and Myspace for kids, Safe, Fun and very educational</a>
  <?php endif; ?>
  
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
    <a href="#" id="kids_login">Login</a> <a href="<?php print site_url('users/user_action:register') ?>" id="fb_login">Register</a>
    <?php endif; ?>
    <?php else: ?>
    <? /*
    Welcome, <?php print CI::model('users')->getPrintableName($user_id); ?>
*/ ?>
    <ul id="userlog">
      <li id="my"><a href="<?php print (site_url('dashboard')); ?>">Dashboard</a></li>
      <li id="logout"><a href="javascript:mw.users.LogOut();">Log out</a></li>
    </ul>
    <div class="c">&nbsp;</div>
    <ul id="nav">
      <?php $menu_items = $this->content_model->getMenuItemsByMenuUnuqueId('header_menu');	?>
      <?php foreach($menu_items as $item): ?>
      <li <?php if($item['is_active'] == true): ?>  class="active"  <?php endif; ?>  ><a title="<?php print addslashes( $item['item_title'] ) ?>" name="<?php print addslashes( $item['item_title'] ) ?>" href="<?php print $item['the_url'] ?>"><?php print ( $item['item_title'] ) ?></a></li>
      <?php endforeach ;  ?>
    </ul>
    <?php $unread_messages =  CI::model('messages')->messagesGetUnreadCountForUser($user_id);  ?>
    <div id="usernav"> <a href="<? print site_url('dashboard/user_action:my-messages') ?>" id="msg" title="Messages (<?php print $unread_messages; ?>)">
      <?php if($unread_messages > 0) : ?>
      <span><?php print $unread_messages; ?></span>
      <?php endif; ?>
      </a>
      <? $fr_req = friend_requests(); 
	  if(count($fr_req) != 0):
	  ?>
      <a href="<? print site_url('dashboard/action:friend-requests') ?>" id="smile" title="Friends Requests (<? print count($fr_req) ?>)"> <span><? print count($fr_req) ?></span> </a>
      <? else: ?>
      <a href="<? print site_url('dashboard/action:my-friends') ?>" id="smile" title="My friends"></a>
      <?php endif; ?>
      <a href="<? print site_url('dashboard/action:my-videos ') ?>" id="video" title="My Videos"> </a> <a href="<? print site_url('dashboard/action:my-games ') ?>" id="game" title="My Games"> </a>
      <!--      <a href="<? print site_url('dashboard/action:my-friends') ?>" id="net" title="My Friends"> </a>-->
      <?php endif; ?>
    </div>
  </div>
  <!-- /#header -->
</div>
<!-- /.wrap -->
<div id="content">
