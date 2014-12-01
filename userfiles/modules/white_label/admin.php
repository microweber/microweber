<?php only_admin_access(); ?>
<?php if(!have_license('modules/white_label')): ?>
<module  type="admin/modules/activate" prefix="modules/white_label" />
<?php return; ?>
<?php endif; ?>
<?php 
$logo_admin = false;
$logo_live_edit = false;
$logo_login = false;
$powered_by_link = false;
$powered_by_link = false;
$brand_name = false;


$settings = get_white_label_config(); 
if(isset($settings['logo_admin'])){
	$logo_admin = $settings['logo_admin'];
}
if(isset($settings['logo_live_edit'])){
	$logo_live_edit = $settings['logo_live_edit'];
}
if(isset($settings['logo_login'])){
	$logo_login = $settings['logo_login'];
}
 
if(isset($settings['powered_by_link'])){
	$powered_by_link = $settings['powered_by_link'];
}
 
?>
<script  type="text/javascript">
$(document).ready(function(){
 
 
 $("#white_label_settings_holder").submit(function() {

    var url = "<?php print api_url() ?>save_white_label_config"; // the script where you handle the form input.

    $.ajax({
           type: "POST",
           url: url,
           data: $("#white_label_settings_holder").serialize(), // serializes the form's elements.
           success: function(data)
           {
               mw.notification.success("White label saved");
           }
         });

    return false; // avoid to execute the actual submit of the form.
});




 
});
</script>

<script type="text/javascript">

  $(document).ready(function(){
	  
	mw.$(".up").each(function(){
		
	var span = mwd.createElement('span');
	span.className = 'mw-ui-btn';
	span.innerHTML = 'Upload';
$(this).after(span);
    var uploader = mw.uploader({
    filetypes:"images",
    multiple:false,
    element:span
  });
  
  uploader.field = this;

  $(uploader).bind("FileUploaded", function(obj, data){

          uploader.field.value = data.src;
      });
      
		
		
	});  
	  

	
	
	
    
 });
</script> 
 
<form id="white_label_settings_holder">
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label-inline">Logo admin</label>
    <input
                name="logo_admin"
                option-group="whitelabel"
                placeholder="Upload your logo"
              
                class="mw-ui-field up"
                type="text" 
                value="<?php print  $logo_admin; ?>"
                />
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label-inline">Logo Live edit</label>
    <input
                name="logo_live_edit"
                option-group="whitelabel"
                placeholder="Upload your logo"
              
                class="mw-ui-field up"
                type="text" 
                value="<?php print  $logo_live_edit; ?>"
                />
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label-inline">Logo login</label>
    <input
                name="logo_login"
                option-group="whitelabel"
                placeholder="Upload your logo"
              
                class="mw-ui-field up"
                type="text" 
                value="<?php print  $logo_login; ?>"
                />
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label-inline">powered_by_link html</label>
    <textarea  name="powered_by_link"   option-group="whitelabel"  placeholder="HTML code for template footer link"  class="mw-ui-field" type="text"><?php print  $powered_by_link; ?></textarea>
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label-inline">brand name</label>
    <input
                name="brand_name"
                option-group="whitelabel"
                placeholder="Enter the name of your company"
              
                class="mw-ui-field "
                type="text" 
                value="<?php print  $brand_name; ?>"
                />
  </div>
  <input type="submit" value="Save settings" />
</form>
