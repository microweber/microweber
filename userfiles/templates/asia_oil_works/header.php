<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>{content_meta_title}</title>
<meta NAME="Description" CONTENT="{content_meta_description}">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 
<link rel="stylesheet" type="text/css" href="<? print TEMPLATE_URL ?>css/style.css" media="all" />
<script type="text/javascript" src="<?  print site_url('api/js'); ?>"></script>
   <!--[if lt IE 9]>
    <link rel="stylesheet" href="<? print TEMPLATE_URL ?>user_cp_static/css/ie.css" />
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
    
    
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/functions.js"></script>

<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/ajax_upload/ajaxfileupload.js"></script>



<? echo '<!--[if IE]><?import namespace="v" implementation="#default#VML" ?><![endif]-->'; ?>
<link rel="stylesheet" href="<? print TEMPLATE_URL ?>user_cp_static/css/visualize.css" />
<link rel="stylesheet" href="<? print TEMPLATE_URL ?>user_cp_static/css/datatables.css" />
<link rel="stylesheet" href="<? print TEMPLATE_URL ?>user_cp_static/css/buttons.css" />
<link rel="stylesheet" href="<? print TEMPLATE_URL ?>user_cp_static/css/checkboxes.css" />
<link rel="stylesheet" href="<? print TEMPLATE_URL ?>user_cp_static/css/inputtags.css" />
<link rel="stylesheet" href="<? print TEMPLATE_URL ?>user_cp_static/css/main.css" />



<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/wymeditor/wymeditor/jquery.wymeditor.min.js"></script>

 
<script src="<? print TEMPLATE_URL ?>user_cp_static/js/excanvas.js"></script>
<script src="<? print TEMPLATE_URL ?>user_cp_static/js/jquery.livesearch.js"></script>
<script src="<? print TEMPLATE_URL ?>user_cp_static/js/jquery.visualize.js"></script>
<script src="<? print TEMPLATE_URL ?>user_cp_static/js/jquery.datatables.js"></script>
<script src="<? print TEMPLATE_URL ?>user_cp_static/js/jquery.placeholder.js"></script>
<script src="<? print TEMPLATE_URL ?>user_cp_static/js/jquery.selectskin.js"></script>
<script src="<? print TEMPLATE_URL ?>user_cp_static/js/jquery.checkboxes.js"></script>
 
<script src="<? print TEMPLATE_URL ?>user_cp_static/js/jquery.validate.js"></script>
<script src="<? print TEMPLATE_URL ?>user_cp_static/js/jquery.inputtags.js"></script>
<script src="<? print TEMPLATE_URL ?>user_cp_static/js/notifications.js"></script>
<script src="<? print TEMPLATE_URL ?>user_cp_static/js/application.js"></script>
</head>
<body>
<div id="container">
<div id="wrapper">
<div id="header"> <a href="#" id="logo" title="Asia Oil Works">Asia Oil Works</a>
  <div id="MemberInfo"> <strong>570 JOBS online </strong> We have <b>2,132</b> <span>members</span> of our comunity </div>
  <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
  <div id="nav">
    <div id="nav_content">
      <microweber module="content/menu"  name="main_menu"  />
      <!--<ul>
        <li class="active"><a href="#">HOME</a></li>
        <li><a href="#">SEARCH JOBS</a></li>
        <li><a href="#">RECRUITERS</a></li>
        <li><a href="#">JOB SEEKERS</a></li>
        <li><a href="#">NEWS</a></li>
        <li><a href="#">About us</a></li>
      </ul>-->
      <div class="dd"> <span class="dd_val">Translate this page</span>
        <ol>
          <li><a>English</a></li>
          <li><a>Chinese</a></li>
          <li><a>Russian</a></li>
          <li><a>French</a></li>
        </ol>
      </div>
      <div class="actions button-container right" style="margin-top:8px;">
      
      
        <div class="button-group">
        
         <? if(intval($user) == 0): ?>  
        <a href="<? print site_url('members') ?>" class="button">Register</a> <a href="<? print site_url('members') ?>" class="button danger">Login</a>
         <? else: ?>
                  <a href="#" class="button primary">Dashboard</a> <a href="#" class="button">Profile</a> <a href="javascript:mw.users.LogOut();" class="button danger">Logout</a>

         <? endif; ?>
         
         
         
          </div>
        
        
        
        
      </div>
    </div>
  </div>
</div>
