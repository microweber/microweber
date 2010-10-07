<?

  if (strpos($_SERVER['HTTP_USER_AGENT'],"MSIE 8")) {header("X-UA-Compatible: IE=7");}
  if (strpos($_SERVER['HTTP_USER_AGENT'],"MSIE 8")) {header("X-UA-Compatible: IE=EmulateIE7");}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v='urn:schemas-microsoft-com:vml'>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="googlebot" content="index,follow" />
<meta name="robots" content="index,follow" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="rating" content="GENERAL" />
<meta name="MSSmartTagsPreventParsing" content="TRUE" />
<link rel="start" href="<? print site_url(); ?>" />
<link rel="home" type="text/html" href="<? print site_url(); ?>"  />
<link rel="index" type="text/html" href="<? print site_url(); ?>" />
<meta name="generator" content="Microweber" />
<title>{content_meta_title}</title>
{content_meta_other_code}
<meta name="keywords" content="{content_meta_keywords}" />
<meta name="description" content="{content_meta_description}" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<link rel="alternate" type="application/rss+xml" title="RSS" href="<? print site_url('main/rss'); ?>" />
<link rel="sitemap" type="application/rss+xml" title="Sitemap" href="<? print site_url('main/sitemaps'); ?>" />
<meta name="reply-to" content="<? print $this->content_model->optionsGetByKey ( 'creator_email' ); ?>" />
<link rev="made" href="mailto:<? print $this->content_model->optionsGetByKey ( 'creator_email' ); ?>" />
<meta name="author" content="http://ooyes.net" />
<meta name="language" content="en" />
<meta name="distribution" content="global" />
<? /*  SCRIPTS AND CSS:   */ ?>
<link rel="stylesheet" type="text/css" media="screen" href="<? print TEMPLATE_URL; ?>front/css/fonts.php" />
<link rel="stylesheet" type="text/css" media="screen" href="<? print TEMPLATE_URL; ?>front/css/style.css" />
<script type="text/javascript" src="<? print TEMPLATE_URL; ?>js/jquery.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL; ?>front/js/functions.js"></script>
<? /*   END SCRIPTS AND CSS:  */ ?>
</head>
<body>
<div id="front-container">
<div id="front-wrapper">
<div id="front-header"> <a id="logo" href="<? print site_url(); ?>" title="Home">RUToolz</a>
  <div id="sign-in">
    <? $user_id = $this->core_model->userId(); 
		 
		
		?>
    <? if($user_id > 0): ?>
     <a href="<? print site_url('dashboard'); ?>" id="top-register">Panel</a>  
    <a href="<? print site_url('users/user_action:exit/back_to:').base64_encode(site_url());; ?>" id="top-login">Exit</a>
    <? else: ?>
    <a href="<? print site_url('users/user_action:register'); ?>" id="top-register">Register</a> <a href="#" id="top-login">Login</a>
    <?  endif; ?>
  </div>
  <div class="c">&nbsp;</div>
  <ul id="nav">
    <?	$menu_items = $this->content_model->getMenuItemsByMenuUnuqueId('main_menu_front'); ?>
    <? foreach($menu_items as $item): ?>
    <?  	//$content_id_item = $this->content_model->contentGetByIdAndCache ( $item['content_id'] );

 
      	  	     ?>
    <li><a   <? if($item['content_id'] == $page['id']): ?>  class="active"  <? endif; ?>    href="<? print $item['the_url'] ?>"><? print ucfirst( $item['item_title'] ) ?></a></li>
    <? endforeach ;  ?>
  </ul>
</div>
<div id="front-content">
