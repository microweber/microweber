<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
<title>Microweber Configuration</title>
<meta charset="utf-8">
<META HTTP-EQUIV="Content-Language" Content="en">
<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>api/api.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/liveadmin.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/admin.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<? print INCLUDES_URL; ?>css/mw_framework.css"/>
<script type="text/javascript" src="<? print INCLUDES_URL; ?>js/jquery.js"></script>
<? $rand = uniqid(); ?>
<script  type="text/javascript">

 
 

$(document).ready(function(){


	 
	 $('#form_<? print $rand ?>').submit(function() {




  $data = $('#form_<? print $rand ?>').serialize();
//  alert($data);
  //alert('<? print url_string() ?>');

  $.post("<? print url_string() ?>", $data,
   function(data) {
	    $('.mw_log').html('');
	   if(data != undefined){
		 if(data == 'done'){
			 window.location.href= '<? print site_url('admin') ?>'
		 } else {
		  $('.mw_log').html(data);	
		 }
		   
	   }
	 

   });
   
   
   return false;

	 });

 
 
 });



 
</script>
<style>
body {
	background: #f4f4f4;
}
.mw-o-box {
	background: white;
	box-shadow:0px 20px 14px -23px #CCCCCC;
}
input[type='text'], input[type='password'] {
	width: 200px;
}
.mw-ui-label {
	display: block;
	float: left;
	width: 150px;
	padding:6px 12px 0 0;
}
</style>
</head>
<body>
<div class="wrapper">
  <div class="page">
    <div class="mw-o-box" style="width: 400px;margin: 100px auto;padding: 20px;">
      <header class="header">
        <h1>Microweber Setup <span class="version">v1.0</span> </h1>
        <p>Welcome to the Microweber configuration panel, here you can setup your website quickly.</p>
        <div class="custom-nav"></div>
      </header>
      <div class="sep"><span class="left-arrow arrow"></span><span class="right-arrow arrow"></span></div>
      <div class="demo" id="demo-one">
        <div class="description">
          <div class="mw_log"> </div>
          <? if ($done == false): ?>
          <form method="post" id="form_<? print $rand ?>">
            <h2>Database setup</h2>
            <div class="hr"></div>
            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Database host</label>
              <input type="text" class="mw-ui-field" autofocus="" name="DB_HOST" <? if(isset($data['db'])== true and isset($data['db']['host'])== true): ?> value="<? print $data['db']['host'] ?>" <? endif; ?> />
            </div>
            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Database username</label>
              <input type="text" class="mw-ui-field" name="DB_USER" <? if(isset($data['db'])== true and isset($data['db']['user'])== true): ?> value="<? print $data['db']['user'] ?>" <? endif; ?> />
            </div>
            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Database password</label>
              <input type="text" class="mw-ui-field" name="DB_PASS" <? if(isset($data['db'])== true and isset($data['db']['pass'])== true): ?> value="<? print $data['db']['pass'] ?>" <? endif; ?> />
            </div>
            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Database name</label>
              <input type="text" class="mw-ui-field" name="dbname" <? if(isset($data['db'])== true and isset($data['db']['dbname'])== true): ?> value="<? print $data['db']['dbname'] ?>" <? endif; ?> />
            </div>
            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Table prefix</label>
              <input type="text" class="mw-ui-field" name="table_prefix" <? if(isset($data['table_prefix'])== true and isset($data['table_prefix'])!= ''): ?> value="<? print $data['table_prefix'] ?>" <? endif; ?> />
            </div>
            <h2>Admin user setup</h2>
            <div class="hr"></div>
            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Admin username</label>
              <input type="text" class="mw-ui-field" name="admin_username" <? if(isset($data['admin_username'])== true and isset($data['admin_username'])!= ''): ?> value="<? print $data['admin_username'] ?>" <? endif; ?> />
            </div>
            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Admin password</label>
              <input type="password" class="mw-ui-field" name="admin_password" <? if(isset($data['admin_password'])== true and isset($data['admin_password'])!= ''): ?> value="<? print $data['admin_password'] ?>" <? endif; ?> />
            </div>
            <div class="mw-ui-field-holder">
              <input type="submit" name="submit" class="mw-ui-btn-action right"  value="Install">
            </div>
            <div class="mw_clear"></div>
            <input name="IS_INSTALLED" type="hidden" value="no" id="is_installed_<? print $rand ?>">
            <input type="hidden" value="UTC" name="default_timezone" />
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
  </div>
  <!-- .page --> 
  
</div>
<!-- .wrapper -->

</body>
</html>