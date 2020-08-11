<?php only_admin_access(); ?>

<?php 
if (isset($params['id'])) {
	$sender = newsletter_get_sender($params);
}
?>

<style>
.mw-ui-field-full-width {
	width:100%;
}
.js-danger-text {
	padding-top: 5px;
	color: #c21f1f;
}
</style>

<script>
	mw.require("<?php print $config['url_to_module'];?>/js/js-helper.js");
	
	$(document).ready(function () {
		
		$(document).on("change", ".js-validation", function() {
			$('.js-edit-sender-form :input').each(function() {
				if ($(this).hasClass('js-validation')) {
					runFieldsValidation(this);
				}
			});
		});

		$(".js-edit-sender-form").submit(function(e) {
			
			e.preventDefault(e);
				
			 var errors = {};
	         var data = mw.serializeFields(this);
	        
			$('.js-edit-sender-form :input').each(function(k,v) {
					if ($(this).hasClass('js-validation')) {
						if (runFieldsValidation(this) == false) {
							errors[k] = true;
						}
					}
			});
			
	        if (isEmpty(errors)) {
				
		        $.ajax({
		            url: mw.settings.api_url + 'newsletter_save_sender',
		            type: 'POST',
		            data: data,
		            success: function (result) {
			            
		                mw.notification.success('<?php _ejs('Sender saved'); ?>');
		
		                // Remove modal
		                if (typeof(edit_campaign_modal) != 'undefined' && edit_campaign_modal.modal) {
		                	edit_campaign_modal.modal.remove();
					     }
					       
		                // Reload the modules
		                mw.reload_module('newsletter/sender_accounts_list')
		                mw.reload_module_parent('newsletter');
		
		            },
					error: function(e) {
						alert('Error processing your request: ' + e.responseText);
					}
		        });
	        } else {
	       		mw.notification.error('<?php _ejs('Please fill correct data.'); ?>');
	        }
		});
		
	});

	function runFieldsValidation(instance) {
		
		var ok = true;
		var inputValue = $(instance).val().trim();
		
		$(instance).removeAttr("style");
		$(instance).parent().find(".js-field-message").html('');

		if (inputValue == "") {
			$(instance).css("border", "1px solid #b93636");
			$(instance).parent().find('.js-field-message').html(errorText('<?php _e('The field cannot be empty'); ?>'));
			ok = false;
		}

		if ($(instance).hasClass('js-validation-email')) {
			if (validateEmail(inputValue) == false) {
				$(instance).css("border", "1px solid #b93636");
				$(instance).parent().find('.js-field-message').html(errorText('<?php _e('The email address is not valid.'); ?>'));
				ok = false;
			}
		}

		return ok;
	}
</script>

<form class="js-edit-sender-form">

	<div class="mw-ui-field-holder">
		<label class="mw-ui-label"><?php _e('Name'); ?></label> 
		<input name="name" value="<?php echo $sender['name']; ?>" type="text" class="mw-ui-field mw-ui-field-full-width js-validation" />
		<div class="js-field-message"></div>
	</div>
	
	<div class="mw-ui-field-holder">
		<label class="mw-ui-label"><?php _e('From Name'); ?></label> 
		<input name="from_name" value="<?php echo $sender['from_name']; ?>" type="text" class="mw-ui-field mw-ui-field-full-width js-validation" />
		<div class="js-field-message"></div>
	</div>
	
	<div class="mw-ui-field-holder">
		<label class="mw-ui-label"><?php _e('From Email'); ?></label> 
		<input name="from_email" value="<?php echo $sender['from_email']; ?>" type="text" class="mw-ui-field mw-ui-field-full-width js-validation js-validation-email" />
		<div class="js-field-message"></div>
	</div>
	
	<div class="mw-ui-field-holder">
		<label class="mw-ui-label"><?php _e('Reply Email'); ?></label> 
		<input name="reply_email" value="<?php echo $sender['reply_email']; ?>" type="text" class="mw-ui-field mw-ui-field-full-width js-validation js-validation-email" />
		<div class="js-field-message"></div>
	</div>
	
	<div class="mw-ui-field-holder">
		<label class="mw-ui-label"><?php _e('Send email function'); ?></label> 
		<select class="mw-ui-field mw-ui-field-full-width js-select-account-type" name="account_type">
		<option value="php_mail">PHP Mail</option>
		<option value="smtp">SMTP Server</option>
		<option value="mailchimp">Mailchimp</option>
		<option value="mailgun">Mailgun</option>
		<option value="mandrill">Mandrill</option>
		<option value="amazon_ses">Amazon SES</option>
		<option value="sparkpost">Sparkpost</option>
		</select>
	</div>
	
	<script>
		$(document).ready(function () {
			
			$(".js-sender-php-mail").show();
			
			$(document).on("change", ".js-select-account-type", function() {
				
				$(".js-sender-wrapper").hide();
				
				switch ($(this).val()) {
					case "mailchimp":
						$(".js-sender-mailchimp").show();
						break;
					case "mailgun":
						$(".js-sender-mailgun").show();
						break;
					case "mandrill":
						$(".js-sender-mandrill").show();
						break;
					case "amazon_ses":
						$(".js-sender-amazon-ses").show();
						break;
					case "sparkpost":
						$(".js-sender-sparkpost").show();
						break;
					case "php_mail":
						$(".js-sender-php-mail").show();
						break;
					case "smtp":
						$(".js-sender-smtp").show();
						break;
					default:
				}
				
			});
		
		});
	</script>
		
	
	<div class="js-sender-wrapper js-sender-mailchimp" style="display:none;">
		<div class="mw-ui-field-holder">
		   <label class="mw-ui-label">
		   Secret
		   </label>
		   <input name="mailchimp_secret" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width" type="text" value="<?php echo $sender['mailchimp_secret']; ?>">
		</div>
	</div>
	
	<div class="js-sender-wrapper js-sender-mandrill" style="display:none;">
		<div class="mw-ui-field-holder">
		   <label class="mw-ui-label">
		   Secret
		   </label>
		   <input name="mandrill_secret" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width" type="text" value="<?php echo $sender['mandrill_secret']; ?>">
		</div>
	</div>
	
	<div class="js-sender-wrapper js-sender-mailgun" style="display:none;">
		<div class="mw-ui-field-holder">
		   <label class="mw-ui-label">
		   Domain
		   </label>
		   <input name="mailgun_domain" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width" type="text" value="<?php echo $sender['mailgun_domain']; ?>">
		</div>
		<div class="mw-ui-field-holder">
		   <label class="mw-ui-label">
		   Secret
		   </label>
		   <input name="mailgun_secret" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width" type="text" value="<?php echo $sender['mailgun_secret']; ?>">
		</div>
	</div>
	
	<div class="js-sender-wrapper js-sender-amazon-ses" style="display:none;">
		<div class="mw-ui-field-holder">
		   <label class="mw-ui-label">
		   Key
		   </label>
		   <input name="amazon_ses_key" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width" type="text" value="<?php echo $sender['amazon_ses_key']; ?>">
		</div>
		<div class="mw-ui-field-holder">
		   <label class="mw-ui-label">
		   Secret
		   </label>
		   <input name="amazon_ses_secret" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width" type="text" value="<?php echo $sender['amazon_ses_secret']; ?>">
		</div>
		<div class="mw-ui-field-holder">
		   <label class="mw-ui-label">
		   Region
		   </label>
		   <input name="amazon_ses_region" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width" type="text" value="<?php echo $sender['amazon_ses_region']; ?>">
		</div>
	</div>
	
	<div class="js-sender-wrapper js-sender-sparkpost" style="display:none;">
		<div class="mw-ui-field-holder">
		   <label class="mw-ui-label">
		   Secret
		   </label>
		   <input name="sparkpost_secret" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width" type="text" value="<?php echo $sender['sparkpost_secret']; ?>">
		</div>
	</div>
	
	<div class="js-sender-wrapper js-sender-php-mail" style="display:none;">
		<!-- settings for php mail -->
	</div>
		
		
	<div class="js-sender-wrapper js-sender-smtp" style="display:none;">
		<div class="mw-ui-field-holder">
		   <label class="mw-ui-label">
		   Smtp Username
		   </label>
		   <input name="smtp_username" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width" type="text" value="<?php echo $sender['smtp_username']; ?>">
		</div>
		<div class="mw-ui-field-holder">
		   <label class="mw-ui-label">
		   Smtp Password
		   </label>
		   <input name="smtp_password" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width" type="text" value="<?php echo $sender['smtp_password']; ?>">
		</div>
		<div class="mw-ui-field-holder">
		   <label class="mw-ui-label">
		   Smtp Email Host
		   </label>
		   <input name="smtp_host" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width" type="text" value="<?php echo $sender['smtp_host']; ?>">
		</div>
		<div class="mw-ui-field-holder">
		   <label class="mw-ui-label">
		   Smtp Email Port
		   </label>
		   <input name="smtp_port" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width" type="text" value="<?php echo $sender['smtp_port']; ?>">
		</div>
	</div>
	
	<script>
		$(document).ready(function () {
			
			$(document).on("click", ".js-sender-test-method", function() {
				$(".js-sender-test-email-wrapper").toggle();
			});
			
			$(document).on("click", ".js-sender-send-test-email", function() {
				$(".js-email-send-test-output").html("Sending...");
				  $.ajax({
		            url: mw.settings.api_url + 'newsletter_test_sender',
		            type: 'POST',
		            data: $('.js-edit-sender-form').serialize(),
		            success: function (result) {
		            	$('.js-email-send-test-output').html(result);
		            },
					error: function(e) {
						$('.js-email-send-test-output').html('Error processing your request: ' + e.responseText);
					}
		        });
			});
		});
	</script>
	
	<table class="mw-ui-box mw-ui-box-content js-sender-test-email-wrapper" style="display:none;background: whitesmoke  none repeat scroll 0% 0%;" width=" 100%" border="0">
		<tbody>
		<tr>
			<td>
			<label class="mw-ui-label">
				Send test email to							
			</label>
			<input name="to_email" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width js-sender-test-email-to" type="text" option-group="email">
			<br /><br />
			<span class="mw-ui-btn mw-ui-btn-green js-sender-send-test-email">
				Send test email							
			</span>
			</td>
		</tr>
		<tr>
			<td>
			<hr />
			<pre class="js-email-send-test-output"></pre>
			</td>
		</tr>
	</tbody>
	</table>
	<br />
	<button type="button" class="mw-ui-btn js-sender-test-method"><?php _e('Test Method'); ?></button>	
	
	<button type="submit" class="mw-ui-btn"><?php _e('Save'); ?></button>
	<?php if(isset($sender['id'])): ?>
	<a class="mw-ui-btn mw-ui-btn-icon" href="javascript:;" onclick="delete_sender('<?php print $sender['id']; ?>')"> <span class="mw-icon-bin"></span> </a>
	<input type="hidden" value="<?php echo $sender['id']; ?>" name="id" />
	<?php endif; ?>
	<br />
	<br />
</form>