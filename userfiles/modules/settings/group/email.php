<?php only_admin_access(); ?>
<script  type="text/javascript">

function saveEmailOptions(notification) {

	if (!!notification) {
		notification = true;
	}
	
	$("input, select, textarea", $('.<?php print $config['module_class'] ?>')).each(function () {
        mw.options.save(this, function () {
            // saved
        });
	});
	
	if (notification) {
		mw.notification.success("<?php _ejs("Email settings are saved"); ?>.");
	}

	mw.reload_module("<?php print $config['module'] ?>");
}

$(document).ready(function(){
	$('.js-email-transport-select').change(function() {
		saveEmailOptions();
	});
});


/*
$(document).ready(function(){
    mw.options.form(".<?php print $config['module_class'] ?>", function () {
        mw.notification.success("<?php _ejs("Email settings are saved"); ?>.");
        mw.reload_module("<?php print $config['module'] ?>");
    });
});
*/
mw.email_send_test = function(){

	$("input, select, textarea", $('.<?php print $config['module_class'] ?>')).each(function () {
        mw.options.save(this, function () {
            // saved
        });
	});
	
	var email_to = {}
	email_to.to = $('#test_email_to').val();
	email_to.subject = $('#test_email_subject').val();

	 $.post("<?php print site_url('api_html/Microweber/Utils/MailSender/test'); ?>", email_to, function(msg){
		 
		 mw.tools.modal.init({
			
			 html:"<pre>"+msg+"</pre>",	
			 title:"Email send results..."
		 });
	 });
}
</script>

<div class="mw-ui-row admin-section-bar">
    <div class="mw-ui-col">
        <h2><span class="mai-mail"></span><?php _e("Email"); ?></h2>
    </div>
</div>
<div class="admin-side-content">

<div class="<?php print $config['module_class'] ?> mw-ui-box">
	<div class="mw-ui-box-content">
	
		<div class="mw-flex-row">
		
		<div class="mw-flex-col-xs-12">
			<h2>Email Settings</h2><p>Set the email transport settings to your website.</p><br />
		</div>
		
		<div class="mw-flex-col-xs-6">
		<div class="mw-ui-field-holder">
			<label class="mw-ui-label">
				<?php _e("Your email address"); ?>
				<br>
				<small>
				<?php _e("The website will send emails on behalf of this address"); ?>
				(
				<?php _e("ex. my@email.com"); ?>
				)</small> </label>
			<input name="email_from" class="mw_option_field mw-ui-field" type="email" type="text" option-group="email"   value="<?php print get_option('email_from','email'); ?>" />
		</div>
		</div>
		
		<div class="mw-flex-col-xs-6">
		<div class="mw-ui-field-holder">
			<label class="mw-ui-label">
				<?php _e("From name"); ?>
				<br>
				<small>
				<?php _e("The website will use this name for the emails"); ?>
				(
				<?php _e("ex. Website Support"); ?>
				)</small> </label>
			<input name="email_from_name" class="mw_option_field mw-ui-field"    type="text" option-group="email"   value="<?php print get_option('email_from_name','email'); ?>" />
		</div>
		</div>
		</div>
		<br />
		<div class="mw-ui-box">
			<div class="mw-ui-box-header"><?php _e("Mail Send Settings"); ?></div>
			<div class="mw-ui-box-content">
			<div class="mw-flex-row">
				
				<div class="mw-flex-col-xs-12">
				<div class="mw-ui-field-holder">
				    <label class="mw-ui-label">
				        <?php _e("Send email function"); ?>
				    </label>
				    <?php
				        $email_transport = get_option('email_transport','email');
				        if($email_transport == false){
				            $email_transport = 'php';
				        }
				    ?>
				    <select name="email_transport" class="mw-ui-field mw_option_field js-email-transport-select"   type="text" option-group="email" data-refresh="settings/group/email">
				        <option value="php" <?php if($email_transport == 'php'): ?> selected="selected" <?php endif; ?>>
				        <?php _e("PHP mail function"); ?>
				        </option>
				        <option value="gmail" <?php if($email_transport == 'gmail'): ?> selected="selected" <?php endif; ?>>
				        <?php _e("GMail"); ?>
				        </option>
				      <?php  /* <option value="yahoo" <?php if($email_transport == 'yahoo'): ?> selected="selected" <?php endif; ?>>
				        <?php _e("Yahoo"); ?>
				        </option>
				        <option value="hotmail" <?php if($email_transport == 'hotmail'): ?> selected="selected" <?php endif; ?>>
				        <?php _e("HotMail"); ?>
				        </option>*/  ?>
				        <option value="smtp" <?php if($email_transport == 'smtp'): ?> selected="selected" <?php endif; ?>>
				        <?php _e("SMTP server"); ?>
				        </option>
				        <option value="cpanel" <?php if($email_transport == 'cpanel'): ?> selected="selected" <?php endif; ?>>
				        <?php _e("cPanel"); ?>
				        </option>
				        <option value="plesk" <?php if($email_transport == 'plesk'): ?> selected="selected" <?php endif; ?>>
				        <?php _e("Plesk"); ?>
				        </option>
				    </select>
				</div>
				</div>
				
				<?php if($email_transport == 'smtp' or $email_transport == 'cpanel' or $email_transport == 'plesk' or $email_transport == 'gmail' or $email_transport == 'yahoo' or $email_transport == 'hotmail' or $email_transport == 'smtp'): ?>
                
                <div class="mw-flex-col-xs-6">
                <div class="mw-ui-field-holder">
				<label class="mw-ui-label"><?php print titlelize($email_transport); ?>
					<?php _e("Username"); ?>
					<br />
					<small>
					<?php _e("example"); ?>
					:
					<?php _e("user@email.com"); ?>
					</small></label>
				<input name="smtp_username" class="mw_option_field mw-ui-field"   type="text" option-group="email"  value="<?php print get_option('smtp_username','email'); ?>" />
                </div>
                </div>
                
                <div class="mw-flex-col-xs-6">
               <div class="mw-ui-field-holder">
				<label class="mw-ui-label"><?php print titlelize($email_transport); ?>
					<?php _e("Password"); ?>
					<br />
					<small>&nbsp;</small>
				</label>
				<input name="smtp_password" class="mw_option_field mw-ui-field"   type="password" option-group="email"  value="<?php print get_option('smtp_password','email'); ?>" />
                </div>
                </div>
                
				<?php endif; ?>
				
			
				
				<?php if($email_transport == 'smtp' || $email_transport == 'plesk' || $email_transport == 'cpanel'): ?>
				 <div class="<?php if($email_transport == 'smtp'): ?>mw-flex-col-xs-6<?php else: ?>mw-flex-col-xs-12<?php endif; ?>">
                <div class="mw-ui-field-holder">
				<label class="mw-ui-label">
					<?php _e("Smtp Email Host"); ?>
					<br />
					<small>
					<?php _e("example"); ?>
					: smtp.gmail.com</small></label>
				<input name="smtp_host" class="mw_option_field mw-ui-field"   type="text" option-group="email"  value="<?php print get_option('smtp_host','email'); ?>" />
				</div>
				</div>
				<?php endif; ?>
				
				<?php if($email_transport == 'smtp'): ?>
				 <div class="mw-flex-col-xs-6">
                <div class="mw-ui-field-holder">
				<label class="mw-ui-label">
					<?php _e("Smtp Email Port"); ?>
					<br />
					<small>
					<?php _e("example"); ?>
					: 587 or 995, 465, 110, 25</small></label>
				<input name="smtp_port" class="mw_option_field mw-ui-field"   type="text" option-group="email"  value="<?php print get_option('smtp_port','email'); ?>" />
				 </div>
				 </div>
				 
				  <div class="mw-flex-col-xs-6">
                 <div class="mw-ui-field-holder">
				<label class="mw-ui-label">
					<?php _e("Enable SMTP authentication"); ?>
				</label>
				<?php  $email_smtp_auth = get_option('smtp_auth','email'); ?>
				
					<select name="smtp_auth" class="mw-ui-field mw_option_field"   type="text" option-group="email" data-refresh="settings/group/email">
						<option value="ssl" <?php if($email_smtp_auth == 'ssl'): ?> selected="selected" <?php endif; ?>>
						ssl
						</option>
						<option value="tls" <?php if($email_smtp_auth == 'tls'): ?> selected="selected" <?php endif; ?>>
						tls
						</option>
                        <option value="" <?php if($email_smtp_auth == ''): ?> selected="selected" <?php endif; ?>>
						none
						</option>
					</select>
			   </div>
			   </div>
			   
			    <div class="mw-flex-col-xs-6">
				<div class="mw-ui-field-holder">
				<label class="mw-ui-label">
					<?php _e("Enable SMTP Secure Method"); ?>
				</label>
				<?php  $email_smtp_secure = get_option('smtp_secure','email'); ?>

					<select name="smtp_secure" class="mw-ui-field mw_option_field"   type="text" option-group="email" data-refresh="settings/group/email">
						<option value="0" <?php if($email_smtp_secure == ''): ?> selected="selected" <?php endif; ?>>
						<?php _e("None"); ?>
						</option>
						<option value="1" <?php if($email_smtp_secure == '1'): ?> selected="selected" <?php endif; ?>>
						<?php _e("SSL"); ?>
						</option>
						<option value="2" <?php if($email_smtp_secure == '2'): ?> selected="selected" <?php endif; ?>>
						<?php _e("TLS"); ?>
						</option>
					</select>
                 </div>
                 </div>
				<?php endif; ?>
				
				<div class="mw-flex-col-xs-12">
				<table width=" 100%" border="0" id="test_eml_toggle"  class="mw-ui-box mw-ui-box-content" style="display:none;background: white;">
					<tr>
						<td>
							<div class="mw-flex-row">
							<div class="mw-flex-col-xs-12"><h3>Make a test email</h3><p>Send test email to check settings are they work correctly.</p><br /></div>
							<div class="mw-flex-col-xs-6">
							<label class="mw-ui-label">
								<?php _e("Send test email to"); ?>
							</label>
							<input name="test_email_to" id="test_email_to" class="mw_option_field mw-ui-field"   type="text" option-group="email" value="<?php print get_option('test_email_to','email'); ?>"  />
							</div>
							<div class="mw-flex-col-xs-4">
							<label class="mw-ui-label">
								<?php _e("Test mail subject"); ?>
							</label>
							<input name="test_email_subject" id="test_email_subject" class="mw_option_field mw-ui-field"   type="text" option-group="email"   value="<?php print get_option('test_email_subject','email'); ?>"  />
							</div>
							<div class="mw-flex-col-xs-2">
							<label class="mw-ui-label">&nbsp;</label>
							<span onclick="mw.email_send_test();" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-notification" style="width:100%;" id="email_send_test_btn">
							<span class="mw-icon-forward"></span> <?php _e("Send Test Email"); ?>
							</span>
							</div>
							</div>
						</td>
						<td><pre id="email_send_test_btn_output"></pre></td>
					</tr>
				</table>
				<br />
				<a class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-info" href="javascript:$('#test_eml_toggle').toggle(); void(0);"><span class="mw-icon-beaker"></span> <?php _e("Test Mail Sending Method"); ?></a> 
				</div>
				
				</div>
				</div>
				</div>
				<br />
		
			<button onClick="saveEmailOptions(1)" class="mw-ui-btn mw-ui-btn-notification"><span class="mw-icon-check"></span> <?php _e("Save email settings"); ?></button>

		<br />
	</div>
</div>
</div>
