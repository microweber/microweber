<script  type="text/javascript">
$(document).ready(function(){

  mw.options.form('.<? print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("Email settings are saved"); ?>.");
    });
});




mw.email_send_test = function(){

	var email_to = {}
	email_to.to = $('#test_email_to').val();;
	email_to.subject = $('#test_email_subject').val();;
	
	
	
	 $.post("<?php print site_url('api_html/email_send_test'); ?>", email_to, function(msg){

			 $('#email_send_test_btn_output').html(msg);
	  });
}
</script>

<div class="<? print $config['module_class'] ?>">
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">Your email address<br>
      <small>Your email address (ex. my@email.com )</small> </label>
    <input name="email_from" class="mw_option_field mw-ui-field"    type="text" option-group="email"   value="<? print get_option('email_from','email'); ?>" />
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">From name<br>
      <small>example: Site Admin</small> </label>
    <input name="email_from_name" class="mw_option_field mw-ui-field"    type="text" option-group="email"   value="<? print get_option('email_from_name','email'); ?>" />
  </div>
  <label class="mw-ui-label">Send email using <small><a href="javascript:$('#test_eml_toggle').toggle(); void(0);">[test]</a></small></label> 
  <?   $email_transport = get_option('email_transport','email');

 if($email_transport == false){
	$email_transport = 'php';
 }
  ?>
  <div class="mw-ui-select">
    <select name="email_transport" class="mw_option_field"   type="text" option-group="email" data-refresh="settings/group/email">
      <option value="php" <? if($email_transport == 'php'): ?> selected="selected" <? endif; ?>>PHP mail function</option>
      <option value="gmail" <? if($email_transport == 'gmail'): ?> selected="selected" <? endif; ?>>GMail</option>
      <option value="yahoo" <? if($email_transport == 'yahoo'): ?> selected="selected" <? endif; ?>>Yahoo</option>
      <option value="hotmail" <? if($email_transport == 'hotmail'): ?> selected="selected" <? endif; ?>>HotMail</option>
      <option value="smtp" <? if($email_transport == 'smtp'): ?> selected="selected" <? endif; ?>>SMTP server</option>
    </select>
 
  </div>
  
  
  
  <div class="vSpace"></div>
  <table width=" 100%" border="0" id="test_eml_toggle" style="display:none">
    <tr>
      <td><label class="mw-ui-label">Send test email to</label>
        <input name="test_email_to" id="test_email_to" class="mw_option_field mw-ui-field"   type="text" option-group="email"   value="<? print get_option('test_email_to','email'); ?>"  />
         <div class="vSpace"></div>
        <label class="mw-ui-label">Test mail subject</label>

        <input name="test_email_subject" id="test_email_subject" class="mw_option_field mw-ui-field"   type="text" option-group="email"   value="<? print get_option('test_email_subject','email'); ?>"  />
        <div class="vSpace"></div>

        <span onclick="mw.email_send_test();" class="mw-ui-btn mw-ui-btn-green" id="email_send_test_btn">Send test email</span></td>
      <td><pre id="email_send_test_btn_output"></pre></td>
    </tr>
    
  </table>
  <div class="vSpace"></div>
  <div class="vSpace"></div>
  
  <? if($email_transport == 'smtp' or $email_transport == 'gmail' or $email_transport == 'yahoo' or $email_transport == 'hotmail' or $email_transport == 'smtp'): ?>
    <label class="mw-ui-label"><? print ucfirst($email_transport); ?> Username <br />
    <small>example: user@email.com</small></label>
  <input name="smtp_username" class="mw_option_field mw-ui-field mw-title-field"   type="text" option-group="email"  value="<? print get_option('smtp_username','email'); ?>" />
  <div class="vSpace"></div>
  <label class="mw-ui-label"><? print ucfirst($email_transport); ?> Password </label>
  <input name="smtp_password" class="mw_option_field mw-ui-field mw-title-field"   type="password" option-group="email"  value="<? print get_option('smtp_password','email'); ?>" />
  <div class="vSpace"></div>
  <? endif; ?>
  
  
  
  <? if($email_transport == 'smtp'): ?>
  <label class="mw-ui-label">Smtp Email Host <br />
    <small>example: smtp.gmail.com</small></label>
  <input name="smtp_host" class="mw_option_field mw-ui-field mw-title-field"   type="text" option-group="email"  value="<? print get_option('smtp_host','email'); ?>" />
  <div class="vSpace"></div>
  <label class="mw-ui-label">Smtp Email Port <br />
    <small>example: 587 or 995, 465, 110, 25</small></label>
  <input name="smtp_port" class="mw_option_field mw-ui-field mw-title-field"   type="text" option-group="email"  value="<? print get_option('smtp_port','email'); ?>" />
  <div class="vSpace"></div>

  <label class="mw-ui-label">Enable SMTP authentication</label>
  <?  $email_smtp_auth = get_option('smtp_auth','email'); ?>
  <div class="mw-ui-select">
    <select name="smtp_auth" class="mw_option_field"   type="text" option-group="email" data-refresh="settings/group/email">
      <option value="n" <? if($email_smtp_auth == 'n'): ?> selected="selected" <? endif; ?>>No</option>
      <option value="y" <? if($email_smtp_auth == 'y'): ?> selected="selected" <? endif; ?>>Yes</option>
    </select>
  </div>
  <div class="vSpace"></div>
  <label class="mw-ui-label">Enable SMTP Secure Method</label>
  <?  $email_smtp_secure = get_option('smtp_secure','email'); ?>
  <div class="mw-ui-select">
    <select name="smtp_secure" class="mw_option_field"   type="text" option-group="email" data-refresh="settings/group/email">
      <option value="0" <? if($email_smtp_secure == ''): ?> selected="selected" <? endif; ?>>None</option>
      <option value="1" <? if($email_smtp_secure == '1'): ?> selected="selected" <? endif; ?>>SSL</option>
      <option value="2" <? if($email_smtp_secure == '2'): ?> selected="selected" <? endif; ?>>TLS</option>
    </select>
  </div>
  <? endif; ?>
</div>
