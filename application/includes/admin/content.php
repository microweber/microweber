<?php $rand = uniqid(); ?>
<script  type="text/javascript">


 

$(document).ready(function(){



     $("#mw_edit_pages").css({
       width:window.innerWidth,
       height:window.innerHeight-50
     });


	
	 mw_append_pages_tree_controlls<? print $rand  ?>();
 
 $('#pages_tree_toolbar<? print $rand  ?> .pages_tree a[data-page-id]').live('click',function(e) {
$p_id = $(this).parent().attr('data-page-id');

mw_set_edit_posts<? print $rand  ?>($p_id );
 return false;
 }); 
 
 
 
  mw.on.moduleReload("pages_tree_toolbar<? print $rand  ?>", function(){
 mw_append_pages_tree_controlls<? print $rand  ?>();
 });

 
$('#pages_tree_toolbar<? print $rand  ?> .pages_tree .mw_del_content').live('click',function(e) {
				$p_id = $(this).parent().attr('data-page-id');
				 $.post("<? print site_url('api/delete_content'); ?>", { id: $p_id },
				   function(data) {
					    $('#pages_tree_toolbar<? print $rand  ?> .pages_tree').find('li[data-page-id="'+$p_id+'"]').remove();
					// mw.log("Data Loaded: " + data);
				   });
 return false;
				 }); 
   
});


$('#pages_tree_toolbar<? print $rand  ?> .pages_tree .mw_ed_content').live('click',function(e) {
				$p_id = $(this).parent().attr('data-page-id');
				 mw_select_page_for_editing($p_id);
 return false;

				 }); 
   
 


$('#pages_tree_container_<? print $rand  ?> .category_tree a[data-category-id]').live('click',function(e) {

	$p_id = $(this).parent().attr('data-category-id');
 
 	mw_select_category_for_editing($p_id);
return false;
 

 
 
 });
 
 
 
function mw_delete_content($p_id){
	 $('#pages_edit_container_<? print $rand  ?>').attr('data-content-id',$p_id);
  	 mw.load_module('content/edit_post','#pages_edit_container_<? print $rand  ?>');
}



function mw_append_pages_tree_controlls<? print $rand  ?>(){
	$b1 = "<span class='mw_ed_content'>[ed]</span> <span class='mw_del_content'>[x]</span>"
	$('#pages_tree_toolbar<? print $rand  ?> .pages_tree a[data-page-id]').after($b1);
}


function mw_select_page_for_editing($p_id){
	$('#pages_edit_container_<? print $rand  ?>').attr('data-page-id',$p_id);
	$('#pages_edit_container_<? print $rand  ?>').removeAttr('data-subtype');
	$('#pages_edit_container_<? print $rand  ?>').removeAttr('data-content-id');
  	 mw.load_module('content/edit_page','#pages_edit_container_<? print $rand  ?>');



	 
 // mw.reload_module('#edit_content_admin_<? print $rand  ?>');
	
}



















function mw_set_edit_categories<? print $rand  ?>(){
	$('#pages_tree_container_<? print $rand  ?>').empty();
	$('#pages_edit_container_<? print $rand  ?>').empty();
	 mw.load_module('categories','#pages_tree_container_<? print $rand  ?>');

	 
	 $('#pages_tree_container_<? print $rand  ?> a').live('click',function() {

	$p_id = $(this).parent().attr('data-category-id');

 	mw_select_category_for_editing($p_id);
 

 return false;});
	
}




function mw_select_category_for_editing($p_id){
	 $('#pages_edit_container_<? print $rand  ?>').attr('data-category-id',$p_id);
  	 mw.load_module('categories/edit_category','#pages_edit_container_<? print $rand  ?>');

	
}




function mw_set_edit_posts<? print $rand  ?>($in_page){



if($in_page != undefined){
 $('#pages_edit_container_<? print $rand  ?>').attr('data-page-id',$in_page);

	
} else {
	 $('#pages_edit_container_<? print $rand  ?>').removeAttr('data-page-id');

}

//	$('#pages_tree_container_<? print $rand  ?>').empty();
//	$('#pages_edit_container_<? print $rand  ?>').empty();
//	 $('#pages_tree_container_<? print $rand  ?>').attr('data-limit','10');






	 mw.load_module('posts_list','#pages_edit_container_<? print $rand  ?>');
	 $('#pages_edit_container_<? print $rand  ?> .paging a').live('click',function() { 
	 
	 $p_id = $(this).attr('data-page-number');
	 $p_param = $(this).attr('data-paging-param'); 
	 $('#pages_edit_container_<? print $rand  ?>').attr('data-page-number',$p_id);
	 $('#pages_edit_container_<? print $rand  ?>').attr('data-page-param',$p_param);
	 mw.load_module('posts','#pages_edit_container_<? print $rand  ?>');
		 return false;
	 });
	 
	 
	 
	  $('#pages_edit_container_<? print $rand  ?> .content-list a').live('click',function() { 
	 $p_id = $(this).parents('.content-item:first').attr('data-content-id');
	  
	 mw_select_post_for_editing($p_id);
	 
	 
		 return false;
	 });

	 
	 
	
}




function mw_select_post_for_editing($p_id){
	 $('#pages_edit_container_<? print $rand  ?>').attr('data-content-id',$p_id);
	 	 	 $('#pages_edit_container_<? print $rand  ?>').removeAttr('data-subtype', 'post');

  	 mw.load_module('content/edit_post','#pages_edit_container_<? print $rand  ?>');
}

function mw_add_product(){
	 $('#pages_edit_container_<? print $rand  ?>').attr('data-content-id',0);
	 $('#pages_edit_container_<? print $rand  ?>').attr('data-subtype','product');

  	 mw.load_module('content/edit_post','#pages_edit_container_<? print $rand  ?>');
}






</script>







<div id="mw_edit_pages">
    <div class="left" style="width: 28%">

    <div class="mw_edit_pages_nav">

        <span class="mw_action_nav mw_action_page" onclick="mw_select_page_for_editing(0);">
            <label>Page</label>
            <button></button>
        </span>
        <span class="mw_action_nav mw_action_post" onclick="mw_select_post_for_editing(0)"><label>Post</label><button>&nbsp;</button></span>
        <span class="mw_action_nav mw_action_category" onclick="mw_select_category_for_editing(0)"><label>Category</label><button>&nbsp;</button></span>
        <span class="mw_action_nav mw_action_product" onclick="mw_add_product(0)"><label>Product</label><button>&nbsp;</button></span>




       <?php /*  <button onclick="mw_set_edit_categories<? print $rand  ?>()">mw_set_edit_categories<? print $rand  ?></button>
        <button onclick="mw_set_edit_posts<? print $rand  ?>()">mw_set_edit_posts<? print $rand  ?></button>
 */ ?>


    </div>


      <div class="pages_tree"  id="pages_tree_container_<? print $rand  ?>">
        <module data-type="pages_menu" include_categories="true" id="pages_tree_toolbar<? print $rand  ?>"  />
      </div>
    </div>


    <div class="left" style="width: 72%">

         <div class="mw_edit_pages_nav">


         </div>
    
    
        <div id="pages_edit_container_<? print $rand  ?>"><module data-type="content/edit_page" id="edit_content_admin_<? print $rand  ?>"  /></div>
        
        
        
        
    </div>
</div>
 
