<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{content_meta_title}</title>
<meta NAME="Description" CONTENT="{content_meta_description}">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="<? print TEMPLATE_URL ?>css/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/cufon-yui.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/Kozuka_Gothic_Pro_OpenType_700.font.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/Kozuka_Gothic_Pro_OpenType_400.font.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/Helvetica_LT_700.font.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/Myriad_Pro_400.font.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/cufon-replace.js"></script>
<!--  button scroller files -->
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/l10n.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jquery.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jquery_003.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jquery_002.js"></script>
 
<!--
  jCarousel library
-->
<script type="text/javascript" src="<? print TEMPLATE_URL ?>/lib/jquery.jcarousel.min.js"></script>
<!--
  jCarousel skin stylesheet
-->
<link rel="stylesheet" type="text/css" href="<? print TEMPLATE_URL ?>/skins/tango/skin.css" />
<script type="text/javascript">

jQuery(document).ready(function() {
    jQuery('#mycarousel').jcarousel({
    	wrap: 'circular'
    });
});

</script>
<!--  button scroller files -->
<style>
body {
	background-color: #ffffff;
 	background-image: url(<? print TEMPLATE_URL ?>images/innerbg.jpg);
	background-repeat: repeat-x;
	background-position: top;
}
</style>
</head>
<body>
<div class="main" align="center">
<div class="top_stripe" align="center">
  <div class="top_content">
    <div class="join"></div>
    <div class="caption">The Best Stuff for Students!</div>
    <div class="likethis"><img src="<? print TEMPLATE_URL ?>images/likethis.jpg" alt="likehtis" /></div>
    <div class="like_box"><img src="<? print TEMPLATE_URL ?>images/likebut.jpg" alt="like" /></div>
    <div class="buzz"><img src="<? print TEMPLATE_URL ?>images/buzz.jpg" alt="buzz" /></div>
    <div class="chat_text">Start chat with others online</div>
  </div>
</div>
<div class="top_line"></div>
<div class="wrapper">
<div class="header">
  <div class="logo"><img src="<? print TEMPLATE_URL ?>images/logo.png" alt="logog" /></div>
  <div class="ad"><img src="<? print TEMPLATE_URL ?>images/ad.jpg" alt="ad" /></div>
  <div class="nav">
    <microweber module="content/menu"  name="main_menu"  />
    
  </div>
</div>
<div class="container">
