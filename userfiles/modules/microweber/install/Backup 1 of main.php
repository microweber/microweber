
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
<title>Microweber Configuration</title>
<meta charset="utf-8">
<META HTTP-EQUIV="Content-Language" Content="en">
<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>api/api.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/liveadmin.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/admin.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/mw_framework.css"/>
<script type="text/javascript" src="<?php print mw_includes_url(); ?>js/jquery.js"></script>
<?php

    $rand = uniqid();

    $ua = $_SERVER["HTTP_USER_AGENT"];

    $defhost =  strpos($_SERVER["HTTP_USER_AGENT"], 'Linux') ? 'localhost' : '127.0.0.1';



?>
<script  type="text/javascript">

function prefix_add(el){
	var val = el.value;
	if(val != ''){
		var last = val.slice(-1);
		if(last != '_'){
			el.value = el.value + '_';
		}	
	}

}


$(document).ready(function(){



   $('#form_<?php print $rand; ?>').submit(function() {


   if(this.elements["admin_password"].value != this.elements["admin_password2"].value){
    alert("Passwords don't match.")
    return false;
   }

         $('#mw_log').hide();
  mw_start_progress();
   $('.mw-install-holder').fadeOut();

  $data = $('#form_<?php print $rand; ?>').serialize();
//  alert($data);
  //alert('<?php print mw()->url->string() ?>');

  $.post("<?php print mw()->url->string() ?>", $data,
   function(data) {

      $('#mw_log').hide().empty();
     if(data != undefined){
     if(data == 'done'){
       // window.location.href= '<?php print site_url('admin') ?>'
	         // window.location.href= window.location.href
			 $('#mw-install-done').fadeIn();

     } else {

      $('#mw_log').html(data).show();
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
  
  setInterval(function(){
	  <?php $log_file = MW_CACHE_ROOT_DIR . DIRECTORY_SEPARATOR . 'install_log.txt'; 
	  $log_file_url = mw()->url->link_to_file($log_file);
	  
	  ?>
	 $.get('<?php print $log_file_url ?>', function(data) {
  		$('#mw_log_holder').html(data);
 	});
	  
	  
	  
	  },1000);
  
  

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
h1, h2, h3, h4, h5 {
	font-weight: normal;
}
.vSpace {
	clear: both;
	height: 12px;
	overflow: hidden;
	position: relative;
}
.installholder {
	width: 590px;
	margin: 80px auto 40px;
}
.mw-ui-box {
	background: white;
}
.mw-ui-box-header, .mw-ui-box-content {
	padding-left: 20px;
	padding-right: 20px;
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
.mw-ui-box ol {
	padding-left: 20px;
}
.error {
	color: red;
}
.error a {
	color: red;
}
.mw-ui-box ol.error {
	color: red;
	padding-top: 10px;
}
.mw-ui-box ol.error li {
	padding: 5px 0;
}
#logo {
	float: left;
}
.Beta {
	float: right;
}
.version {
	color: #757575;
}
#mw-install-done {
	text-align: center;
	padding-bottom: 40px;
	clear: both;
	overflow: hidden;
}
#mw-install-done .mw-ui-btn {
	margin: 0 0 0 35px;
	width: 220px;
}
#mw-install-done .mw-ui-btn-blue {
	margin: 0 35px 0 0;
}
</style>
</head>
<body>
<div class="wrapper">
  <div class="installholder"> <a href="http://microweber.net" target="_blank" id="logo"> <img src="//microweber.net/webinstall/logo.png" alt="Microweber" /> <small class="version">v. <?php print MW_VERSION ?></small> </a> <span class="Beta">Beta Version</span>
    <div class="vSpace"></div>
    <div class="mw-ui-box" >
      <div class="mw-ui-box-header">
        <h2>
          <?php _e("Setup"); ?>
        </h2>
        <p>
          <?php _e("Welcome to the Microweber configuration panel, here you can setup your website."); ?>
        </p>
        <div class="custom-nav"></div>
      </div>
      <div class="mw-ui-box-content">
        <div class="sep"><span class="left-arrow arrow"></span><span class="right-arrow arrow"></span></div>
        <div class="demo" id="demo-one">
          <div class="description">
            <div id="mw_log" class="error mw-ui-box mw-ui-box-content" style="display: none"></div>
            <div class="mw_install_progress">
              <progress max="5000" value="1" id="mw_install_progress_bar"></progress>
              <br />
           
              <div id="mw_log_holder"></div>
            </div>
            <div class="mw-install-holder">
              <?php if ($done == false): ?>
              <?php

$check_pass = true;
$server_check_errors = array();
if (version_compare(phpversion(), "5.3.0", "<=")) {
	$check_pass = false;
	$server_check_errors['php_version'] = _e("You must run PHP 5.3 or greater", true);
}
if (!ini_get('allow_url_fopen')) {
	$check_pass = false;
	$server_check_errors['allow_url_fopen'] =  _e("You must enable allow_url_fopen from php.ini", true);
}
$here = dirname(__FILE__).DIRECTORY_SEPARATOR.uniqid();
if (is_writable($here)) {
	$check_pass = false;
	$server_check_errors['not_wrtiable'] =  _e("The current directory is not writable", true);
}
if(function_exists('apache_get_modules') ){
	 if(!in_array('mod_rewrite',apache_get_modules())){
	 	$check_pass = false;
		$server_check_errors['mod_rewrite'] =  _e("mod_rewrite is not enabled on your server", true);
	 }
}


if(!extension_loaded("dom")){
  $check_pass = false;
  $server_check_errors['dom'] =  _e("The DOM PHP extension must be loaded", true);

}

if(!extension_loaded("xml")){
  $check_pass = false;
  $server_check_errors['xml'] =  _e("The lib-xml PHP extension must be loaded", true);

}

if(!extension_loaded("json")){
  $check_pass = false;
  $server_check_errors['json'] =  _e("The json PHP extension must be loaded", true);
}

 

if (extension_loaded('gd') && function_exists('gd_info')) {
    
} else {
	$check_pass = false; 
    $server_check_errors['gd'] =  _e("The GD extension must be loaded in PHP", true);
}

if(defined('userfiles_path()') and is_dir(userfiles_path()) and !is_writable(userfiles_path())){
  $check_pass = false;
  $must_be = userfiles_path();
  $server_check_errors['userfiles_path()'] =  _e("The directory ". userfiles_path() ." must be writable", true);
}

if(defined('MW_CACHE_ROOT_DIR') and is_dir(MW_CACHE_ROOT_DIR) and !is_writable(MW_CACHE_ROOT_DIR)){
  $check_pass = false;
  $must_be = MW_CACHE_ROOT_DIR;
  $server_check_errors['MW_CACHE_ROOT_DIR'] =  _e("The directory ". MW_CACHE_ROOT_DIR ." must be writable", true);
}


if(defined('MW_CACHE_ROOT_DIR') and is_dir(MW_CACHE_ROOT_DIR) and !is_writable(MW_CACHE_ROOT_DIR)){
  $check_pass = false;
  $must_be = MW_CACHE_ROOT_DIR;
  $server_check_errors['MW_CACHE_ROOT_DIR'] =  _e("The directory ". MW_CACHE_ROOT_DIR ." must be writable", true);
}

if(defined('media_base_path()') and is_dir(media_base_path()) and !is_writable(media_base_path())){
  $check_pass = false;
  $must_be = media_base_path();
  $server_check_errors['media_base_path()'] =  _e("The directory ". media_base_path() ." must be writable", true);
}
if(defined('MW_PATH') and is_dir(MW_PATH) and !is_writable(MW_PATH)){
  $check_pass = false;
  $must_be = MW_PATH;
  $server_check_errors['MW_PATH'] =  _e("The directory ". MW_PATH ." must be writable", true);
}



            ?>
              <?php if ($check_pass == false): ?>
              <?php if(!empty($server_check_errors)): ?>
              <h3>
                <?php _e("Server check"); ?>
              </h3>
              <h4>
                <?php _e("There are some errors on your server that will prevent Microweber from working properly"); ?>
              </h4>
              <ol class="error">
                <?php foreach($server_check_errors as $server_check_error): ?>
                <li> <?php print $server_check_error; ?> </li>
                <?php endforeach ?>
              </ol>
              <?php endif; ?>
              <?php else: ?>
              <?php
$hide_db_setup = false;
if(isset($_REQUEST['basic'])){
$hide_db_setup = 1;
}

  ?>
              <form method="post" id="form_<?php print $rand; ?>" autocomplete="true">
                <?php if ($hide_db_setup == false): ?>
                <h2>
                  <?php _e("Database setup"); ?>
                </h2>
                <?php else: ?>
                <h2><span class="mw-ui-btn" onclick="$('#mw_db_setup_toggle').toggle();">
                  <?php _e("Database setup"); ?>
                  </span></h2>
                <?php endif; ?>
                <div class="hr"></div>
                <div id="mw_db_setup_toggle" <?php if ($hide_db_setup == true): ?> style="display:none;" <?php endif; ?>>
                  <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">
                      <?php _e("MySQL hostname"); ?>
                      <span class="mw-help" data-help="<?php _e("The address where your database is hosted."); ?>">?</span></label>
                    <input type="text" class="mw-ui-field" required="true" autofocus="" name="DB_HOST" <?php if(isset($data['db'])== true and isset($data['db']['host'])== true and $data['db']['host'] != '{DB_HOST}'): ?> value="<?php print $data['db']['host'] ?>" <?php elseif(isset($data['db'])!= true): ?> value="<?php print $defhost; ?>" <?php endif; ?> />
                  </div>
                  <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">
                      <?php _e("MySQL username"); ?>
                      <span class="mw-help" data-help="<?php _e("The username of your database."); ?>">?</span></label>
                    <input type="text" class="mw-ui-field" required="true" name="DB_USER" <?php if(isset($data['db'])== true and isset($data['db']['user'])== true and $data['db']['user'] != '{DB_USER}'): ?> value="<?php print $data['db']['user'] ?>" <?php endif; ?> />
                  </div>
                  <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">
                      <?php _e("MySQL password"); ?>
                    </label>
                    <input type="password"   class="mw-ui-field" name="DB_PASS" <?php if(isset($data['db'])== true and isset($data['db']['pass'])== true  and $data['db']['pass'] != '{DB_PASS}' ): ?> value="<?php print $data['db']['pass'] ?>" <?php endif; ?> />
                  </div>
                  <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">
                      <?php _e("Database name"); ?>
                      <span class="mw-help" data-help="<?php _e("The name of your database."); ?>">?</span></label>
                    <input type="text" class="mw-ui-field" required="true" name="dbname" <?php if(isset($data['db'])== true and isset($data['db']['dbname'])== true   and $data['db']['dbname'] != '{dbname}'): ?> value="<?php print $data['db']['dbname'] ?>" <?php endif; ?> />
                  </div>
                  <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">
                      <?php _e("Table prefix"); ?>
                      <span class="mw-help" data-help="<?php _e("Change this If you want to install multiple instances of Microweber to this database."); ?>">?</span></label>
                    <input type="text" class="mw-ui-field" name="table_prefix" <?php if(isset($data['table_prefix'])== true and isset($data['table_prefix'])!= '' and trim($data['table_prefix'])!= '{table_prefix}'): ?> value="<?php print $data['table_prefix'] ?>" <?php endif; ?> onblur="prefix_add(this)" />
                  </div>




                    <?php
                    $templates= mw()->template->site_templates();
 

                    ?>
					<?php  if(is_array($templates) and !empty($templates)): ?>
                    
                    
                    <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">
                      <?php print("Template"); ?>
                      <span class="mw-help" data-help="<?php print("Choose default site template"); ?>">?</span></label>
                    
                    
                     <select class="mw-ui-field" name="default_template">
                    <?php foreach( $templates as  $template): ?>
                    <?php  if(isset($template['dir_name']) and isset($template['name'])): ?>
                    <option <?php  if(isset($template['is_default']) and ($template['is_default']) != false): ?> selected="selected" <?php endif; ?> value="<?php print $template['dir_name']; ?>"><?php print $template['name']; ?></option>
                    <?php endif; ?>
                    <?php  endforeach; ?>
                    </select>
                    
                    
                    
                  </div>

                    
                    
                   
                    <?php endif; ?>

                </div>





                <!-- <div class="mw-ui-field-holder">
              <label class="mw-ui-label">Database type</label>
              <input type="hidden" class="mw-ui-field" name="DB_TYPE" <?php if(isset($data['db'])== true and isset($data['db']['type'])== true): ?> value="<?php print $data['db']['type'] ?>" <?php endif; ?> />
            </div>-->
                <div class="admin-setup">
                  <h2>
                    <?php _e("Create your Admin user"); ?>
                  </h2>
                  <div class="hr"></div>
                  <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">
                      <?php _e("Admin username"); ?>
                    </label>
                    <input type="text" class="mw-ui-field" required="true" name="admin_username" <?php if(isset($data['admin_username'])== true and isset($data['admin_username'])!= ''): ?> value="<?php print $data['admin_username'] ?>" <?php endif; ?> />
                  </div>
                  <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">
                      <?php _e("Admin email"); ?>
                    </label>
                    <input type="text" class="mw-ui-field" required="true" name="admin_email" <?php if(isset($data['admin_email'])== true and isset($data['admin_email'])!= ''): ?> value="<?php print $data['admin_email'] ?>" <?php endif; ?> />
                  </div>
                  <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">
                      <?php _e("Admin password"); ?>
                    </label>
                    <input type="password" required="true" class="mw-ui-field" name="admin_password" <?php if(isset($data['admin_password'])== true and isset($data['admin_password'])!= ''): ?> value="<?php print $data['admin_password'] ?>" <?php endif; ?> />
                  </div>
                  <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">
                      <?php _e("Repeat password"); ?>
                    </label>
                    <input type="password" required="true" class="mw-ui-field" name="admin_password2" <?php if(isset($data['admin_password'])== true and isset($data['admin_password'])!= ''): ?> value="<?php print $data['admin_password'] ?>" <?php endif; ?> />
                  </div>
                </div>
                <?php 	$default_content_file = mw_includes_path() . 'install' . DIRECTORY_SEPARATOR . 'mw_default_content.zip'; ?>
                <?php if(is_file($default_content_file)): ?>
                <div class="mw-ui-field-holder">
                  <label class="mw-ui-check">
                    <input name="with_default_content" type="checkbox" checked="checked" value="1">
                    <span></span>&nbsp;
                    <?php _e("Install default content"); ?>
                    <span class="mw-help" data-help="<?php _e("If checked, some default content will be added."); ?>">?</span>
                  </label>
                </div>
                <?php endif; ?>
                <?php 	$default_content_file = MW_ROOTPATH .  '.htaccess'; ?>
                <div class="mw-ui-field-holder"> <small>
                  <?php if(is_file($default_content_file)): ?>
                  <?php _e("Your .htaccess file will be modified"); ?>
                  <?php else: ?>
                  <?php _e("A new .htaccess file will be created"); ?>
                  <?php endif; ?>
                  <?php //d($_SERVER);  ?>
                  </small> </div>
                <div class="mw-ui-field-holder">
                  <input type="submit" name="submit" class="mw-ui-btn-action right"  value="Install">
                  <div class="vSpace"></div>
                </div>
                <div class="mw_clear"></div>
                <input name="IS_INSTALLED" type="hidden" value="no" id="is_installed_<?php print $rand; ?>">
                <input type="hidden" value="UTC" name="default_timezone" />
              </form>
              <?php endif; ?>
              <?php else: ?>
              <h2><?php _e("Welcome to your new website!"); ?></h2>
              <br />
              <a href="<?php print site_url() ?>admin" class="mw-ui-btn mw-ui-btn-blue left">
              <?php _e("Click here to go to Admin Panel"); ?>
              </a> <a href="<?php print site_url() ?>" class="mw-ui-btn left" style="margin-left: 20px;">
              <?php _e("Click here visit your site"); ?>
              </a> 
              <div class="vSpace"></div>
              <?php endif; ?>
            </div>
            <div id="mw-install-done" style="display:none">
              <h2>
                <?php _e("Installation is completed"); ?>
              </h2>
              <br />
              <a href="<?php print site_url() ?>admin" class="mw-ui-btn mw-ui-btn-blue right">
              <?php _e("Click here to go to Admin Panel"); ?>
              </a> <a href="<?php print site_url() ?>" class="mw-ui-btn left">
              <?php _e("Click here visit your site"); ?>
              </a> </div>
          </div>
          <!-- .description --> 
          
        </div>
        <!-- .demo --> 
      </div>
    </div>
  </div>
  <!-- .page --> 
  
</div>
<!-- .wrapper -->

</body>
</html>