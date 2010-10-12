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
   <?php require (ACTIVE_TEMPLATE_DIR.'header_user_top.php') ?>
  </div>
  <!-- /#header-top -->
  <?php $menu_items = $this->content_model->getMenuItemsByMenuUnuqueId('main_menu'); ?>
  
  <!-- /#navigation-bar --> 
  
  <span id="footer_left"></span> <span id="footer_right"></span> </div>
<!-- /#header -->
<div id="content-training">
