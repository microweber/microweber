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
			            
		                mw.notification.success('<?php _e('Sender saved'); ?>');
		
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
	       		mw.notification.error('<?php _e('Please fill correct data.'); ?>');
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
		<select class="mw-ui-field mw-ui-field-full-width" name="account_type">
		<option value="php_mail">PHP Mail</option>
		<option value="smtp">SMTP Server</option>
		<option value="mailchimp">Mailchimp</option>
		<option value="mailgun">Mailgum</option>
		<option value="amazon_ses">Amazon SES</option>
		<option value="sparkpost">Sparkpost</option>
		</select>
	</div>
	
	
		
	<div class="js-sender-account-type">
	<div class="mw-ui-field-holder">
	   <label class="mw-ui-label">Smtp Username	
	   <br>
	   <small>example: user@email.com</small></label>
	   <input name="smtp_username" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width" type="text" value="<?php echo $sender['smtp_username']; ?>">
	</div>
	<div class="mw-ui-field-holder">
	   <label class="mw-ui-label">Smtp Password</label>
	   <input name="smtp_password" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width" type="text" value="<?php echo $sender['smtp_password']; ?>">
	</div>
	<div class="mw-ui-field-holder">
	   <label class="mw-ui-label">
	   Smtp Email Host <br>
	   <small>example: smtp.gmail.com</small>
	   </label>
	   <input name="smtp_host" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width" type="text" value="<?php echo $sender['smtp_host']; ?>">
	</div>
	<div class="mw-ui-field-holder">
	   <label class="mw-ui-label">
	   Smtp Email Port<br>
	   <small>example: 587 or 995, 465, 110, 25</small>
	   </label>
	   <input name="smtp_port" class="mw_option_field mw-ui-field mw-options-form-binded mw-ui-field-full-width" type="text" value="<?php echo $sender['smtp_port']; ?>">
	</div>
	</div>

	
	<button type="submit" class="mw-ui-btn"><?php _e('Save'); ?></button>
	<?php if(isset($sender['id'])): ?>
	<a class="mw-ui-btn mw-ui-btn-icon" href="javascript:;" onclick="delete_sender('<?php print $sender['id']; ?>')"> <span class="mw-icon-bin"></span> </a>
	<input type="hidden" value="<?php echo $sender['id']; ?>" name="id" />
	<?php endif; ?>
	<br />
	<br />
</form>