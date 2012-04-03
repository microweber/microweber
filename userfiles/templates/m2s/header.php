<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <? include TEMPLATE_DIR. "head_tags.php"; ?>
 <script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jquery.embedly.min.js"></script>
 <style>
 .top_stripe {
    position: fixed;
    width: 100%;
    height: 45px !important;
    top: 0;
    left: 0;
	z-index:999999 !important; 
    
}
body {
    margin-top: 45px;
}
 
 </style>
 
 
 <script type="text/javascript">
 
   $(document).ready(function(){
  $('.tube').embedly({maxWidth: 690, wrapElement: 'div' });
 

    });

 	
 
 
 
 
 
function setHomepage()
{
 if (document.all)
    {
        document.body.style.behavior='url(#default#homepage)';
  document.body.setHomePage('<? print site_url(); ?>');

    }
    else if (window.sidebar)
    {
    if(window.netscape)
    {
         try
   {  
            netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");  
         }  
         catch(e)  
         {  
            alert("this action was aviod by your browser,if you want to enable,please enter about:config in your address line,and change the value of signed.applets.codebase_principal_support to true");  
         }
    } 
    var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components. interfaces.nsIPrefBranch);
    prefs.setCharPref('browser.startup.homepage','<? print site_url(); ?>');
 }
}


	 
	 
</script>


 </head>
<body>
 <? include TEMPLATE_DIR. "toolbar.php"; ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=121363397926685";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="main" align="center">
 
 
 
 

<div class="wrapper">
<div class="header">
  <div class="logo"><a  href="<? print site_url('home'); ?>"><img src="<? print TEMPLATE_URL ?>images/logo_beta.png" alt="Money 2 Study" /></a></div>
  <div class="ad">
    <script language='JavaScript' type='text/javascript'>
<!--
   if (!document.phpAds_used) document.phpAds_used = ',';
   phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);
   
   document.write ("<" + "script language='JavaScript' type='text/javascript' src='");
   document.write ("http://money2study.co.uk/openads/adjs.php?n=" + phpAds_random);
   document.write ("&amp;what=zone:1&amp;target=_blank");
   document.write ("&amp;exclude=" + document.phpAds_used);
   if (document.referrer)
      document.write ("&amp;referer=" + escape(document.referrer));
   document.write ("'><" + "/script>");
//-->
</script>
    <noscript>
    <a href='http://money2study.co.uk/openads/adclick.php?n=a20200bb' target='_blank'><img src='http://money2study.co.uk/openads/adview.php?what=zone:1&amp;n=a20200bb' border='0' alt=''></a>
    </noscript>
  </div>
  <div class="nav">
    <microweber module="content/menu"  name="main_menu"  />
  </div>
</div>
<div class="container">
