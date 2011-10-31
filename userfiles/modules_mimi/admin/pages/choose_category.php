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



$(document).ready(function () {
  //
  
  $('#content_subtype_value').live('change', function(){
													       content_subtype_show_info()
               // $(this).after('<b>@</b>');
              });
  
  
  
     content_subtype_show_info()
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





function content_subtype_show_info(){
 $v =  $('#content_subtype').val();

	 $(".layout_types").hide();
	  $(".layout_type_"+$v).show();
	  
	  
	  
	 content_subtype_value_val = $("#content_subtype_value").val();
	  if(content_subtype_value_val != undefined && content_subtype_value_val != ''){
		  
		  
		  $.getJSON('<? print site_url('api/content/get_taxonomy');?>/id:' + content_subtype_value_val, function(data) {
		 if(data != null){
							if(data.taxonomy_value != undefined || data.taxonomy_value != null ){																					 
						  $('.category_name_holder').html(data.taxonomy_value);
						   $('.category_edit_link_holder').attr('href', '<? print ADMIN_URL ?>/action:posts/category_id:'+content_subtype_value_val);
						  
							}
  
		 }
 
		});
		  
		  
		   
		  
		  
		   $('#add_to_category').hide();
		    $('#page_existing_category').show();
	  } else {
		  
		   $('#add_to_category').show();
	  }

}

function content_subtype_change_cat_toggle(){
   $('#add_to_category').toggle();
    $('#page_existing_category').toggle();
 

}

 
</script>

<div class="layout_types layout_type_static" style="display:none"> Your layout type is static </div>
<div class="layout_types layout_type_dynamic"  style="display:none">
  <b>Your layout type is dynamic. You can connect it to category and add posts in it.</b>
  <br>
<br>

  <input   name="content_subtype" id="content_subtype" type="hidden" value="<?php print $form_values['content_subtype']; ?>" >
  <!--<select   name="content_subtype" id="content_subtype" onchange="ajax_content_subtype_change()">
  <option <?php if($form_values['content_subtype'] == '' ): ?> selected="selected" <?php endif; ?>  value="">None</option>
  <option <?php if($form_values['content_subtype'] == 'blog_section' ): ?> selected="selected" <?php endif; ?>  value="blog_section">Blog section</option>
  <option <?php if($form_values['content_subtype'] == 'module' ): ?> selected="selected" <?php endif; ?>  value="module">Module</option>
</select>-->
  <div id="page_existing_category">
    <input   name="content_subtype_value" id="content_subtype_value" type="hidden" value="<?php print $form_values['content_subtype_value']; ?>" >
    <p>You page is connected to <strong><span class="category_name_holder"></span></strong> category. <a href="" target="_blank" class="blue category_edit_link_holder">Go to <span class="category_name_holder"></span></strong></a></p>
    
  	 
 
     
  
 <br>
<br>

  
    <a class="btn" href="javascript:content_subtype_change_cat_toggle();">change category</a> </div>
  <div id="add_to_category" style="display:none;">
  
  
  
  <table width="100" border="0" cellspacing="5" cellpadding="5">
  <tr>
    <td><label>Create new category</label></td>
    <td> </td>
     <td><label>Connect to existing</label></td>
  </tr>
   <tr>
  
     <td>
     <div class="formitem left">
      
      <div class="formfield">
        <input name="content_subtype_value_new" id="content_subtype_value_new" type="text"  >
        <input    name="auto_create_categories" id="auto_create_categories" type="hidden"  >
      </div>
    </div>
    
    </td>
      <td><h5><em>or</em></h5></td>
    <td>    <div id="content_subtype_changer"></div>
    <div class="drop drop_white left" style="width:150px;"> <span class="drop_arr"></span> <span class="val">Choose Category</span>
      <div class="drop_list" style="height: 227px;">
        <mw module="admin/content/category_selector"  active_categories="<?php print $form_values['content_subtype_value']; ?>" update_field="#content_subtype_value"  />
      </div>
    </div></td>
  </tr>
</table>

  

   
    
    
    
    
  </div>
</div>
