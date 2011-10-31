<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>{content_meta_title}</title>
<meta NAME="Description" CONTENT="{content_meta_description}">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript">

var img_url = '<? print TEMPLATE_URL ?>/img/'

</script>
<script type="text/javascript" src="<?  print site_url('api/js'); ?>"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" media="all" href="<? print TEMPLATE_URL ?>css/main.css" />
<!--<script src="<? print TEMPLATE_URL ?>js/jquery-1.3.2.min.js" type="text/javascript"></script>
-->
<script src="<? print TEMPLATE_URL ?>js/jquery.scrollTo-1.4.0-min.js" type="text/javascript"></script>
<script src="<? print TEMPLATE_URL ?>js/jquery.serialScroll-1.2.1-min.js" type="text/javascript"></script>
<script src="<? print TEMPLATE_URL ?>js/jquery.localscroll-1.2.6-min.js" type="text/javascript"></script>
<script src="<? print TEMPLATE_URL ?>js/functions.js" type="text/javascript"></script>
<!--[if lt IE 7]>
<script src="<? print TEMPLATE_URL ?>js/pngfix.js" type="text/javascript"></script>
<![endif]-->
</head>
<body class="mw">
<!-- header -->
<div id="header_container">
  <div id="header">
    <!-- logo -->
    <h1 id="logo"><a href="<? print site_url(); ?>">Global Seos</a></h1>
    <!-- /logo -->
    <!-- nav -->
    <div id="nav">
      <? $nav = CI::model ( 'content' )->getMenuItemsByMenuName('main_menu');
		
		//p($nav);
		?>
      <ul>
        <? foreach($nav as $n): ?>
        <li <? if($n["is_active"] == true) : ?>  id="active"  <? endif; ?> ><a href="<? print ucwords($n["url"]); ?>"><span><? print ucwords($n["title"]); ?></span></a></li>
        <? endforeach; ?>
        <!--        <li ><a href="index-2.html"><span>Home</span></a></li>
        <li><a href="portfolio.html"><span>Link Building Services</span></a></li>
        <li><a href="services.html"><span>SEO Blogs</span></a></li>
        <li><a href="contact.html"><span>Ranking Examples</span></a></li>
        <li><a href="contact.html"><span>Why Choose Us</span></a></li>
         <li><a href="contact.html"><span>Contact</span></a></li>-->
      </ul>
    </div>
    <br class="clear-right" />
    <!-- /nav -->
  </div>
</div>
<!-- /header -->

<!-- content -->
