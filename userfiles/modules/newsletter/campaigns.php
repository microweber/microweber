<?php only_admin_access(); ?>
<script>
function edit_campaign(form){
	 var data = mw.serializeFields(form);
	 $.ajax({
        url: mw.settings.api_url + 'newsletter_save_campaign',
        type: 'POST',
        data: data,
        success: function (result) {
			mw.notification.success('Campaign saved');
			$('#add-campaign-form').hide();
			$('#add-campaign-form')[0].reset();
			
			//reload the modules
			mw.reload_module('newsletter/campaigns_list')
			mw.reload_module_parent('newsletter'); 
         }
    });
	return false;
}
function delete_campaign(id){
	var ask = confirm("Are you sure you want to delete this campaign?");
	if (ask == true) {
		 var data = {};
		 data.id = id;
		 $.ajax({
			url: mw.settings.api_url + 'newsletter_delete_campaign',
			type: 'POST',
			data: data,
			success: function (result) {
				mw.notification.success('Campaign deleted');
				
				//reload the modules
				mw.reload_module('newsletter/campaigns_list')
				mw.reload_module_parent('newsletter')
			 }
		});	 
	}
	 
	 
	return false;
}
</script>

<a class="mw-ui-btn mw-ui-btn-icon" href="javascript:;" onclick="$('#add-campaign-form').show()"> <span class="mw-icon-plus">Add new campaign</span> </a>
<form id="add-campaign-form" onSubmit="edit_campaign(this); return false;" style="display:none">
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">campaign Name</label>
    <input name="name" type="text" class="mw-ui-field" />
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">campaign Email</label>
    <input name="email" type="text" class="mw-ui-field" />
  </div>
  <button type="submit" class="mw-ui-btn">Save</button>
</form>
<div class="mw-clear"></div>
<br />
<module type="newsletter/campaigns_list" />
