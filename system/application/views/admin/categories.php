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


$cat_edit_link2 = base64_encode(($cat_edit_link));

?>

function delete_category($id){
  if (confirm("Are you sure you want to delete")) {
	  
 	$.post("<? print site_url('api/content/delete_taxonomy') ?>", { id: $id} ,	function(data1){  
						$('[category_id="'+$id+'"]').fadeOut();																
					
					
																									
																						
																						 });
  }
	
}
		 
function update_category_list(){
	 data1 = {}
   data1.module = 'admin/content/category_selector';
   data1.update_field = "#cat_list_select" ;
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
    
	
   $.ajax({
  url: '<? print site_url('api/module') ?>',
   type: "POST",
      data: data1,

      async:true,

  success: function(resp) {
 
   //$('#content_list').html(resp);
   mw.modal.close('edit_cat_modal');
   mw.modal.overlay();
   mw.modal.init({
     html:resp,
     id:'edit_cat_modal',
     height:'auto',
     customPosition:{
       top:100,
       left:'center'
     }
   })
   
	
	//$('#results_holder_title').html("Search results for: "+ $kw);


  }
    });
   
   
   
   
   
	
	
   
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

 
	$(document).ready(function () {
					
					

					
	update_category_list()				
								
    
 });



</script>

<div id="posts_nav">
  <h2>Categories</h2>
  <ul class="posts_nav_list">
    <li><a href="#" class="view_posts_btn">All categories</a></li>
    <li><a href="javascript:edit_category_dialog(0);" class="add_post_btn">Add category</a></li>
  </ul>
</div>





<div id="categories" class="edit_categories_main"></div>
<div id="cat_list_select"></div><div id="content_list"></div>





 
<?

 $params = array();

 $params['link'] = '<a href="javascript:edit_category_dialog({id});">{taxonomy_value}</a>';


//category_tree( $params ) ; ?>

