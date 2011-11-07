<script type="text/javascript">
    <? 
           $iframe_module_params = array();
           $iframe_module_params['module'] = 'admin/content/category_edit';
           
           $iframe_module_params = base64_encode(serialize($iframe_module_params));
           
           
           
           ?>
         

		   <? $cat_edit_link = '';
              $cat_edit_link .= '<div class="inline_cat" category_id="{id}"><strong>{taxonomy_value}</strong>';
              $cat_edit_link .= '<div class="cat_Inline_Bar"><samp class="inline_edit" onclick="edit_category_dialog({id})">Edit Category</samp>';
              $cat_edit_link .= '<samp class="inline_add_sub" onclick="edit_category_dialog(0,{id})">Add sub category</samp>';
              $cat_edit_link .= '<samp class="inline_del" onclick="delete_category({id})">Delete Category</samp></div>';
              $cat_edit_link .= '</div>';
			  
			  
			  $url  =  '<div class="field" category_id="{id}"><div class="field_ctrl">';

    //$url .=  '<a href="'.ADMIN_URL .'action:page_edit/id:{id}">{content_title}</a>';
    $url .=  '<a class="fiedl_edit" href="javascript:edit_category_dialog({id})">Edit category</a>';
 
    $url .=  '<a class="fiedl_add_sub_page" href="javascript: edit_category_dialog(0,{id})">Add sub-category</a>';
	//$url .=  '<a class="fiedl_add_sub_page" href="'.ADMIN_URL .'/action:toolbar_fs/page_id:{id}">iframe</a>';
    $url .=  '<a class="fiedl_delete_page"  href="javascript:delete_category({id})">Delete category</a>';
    $url .= '</div>';
    $url .= '<h2><span class="field_ctrl_plus"></span><a href="#">{taxonomy_value}</a></h2>';
    $url .= '</div>';
	 $cat_edit_link = $url;
	
	
	


$cat_edit_link2 = base64_encode(($cat_edit_link));

?>

function delete_category($id){
  if (confirm("Are you sure you want to delete this category?")) {
	  
 	$.post("<? print site_url('api/content/delete_taxonomy') ?>", { id: $id} ,	function(data1){  
						$('[category_id="'+$id+'"]').fadeOut();																
					
					
																									
																						
																						 });
  }
	
}
		 
function update_category_list(){
	 data1 = {}
   data1.module = 'admin/content/category_selector';
   data1.update_field = "#cat_list_select" ;
   data1.sortable = 'yes' ;
   
   data1.holder_class_name= 'mw_admin_categories';
   data1.ul_class_name= 'mw_admin_categories';
   data1.link_base64 = "<? print $cat_edit_link2  ?>" ;
    
	
   $.ajax({
  url: '<? print site_url('api/module') ?>',
   type: "POST",
      data: data1,

      async:false,

  success: function(resp) {

   $('#categories').html(resp);


   $('#categories li').each(function(){
        if($(this).parents("ul").length==1){
          $(this).find(".inline_cat:first").addClass("CatMaster");
        }

   });
    $(".CatMaster").click(function(){
      $(this).parent().find("ul:first").slideToggle();
    });
    $(".cat_Inline_Bar samp").click(function(event){
      mw.prevent(event);
    })
	
	
	
	
	$('.mw_admin_categories').sortable({
					opacity: '0.5',
					containment: 'parent',
					items: 'ul, li' ,
					forcePlaceholderSize: true,
					forceHelperSize: true ,
					 
					update: function(e, ui){
						serial = $(this).sortable("serialize");
						$.ajax({
							url: "<?php print site_url('api/content/save_taxonomy_items_order')  ?>",
							type: "POST",
							data: serial,
							// complete: function(){},
							success: function(feedback){
							//alert(feedback);
								//$('#data').html(feedback);
							}
							// error: function(){}
						});
					}
				});
	
	
	
	
	
	//
//	$('ul.category_tree').nestedSortable({
//			disableNesting: 'no-nest',
//			forcePlaceholderSize: true,
//			connectWith: "ul.category_tree", 
//			accept: 'category_element',
//			handle: 'div',
//			items: 'li',
//			opacity: .6,
//			placeholder: 'placehulder',
//			tabSize: 25,
//			tulerance: 'pointer',
//			update: function(serialized) {    
//			serialized = $('ul.category_tree').nestedSortable('serialize');
//			
//			//alert(serialized);
//                     // alert(arraied);   
//					 
// 	 $.post("<? print site_url('api/content/save_taxonomy_items_order') ?>", { items: serialized },
//   function(data){
//    //alert("Data Loaded: " + data);
//	//add_menus_controlls();
//   });
//					 
//					 
//                } 
//		}); 
//	
//	
//	
	
	
	
	
	
	
	

	
	
	
	
	
	//$('#results_holder_title').html("Search results for: "+ $kw);


  }
    });
	
	
}
		   
		   
function edit_category_dialog( $category_id, $parent_cat){
	 data1 = {}
   data1.module = 'admin/content/category_edit';
  
  
  if($category_id != undefined ){
	  
	   data1.id = $category_id;
	   
  }
  
 
   
   if($parent_cat != undefined ){
	  
	   data1.taxonomy_parent = $parent_cat;
	   
	   jQuery.each(data1, function(i, val) {
								   //alert(val);
      
    });

	     
	   
   }
   
   
   		var elem = $('#edit_category_inner')
										
										
								 
										 url1= '{SITE_URL}api/module/';
										 elem.load(url1,data1,function($resp) {
											  //mw.modal.close('edit_cat_modal');
 											    $('#edit_category').show();
												$('#edit_category_inner').show();
												
												
												 $('#categories').hide();
										 });
										 
										 
										 
										 
										 
    
//	
//   $.ajax({
//  url: '<? print site_url('api/module') ?>',
//   type: "POST",
//      data: data1,
//
//      async:false,
//
//  success: function(resp) {
//
//
//
//   mw.modal.close('edit_cat_modal');
//   mw.modal.overlay();
//   mw.modal.init({
//     html:resp,
//     id:'edit_cat_modal',
//     height:'auto',
//     customPosition:{
//       top:100,
//       left:'center'
//     }
//   })
//   
//	
//	//$('#results_holder_title').html("Search results for: "+ $kw);
//
//
//  }
//    });
   
   
   
   
   
	
	
   
/*   frobj = {};
   frobj.src = '<? print site_url('api/module/iframe:'. $iframe_module_params) ?>/id:'+$category_id;
   frobj.width = '800';
   frobj.height = '600';*/

    /* mw.modal.init({
         
          width:600,
          height:530,
          id:'categories_popup',
          oninit:function(){
          //  $("#cat_lis li input").uncheck();
          //  $("#cat_lis li span").removeClass("active");
          }
        })*/
  // mw.modal.iframe(frobj);
// content_list('', $category_id);
   
 
}


function content_list($kw, $category_id){
   
   
   
   data1 = {}
   data1.module = 'admin/posts/list';
   if(($kw == false) || ($kw == '') || ($kw == undefined)){
	$kw = null;   
   } else {
	data1.keyword = $kw;
	data1.items_per_page = 1000;
	
   }
   
   
     if(($category_id == false) || ($category_id == '') || ($category_id == undefined)){
	$category_id = null;   
   } else {
	   data1.category = $category_id;
   }
   
   
   
   
   
   
   $.ajax({
  url: '<? print site_url('api/module') ?>',
   type: "POST",
      data: data1,

      async:false,

  success: function(resp) {
 
   $('#content_list').html(resp);
   
	
	//$('#results_holder_title').html("Search results for: "+ $kw);


  }
    });
   
 
}

 
	//$(document).ready(function () {
					
			 window.onload = function () {		

					
	update_category_list()				
								
    
 } 
function back_to_category_list(){
	  $('#edit_category').hide();
												$('#edit_category_inner').hide();
												
												
												 $('#categories').show();
	
}


</script>

<div class="box radius">
  <div class="box_header radius_t"> 
  
  
  
  <a href="#" class="cms_help">Help</a> <a class="sbm right" href="javascript:edit_category_dialog(0);"  >New category</a>
  
   <a class="xbtn right" href="<? print site_url('admin/action:posts'); ?>"  style="margin-right:5px;" >Back to posts list</a>
    <h2>Categories</h2>
  </div>
  <div class="Pages">
    <div id="edit_category" style="display:none; width:400px; margin:15px" >
      <div id="edit_category_inner" style="display:none" ></div>
      <br />
      <br />
      <a class="btn right" href="javascript:back_to_category_list()">Back to categories</a> </div>
    <div id="categories" class="edit_categories_main"></div>
    <div id="cat_list_select"></div>
  </div>
  <br />
  <br />
  <br />
  <div class="box_footer radius_b" > <a href="#" class="cms_help">Help</a> <a class="sbm right" href="javascript:edit_category_dialog(0);"  >New category</a>
    <h2>Categories</h2>
  </div>
</div>
<!--<div id="posts_nav">
  <ul class="posts_nav_list">
    <li><a href="#" class="view_posts_btn">All categories</a></li>
    <li><a href="javascript:edit_category_dialog(0);" class="add_post_btn">Add category</a></li>
  </ul>
</div>-->
<?

 $params = array();

 $params['link'] = '<a href="javascript:edit_category_dialog({id});">{taxonomy_value}</a>';


//category_tree( $params ) ; ?>
</div>
