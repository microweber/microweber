<?
$id = $params['id'];



$form_values = get_page($id);
//p($form_values);

?>
 
 
<script type="text/javascript">



$(document).ready(function () {
 
 

   var flora_tabs = $(".flora").tabs();
 
   

});

 

</script>

<form action="" method="post" id="save_post_form">
<input name="save" type="submit" value="save" />
<input name="id" id="page_id" type="hidden" value="<? print $form_values['id'] ?>" />
<input name="content_type" type="hidden" value="post" />
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

$(window).load(function(){
   set_categories();
 hide_pages_without_categories();
});

$(document).ready(function () {



   var flora_tabs = $(".flora").tabs();
 
   

});


 
 function hide_pages_without_categories(){
	 $('input[name=\'content_parent\']').each(function(index) {
   // alert(index + ': ' + $(this).text());
   $catid =  $(this).attr('category_id');
   
   if($catid==""){
		$(this).parent().hide();   
	}
  // alert($catid);
  //$catid  = parseInt($catid );
   // $("#save_post_form").append($catid);
  
   
   
  });
	 
 }
 


function set_categories(){

	var active_category = $("#taxonomy_categories").val();
	var content_parent = $("input[name='content_parent']:checked").val();
	var post_id = $("#post_id").val();
	
	

	 $.ajax({
  url: '<? print site_url('api/module'); ?>',
   type: "POST",
      data: ({module : 'admin/content/category_selector' ,active_category : active_category, update_field: '#taxonomy_categories', multiselect:true, for_page:content_parent }),
     // dataType: "html",
      async:true,
      
  success: function(resp) {
      $("#category_module_holder").html(resp);
	  
	  
	  var temp_1 = $("input[name='content_parent']:checked").attr("category_id");
	  if(temp_1 != ""){
		 $("#category_module_holder span[category_id='" + temp_1 + "']").addClass("active");
		 $("#taxonomy_categories").val(temp_1 + "," + $("#taxonomy_categories").val())
	}
	  
	  
	  	$.ajax({
  type: 'POST',
  url: '<? print site_url('api/content/get_page'); ?>',
  data: ({id : content_parent }),
  success: function(resp2) {
	 // alert(resp2);
	    $("#content_url_page").html(resp2.url);
	   
  },
  dataType: 'json'
});
		
		
		
			  	$.ajax({
  type: 'POST',
  url: '<? print site_url('api/module'); ?>',
  data: ({module : 'admin/content/custom_fields_editor',page_id : content_parent , post_id : post_id}),
  success: function(resp3) {
	 // alert(resp2);
	   $("#post_custom_fields").html(resp3);
	   
  },
  dataType: 'html'
});
		
		
	  
	  
	  
  }
    });
	
	
	
	
	
 

}
 


</script>
<form action="" method="post" id="save_post_form">
  <input name="save" type="submit" value="save" />
  <input name="id" id="post_id" type="hidden" value="<? print $form_values['id'] ?>" />
  <input name="content_type" type="hidden" value="post" />
  <hr />
  parent page
  <?php
 
 CI::model('content')->content_helpers_getPagesAsUlTree(0, "<input onchange='javascript:set_categories()' type='radio' name='content_parent' category_id='{content_subtype_value}'  {removed_ids_code}  {active_code}  value='{id}' />{content_title}", array($form_values['content_parent']), 'checked="checked"', array($form_values['id']) , 'disabled="disabled"' );  ?>
 
 
 <div id="post_custom_fields"></div>
 
 
 
 
  <? 
/*$pages_params = array();
$pages_params['content_subtype'] = 'blog_section';
$pages_params['content_type'] = 'page';
$pages = get_pages($pages_params);
*/
 ?>
  <? if(!empty($pages['posts'])): ?>
  <select name="content_parent">
    <?  foreach($pages['posts'] as $page ) :?>
    <option category_id="<? print $page['content_subtype_value'] ?>" value="<? print $page['id'] ?>" <? if($form_values['content_parent'] == $page['id']): ?>  selected="selected" <? endif; ?>  ><? print $page['content_title'] ?></option>
    <? // p($page);  ?>
    <? endforeach; ?>
  </select>
  <? endif; ?>
  <?
 $get_categories_params = array(); 
    $get_categories_params['for_content'] = $form_values['id']; //if integer - will get the categories for given content it (post)
$get_categories = get_categories($get_categories_params) ;
//p($get_categories);
?>
  <? if(!empty($get_categories)): ?>
  <? $category_ids = CI::model('core')->dbExtractIdsFromArray($get_categories); ?>
  <? else : ?>
  <? endif; ?>
  taxonomy_categories
  <input name="taxonomy_categories" id="taxonomy_categories" type="text"   value="<? print implode(',',$category_ids); ?>" />
  <div id="category_module_holder"></div>
  <hr />
  content_title
  <input name="content_title" type="text" value="<? print $form_values['content_title'] ?>" />
  <hr />
  content_url
  
  
  <div id="content_url_page"></div>
  <input name="content_url" type="text" style="width:300px;" value="<? print $form_values['content_url'] ?>" />
  <hr />
  content_description
  <input name="content_description" type="text" value="<? print $form_values['content_description'] ?>" />
  <hr />
  content_body
  <textarea name="content_body" cols="50" rows="80"><? print $form_values['content_body'] ?></textarea>
  <br />
  <br />
  <br />
  <hr />
  <hr />
  require_login
  <input name="require_login" type="text" value="<? print $form_values['require_login'] ?>" />
  <hr />
  original_link
  <input name="original_link" type="text" value="<? print $form_values['original_link'] ?>" />
 
 
  <hr />
  Meta<br />
  <br />
  content_meta_title
  <input name="content_meta_title" type="text" value="<? print $form_values['content_meta_title'] ?>" />
  <hr />
  content_meta_description
  <input name="content_meta_description" type="text" value="<? print $form_values['content_meta_description'] ?>" />
  <hr />
  content_meta_keywords
  <input name="content_meta_keywords" type="text" value="<? print $form_values['content_meta_keywords'] ?>" />
  <hr />
  <fieldset>
    <legend>Add this post to menus</legend>
    <?php  CI::model('content')->content_model->getMenus(array('item_type' => 'menu')); ?>
    <?php foreach($menus as $item): ?>
    <?php $is_checked = false; $is_checked = CI::model('content')->content_helpers_IsContentInMenu($form_values['id'],$item['id'] ); ?>
    <label class="lbl"> <?php print $item['item_title'] ?>&nbsp;
      <input name="menus[]" type="checkbox" <?php if($is_checked  == true): ?> checked="checked"  <?php endif; ?> value="<?php print $item['id'] ?>" />
    </label>
    <?php endforeach; ?>
    <?php //  var_dump( $menus);  ?>
  </fieldset>
</form> 






 