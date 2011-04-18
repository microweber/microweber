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
  <br />
  <br />
  <br />
  <script>
  $(document).ready(function() {
    //$("#tabs").tabs();
  });
  </script>


  <div id="orders_tabs" class="mw_box">
  <div class="mw_box_tab_content">
  <div class="shop_nav_main">
      <h2 class="box_title">Options</h2>
      <ul class="shop_nav">
      <li><a href="#tab=fragment-1"><span>Categories</span></a></li>
      <li><a href="#tab=fragment-2"><span>Media</span></a></li>
      <li><a href="#tab=fragment-4"><span>Custom Fields</span></a></li>
      <li><a href="#tab=fragment-3"><span>Advanced options</span></a></li>
      <li><a href="#tab=fragment-5"><span>Meta tags</span></a></li>
      <li><a href="#tab=fragment-6"><span>Menus</span></a></li>
      </ul>
    </div>





  <div id="tabs">






    <div id="fragment-1" class="tab">
      <mw module="admin/posts/select_categories_for_post" id="<? print $form_values['id'] ?>" />
    </div>
    <div id="fragment-2" class="tab">
      <mw module="admin/media/gallery" for="post" post_id="<? print $form_values['id'] ?>" />
    </div>
    <div id="fragment-3" class="tab">
      <mw module="admin/content/advanced_options" id="<? print $form_values['id'] ?>" />
    </div>
    <div id="fragment-4" class="tab">
      <div id="post_custom_fields"></div>
    </div>
    <div id="fragment-5" class="tab">
      <mw module="admin/content/meta_tags" id="<? print $form_values['id'] ?>" />
    </div>
    <div id="fragment-6" class="tab">
      <mw module="admin/content/content_to_menus" id="<? print $form_values['id'] ?>" />
    </div>
  </div>
  </div>
  </div>
</form>
