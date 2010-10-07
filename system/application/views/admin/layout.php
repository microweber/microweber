<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0051)http://themes.mudodesigns.com/adminbase/index.html# -->
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<LINK type="text/css" rel="stylesheet" media="all" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/base.css">
<LINK type="text/css" rel="stylesheet" media="all" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/jquery-ui.css">
<LINK type="text/css" rel="stylesheet" media="all" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/grid.css">
<LINK type="text/css" rel="stylesheet" media="all" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/visualize.css">
<title>Admin</title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="Robots" content="noindex,nofollow" />
<?php include_once ('layout_headers.php') ?>
<style>
#loadingDiv {
	display:none
}
</style>
</HEAD><BODY>
<DIV id="header">
  <div id="loadingDiv">Loading...</div>
  <DIV class="header-top tr">
    <?php $the_user = $this->session->userdata ( 'the_user' );
	  $site_url = site_url ();
	   $exit_url = site_url ('login/leave');
	  ?>
    <?php //var_Dump($the_user); ?>
    <P>Hello, <strong><?php print  $the_user['username'] ?></strong> | <strong><a href="<?php print $site_url ?>" title="<?php print $site_url ?>" target="_blank">Site</a></strong> | <a href="javascript:clear_cache_admin_link()" id="clear_cache_admin_link">Clear cache</a> | <a href="<?php print $exit_url ?>">Exit 
      <!--<img src="<?php print_the_static_files_url() ; ?>icons/lock_small.png"  border="0" alt=" " />--> 
      </a> </P>
    <!-- start dialogue box --> 
    <!-- end dialogue box --> 
  </DIV>
  <DIV class="header-middle">
    <?php $this->load->view('admin/top_nav'); ?>
    <IMG id="logo" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/logo.png" alt="Admin Theme">
    <?php if(!empty($form_validation_errors)):  ?>
    <DIV class="grid_5">
      <DIV class="box-header"> Notifications </DIV>
      <DIV class="box">
        <DIV class="notification success" style="display: block; "> <SPAN class="strong">SUCCESS!</SPAN> This is a success message. <SPAN class="close" title="Dismiss"></SPAN></DIV>
        <DIV class="notification error" style="display: block; "> <SPAN class="strong">ERROR!</SPAN> This is a error message. <SPAN class="close" title="Dismiss"></SPAN></DIV>
        <DIV class="notification warning" style="display: block; "> <SPAN class="strong">WARNING!</SPAN> This is a warning message. <SPAN class="close" title="Dismiss"></SPAN></DIV>
        <DIV class="notification info" style="display: block; "> <SPAN class="strong">INFORMATION!</SPAN> This is a informative message. <SPAN class="close" title="Dismiss"></SPAN></DIV>
        <DIV class="notification tip" style="display: block; "> <SPAN class="strong">TIP:</SPAN> This is a tip message. <SPAN class="close" title="Dismiss"></SPAN></DIV>
      </DIV>
    </DIV>
    <div class="error">
      <?php foreach($form_validation_errors as $item):  ?>
      <ul>
        <li><?php print($item); ?></li>
      </ul>
      <?php endforeach ; ?>
    </div>
    <?php endif; ?>
    <BR class="cl">
  </DIV>
  <DIV class="header-bottom"> 
    <!-- Start Breadcrumbs -->
    <UL id="breadcrumbs">
      <LI><A href="<?php   print( site_url('admin'));  ?>">Main</A></LI>
      <!-- <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Level One</A> »</LI>
      <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Level Two</A></LI>-->
    </UL>
    <!-- End Breadcrumbs --> 
  </DIV>
</DIV>
<DIV id="page-wrapper">
  <DIV class="page">{primarycontent}</DIV>
</DIV>
<!-- Start Footer -->
<DIV class="footer"> Powered by <a href="http://microweber.com/" target="_blank" title="CMS">Microweber</a> (<a href="http://www.microweber.info/" target="_blank" title="Microweber documentation">Documentation</a>), &copy; Copyright <?php print date("Y"); ?>, <a href="http://ooyes.net" title="web design company">Mass Media Group</a>
  </td>
  Elapsed time:
  <?php
	$this->benchmark->mark('end');

	echo $this->benchmark->elapsed_time('start', 'end');?>
  , Memory: <?php echo $this->benchmark->memory_usage();?> </DIV>
<!-- End Footer -->
<UL class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" role="listbox" aria-activedescendant="ui-active-menuitem" style="z-index: 1; top: 0px; left: 0px; display: none; ">
</UL>
<DIV style="display: none; z-index: 1000; outline-width: 0px; outline-style: initial; outline-color: initial; position: absolute; " class="ui-dialog ui-widget ui-widget-content ui-corner-all  ui-draggable ui-resizable" tabindex="-1" role="dialog" aria-labelledby="ui-dialog-title-dialog">
  <DIV class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix" unselectable="on"><SPAN class="ui-dialog-title" id="ui-dialog-title-dialog" unselectable="on">Messages</SPAN><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm" class="ui-dialog-titlebar-close ui-corner-all" role="button" unselectable="on"><SPAN class="ui-icon ui-icon-closethick" unselectable="on">close</SPAN></A></DIV>
  <DIV id="dialog" class="ui-dialog-content ui-widget-content">
    <DIV class="notification info" style="display: block; "> <SPAN class="strong">INFORMATION!</SPAN> You have 1 new message. <SPAN class="close" title="Dismiss"></SPAN></DIV>
    <!-- start new message -->
    <DIV class="message new">
      <H4>Welcome...<SMALL class="fr">28rd April 2010 from <A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">MaestroX</A></SMALL></H4>
      <P> This is an example of the dialog box. It's powered by jQuery UI so there's tons of options and it's flexible. Checkout the documentation for more information.</P>
    </DIV>
    <!-- end new message --> 
    <!-- start old message -->
    <DIV class="message">
      <H4>Old Message<SMALL class="fr">28rd April 2010 from <A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">System</A></SMALL></H4>
      <P> Welcome to your shiny new admin panel. Take a look around!</P>
    </DIV>
    <!-- end old message --> 
    <!-- start write new message -->
    <FORM method="post" action="">
      <H3>New Message</H3>
      <FIELDSET>
        <TEXTAREA rows="5" cols="55" name="textfield" class="textarea" style="width:97%"></TEXTAREA>
      </FIELDSET>
      <FIELDSET>
        <INPUT type="submit" value="Send" class="button medium fr">
      </FIELDSET>
    </FORM>
    <!-- end write new message --> 
  </DIV>
  <DIV class="ui-resizable-handle ui-resizable-n" unselectable="on"></DIV>
  <DIV class="ui-resizable-handle ui-resizable-e" unselectable="on"></DIV>
  <DIV class="ui-resizable-handle ui-resizable-s" unselectable="on"></DIV>
  <DIV class="ui-resizable-handle ui-resizable-w" unselectable="on"></DIV>
  <DIV class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se ui-icon-grip-diagonal-se" style="z-index: 1001; " unselectable="on"></DIV>
  <DIV class="ui-resizable-handle ui-resizable-sw" style="z-index: 1002; " unselectable="on"></DIV>
  <DIV class="ui-resizable-handle ui-resizable-ne" style="z-index: 1003; " unselectable="on"></DIV>
  <DIV class="ui-resizable-handle ui-resizable-nw" style="z-index: 1004; " unselectable="on"></DIV>
</DIV>
<div id="loadingOverlay"></div>
<div id="the_delete_category_dialog_container_cat_id_to_delete" style="display:none;"></div>
<div class="the_edit_categories_ajax_container" style="display:none;"></div>
<div id="the_delete_category_dialog_container" title="Really delete this category?"  style="display:none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>These items will be permanently deleted and cannot be recovered. Are you sure?</p>
</div>
<div id="ooyesTreeContextMenu" class="context"> 
  <!-- Context Goes Here --> 
</div>
</BODY>
</HTML>