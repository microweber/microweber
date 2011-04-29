<?
$id = intval( $params['id']);



$form_values = get_page($id);
//p($form_values);

if(intval($id) == 0){
	$try_parent = $params['content_parent'];
	if(intval($try_parent) == 0){
		$try_parent = url_param('content_parent');;
	}
	if(intval($try_parent) != 0){
	$form_values['content_parent'] = $try_parent;
	}


}
//p($form_values);

?>
<script type="text/javascript">
// prepare the form when the DOM is ready
$(document).ready(function() {
    var save_page_options = {
        type:      'post',
		dataType: 'json',
		url:       '<? print site_url('api/content/save_page') ?>' ,
        beforeSubmit:  save_page_showRequest,  // pre-submit callback
        success:       save_page_showResponse  // post-submit callback
    };

    $('#save_page_form').submit(function() {
        $(this).ajaxSubmit(save_page_options);
        return false;
    });

	//call_layout_config_module();
});

// pre-submit callback
function save_page_showRequest(formData, jqForm, options) {
    var queryString = $.param(formData);
    return true;
}

// post-submit callback
function save_page_showResponse(responseText, statusText, xhr, $form)  {
//document.getElementById('edit_frame').contentWindow.location.reload();


   // alert('status: ' + statusText + '\n\nresponseText: \n' + responseText +    '\n\nThe output div should have already been updated with the responseText.');
}
</script>

<div class="box radius">
  <div class="box_header radius_t">
    <input type="submit" value="Save changes" onclick="$('#save_page_form').submit()" class="sbm right" name="save">
    <h2>Edit Page</h2>
  </div>
  <div class="box_content">
    <form action="" method="post" id="save_page_form">
      <input name="id" id="id" type="hidden" value="<? print $form_values['id'] ?>" />
      <input name="page_id" id="page_id" type="hidden" value="<? print $form_values['id'] ?>" />
      <input name="content_type" type="hidden" value="page" />
      <mw module="admin/content/title_and_body" id="<? print $form_values['id'] ?>" />
      <br />
      <br />
      <br />
      <div class="mw_box mw_box_closed">
        <div class="mw_box_header"> <span class="mw_boxctrl"> Open </span>
          <h3>Layout and category</h3>
        </div>
        <div class="mw_box_content">
          <mw module="admin/pages/layout_and_category" id="<? print $form_values['id'] ?>"   />
        </div>
      </div>
      <div class="mw_box mw_box_closed">
        <div class="mw_box_header"> <span class="mw_boxctrl"> Open </span>
          <h3>Advanced options</h3>
        </div>
        <div class="mw_box_content">
          <mw module="admin/content/advanced_options" id="<? print $form_values['id'] ?>" for="page"  />
        </div>
      </div>
      
      <div class="mw_box mw_box_closed">
        <div class="mw_box_header"> <span class="mw_boxctrl"> Open </span>
          <h3>Media</h3>
        </div>
        <div class="mw_box_content">
          <mw module="admin/media/gallery" page_id="<? print $form_values['id'] ?>" for="page"  />
        </div>
      </div>
      
      
      
      <div class="mw_box mw_box_closed">
        <div class="mw_box_header"> <span class="mw_boxctrl"> Open </span>
          <h3>Meta tags</h3>
        </div>
        <div class="mw_box_content">
          <mw module="admin/content/meta_tags" id="<? print $form_values['id'] ?>" />
        </div>
      </div>
      <div class="mw_box mw_box_closed">
        <div class="mw_box_header"> <span class="mw_boxctrl"> Open </span>
          <h3>Menus</h3>
        </div>
        <div class="mw_box_content">
          <mw module="admin/content/content_to_menus" id="<? print $form_values['id'] ?>" />
        </div>
      </div>
      
      
      <div class="mw_box mw_box_closed">
        <div class="mw_box_header"> <span class="mw_boxctrl"> Open </span>
          <h3>Custom Fields</h3>
        </div>
        <div class="mw_box_content">
          <mw module="admin/content/custom_fields_creator" page_id="<? print $form_values['id'] ?>" />
        </div>
      </div>
    </form>
  </div>
</div>
