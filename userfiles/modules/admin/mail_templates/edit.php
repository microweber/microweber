<?php only_admin_access(); ?>


<?php 
$template_id = (int) $params['data_template_id'];
$template = get_mail_templates("id=".$template_id."&single=1");
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

    initEditor = function() {
        if (!window.editorLaunced) {
               editorLaunced = true;
               mw.editor({
                   element:mwd.getElementById('editorAM'),
                   hideControls:['format', 'fontsize', 'justifyfull']
              });
          }
      }

      $(document).ready(function(){
         initEditor();
      });
</script>

<form id="edit-mail-template-form">

  <h3>Edit mail template</h3>
  <br />
  <div class="mw-flex-row">
  
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
	  <input type="submit" name="submit" value="Save changes" class="mw-ui-btn"/>
	  &nbsp;&nbsp;
	  <input name="submit" value="Cancel" onClick="mw.reload_module('#edit-mail-template')" class="mw-ui-btn"/>
    </div>
  
  </div>
</form>