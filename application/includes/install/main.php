<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
<title>Microweber Configuration</title>
<meta charset="utf-8">
<META HTTP-EQUIV="Content-Language" Content="en">
<link type="text/css" rel="stylesheet" media="all" href="<?php print INCLUDES_URL; ?>api/api.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print INCLUDES_URL; ?>css/liveadmin.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print INCLUDES_URL; ?>css/admin.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print INCLUDES_URL; ?>css/mw_framework.css"/>
<script type="text/javascript" src="<?php print INCLUDES_URL; ?>js/jquery.js"></script>
<?php  $rand = uniqid(); ?>
<script  type="text/javascript">




$(document).ready(function(){



   $('#form_<?php print $rand; ?>').submit(function() {


  mw_start_progress();
   $('.mw-install-holder').fadeOut();

  $data = $('#form_<?php print $rand; ?>').serialize();
//  alert($data);
  //alert('<?php print url_string() ?>');

  $.post("<?php print url_string() ?>", $data,
   function(data) {

      $('.mw_log').html('');
     if(data != undefined){
     if(data == 'done'){
       window.location.href= '<?php print site_url('admin') ?>'
     } else {
      $('.mw_log').html(data);
      $('.mw-install-holder').fadeIn();
     }

     }
 $('.mw_install_progress').fadeOut();
   });


   return false;

   });



 });



function mw_start_progress(){


  $('.mw_install_progress').fadeIn();

 var interval = 2, //How much to increase the progressbar per frame
        updatesPerSecond = 1000/60, //Set the nr of updates per second (fps)
        progress =  $('#mw_install_progress_bar'),
        animator = function(){
            progress.val(progress.val()+interval);
          //  $('#val').text(progress.val());
            if ( progress.val()+interval < progress.attr('max')){
               setTimeout(animator, updatesPerSecond);
            } else {
              //  $('#val').text('Done');
                progress.val(progress.attr('max'));
            }
        }

    setTimeout(animator, updatesPerSecond);



}
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
  width: 155px;
  padding:6px 12px 0 0;
}
.mw_install_progress {
 display: none;
}

</style>
</head>
<body>
<div class="wrapper">
  <div class="page">
    <div class="mw-o-box" style="width: 400px;margin: 100px auto;padding: 20px;">
      <header class="header">
        <h1>Microweber Setup</h1>
        <small class="version">version <?php print MW_VERSION ?></small>

        <p><br>Welcome to the Microweber configuration panel, here you can setup your website quickly.</p>
        <div class="custom-nav"></div>
      </header>
      <div class="sep"><span class="left-arrow arrow"></span><span class="right-arrow arrow"></span></div>
      <div class="demo" id="demo-one">
        <div class="description">
          <div class="mw_log"> </div>
          <div class="mw_install_progress">
          <progress max="5000" value="1" id="mw_install_progress_bar"></progress>


          </div>


          <div class="mw-install-holder">


          <?php if ($done == false): ?>
          <form method="post" id="form_<?php print $rand; ?>" autocomplete="true">
            <h2>Database setup</h2>
            <div class="hr"></div>

            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">MySQL hostname <span class="mw-help" data-help="The address where your database is hosted.">?</span></label>
              <input type="text" class="mw-ui-field" required="true" autofocus="" name="DB_HOST" <?php if(isset($data['db'])== true and isset($data['db']['host'])== true and $data['db']['host'] != '{DB_HOST}'): ?> value="<?php print $data['db']['host'] ?>" <?php endif; ?> />
            </div>
            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">MySQL username <span class="mw-help" data-help="The username of your database.">?</span></label>
              <input type="text" class="mw-ui-field" required="true" name="DB_USER" <?php if(isset($data['db'])== true and isset($data['db']['user'])== true and $data['db']['user'] != '{DB_USER}'): ?> value="<?php print $data['db']['user'] ?>" <?php endif; ?> />
            </div>
            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">MySQL password</label>
              <input type="text" required="true" class="mw-ui-field" name="DB_PASS" <?php if(isset($data['db'])== true and isset($data['db']['pass'])== true  and $data['db']['pass'] != '{DB_PASS}' ): ?> value="<?php print $data['db']['pass'] ?>" <?php endif; ?> />
            </div>

            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Database name <span class="mw-help" data-help="The name of your database.">?</span></label>
              <input type="text" class="mw-ui-field" required="true" name="dbname" <?php if(isset($data['db'])== true and isset($data['db']['dbname'])== true   and $data['db']['dbname'] != '{dbname}'): ?> value="<?php print $data['db']['dbname'] ?>" <?php endif; ?> />
            </div>

            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Table prefix <span class="mw-help" data-help="Change this If you want to install multiple instances of Microweber to this database.">?</span></label>
              <input type="text" required="true" class="mw-ui-field" name="table_prefix" <?php if(isset($data['table_prefix'])== true and isset($data['table_prefix'])!= '' and trim($data['table_prefix'])!= '{table_prefix}'): ?> value="<?php print $data['table_prefix'] ?>" <?php endif; ?> />
            </div>

            <!-- <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Database type</label>
              <input type="hidden" class="mw-ui-field" name="DB_TYPE" <?php if(isset($data['db'])== true and isset($data['db']['type'])== true): ?> value="<?php print $data['db']['type'] ?>" <?php endif; ?> />
            </div>-->



            <h2>Admin user setup</h2>
            <div class="hr"></div>
            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Admin username</label>
              <input type="text" class="mw-ui-field" required="true" name="admin_username" <?php if(isset($data['admin_username'])== true and isset($data['admin_username'])!= ''): ?> value="<?php print $data['admin_username'] ?>" <?php endif; ?> />
            </div>
             <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Admin email</label>
              <input type="text" class="mw-ui-field" required="true" name="admin_email" <?php if(isset($data['admin_email'])== true and isset($data['admin_email'])!= ''): ?> value="<?php print $data['admin_email'] ?>" <?php endif; ?> />
            </div>
            <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Admin password</label>
              <input type="password" required="true" class="mw-ui-field" name="admin_password" <?php if(isset($data['admin_password'])== true and isset($data['admin_password'])!= ''): ?> value="<?php print $data['admin_password'] ?>" <?php endif; ?> />
            </div>
            
            
            
            <?php 		$default_content_file = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'mw_default_content.zip'; ?>
            <?php if(is_file($default_content_file)): ?>
            <div class="mw-ui-field-holder">
            <label class="mw-ui-check"><input name="with_default_content" type="checkbox" value="1"><span></span>&nbsp; Install default content</label>
            </div>
            <?php endif; ?>
            
            <div class="mw-ui-field-holder">
              <input type="submit" name="submit" class="mw-ui-btn-action right"  value="Install">
            </div>
            <div class="mw_clear"></div>
            <input name="IS_INSTALLED" type="hidden" value="no" id="is_installed_<?php print $rand; ?>">
            <input type="hidden" value="UTC" name="default_timezone" />
          </form>
          <?php else: ?>
          <h2>Done, </h2>
          <a href="<?php print site_url('admin') ?>">click here to to to admin</a> <a href="<?php print site_url() ?>">click here to to to site</a>
          <?php endif; ?>
           </div>
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