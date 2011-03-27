<?
$id = $params['id'];



$form_values = get_page($id);

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

<form action="" method="post" id="save_page_form">
  <input class="btn" name="save" type="submit" value="save" />
  <input name="id" id="page_id" type="hidden" value="<? print $form_values['id'] ?>" />
  <input name="content_type" type="hidden" value="page" />
  <label>Page title</label>
  <span class="formfield">
  <input name="content_title" onchange="mw.buildURL(this.value, '#content_url')" type="text" value="<? print $form_values['content_title'] ?>" />
  </span>
  <label>Content URL </label>
  <span class="formfield">
  <input id="content_url" name="content_url" type="text" value="<? print $form_values['content_url'] ?>" />
  </span>
  <label>Description</label>
  <span class="formfield">
  <input name="content_description" type="text" value="<? print $form_values['content_description'] ?>" />
  </span>
  <label>Content</label>
  <span class="formfield">
  <textarea name="content_body" class="richtext" cols="" rows=""><? print $form_values['content_body'] ?></textarea>
  </span> <br />
  <hr />
  <mw module="admin/pages/layout_and_category" id="<? print $form_values['id'] ?>"   />
  <div class="formitem">
    <label>Page File</label>
    <div class="formfield">
      <input name="content_layout_file" type="text" id="content_layout_file" value="<? print $form_values['content_layout_file'] ?>" />
    </div>
  </div>
  <div class="formitem">
    <label>Layout</label>
    <span class="formfield">
    <input name="content_layout_name" type="text" id="content_layout_name" value="<? print $form_values['content_layout_name'] ?>" />
    </span> </div>
  <br />
  <hr />
  <h2> parent page </h2>
  <?php

 CI::model('content')->content_helpers_getPagesAsUlTree(0, "<input type='radio' name='content_parent'  {removed_ids_code}  {active_code}  value='{id}' />{content_title}", array($form_values['content_parent']), 'checked="checked"', array($form_values['id']) , 'disabled="disabled"' );  ?>
 
 
 
 
 
 
 <br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />

 
 
 
 
  <div class="mw_box mw_box_closed">
    <div class="mw_box_header"> <span class="mw_boxctrl"> Open </span>
      <h3>Advanced options</h3>
    </div>
    <div class="mw_box_content">
      <mw module="admin/content/advanced_options" id="<? print $form_values['id'] ?>" for="page"  />
    </div>
  </div>
  <div class="mw_box mw_box_open">
    <div class="mw_box_header"> <span class="mw_boxctrl"> Open </span>
      <h3>Custom Fields</h3>
    </div>
    <div class="mw_box_content">
      <mw module="admin/content/custom_fields_creator" page_id="<? print $form_values['id'] ?>" />
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
</form>
