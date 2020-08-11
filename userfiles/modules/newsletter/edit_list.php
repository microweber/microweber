<?php only_admin_access(); ?>

<?php 
if (isset($params['id'])) {
	$list = newsletter_get_list($params['id']);
}

$templates_params = array();
$templates_params['no_limit'] = true;
$templates_params['order_by'] = "created_at desc";
$templates = newsletter_get_templates($templates_params);

$senders_params = array();
$senders_params['no_limit'] = true;
$senders_params['order_by'] = "created_at desc";
$senders = newsletter_get_senders($senders_params);
?>

<style>
.mw-ui-field-full-width {
	width:100%;
}
.js-danger-text {
	padding-top: 5px;
	color: #c21f1f;
}
.js-template-select-table {
	border:0px;
	width:100%;
}
.js-template-select-table tr {
	height:50px;
}
.js-template-select-table td {
	height:50px;
}
</style>

<script>
	mw.require("<?php print $config['url_to_module'];?>/js/js-helper.js");
	
	$(document).ready(function () {
		
		$(document).on("change", ".js-validation", function() {
			$('.js-edit-list-form :input').each(function() {
				if ($(this).hasClass('js-validation')) {
					runFieldsValidation(this);
				}
			});
		});

		$(".js-edit-list-form").submit(function(e) {
			
			e.preventDefault(e);
				
			 var errors = {};
	         var data = mw.serializeFields(this);
	        
			$('.js-edit-list-form :input').each(function(k,v) {
					if ($(this).hasClass('js-validation')) {
						if (runFieldsValidation(this) == false) {
							errors[k] = true;
						}
					}
			});
			
	        if (isEmpty(errors)) {
				
		        $.ajax({
		            url: mw.settings.api_url + 'newsletter_save_list',
		            type: 'POST',
		            data: data,
		            success: function (result) {
						
		                mw.notification.success('<?php _ejs('List saved'); ?>');
		
		                // Remove modal
		                if (typeof(edit_list_modal) != 'undefined' && edit_list_modal.modal) {
		                	edit_list_modal.modal.remove();
					     }
					       
		                // Reload the modules
		                mw.reload_module('newsletter/lists_list')
		                mw.reload_module('newsletter/edit_campaign')
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

		return ok;
	}
</script>

<form class="js-edit-list-form">

	<div class="mw-ui-field-holder">
		<label class="mw-ui-label"><?php _e('Name'); ?></label> 
		<input name="name" value="<?php echo $list['name']; ?>" type="text" class="mw-ui-field mw-ui-field-full-width js-validation" />
		<div class="js-field-message"></div>
	</div>
	
	<?php if (empty($templates)): ?>
		<b style="color:#b93636;">First you need to create templates.</b>
	<?php endif; ?>
	
	<?php if (!empty($templates)): ?>
		<table class="js-template-select-table">
		<tr>
		<td style="width:50%;">
		<label class="mw-ui-label"><?php _e('Success Email Template'); ?></label> 
		</td>
		<td style="width:50%;">
		<select name="success_email_template_id" class="mw-ui-field mw-ui-field-full-width">
		<?php foreach($templates as $template) : ?>
		<option <?php if($list['success_email_template_id'] == $template['id']):?>selected="selected"<?php endif;?> value="<?php echo $template['id']; ?>"><?php echo $template['title']; ?></option>
		<?php endforeach; ?>
		</select>
		<div class="js-field-message"></div>
		</td>
		</tr>
		
		<tr>
		<td>
		<label class="mw-ui-label"><?php _e('Success Email Sender'); ?></label> 
		</td>
		<td>
		<?php if (!empty($senders)): ?>
		<select name="success_sender_account_id" class="mw-ui-field mw-ui-field-full-width">
		<?php foreach($senders as $sender) : ?>
		<option <?php if($list['success_sender_account_id'] == $sender['id']):?>selected="selected"<?php endif;?> value="<?php echo $sender['id']; ?>"><?php echo $sender['name']; ?></option>
		<?php endforeach; ?>
		</select>
		<?php else: ?>
		<b style="color:#b93636;">First you need to add senders.</b>
		<?php endif; ?>
		<div class="js-field-message"></div>
		</td>
		</tr>
		
		<tr>
		<td>
		<label class="mw-ui-label"><?php _e('Unsubscription Email Template'); ?></label> 
		</td>
		<td>
		<select name="unsubscription_email_template_id" class="mw-ui-field mw-ui-field-full-width">
		<?php foreach($templates as $template) : ?>
		<option <?php if($list['unsubscription_email_template_id'] == $template['id']):?>selected="selected"<?php endif;?> value="<?php echo $template['id']; ?>"><?php echo $template['title']; ?></option>
		<?php endforeach; ?>
		</select>
		<div class="js-field-message"></div>
			</td>
		</tr>
		
		<tr>
		<td>
		<label class="mw-ui-label"><?php _e('Unsubscription Email Sender'); ?></label> 
		</td>
		<td>
		<?php if (!empty($senders)): ?>
		<select name="unsubscription_sender_account_id" class="mw-ui-field mw-ui-field-full-width">
		<?php foreach($senders as $sender) : ?>
		<option <?php if($list['unsubscription_sender_account_id'] == $sender['id']):?>selected="selected"<?php endif;?> value="<?php echo $sender['id']; ?>"><?php echo $sender['name']; ?></option>
		<?php endforeach; ?>
		</select>
		<?php else: ?>
		<b style="color:#b93636;">First you need to add senders.</b>
		<?php endif; ?>
		<div class="js-field-message"></div>
			</td>
		</tr>
	
		<tr>
		<td>
		<label class="mw-ui-label"><?php _e('Confirmation Email Template'); ?></label>
		 </td>
		 <td>
		<select name="confirmation_email_template_id" class="mw-ui-field mw-ui-field-full-width">
		<?php foreach($templates as $template) : ?>
		<option <?php if($list['confirmation_email_template_id'] == $template['id']):?>selected="selected"<?php endif;?> value="<?php echo $template['id']; ?>"><?php echo $template['title']; ?></option>
		<?php endforeach; ?>
		</select>
		<div class="js-field-message"></div>
		</td>
		</tr>
		
		<tr>
		<td>
		<label class="mw-ui-label"><?php _e('Confirmation Email Sender'); ?></label> 
		</td>
		<td>
		<?php if (!empty($senders)): ?>  
		<select name="confirmation_sender_account_id" class="mw-ui-field mw-ui-field-full-width">
		<?php foreach($senders as $sender) : ?>
		<option <?php if($list['confirmation_sender_account_id'] == $sender['id']):?>selected="selected"<?php endif;?> value="<?php echo $sender['id']; ?>"><?php echo $sender['name']; ?></option>
		<?php endforeach; ?>
		</select>
		<?php else: ?>
		<b style="color:#b93636;">First you need to add senders.</b>
		<?php endif; ?>
		<div class="js-field-message"></div>
		</td>
		</tr>
		
	</table>
	<?php endif; ?>
	
	

	<button type="submit" class="mw-ui-btn"><?php _e('Save'); ?></button>
	<?php if(isset($list['id'])): ?>
	<a class="mw-ui-btn mw-ui-btn-icon" href="javascript:;" onclick="delete_list('<?php print $list['id']; ?>')"> <span class="mw-icon-bin"></span> </a>
	<input type="hidden" value="<?php echo $list['id']; ?>" name="id" />
	<?php endif; ?>
</form>