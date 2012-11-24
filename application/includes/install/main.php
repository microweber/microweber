<!DOCTYPE html>
<!--[if lt IE 7]><html class="ie9 ie8 ie7 ie6" lang="en"><![endif]-->
<!--[if IE 7]><html class="ie9 ie8 ie7" lang="en"><![endif]-->
<!--[if IE 8]><html class="ie9 ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if gt IE 9]><!--><html lang="en"><!--<![endif]-->

<head>
<title>Microweber Configuration</title>
<meta charset="utf-8">
<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>api/api.css"/>
<script type="text/javascript" src="<? print INCLUDES_URL; ?>js/jquery-latest.js"></script>
<script src="<? print INCLUDES_URL; ?>install/bootstrap.js"></script>
<? $rand = uniqid(); ?>
<script  type="text/javascript">

 
 

$(document).ready(function(){
	
	 
	 
	 mw.$('#form_<? print $rand ?>').submit(function() { 

  $data = mw.$('#form_<? print $rand ?>').serialize();
//  alert($data);
  //alert('<? print url_string() ?>');
  
  $.post("<? print url_string() ?>", $data,
   function(data) {
	    mw.$('.mw_log').html('');
	   if(data != undefined){
		 if(data == 'done'){
			 window.location.href= '<? print site_url('admin') ?>'
		 } else {
		  mw.$('.mw_log').html(data);	
		 }
		   
	   }
	 
    
   });
   
   
   return false;

	 });

 
 
 });
   
 
</script>
</head>
<body>
<div class="wrapper">
  <div class="page"> 
    <!--<nav class="nav">
                  <ul>
                    <li><a href="{{author-url}}">About the Author</a></li>
                    <li><a href="{{project-url}}">Documentation</a></li>
                    <li class="download"><a href="{{project-download-url}}">Download <span class="version">v1.0</span></a></li>
                  </ul>
                </nav>-->
    <header class="header">
      <h1>Microweber Setup <span class="version">v1.0</span></h1>
      <p>Welcome to the Microweber configuration panel, here you can setup your website quickly.
      <p>
      <div class="custom-nav"></div>
    </header>
    <div class="sep"><span class="left-arrow arrow"></span><span class="right-arrow arrow"></span></div>
    <div class="demo" id="demo-one">
      <div class="description">
        <div class="mw_log"> </div>
        <? if ($done == false): ?>
        <h2>Database setup</h2>
        <form method="GET" id="form_<? print $rand ?>">
          <table  cellspacing="5" cellpadding="5">
            <tr>
              <td>DB_HOST</td>
              <td><input name="DB_HOST"  /></td>
            </tr>
            <tr>
            <tr>
              <td>DB_USER</td>
              <td><input name="DB_USER" /></td>
            </tr>
            <tr>
            <tr>
              <td>DB_PASS</td>
              <td><input name="DB_PASS" /></td>
            </tr>
            <tr>
              <td>db name</td>
              <td><input name="dbname" /></td>
            </tr>
            
            <tr>
              <td>Test</td>
              <td></td>
            </tr>
            <tr>
              <td>Save</td>
              <td><input type="submit" name="submit"  value="install">
                <input name="IS_INSTALLED" type="hidden" value="no" id="is_installed_<? print $rand ?>">
                <!--       <input type="submit" name="submit"  value="install" >--></td>
            </tr>
          </table>
        </form>
        <? else: ?>
        <h2>Done, </h2>
        <a href="<? print site_url('admin') ?>">click here to to to admin</a> <a href="<? print site_url() ?>">click here to to to site</a>
        <? endif; ?>
      </div>
      <!-- .description --> 
      
    </div>
    <!-- .demo --> 
    
  </div>
  <!-- .page --> 
  
</div>
<!-- .wrapper -->

</body>
</html>