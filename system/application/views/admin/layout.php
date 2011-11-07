<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html  class="<?php echo css_browser_selector() ?>"  xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:v='urn:schemas-microsoft-com:vml'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<title></title>
<?  include('header_scripts.php'); ?>
<? include('headers_shared.php')  ?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
</head>
<body>
<div id="container" class="mw_admin" align="center">
  <div id="liquid" align="left">
    <div id="header"> <a href="<?php print site_url('admin')  ?>"   id="logo" title="Microweber">Microweber</a>
      <div id="nav"> <a href="<? print ADMIN_URL ?>/action:pages" <? if((url_param('action') == 'pages') or (url_param('action') == 'page_edit')): ?> class="active" <? endif; ?>>Pages</a> <a href="<? print ADMIN_URL ?>/action:posts" <? if((url_param('action') == 'posts') or (url_param('action') == 'post_edit')): ?> class="active" <? endif; ?>>Posts</a> <a href="<? print ADMIN_URL ?>/action:shop" <? if((url_param('action') == 'shop') or (url_param('action') == 'orders')): ?> class="active" <? endif; ?>>Online Shop</a>
        <? /*
        <a href="<? print ADMIN_URL ?>/action:categories" <? if((url_param('action') == 'categories') or (url_param('action') == 'category_edit')): ?> class="active" <? endif; ?>>Categories</a>
        */ ?>
        <? /*
        <a href="<? print ADMIN_URL ?>/action:menus" <? if((url_param('action') == 'menus') or (url_param('action') == 'menu_edit')): ?> class="active" <? endif; ?>>Menus</a>
        */ ?>
        <a href="<? print ADMIN_URL ?>/action:options"  <? if((url_param('action') == 'options') or (url_param('action') == 'option_edit')): ?> class="active" <? endif; ?>>Options</a> <a href="<? print ADMIN_URL ?>/action:users"  <? if((url_param('action') == 'users') or (url_param('edit_user'))): ?> class="active" <? endif; ?>>Users</a> </div>
      <? $p = get_page();
						if(!empty($p)){
							$le_link = page_link($p['id']);
							$le_link = $le_link .'/editmode:y';
							 
							 
							
							
						}?>
      <a class="go_live_edit" href="<? print $le_link  ?>"><span>Go Live Edit</span></a> <a href="<? print ADMIN_URL ?>/action:comments" class="comments_url <? if((url_param('action') == 'comments') or (url_param('action') == 'comment_edit')): ?>active<? endif; ?>"> 12 <span class="comments_arr">&nbsp;</span> </a> </div>
    <!-- /#header -->
    <div id="content"> {content}
      <!-- /.mainbox -->
      <div id="user_info">
        <table width="100%" border="0" cellspacing="3" cellpadding="3">
          <tr valign="middle">
            <td><span>Site:</span> <a class="blue" href="<?php print site_url(); ?>" target="_blank"><?php print site_url(); ?></a> | Logged in as: <strong class="blue"><?php print  user_name(); ?></strong> <small><a title="Log out" class="blue" href="<?php print site_url('login/leave'); ?>">(Log out)</a></small> | <a href="javascript:mw.clear_cache()" class="blue" id="clear_cache_admin_link">Clear site cache</a></td>
            <td><div style="float:right"> <span>Powered by </span> <a class="blue" href="http://microweber.com" target="_blank">Microweber</a></div></td>
          <tr valign="middle">
            <td></td>
            <td><div style="float:right">
               
               <table border="0">
  <tr>
    <td> <a href="https://www.facebook.com/Microweber" target="_blank"><img border="0" src="<?php  print( ADMIN_STATIC_FILES_URL);  ; ?>img/facebook.png" height="16" /></a></td>
    <td> 
               <div id="fb-root"></div>
                
                
                
                
                
                <script src="http://connect.facebook.net/en_US/all.js#appId=134410643301789&amp;xfbml=1"></script>
                <fb:like href="https://www.facebook.com/Microweber"  layout="button_count" width="100" show_faces="false" css="http://microweber.com/global/fb_like.css"  font="verdana"></fb:like></td>
  </tr>
</table>

              
              
              </div></td>
          </tr>
        </table>
      </div>
    </div>
    <!-- /#content -->
  </div>
  <!-- /#wrapper -->
</div>
<div id="quick_live_edit_frame"></div>
<!-- /#container -->
<?  include('footer.php'); ?>
<script type="text/javascript">
try {
  var pageTracker = _gat._getTracker("UA-1065179-34");
  pageTracker._setDomainName('<?php  print $_SERVER['HTTP_HOST'];   ?>');
  pageTracker._setAllowLinker(true);
  pageTracker._trackPageview();
} catch(err) {}
</script>
<div class="helper" for="content" position="top">
  <div class="helper_content"> This is help </div>
</div>
</body>
</html>
