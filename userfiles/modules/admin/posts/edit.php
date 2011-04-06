<?
$id = $params['id'];



$form_values = get_page($id);
//p($form_values);

?>
<script type="text/javascript">



$(document).ready(function () {
 
 

    
 
   

});

 

</script>

 
<?
$id = $params['id'];



$form_values = get_page($id);
//p($form_values);

?>
<script type="text/javascript">
// prepare the form when the DOM is ready 
$(document).ready(function() { 
    var save_post_options = { 
        type:      'post',
		dataType: 'json',
		url:       '<? print site_url('api/content/save_post') ?>' ,
        beforeSubmit:  save_post_showRequest,  // pre-submit callback 
        success:       save_post_showResponse  // post-submit callback 
    }; 
 
    $('#save_post_form').submit(function() { 
        $(this).ajaxSubmit(save_post_options); 
        return false; 
    }); 
	
	 
});
 
// pre-submit callback 
function save_post_showRequest(formData, jqForm, options) { 
    var queryString = $.param(formData); 
    return true; 
} 
 
// post-submit callback 
function save_post_showResponse(responseText, statusText, xhr, $form)  { 
//document.getElementById('edit_frame').contentWindow.location.reload();


   // alert('status: ' + statusText + '\n\nresponseText: \n' + responseText +    '\n\nThe output div should have already been updated with the responseText.'); 
} 
</script>
<script type="text/javascript">
 
 


</script>
<form action="" method="post" id="save_post_form">
  <input name="id" id="post_id" type="hidden" value="<? print $form_values['id'] ?>" />
  <input name="content_type" type="hidden" value="post" />
 
 
 
 
  <mw module="admin/content/title_and_body" id="<? print $form_values['id'] ?>" />
 
 
 

  
  
  
  
  
  
  
  
  
  
  
  
  <mw module="admin/posts/select_categories_for_post" id="<? print $form_values['id'] ?>" />
  <div class="mw_box mw_box_closed">
    <div class="mw_box_header"> <span class="mw_boxctrl"> Open </span>
      <h3>Media</h3>
    </div>
    <div class="mw_box_content">
      <mw module="admin/media/gallery" for="post" post_id="<? print $form_values['id'] ?>" />
    </div>
  </div>
  <div class="mw_box mw_box_closed">
    <div class="mw_box_header"> <span class="mw_boxctrl"> Open </span>
      <h3>Advanced options</h3>
    </div>
    <div class="mw_box_content">
      <mw module="admin/content/advanced_options" id="<? print $form_values['id'] ?>" />
    </div>
  </div>
  <div class="mw_box mw_box_closed">
    <div class="mw_box_header"> <span class="mw_boxctrl"> Open </span>
      <h3>Custom Fields</h3>
    </div>
    <div class="mw_box_content">
      <div id="post_custom_fields"></div>
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
