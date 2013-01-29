<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Scaffolding · Bootstrap</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="<? print $config['url_to_module'] ?>static/css/bootstrap.css" rel="stylesheet">
<link href="<? print $config['url_to_module'] ?>static/css/bootstrap-responsive.css" rel="stylesheet">
<link href="<? print $config['url_to_module'] ?>static/css/docs.css" rel="stylesheet">
<link href="<? print $config['url_to_module'] ?>static/js/google-code-prettify/prettify.css" rel="stylesheet">
<link href="<? print $config['url_to_module'] ?>static/help.css" rel="stylesheet">

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<!-- Le fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<? print $config['url_to_module'] ?>static/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<? print $config['url_to_module'] ?>static/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<? print $config['url_to_module'] ?>static/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<? print $config['url_to_module'] ?>static/ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="<? print $config['url_to_module'] ?>static/ico/favicon.png">
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar">
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid"> <a class="brand" href="<? print $config['module_view'] ?>">MW Help</a>
      <div class="nav">
        <form class="navbar-form pull-right">
          <input name="kw" type="text" class="span2" <? if(isset($_GET['kw'])): ?> value="<?  print $_GET['kw'] ?>" <? endif; ?> />
          <button type="submit" class="btn btn-mini">Search</button>
        </form>
        <ul class="nav pull-left">
          <li class="active"><a href="<? print $config['module_view'] ?>?docs_path=all">Home</a></li>
          <li><a href="?docs_path=php api">PHP Api</a></li>
          <li><a href="?docs_path=js api">JS Api</a></li>
        </ul>
      </div>
      <!--/.nav-collapse --> 
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span3">
      <div class="well sidebar-nav">
      
      <? if(isset($_GET['docs_path'])){
		  $base_path = $_GET['docs_path'];
		  if( $base_path == 'all'){
			  		  session_set('docs_path',false);
					   $base_path = false;

		  } else {
			  		  session_set('docs_path',$_GET['docs_path']);

		  }
	  } else {
		   $base_path = session_get('docs_path');
	  }
	  ?>
        <module type="help/browser" <? if(isset($base_path)): ?> base_path="<? print $base_path ?>" <? endif; ?>  ul_class="nav nav-list" />
        
        <!-- <ul class="nav nav-list">
          <li class="nav-header">Sidebar</li>
          <li class="active"><a href="#">Link</a></li>
          <li><a href="#">Link</a></li>
          <li><a href="#">Link</a></li>
          <li><a href="#">Link</a></li>
          <li class="nav-header">Sidebar</li>
          <li><a href="#">Link</a></li>
          <li><a href="#">Link</a></li>
          <li><a href="#">Link</a></li>
          <li><a href="#">Link</a></li>
          <li><a href="#">Link</a></li>
          <li><a href="#">Link</a></li>
          <li class="nav-header">Sidebar</li>
          <li><a href="#">Link</a></li>
          <li><a href="#">Link</a></li>
          <li><a href="#">Link</a></li>
        </ul>--> 
      </div>
      <!--/.well --> 
    </div>
    <!--/span-->
    <div class="span9">
      <div id="wrapper">
      
        <? 
		 $path = $config['path_to_module'].'docs'.DS;
		if(isset($_GET['file'])): ?>
        <?  $file =$path.html_entity_decode($_GET['file']);
 $file = str_replace('..','', $file);
  
if(is_file($file)){
include($file);	
}
 ?>
        <? else: ?>
        <module type="help/browser" <? if(isset($_GET['path'])): ?> from_path="<? print $_GET['path'] ?>" <? endif; ?> <? if(isset($_GET['kw'])): ?> kw="<? print $_GET['kw'] ?>" <? endif; ?>  <? if(isset($base_path)): ?> base_path="<? print $base_path ?>" <? endif; ?>  />
        <? endif; ?>
      </div>
    </div>
    <!--/span--> 
  </div>
  <!--/row-->
  
  <hr>
  <footer>
    <p>&copy; Microweber LTD <? print date("Y"); ?></p>
  </footer>
</div>
<!--/.fluid-container--> 

<!-- Le javascript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/jquery.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/bootstrap-transition.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/bootstrap-alert.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/bootstrap-modal.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/bootstrap-dropdown.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/bootstrap-scrollspy.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/bootstrap-tab.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/bootstrap-tooltip.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/bootstrap-popover.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/bootstrap-button.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/bootstrap-collapse.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/bootstrap-carousel.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/bootstrap-typeahead.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/bootstrap-affix.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/holder/holder.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/google-code-prettify/prettify.js"></script> 
<script src="<? print $config['url_to_module'] ?>static/js/application.js"></script> 

<!-- Analytics
    ================================================== -->

</body>
</html>
