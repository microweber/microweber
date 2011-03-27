<?
 $id = $params['id'];



$form_values = get_page($id);
 
 ?>
<script type="text/javascript">
function set_layout($filename, $layout_name){

	 $('#content_layout_file').val($filename);

  $('#content_layout_name').val($layout_name);


  call_layout_config_module();
}

$(window).load(function(){
  call_layout_config_module();
  ajax_content_subtype_change();
});

function call_layout_config_module(){

 $file = $('#content_layout_file').val();
 $page_id = $('#page_id').val();
$('#auto_create_categories').val('');

 $.ajax({
  url: '<? print site_url('api/module'); ?>',
   type: "POST",
      data: ({module : 'admin/pages/layout_config' ,page_id : $page_id, file: $file }),
     // dataType: "html",
      async:false,
	  success: function(resp) {
	   $('#layout_config_module_placeholder').html(resp);
	   load_layout_config_file()
	  }
    });


}


function load_layout_config_file(){

 $file = $('#content_layout_file').val();
 $page_id = $('#page_id').val();


 $.ajax({
  url: '<? print site_url('api/content/get_layout_config'); ?>',
   type: "POST",
      data: ({filename :  $file ,page_id : $page_id, file: $file }),
      dataType: "json",
      async:true,
	  success: function($resp) {
	 // alert(resp);
	 if($resp != undefined){
	  $check_existing_cat = $('#content_subtype_value').val();
	 //alert($check_existing_cat);
	 if($check_existing_cat == ''){
		 if($resp.type != undefined){
		 $type_from_cfg = $resp.type
		  if($type_from_cfg == 'dynamic'){
			  $layout_name1 = $('#content_layout_name').val();
			   $('#content_subtype_value_new').val( $layout_name1);
			   
			   
			   $subcats_from_cfg = $resp.auto_create_categories
			   if($subcats_from_cfg != undefined){
				  // alert($subcats_from_cfg);
				  $('#auto_create_categories').val( $subcats_from_cfg);
				  
			   }
			   
		  }
		 }
	 } else {
		// alert(resp);
	 }
	 }
	 
	 
	 
	 
	  }
	  
	  
	  
	  
	  
	  
	  
	  
    });


}

</script>
<script type="text/javascript">



$(document).ready(function () {
  //
    
 });







function ajax_content_subtype_change(){





	var content_subtype = $("#content_subtype").val();

	var content_subtype_value = $("#content_subtype_value").val();




	 $.ajax({
  url: '<? print site_url('api/module'); ?>',
   type: "POST",
      data: ({module : 'admin/content/pages_content_subtype' ,content_subtype : content_subtype, content_subtype_value: content_subtype_value }),
     // dataType: "html",
      async:true,

  success: function(resp) {
     // $("#content_subtype_changer").html(resp);
  }
    });



}





function ajax_content_subtype_change_set_form_value(val){


	 $("#content_subtype_value").setValue(val);

}







</script>

<h1>Advanced</h1>
<div id="layout_config_module_placeholder"></div>
<? $layouts = CI::model('template')->layoutsList();  ?>
<? if(!empty($layouts)): ?>
<? foreach($layouts as $layout): ?>
<? if($layout['screenshot']): ?>
<!-- <a href="<? print $layout['screenshot'] ?>"> <img src="<? print $layout['screenshot'] ?>" height="100" /></a>-->
<? endif; ?>
<input type="radio"  value="<? print $layout['filename'] ?>" name="layoutsList" onclick="set_layout(this.value, '<? print $layout['layout_name'] ?>')"  />
<? print $layout['name'] ?> <? print $layout['description'] ?>
<? endforeach; ?>
<? endif; ?>
<label>Filename</label>
<input name="content_filename" type="text" value="<? print $form_values['content_filename'] ?>" />
<!--<legend>Content sub type</legend>-->
<label class="lbl">Content Subtype: </label>
<select   name="content_subtype" id="content_subtype" onchange="ajax_content_subtype_change()">
  <option <?php if($form_values['content_subtype'] == '' ): ?> selected="selected" <?php endif; ?>  value="">None</option>
  <option <?php if($form_values['content_subtype'] == 'blog_section' ): ?> selected="selected" <?php endif; ?>  value="blog_section">Blog section</option>
  <option <?php if($form_values['content_subtype'] == 'module' ): ?> selected="selected" <?php endif; ?>  value="module">Module</option>
</select>
<label class="lbl">Content Subtype Value:</label>
<input   name="content_subtype_value" id="content_subtype_value" type="text" value="<?php print $form_values['content_subtype_value']; ?>" >
new category
<input   name="content_subtype_value_new" id="content_subtype_value_new" type="text"  >
auto_create_categories
<input   name="auto_create_categories" id="auto_create_categories" type="text"  >
<div id="content_subtype_changer"></div>
<mw module="admin/content/category_selector"  active_categories="<?php print $form_values['content_subtype_value']; ?>" update_field="#content_subtype_value"  />
