<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>{content_meta_title}</title>
<meta NAME="Description" CONTENT="{content_meta_description}">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="<? print TEMPLATE_URL ?>src/js/jquery-1.4.2.min.js"></script>
<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js"></script>
-->
<link rel="stylesheet" href="<? print TEMPLATE_URL ?>src/css/jquery.treeview.css" />
<script src="<? print TEMPLATE_URL ?>src/js/jquery.cookie.js" type="text/javascript"></script>
<script src="<? print TEMPLATE_URL ?>src/js/jquery.treeview.js" type="text/javascript"></script>
<meta name="google-site-verification" content="RSQmQK2pu55DV9DLYm8R31mJP5kAgiVQqhm6CghrHy8" />
<link type="text/css" rel="stylesheet" href="<? print TEMPLATE_URL ?>src/css/common.css">
</link>
<link type="text/css" rel="stylesheet" href="<? print TEMPLATE_URL ?>src/css/docs.css">
</link>
<link type="text/css" rel="stylesheet" href="<? print TEMPLATE_URL ?>src/css/cse.css"></link>


<link type="text/css" rel="stylesheet" href="<? print TEMPLATE_URL ?>src/css/richtext.css"></link> 

<link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css">

<script src="http://twitter.github.com/bootstrap/assets/js/google-code-prettify/prettify.js" type="text/javascript"></script>
 
<link rel="stylesheet" href="http://twitter.github.com/bootstrap/assets/js/google-code-prettify/prettify.css">
<script type="text/javascript">
		$(document).ready(function(){
								  prettyPrint()
			$("#tree").treeview({
				animated: "fast",
				control:"#sidetreecontrol",
				persist: "cookie",
				cookieId: "treeview-black",
				collapsed: true
			
			});
		})
		
	</script>
<style type="text/css">
<!--

body {
	font: 16px  Verdana, Arial, Helvetica, sans-serif;
	 
 background-image: url(<? print TEMPLATE_URL ?>img/left_content_bg.jpg);
	background-repeat: repeat-x;
	margin-left: 30px;
	margin-top: 0px;
	margin-right: 30px;
	margin-bottom: 0px;
}

a:link, a:visited {
 	text-decoration:none;
}

a:hover {
	text-decoration: none !important;
	color: blue !important;
	background-color: white !important;
 }

a.logo:hover {
 }

a, .treeview li.collapsable span {
	padding-left: 3px;
	padding-right: 3px;
	text-decoration:none !important;
}

a:active, a:focus {
 	 
	outline:0;
	text-decoration:none;
} 

#sidetreecontrol {
	margin-bottom:10px;
}

#sidetreecontrol a {
	color:#000;
}

#sidetree {
	float:left;
	margin:10px;
	margin-right:15px;
	width:15%;
}

#content {
	float:right;
	width:80%;
}

#content h2{
	border-bottom: 1px solid #CCCCCC !important;
    color: #383838 !important;
    
    
    line-height: 1.2em !important;
    margin-bottom: 1em !important;
}


	
	

#content_title {
	font-size:31px;
}
 #content h1:first {
 color:#FFF;
}

#content_title_space {
	padding-bottom:15px;
	clear:both;
	padding-top:15px;
}

#logo {
	
}
.first_li {
	display:none;
}

textarea.code {

   width: 90%;
 height: 200px;
 
  
 
background: #333;
color: #DFC484;
font-family: Monaco, Courier, MonoSpace;
 
-moz-border-radius: 8px;
-webkit-border-radius: 8px;
border-radius: 8px;
font-size: 11px;
padding: 5px;
margin: 0 0 25px 0;
overflow: auto;

}













-->
</style>
  <script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-modal.js"></script>
    <script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-alerts.js"></script>

    <script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-twipsy.js"></script>
    <script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-popover.js"></script>
    <script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-dropdown.js"></script>
    <script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-scrollspy.js"></script>
    <script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-tabs.js"></script>
    <script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-buttons.js"></script>

<script type="text/javascript">

//var img_url = '<? print TEMPLATE_URL ?>/img/'

</script>
<link rel="shortcut icon" href="<?  print site_url('favicon.ico'); ?>">
<link rel="apple-touch-icon" href="<?  print site_url('favicon.ico'); ?>">
<script type="text/javascript">

  $(document).ready(function(){
   $('#topbar').dropdown()
  });

  </script>
</head>
<body>
<div class="topbar-wrapper">
            <div data-dropdown="dropdown" class="topbar" id="topbar-example">
              <div class="topbar-inner">
                <div class="container" style="width:90%;">
                  <h3><a class="logo" style="" href="<? print site_url(); ?>" ><img src="http://microweber.com/userfiles/templates/mw/img/logo.png"  border="0" height="20"/></a></h3>
                  <ul>
                    <li><a href="#">Link</a></li>
                    <li><a href="#">Link</a></li>
                  </ul>
                  <form action="">
                    <input type="text" placeholder="Search">
                  </form>
                  <ul class="nav secondary-nav">
                    <li class="menu">
                      <a class="menu" href="#">Dropdown 1</a>
                      <ul class="menu-dropdown">
                        <li><a href="#">Secondary link</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Another link</a></li>
                      </ul>
                    </li>
                    <li class="menu">
                      <a class="menu" href="#">Dropdown 2</a>
                      <ul class="menu-dropdown">
                        <li><a href="#">Secondary link</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Another link</a></li>
                      </ul>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
<div id="logo" style="visibility:hidden;"> 
 <a class="logo" style="" href="<? print site_url(); ?>" ><img src="http://microweber.com/userfiles/templates/mw/img/logo.png"  border="0"  /></a>

 
</div>
 
<div id="sidetree" style="margin-top:10px;">
  <p> 
    <br />
    <small><a href="<? print site_url(); ?>">Documentation home</a></small><br />
    <small><a href="http://microweber.com" target="_blank">Main site</a></small></p>
  <div class="treeheader">&nbsp;</div>
  <div id="sidetreecontrol"> <a href="#">Collapse All</a> | <a href="#">Expand All</a> </div>
  <?php

 

if($params['from'] != false){
	$from  =$params['from'];
	
} else {
 $from  =option_get('from', $params['module_id']);
}


if(intval( $from) == 0){
	$par =  CI::model('content')->getParentPagesIdsForPageIdAndCache($page['id']);
$last =  end($par); // last

if($last == 0){
$from = 	$page['id'];
} else {
	
}

}

$from = 	0;



	?>
  <ul class="<? print $params['ul_class'] ?> first_item treeview" id="tree">
    <?
	if($params['thumbnail']): ?>
    <!--<li class="first_li"><a  <? if($from == PAGE_ID): ?> class="active" <? endif; ?>  href="<? print page_link($from);?>"><img src='<? print thumbnail($from, 'original');?>' ><? print page_title($from);?></a></li>-->
    <? else :  ?>
    <li class="first_li"><a href="<? print page_link($from);?>"><? print page_title($from);?></a></li>
    <? endif; ?>
    <?
	if($params['thumbnail']){
 CI::model('content')->content_helpers_getPagesAsUlTree($from , "<a href='{link}'   {removed_ids_code}  {active_code}  value='{id}' ><img src='{tn}' ><span>{content_title}</span></a>", array(PAGE_ID), 'class="active"', array($form_values['id']) , 'class="hidden"' , false, false, $params['ul_class'], 1 );
 
	} else {
		
		 CI::model('content')->content_helpers_getPagesAsUlTree($from , "<a href='{link}'   {removed_ids_code}  {active_code}  value='{id}' ><span>{content_title}</span></a>", array(PAGE_ID), 'class="active"', array($form_values['id']) , 'class="hidden"' , false, false, $params['ul_class'],1 );
	}

 ?>
  </ul>
  

  
  <br />
<br />


 <editable  rel="global" field="site_sidebar_txt">
    <h3>Default text</h3>
    <p>Type your text here</p>
  </editable>

</div>

