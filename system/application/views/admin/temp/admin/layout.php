<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<?php include_once ('layout_headers.php') ?>
</head>
<!--<body  onunload="GUnload()">-->
<body>

<style>
#MWlogo, #footer{display:none}
#navigation-bar ul li:first-child{
	display:none;
}
</style>
<div id="container">
<div id="loadingDiv">Loading...</div>
<div id="wrapper">
  <!-- <div id="uc" style="font:bold 16px Arial;color:#fff;position:absolute;top:15px;left:50%;margin-left:-93px;">Under Construction</div>-->
  <?php // if($this->session->userdata('user') != false) : ?>
  <div id="header">
  <div id="top">

  <a href="<?php print site_url('admin/content/posts_manage')  ?>" id="MWlogo">Microweber</a>

    <?php $the_user = $this->session->userdata ( 'the_user' );
	  $site_url = site_url ();
	   $exit_url = site_url ('login/leave');
	  ?>

    <?php //var_Dump($the_user); ?>
    <div id="AdminInfo">

        <strong>Site:</strong> <a href="<?php print $site_url ?>" target="_blank">
          <?php print $site_url ?>
          </a>
          <div class="d"></div>
        Hello, <strong><?php print  $the_user['username'] ?></strong> | <a href="javascript:clear_cache_admin_link()" id="clear_cache_admin_link">Clear cache</a> | <a href="<?php print $exit_url ?>">Exit
          <!--<img src="<?php print_the_static_files_url() ; ?>icons/lock_small.png"  border="0" alt=" " />-->
          </a>
    </div>
    <div id="search"> </div>
  </div>
  <div id="navigation-bar" class="wrap">
    <!--
      <ul>
        <li  <?php if( $className == 'index') : ?> class="active" <?php endif; ?>><a href="<?php print site_url() ?>">1</a></li>
             
      </ul>
    -->
    <?php $this->load->view('admin/top_nav'); ?>
  </div>

  <div>
    <?php if(!empty($form_validation_errors)):  ?>
    <div class="error">
      <?php foreach($form_validation_errors as $item):  ?>
      <ul>
        <li><?php print($item); ?></li>
      </ul>
      <?php endforeach ; ?>
    </div>
    <?php endif; ?>

  </div>
  <?php // endif; ?>
  </div> <!-- /Header -->
  <div id="main">
      <!-- Secondary content start -->
     
      <!-- Secondary content end -->
      <!-- Primary content start -->
      {primarycontent}

      <!-- Primary content end -->
  </div>

  <div id="bl">&nbsp;</div>
  <div id="br">&nbsp;</div>
</div><!-- /wrapper -->
<div id="loading"></div>
</div>
<div class="clear" style="height:40px"></div>

<div id="footer">


   Powered by
   <a href="http://microweber.com/" target="_blank" title="CMS">Microweber</a> (<a href="http://www.microweber.info/" target="_blank" title="Microweber documentation">Documentation</a>), &copy; Copyright <?php print date("Y"); ?>, <a href="http://ooyes.net" title="web design company">Mass Media Group</a></td>
    Elapsed time: <?php
	$this->benchmark->mark('end');

	echo $this->benchmark->elapsed_time('start', 'end');?>
      , Memory: <?php echo $this->benchmark->memory_usage();?>





  </div>




<div id="loadingOverlay"></div>
<div id="the_delete_category_dialog_container_cat_id_to_delete" style="display:none;"></div>
<div class="the_edit_categories_ajax_container" style="display:none;"></div>
<div id="the_delete_category_dialog_container" title="Really delete this category?"  style="display:none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>These items will be permanently deleted and cannot be recovered. Are you sure?</p>
</div>

<div id="ooyesTreeContextMenu" class="context">
     <!-- Context Goes Here -->
</div>

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