<?php

/*

type: layout

name: Index

description: Home site layout

*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Twitter Bootstrap</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<script src="{TEMPLATE_URL}assets/js/jquery.js"></script>
<script src="{TEMPLATE_URL}assets/js/google-code-prettify/prettify.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-transition.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-alert.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-modal.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-dropdown.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-scrollspy.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-tab.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-tooltip.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-popover.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-button.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-collapse.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-carousel.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-typeahead.js"></script>
<script src="{TEMPLATE_URL}assets/js/bootstrap-affix.js"></script>
<script src="{TEMPLATE_URL}assets/js/application.js"></script>
<link href="{TEMPLATE_URL}assets/css/bootstrap.css" rel="stylesheet">
<link href="{TEMPLATE_URL}assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="{TEMPLATE_URL}assets/css/docs.css" rel="stylesheet">
<link href="{TEMPLATE_URL}assets/js/google-code-prettify/prettify.css" rel="stylesheet">

<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<link rel="shortcut icon" href="{TEMPLATE_URL}assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{TEMPLATE_URL}assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{TEMPLATE_URL}assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{TEMPLATE_URL}assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="{TEMPLATE_URL}assets/ico/apple-touch-icon-57-precomposed.png">
</head>

<body>
<section id="buttonDropdowns">
  <div class="page-header">
   Modules list
  </div>
  <div class="btn-toolbar" style="margin: 0;" > 
  
  
  <? $temp = array('highlight_code', 'custom_menu', 'site_stats'); ?>
  
  <?  foreach( $temp  as $item) :?>
  <h1><? print $item ?></h1>
    <h3><? print site_url('update.php/download/latest-module/'); ?><? print $item ?></h3>
    <div class="btn-group">
      <?
	  
	   $m = array();
	    $m['action'] = 'install';
		$m['type'] = 'modules';
		$m['name'] = $item;
	  $m['url'] = site_url('update.php/download/latest-module/'.$item);
  $m = serialize( $m);
	  
	   ?>
      <button onclick="window.parent.postMessage('<? print (urlencode($m));  ?>', '*');" class="btn dropdown-toggle">Install this module</button>
    </div>
    
    <? endforeach; ?>
    
    
    <!-- /btn-group --> 
  </div>
  <p>&nbsp;</p>
</section>
</body>
</html>
