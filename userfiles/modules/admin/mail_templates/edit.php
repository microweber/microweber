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

    NewMailEditor = mw.editor({
        element:mwd.getElementById('editorAM'),
        hideControls:['format', 'fontsize', 'justifyfull'],
        height:900,
        addControls: mwd.getElementById('editorctrls').innerHTML,
        ready: function (content) {
            content.defaultView.mw.dropdown();
            mw.$("#email_content_dynamic_vals li", content).bind('click', function () {
            	NewMailEditor.api.insert_html($(this).attr('value'));
            });
        }
   });
    $(NewMailEditor).bind('change', function () {

    });
</script>

<div id="editorctrls" style="display: none">

    <span class="mw_dlm"></span>
    <div style="width: 112px;" data-value="" title="<?php _e("These values will be replaced with the actual content"); ?>" id="email_content_dynamic_vals" class="mw-dropdown mw-dropdown-type-wysiwyg mw-dropdown-type-wysiwyg_blue mw_dropdown_action_dynamic_values">
        <span class="mw-dropdown-value">
            <span class="mw-dropdown-arrow"></span>
            <span class="mw-dropdown-val"><?php _e("E-mail Values"); ?></span>
        </span>
        <div class="mw-dropdown-content">
            <ul>
				<?php foreach(get_mail_template_fields($template['type']) as $field): ?>
                <li value="<?php echo $field['tag']; ?>"><a href="javascript:;"><?php _e($field['name']); ?></a></li>
               <?php endforeach; ?>
            </ul>
        </div>
    </div>

</div>

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