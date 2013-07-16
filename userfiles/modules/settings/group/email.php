<?php only_admin_access(); ?>
<script  type="text/javascript">
$(document).ready(function(){

  mw.options.form('.<?php print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("Email settings are saved"); ?>.");
    });
});

mw.email_send_test = function(){

	var email_to = {}
	email_to.to = $('#test_email_to').val();
	email_to.subject = $('#test_email_subject').val();

	 $.post("<?php print site_url('api_html/mw/email/Sender/test'); ?>", email_to, function(msg){
		mw.tools.modal.init({
			html:"<pre>"+msg+"</pre>",	
			title:"Email send results..."
		});
	  });
}
</script>

<div class="<?php print $config['module_class'] ?> mw-o-box">
<div class="mw-o-box-content">
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label"><?php _e("Your email address"); ?><br>
      <small><?php _e("Your email address"); ?> (<?php _e("ex. my@email.com"); ?> )</small> </label>
    <input name="email_from" class="mw_option_field mw-ui-field"    type="text" option-group="email"   value="<?php print get_option('email_from','email'); ?>" />
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label"><?php _e("From name"); ?><br>
      <small><?php _e("example"); ?>: <?php _e("Site Admin"); ?></small> </label>
    <input name="email_from_name" class="mw_option_field mw-ui-field"    type="text" option-group="email"   value="<?php print get_option('email_from_name','email'); ?>" />
  </div>

  <div class="vSpace"></div>
  <hr>
  <div class="vSpace"></div>
  <div class="mw-o-box">
    <div class="mw-o-box-header" style="margin: 0;">Mail Send Settings</div>
    <div class="mw-o-box-content" style="background: #FBFBFB">






  <label class="mw-ui-label"><?php _e("Send email function"); ?> </label>
  <?php   $email_transport = get_option('email_transport','email');

 if($email_transport == false){
	$email_transport = 'php';
 }
  ?>
  <div class="mw-ui-select">
    <select name="email_transport" class="mw_option_field"   type="text" option-group="email" data-refresh="settings/group/email">
      <option value="php" <?php if($email_transport == 'php'): ?> selected="selected" <?php endif; ?>><?php _e("PHP mail function"); ?></option>
      <option value="gmail" <?php if($email_transport == 'gmail'): ?> selected="selected" <?php endif; ?>><?php _e("GMail"); ?></option>
      <option value="yahoo" <?php if($email_transport == 'yahoo'): ?> selected="selected" <?php endif; ?>><?php _e("Yahoo"); ?></option>
      <option value="hotmail" <?php if($email_transport == 'hotmail'): ?> selected="selected" <?php endif; ?>><?php _e("HotMail"); ?></option>
      <option value="smtp" <?php if($email_transport == 'smtp'): ?> selected="selected" <?php endif; ?>><?php _e("SMTP server"); ?></option>
    </select>

  </div>
  

  

  <div class="vSpace"></div>
  
  <?php if($email_transport == 'smtp' or $email_transport == 'gmail' or $email_transport == 'yahoo' or $email_transport == 'hotmail' or $email_transport == 'smtp'): ?>
    <label class="mw-ui-label"><?php print ucfirst($email_transport); ?> <?php _e("Username"); ?> <br />
    <small><?php _e("example"); ?>: <?php _e("user@email.com"); ?></small></label>
  <input name="smtp_username" class="mw_option_field mw-ui-field"   type="text" option-group="email"  value="<?php print get_option('smtp_username','email'); ?>" />
  <div class="vSpace"></div>
  <label class="mw-ui-label"><?php print ucfirst($email_transport); ?> <?php _e("Password"); ?> </label>
  <input name="smtp_password" class="mw_option_field mw-ui-field"   type="password" option-group="email"  value="<?php print get_option('smtp_password','email'); ?>" />
  <div class="vSpace"></div>
  <?php endif; ?>


  
  <?php if($email_transport == 'smtp'): ?>
  <label class="mw-ui-label"><?php _e("Smtp Email Host"); ?> <br />
    <small><?php _e("example"); ?>: smtp.gmail.com</small></label>
  <input name="smtp_host" class="mw_option_field mw-ui-field"   type="text" option-group="email"  value="<?php print get_option('smtp_host','email'); ?>" />
  <div class="vSpace"></div>
  <label class="mw-ui-label"><?php _e("Smtp Email Port"); ?> <br />
    <small><?php _e("example"); ?>: 587 or 995, 465, 110, 25</small></label>
  <input name="smtp_port" class="mw_option_field mw-ui-field"   type="text" option-group="email"  value="<?php print get_option('smtp_port','email'); ?>" />
  <div class="vSpace"></div>

  <label class="mw-ui-label"><?php _e("Enable SMTP authentication"); ?></label>
  <?php  $email_smtp_auth = get_option('smtp_auth','email'); ?>
  <div class="mw-ui-select">
    <select name="smtp_auth" class="mw_option_field"   type="text" option-group="email" data-refresh="settings/group/email">
      <option value="n" <?php if($email_smtp_auth == 'n'): ?> selected="selected" <?php endif; ?>><?php _e("No"); ?></option>
      <option value="y" <?php if($email_smtp_auth == 'y'): ?> selected="selected" <?php endif; ?>><?php _e("Yes"); ?></option>
    </select>
  </div>
  <div class="vSpace"></div>
  <label class="mw-ui-label"><?php _e("Enable SMTP Secure Method"); ?></label>
  <?php  $email_smtp_secure = get_option('smtp_secure','email'); ?>
  <div class="mw-ui-select">
    <select name="smtp_secure" class="mw_option_field"   type="text" option-group="email" data-refresh="settings/group/email">
      <option value="0" <?php if($email_smtp_secure == ''): ?> selected="selected" <?php endif; ?>><?php _e("None"); ?></option>
      <option value="1" <?php if($email_smtp_secure == '1'): ?> selected="selected" <?php endif; ?>><?php _e("SSL"); ?></option>
      <option value="2" <?php if($email_smtp_secure == '2'): ?> selected="selected" <?php endif; ?>><?php _e("TLS"); ?></option>
    </select>
  </div>
  <?php endif; ?>

  <div class="vSpace"></div>
  <table width=" 100%" border="0" id="test_eml_toggle"  class="mw-o-box mw-o-box-content" style="display:none;background: white;">
    <tr>
      <td><label class="mw-ui-label"><?php _e("Send test email to"); ?></label>
        <input name="test_email_to" id="test_email_to" class="mw_option_field mw-ui-field"   type="text" option-group="email" value="<?php print get_option('test_email_to','email'); ?>"  />
         <div class="vSpace"></div>
        <label class="mw-ui-label"><?php _e("Test mail subject"); ?></label>

        <input name="test_email_subject" id="test_email_subject" class="mw_option_field mw-ui-field"   type="text" option-group="email"   value="<?php print get_option('test_email_subject','email'); ?>"  />
        <div class="vSpace"></div>

        <span onclick="mw.email_send_test();" class="mw-ui-btn mw-ui-btn-green" id="email_send_test_btn"><?php _e("Send test email"); ?></span></td>
      <td><pre id="email_send_test_btn_output"></pre></td>
    </tr>

  </table>
  <div class="vSpace"></div>


     <a class="mw-ui-btn " href="javascript:$('#test_eml_toggle').toggle(); void(0);">Test Method</a>

      </div>
  </div>


</div>
</div>
