<?php only_admin_access(); ?>

<?php 
$template_id = (int) (isset($params['data_template_id']) ? $params['data_template_id'] : 0);
$template = get_mail_template_by_id($template_id);
?>

<script>
    $(document).ready(function () {
        $("#edit-mail-template-form").submit(function (event) {
            event.preventDefault();
            var data = $(this).serialize();
            var url = "<?php print api_url('save_mail_template'); ?>";
            var post = $.post(url, data);
            post.done(function (data) {
                mw.reload_module("admin/mail_templates");
                mw.reload_module("admin/mail_templates/list");
            });
        });
    });

    mw.editor({
        element:mwd.getElementById('editorAM'),
        hideControls:['format', 'fontsize', 'justifyfull']
   });
</script>

<form id="edit-mail-template-form">

  <h3>Edit mail template</h3>
  <br />
  <div class="mw-flex-row">
  
   <div class="mw-flex-col-md-5">
  <label class="mw-ui-label">Template Name</label> 
  <input type="text" name="name" value="<?php echo $template['name']; ?>" class="mw-ui-field" style="width:100%;">
  </div>
  
   <div class="mw-flex-col-md-12"><br /></div>
  
  <div class="mw-flex-col-md-2">
  <label class="mw-ui-label">From Name</label>
  <input type="text" name="from_name" value="<?php echo $template['from_name']; ?>" class="mw-ui-field" style="width:100%;">
  </div>
  
  <div class="mw-flex-col-md-3">
  <label class="mw-ui-label">From Email</label>
  <input type="text" name="from_email" value="<?php echo $template['from_email']; ?>" class="mw-ui-field" style="width:100%;">
  </div>
  
  <div class="mw-flex-col-md-12"></div>
  
  <div class="mw-flex-col-md-5">
  <br />
  <label class="mw-ui-label">Copy To</label>
   <input type="text" name="copy_to" class="mw-ui-field" value="<?php echo $template['copy_to']; ?>" style="width:100%;">
  </div>
  
  <div class="mw-flex-col-md-12"></div>
   
	 <div class="mw-flex-col-md-5">
	 	<br />
		<label class="mw-ui-label"><?php _e("Email attachments"); ?></label>  
		<module type="admin/components/file_append" option_group="mail_template_id_<?php echo $template_id; ?>" />
	</div>
  
  <div class="mw-flex-col-md-12"></div>
  
   <div class="mw-flex-col-md-5">
  <br />
  <label class="mw-ui-label">Subject</label>
   <input type="text" name="subject" value="<?php echo $template['subject']; ?>" class="mw-ui-field" style="width:100%;">
  </div>
  
  <div class="mw-flex-col-md-12">
  <br />
  <textarea id="editorAM" name="content" class="mw-ui-field" style="width:100%;"><?php echo $template['message']; ?></textarea>
  </div>
  
	<div class="mw-flex-col-md-12">
  	 <br />
  	  <input type="hidden" name="id" value="<?php echo $template['id']; ?>">
  	  <input type="hidden" name="type" value="<?php echo $template['type']; ?>">
  	  
	  <input type="submit" name="submit" value="Save changes" class="mw-ui-btn"/>
	  &nbsp;&nbsp;
	  <input name="submit" value="Cancel" onClick="mw.reload_module('admin/mail_templates')" class="mw-ui-btn"/>
    </div>
  
  </div>
</form>