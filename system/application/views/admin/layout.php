<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html  class="<?php echo css_browser_selector() ?>"  xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:v='urn:schemas-microsoft-com:vml'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title></title>
<?  include('header_scripts.php'); ?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
</head>
<body>
<div id="container" align="center">
  <div id="liquid" align="left">
    <div id="header"> <a href="<?php print site_url('admin')  ?>" target="_blank" id="logo" title="MicroWeber">MicroWeber</a>
      <div id="user_info">
        <div id="support_nav_block">
          <div class="item"> <span>Site:</span> <a class="blue" href="<?php print site_url(); ?>" target="_blank"><?php print site_url(); ?></a> </div>
          <div class="item"> Logged in as: <strong class="blue"><?php print  user_name(); ?></strong> | <a href="javascript:mw.clear_cache()" class="blue" id="clear_cache_admin_link">Clear cache</a>| <a href="javascript:;" class="blue" onclick="$('#mw_console').toggle()"><em>Console</em></a> </div>
        </div>
        <ul id="support_nav">
          <li><a href="#" title="Support">Support</a>&nbsp;|&nbsp;</li>
          <li><a title="Log out" href="<?php print $exit_url ?>">Log out</a></li>
        </ul>
      </div>
      <div id="nav"> <a href="<? print ADMIN_URL ?>/action:pages" <? if((url_param('action') == 'pages') or (url_param('action') == 'page_edit')): ?> class="active" <? endif; ?>>Pages</a> <a href="<? print ADMIN_URL ?>/action:posts" <? if((url_param('action') == 'posts') or (url_param('action') == 'post_edit')): ?> class="active" <? endif; ?>>Posts</a> <a href="<? print ADMIN_URL ?>/action:shop" <? if((url_param('action') == 'shop') or (url_param('action') == 'orders')): ?> class="active" <? endif; ?>>Online Shop</a> <a href="<? print ADMIN_URL ?>/action:categories" <? if((url_param('action') == 'categories') or (url_param('action') == 'category_edit')): ?> class="active" <? endif; ?>>Categories</a> <a href="<? print ADMIN_URL ?>/action:menus" <? if((url_param('action') == 'menus') or (url_param('action') == 'menu_edit')): ?> class="active" <? endif; ?>>Menus</a> <a href="<? print ADMIN_URL ?>/action:options"  <? if((url_param('action') == 'options') or (url_param('action') == 'option_edit')): ?> class="active" <? endif; ?>>Options</a> 
      
      <a href="<? print ADMIN_URL ?>/action:users"  <? if((url_param('action') == 'users') or (url_param('edit_user'))): ?> class="active" <? endif; ?>>Users</a>
      
      <a href="<? print ADMIN_URL ?>/action:comments" <? if((url_param('action') == 'comments') or (url_param('action') == 'comment_edit')): ?> class="active" <? endif; ?>>Comments</a> </div>
    </div>
    <!-- /#header -->
    <div id="content">
      <div class="box radius mainbox">
        <div class="box_header radius_t">
            <h2>Create new page</h2>
        </div>
        <div class="box_content">
            {content}
        </div>
      </div>

      <!-- /.mainbox -->
    </div>
    <!-- /#content -->
  </div>
  <!-- /#wrapper -->
</div>
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
</body>
</html>
