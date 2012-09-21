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
<div class="navbar navbar-inverse">
  <div class="navbar-inner">
    <div class="container-fluid"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a> <a class="brand" href="#">Microweber</a>
      <div class="nav-collapse collapse">
        <p class="navbar-text pull-right"> Logged in as <a href="#" class="navbar-link">Username</a> </p>
        <module type="pages_menu" ul_class_name="nav" />
      </div>
      <!--/.nav-collapse --> 
    </div>
  </div>
</div>
<div class="container-fluid">
<div class="row-fluid">
  <div class="span3">
    <div class="well sidebar-nav">
      <? if(MAIN_PAGE_ID == 0){
		$par = PAGE_ID;  
	  } else {
		  $par = MAIN_PAGE_ID;  
		  
	  }?>
      <module type="pages_menu" ul_class_name="nav nav-list" parent="<? print $par ?>" />
    </div>
    <!--/.well --> 
  </div>
  <!--/span-->
  <div class="span9 edit" field="content">
    <div class="element">
      <h2>Heading</h2>
      <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
      <p><a class="btn" href="#">View details &raquo;</a></p>
    </div>
    <!--/span
    </div>
    <!--/span--> 
  </div>
  <!--/row-->
  
  <hr>
  <footer>
    <p>&copy; Company 2012</p>
  </footer>
</div>
<!--/.fluid-container-->

</body>
</html>
