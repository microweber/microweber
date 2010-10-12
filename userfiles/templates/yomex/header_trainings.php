<?php if (strpos($_SERVER['HTTP_USER_AGENT'],"MSIE 8")) {header("X-UA-Compatible: IE=7");}
  if (strpos($_SERVER['HTTP_USER_AGENT'],"MSIE 8")) {header("X-UA-Compatible: IE=EmulateIE7");}

?>
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
{content_meta_other_code}
<meta name="keywords" content="{content_meta_keywords}" />
<meta name="description" content="{content_meta_description}" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php print site_url('main/rss'); ?>" />
<link rel="sitemap" type="application/rss+xml" title="Sitemap" href="<?php print site_url('main/sitemaps'); ?>" />
<meta name="reply-to" content="<?php print $this->core_model->optionsGetByKey ( 'creator_email' ); ?>" />
<link rev="made" href="mailto:<?php print $this->core_model->optionsGetByKey ( 'creator_email' ); ?>" />
<meta name="author" content="http://ooyes.net" />
<meta name="language" content="en" />
<meta name="distribution" content="global" />
<meta http-equiv="Page-Enter" content="revealtrans(duration=0.0)" />
<meta http-equiv="Page-Exit" content="revealtrans(duration=0.0)" />
<?php include (ACTIVE_TEMPLATE_DIR.'header_scripts_and_css.php') ?>
<?php if(intval($page['id']) == 0){
$page['id'] = 1;

}?>
</head><body class="Training"  id="page-<?php print $page['id']; ?>" >
<div id="container">
<div id="wrapper">
<div id="header">
  <div id="header-top"> <a href="<?php print site_url(); ?>" id="logo">School of Online Business</a>
    <?php if(strval($user_session['is_logged'] ) == 'yes'):  ?>
    <?php $user_data = $this->users_model->getUserById ( $user_session ['user_id'] );   ?>
    <?php //   var_dump($user_data); ?>
    <div id="user-top">
      <div id="user-top-1"> <a class="logout" href="<?php print site_url('users/user_action:exit'); ?>">Logout</a> Hello, <span id="user-name-top"><?php $user_data['first_name']? print $user_data['first_name'] : print $user_data['username'] ;  ?></span> 
      
      
      <?php $msg_count = $this->users_model->messagesGetUnreadCountForUser($user_session ['user_id']);  ?>
      <?php if($msg_count > 0): ?>
      <a href="<?php print site_url('dashboard/action:messages/show_inbox:1'); ?>" class="inbox hasInbox"><?php print  $msg_count ; ?></a>
      <?php else : ?>
      <a href="<?php print site_url('dashboard/action:messages/show_inbox:1'); ?>" class="inbox"><?php print  $msg_count ; ?></a>
      <?php endif;  ?>
      
      <?php $msg_count = $this->users_model->notificationsGetUnreadCountForUser($user_session ['user_id']);  ?>
      <?php if($msg_count > 0): ?>
      <a href="<?php print site_url('dashboard/action:notifications/unreaded:1'); ?>" class="inbox_notification hasInbox"><?php print  $msg_count ; ?></a>
      <?php else : ?>
      <a href="<?php print site_url('dashboard/action:notifications'); ?>" class="inbox_notification"><?php print  $msg_count ; ?></a>
      <?php endif;  ?>
      
      <br />
      </div>
      <div id="user-top-2">
        <?php $thumb = $this->users_model->getUserThumbnail( $user_data['id'], 40); ?>
        <?php if($thumb != ''): ?>
   <!--     <a href="<?php print site_url('users/user_action:profile'); ?>" class="img"> <span style="background-image:url(<?php print $thumb; ?>)"></span> </a>-->
        
         <a href="<?php print site_url('dashboard'); ?>" class="img"> <span style="background-image:url(<?php print $thumb; ?>)"></span> </a>
        <?php endif; ?>
        <ul id="user-top-nav">
        <li> <a href="<?php print site_url('dashboard'); ?>" <?php if(getCurentURL() == site_url('dashboard') ) :  ?> class="active" <?php endif; ?> > Dashboard</a> </li>
        
        
<!--          <li> <a href="<?php print site_url('users/user_action:posts'); ?>">All my posts</a> </li>
          <li> <a href="<?php print site_url('users/user_action:post'); ?>">Add post</a> </li>-->
          <li> <a class="last<?php if(getCurentURL() == site_url('users/user_action:profile') ) :  ?> active<?php endif; ?>" href="<?php print site_url('users/user_action:profile'); ?>">Profile</a> </li>
        </ul>
      </div>
    </div>
    <?php //var_dump($shipping_service = $this->session->userdata ( 'user' )) ; ?>
    <?php else :  ?>
    <div id="login-or-register"> <a href="<?php print site_url('users/user_action:register'); ?>" id="top-join-free">Join Free</a> <span>OR</span> <a href="#" id="top-login">Login</a>
    
    
     </div>
    <form style="display:none;" id="header-login" action="<?php print site_url('users/user_action:login'); ?>" method="post">
      <label>Username</label>
      <span>
      <input name="username"  class="text-field" type="text" value="<?php print $form_values['username'];  ?>" />
      </span>
      <label>Password</label>
      <span>
      <input name="password" class="text-field" type="password" value="<?php print $form_values['password'];  ?>" />
      </span>
      <input type="submit" class="submit-field" value="" />
    </form>
    <!-- <a href="<?php print site_url('users/user_action:login'); ?>" class="<?php if($user_action == 'login') : ?> active<?php endif; ?>">Login</a>
          <a href="<?php print site_url('users/user_action:register'); ?>" class="<?php if($user_action == 'register') : ?> active<?php endif; ?>">Register</a>-->
    <?php endif; ?>
  </div>
  <!-- /#header-top -->
  <?php $menu_items = $this->content_model->getMenuItemsByMenuUnuqueId('main_menu'); ?>

  <!-- /#navigation-bar -->

  <span id="footer_left"></span> <span id="footer_right"></span>

</div>
<!-- /#header -->
<div id="content-training">
