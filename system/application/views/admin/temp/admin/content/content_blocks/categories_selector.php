<?php if(intval($form_values['id']  != 0)){
	
	$content_selected_categories = $this->taxonomy_model->getCategoriesForContent ( $form_values ['id'], true );
  $actve_ids = $content_selected_categories ;
	$form_values['active_categories'] = $content_selected_categories;
}
//var_dump($actve_ids);
//exit();
?>

<script type="text/javascript">
function add_selected_categories($cat_id){
temp = $("#the_category_selector_"+$cat_id).attr('checked', true);
process_selected_categoeries();
tb_remove() ;
}

function remove_selected_categories($cat_id){
temp = $("#the_category_selector_"+$cat_id).attr('checked', false);
process_selected_categoeries();
}

function content_show_the_add_categories_dialog(){
//alert(1);
}



function process_selected_categoeries(){
	var tax_parent = $(".content-category-tree-selector");
		var find_c = tax_parent.find(":checked");
			var cc = find_c.serializeArray();
			var temp = new Array();
			 jQuery.each(cc, function(i, field){
			 temp[i] = field.value;
      		});
			
			temp = temp.join(',') 

			  
			  
			  
			  $.ajax({
   type: "POST",
   
   url: "<?php print site_url('ajax_helpers/taxonomy_getParentsIds') ?>",
   data: "category_ids="+temp,
     async: false,
   success: function(data){
    
	
	
	
	
	  $brokenstring=data.split(',');
			  jQuery.each($brokenstring, function() {
				temp = $("#the_category_selector_"+this).attr('checked', true);
				});  

   }
 });
			
			
			
			
			
			
			
			var tax_parent = $(".content-category-tree-selector");
		var find_c = tax_parent.find(":checked");
			var cc = find_c.serializeArray();
			var temp = new Array();
			 jQuery.each(cc, function(i, field){
			 temp[i] = field.value;
			
      		});
			


			temp = temp.join(',')
			append_custom_html_to_the_last_li = "<a class='remove_cat' href='javascript:remove_selected_categories({id})'></a>"
			$.post("<?php print site_url('ajax_helpers/taxonomy_categories_print_breadcrumbs_path_for_categories_ids') ?>", {
			category_ids: temp,
			append_custom_html_to_the_last_li: append_custom_html_to_the_last_li
			 },
			  function(data){
			  
			  if(data == ''){
			  $("#validation_valid_categories").val(''); 
	
	} else {
	 
	   $("#validation_valid_categories").val('yes');
	}
			  
			  
			   $("#selected-categories").html(data);
			  });



			$.post("<?php print site_url('ajax_helpers/taxonomy_categories_get_main_site_section_id_for_caegory_ids') ?>", {  
			category_ids: temp
			 },
			  function(data){
			   $("#content_parent").val(data);
			   

					
					if (document.getElementById("content_url_demo_holder") != null){
			   		//lets get the url
							$.post("<?php print site_url('ajax_helpers/content_get_url_by_id') ?>", {  
							id: data
							 },
							  function(data1){
							   $("#content_url_demo_holder").html(data1);
							  });
					   }
			  });
			  
			  
			  
			  
			  







			//lets get the main section
			



}


$(document).ready(function() {
process_selected_categoeries()
});
</script>


<div id="add_content_to_more_categories_dialog_container" style="display:none;">
       <?php $link = 'javascript:add_selected_categories({id})';
	  $link = "<a {active_code} href=$link name='{id}'>{taxonomy_value}</a>"; 
	 $tree_params = array();
	 $tree_params['content_parent'] = 0;
	 $tree_params['link'] = $link;
	 $tree_params['actve_ids'] = $content_selected_categories;
	 $tree_params['active_code'] = " class='active'  ";
	// $tree_params['content_type'] =  $content_type;
	 $tree_params_string = $this->core_model->securityEncryptArray($tree_params);
	 
	 
	// var_dump( $tree_params);
	 ?>
     
     
      <div class="ooyes_ul_tree_container" treeparams='<?php print $tree_params_string;  ?>'></div>
      <?php $tree_params_string = false;  ?>
</div>



<?php //var_dump($selected_categories);

//var_dump($form_values); ?>

<div id="selected-categories-hidden" style="display:none">
  <?php 
  if(empty( $actve_ids)){
  $actve_ids =  $form_values['active_categories'];
  }
//var_dump($actve_ids );
$active_code = ' checked="checked" ' ;
$content_type = false;
	  $this->content_model->content_helpers_getCaregoriesUlTree(0, "<label><input  name='taxonomy_categories[]' type='checkbox' onchange='process_selected_categoeries()'  {active_code}  title='{taxonomy_value}'  id='the_category_selector_{id}' value='{id}' />{taxonomy_value}</label>", $actve_ids , $active_code , $remove_ids = false, $removed_ids_code = false, $ul_class_name='content-category-tree-selector', $include_first = false, $content_type = $content_type);
	//  var_dump( $content_type);
?>
</div>
<div id="selected-categories" style="display:block"> categories </div>
<input name="content_parent"  id="content_parent" type="hidden" value="<?php print $form_values['content_parent'] ?>"   />

<input name="validation_valid_categories" class="required"  id="validation_valid_categories" type="text"  style="display:none;"  />

<div style="clear: both;height: 7px;overflow: hidden"><!--  --></div>
<a class='add_more_categories thickbox' href="#TB_inline?height=500&width=400&inlineId=add_content_to_more_categories_dialog_container&modal=false">Add more categories</a>
<div style="clear: both;height: 5px;overflow: hidden"><!--  --></div>






<?php ?>