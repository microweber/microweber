<?

  if (strpos($_SERVER['HTTP_USER_AGENT'],"MSIE 8")) {header("X-UA-Compatible: IE=7");}
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
<? include (ACTIVE_TEMPLATE_DIR.'header_scripts_and_css.php') ?>
<? if(intval($page['id']) == 0){
$page['id'] = 1;

}?>
</head>
<body>
<? if($no_content == false): ?>
<div id="RU-container">
<!--HEADER-->
<div id="RU-header">
  <div class="pad1"></div>
  <div class="h-left">
    <div class="left"> <a href="#" title="#"><img src="<? print TEMPLATE_URL; ?>images/logo.gif" alt="Ru Tools"/></a> </div>
    <div class="right">
      <ul>
        <li><a href="<? print site_url(''); ?>" title="#" class="home">Home</a></li>
        <li class="settings"> <a href="<? print site_url('users/user_action:profile/'); ?>" class="<? ($user_action == 'profile') ? print 'active' : ''?>">Settings</a>
          <? /*
          <ul>
            <li><a href="#" title="#">Setting</a></li>
            <li><a href="#" title="#">Setting</a></li>
            <li><a href="#" title="#">Setting</a></li>
            <li><a href="#" title="#">Setting</a></li>
            <li><a href="#" title="#">Setting</a></li>
          </ul>
          */ ?>
        </li>
      </ul>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
  <div class="h-right">
    <div id="dynatip">&nbsp;</div>
    <ul id="dyna">
      
      <!--    	<li> <a href="<? print $link = $this->content_model->getContentURLById(486) ?>" title="This Manager will helps you to create the perfect sales page" class="page-mng<? ($page['id'] == '486') ? print ' active' : ''?>">Pages manager</a></li>-->
      
      <li> <a href="<? print site_url('users/user_action:posts/'); ?>/type:none" title="This Manager will helps you to create the perfect sales page" class="page-mng<? ($pages_manager_active == true) ? print ' active' : ''?>">Pages manager</a></li>
      <li><a href="<? print site_url('users/user_action:posts/'); ?>/type:form" title="This Manager will helps you to create the perfect form for your online trade" class="form-mng<? ($forms_manager_active == true) ? print ' active' : '' ?>">Form Manager </a></li>
      <li> <a href="<? print site_url('users/user_action:content-groups/'); ?>" title="This Manager will helps you to create and check your campains" class="campain-mng<? ($campaigns_manager_active == true) ? print ' active' : ''?>">Campain Manager</a></li>
      
      <!--     <li> <a href="#" title="This Manager will helps you to create and check your campains" class="campain-mng">Campain Manager</a></li>-->
      <li> <a href="#" title="This Manager will helps you to create affiliates pages" class="affiliate-mng">Affiliates Manager</a></li>
    </ul>
    <div class="clr"></div>
  </div>
  <!--END HEADER-->
  <div class="clr"></div>
</div>

<!--CONTENT--> 
<? endif; ?>