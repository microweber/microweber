<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>MW Api</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<script type="text/javascript" src="<?php print( INCLUDES_URL); ?>js/jquery.js"></script>
<script type="text/javascript" src="<?php print( site_url('apijs')); ?>"></script>

<!-- Le styles -->
<link href="<?php print $config['url_to_module'] ?>static/css/bootstrap.css" rel="stylesheet">
<link href="<?php print $config['url_to_module'] ?>static/css/bootstrap-responsive.css" rel="stylesheet">
<link href="<?php print $config['url_to_module'] ?>static/css/docs.css" rel="stylesheet">
<link href="<?php print $config['url_to_module'] ?>static/css/prettify.css" rel="stylesheet">
<link href="<?php print $config['url_to_module'] ?>static/help.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>static/pretty-json/css/pretty-json.css" />

<!-- lib -->
<script type="text/javascript" src="<?php print $config['url_to_module'] ?>static/pretty-json/libs/underscore-min.js" ></script>
<script type="text/javascript" src="<?php print $config['url_to_module'] ?>static/pretty-json/libs/backbone-min.js" ></script>
<script type="text/javascript" src="<?php print $config['url_to_module'] ?>static/pretty-json/src/util.js" ></script>
<script type="text/javascript" src="<?php print $config['url_to_module'] ?>static/pretty-json/src/tpl.js" ></script>
<script type="text/javascript" src="<?php print $config['url_to_module'] ?>static/pretty-json/src/node.js" ></script>
<script type="text/javascript" src="<?php print $config['url_to_module'] ?>static/pretty-json/src/leaf.js" ></script>

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<!-- Le fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php print $config['url_to_module'] ?>static/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php print $config['url_to_module'] ?>static/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php print $config['url_to_module'] ?>static/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?php print $config['url_to_module'] ?>static/ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="<?php print $config['url_to_module'] ?>static/ico/favicon.png">
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar">
<?php if(is_admin() == false): ?>
<div class="well span6">
  <h4>You need to login as admin to access the documentation</h4>
  <module type="users/login" />
</div>
<?php else: ?>
<script type="text/javascript">
mw.require("events.js");
mw.require("url.js");
mw.require("tools.js");
mw.require("forms.js");
mw.require('wysiwyg.js');
</script> 
<script type="text/javascript">

 

$(document).ready(function () {
	 mw.$(".mw-exec-option").bind("change", function () {
	   var par = $(this).parents('.mw-exec:first');
	   

	   
		   if(par != undefined){
			 
			    
			   
			   
			var function_exec =par.attr('data-api-function');
	   	   var function_target =par.attr('data-target');  
			   if(function_exec != undefined && function_target != undefined){
				   
				 
			   		 	data = par.find(".mw-exec-option[value][value!='']").serialize(); 
					//	data = serialized; 
					
					$(function_target+'_src').html(function_exec+'("'+data+'")');
					  
					
										
						$.post('<?php print api_url(); ?>'+function_exec,data, function(data) {
							
							
							
							
							
							
						if(data == 'false' || data == false){
							
							$(function_target).html(data);
						} else {
						
						
						 var z = typeof(data);
						 if(z != 'object'){
						  var json = data;
							 
									var o;
									try{ o = JSON.parse(json); }
									catch(e){ 
									 
										$(function_target).html(data);
									 
									}
						 } else {
							 var o = data; 
						 }
 
            var node = new PrettyJSON.view.Node({ 
                el:$(function_target), 
                data:o
            });
				
				node.expandAll();
						}
						
						//  
						});			   
				}
			  
		   }
	 });
});
  </script>
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid"> <a class="brand" href="<?php print $config['module_view'] ?>">MW Api</a>
      <div class="nav">
        <form class="navbar-form pull-right">
          <input name="kw" type="text" class="span2" <?php if(isset($_GET['kw'])): ?> value="<?php  print $_GET['kw'] ?>" <?php endif; ?> />
          <button type="submit" class="btn btn-mini">Search</button>
        </form>
        <?php
		
		 $helpers_path=$config['path_to_module'].'helpers'.DS;

		
		
		 if(isset($_GET['docs_path'])){
		  $base_path = $_GET['docs_path'];
		  
		  if( $base_path == 'all'){
			  		  session_set('docs_path',false);
					   $base_path = false;

		  } else {
			  $base_path = str_replace('..','',$base_path);
			  		  session_set('docs_path',$_GET['docs_path']);

		  }
	  } else {
		   $base_path = session_get('docs_path');
	  }
	  
	  ?>
        <ul class="nav pull-left">
          <li <?php if($base_path == false): ?> class="active" <?php endif; ?> ><a href="<?php print $config['module_view'] ?>?docs_path=all">Home</a></li>
          <li  <?php if($base_path == 'core'): ?> class="active" <?php endif; ?> ><a href="?docs_path=core">Core Api</a></li>
          <li  <?php if($base_path == 'modules'): ?> class="active" <?php endif; ?> ><a href="?docs_path=modules">Modules Api</a></li>
          <li  <?php if($base_path == 'js'): ?> class="active" <?php endif; ?> ><a href="?docs_path=js">JS Api</a></li>
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
        <module type="help/browser" <?php if(isset($base_path)): ?> base_path="<?php print $base_path ?>" <?php endif; ?>  ul_class="nav nav-list" />
        
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
        <?php 
		 $path = $config['path_to_module'].'docs'.DS;
		if(isset($_GET['file'])): ?>
        <?php  $file =$path.html_entity_decode($_GET['file']);
 $file = str_replace('..','', $file);
  
if(is_file($file)){
include($file);	
}
 ?>
        <?php else: ?>
        <module type="help/browser" <?php if(isset($_GET['path'])): ?> from_path="<?php print $_GET['path'] ?>" <?php endif; ?> <?php if(isset($_GET['kw'])): ?> kw="<?php print $_GET['kw'] ?>" <?php endif; ?>  <?php if(isset($base_path)): ?> base_path="<?php print $base_path ?>" <?php endif; ?>  />
        <?php endif; ?>
      </div>
    </div>
    <!--/span--> 
  </div>
  <!--/row-->
  
  <hr>
  <footer>
    <p>&copy; Microweber LTD <?php print date("Y"); ?></p>
  </footer>
</div>
<!--/.fluid-container--> 

<!-- Le javascript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 

<script src="<?php print $config['url_to_module'] ?>static/js/bootstrap.js"></script> 

<!-- Analytics
    ================================================== -->
<?php endif; ?>
</body>
</html>
