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



 mw.on.hashParam("page-posts", function(){
      mw_set_edit_posts<? print $rand  ?>(this);
 });
 
 

  mw.on.moduleReload("pages_tree_toolbar<? print $rand  ?>", function(){
 mw_append_pages_tree_controlls<? print $rand  ?>();
 });

 
$('#pages_tree_toolbar<? print $rand  ?> .pages_tree .mw_del_tree_content').live('click',function(e) {
				$p_id = $(this).parent().attr('data-page-id');
				 $.post("<? print site_url('api/delete_content'); ?>", { id: $p_id },
				   function(data) {
					    $('#pages_tree_toolbar<? print $rand  ?> .pages_tree').find('li[data-page-id="'+$p_id+'"]').remove();
					// mw.log("Data Loaded: " + data);
				   });
 return false;
				 }); 
   
});




 
 
 
function mw_delete_content($p_id){
	 $('#pages_edit_container_<? print $rand  ?>').attr('data-content-id',$p_id);
  	 mw.load_module('content/edit_post','#pages_edit_container_<? print $rand  ?>');
}


mw_edit_btns = function(pageid){
  return "\
  <span class='mw_del_tree_content' title='<?php _e("Delete"); ?>'>\
        <?php _e("Delete"); ?>\
    </span>\
  <span class='mw_ed_tree_content' onclick='mw.url.windowHashParam(\"action\", \"editpage:"+pageid+"\");return false;' title='<?php _e("Edit"); ?>'>\
        <?php _e("Edit"); ?>\
    </span>\
    ";
}


function mw_append_pages_tree_controlls<? print $rand  ?>(){



    mw.$('#pages_tree_toolbar<? print $rand  ?> .pages_tree a').each(function(){
        var el = this;
        el.href = 'javascript:void(0);';
        var html = el.innerHTML;
        var attr = el.attributes;
        if(attr['data-page-id']!==undefined){
            var pageid = attr['data-page-id'].nodeValue;
            el.setAttribute("onclick", "mw.url.windowHashParam('action', 'editpage:"+pageid+"')");
            if(el.parentNode.className.contains('have_category')){
               el.innerHTML = '<span class="mw_toggle_tree" onclick="mw.tools.tree(this.parentNode.parentNode, event).toggle();"></span><span class="pages_tree_link_text">'+html+'</span>'+mw_edit_btns(pageid);
            }
            else{
               el.innerHTML = '<span class="pages_tree_link_text">'+html+'</span>'+mw_edit_btns(pageid);
            }

        }

    });


}


function mw_select_page_for_editing($p_id){
	$('#pages_edit_container_<? print $rand  ?>').attr('data-page-id',$p_id);
	$('#pages_edit_container_<? print $rand  ?>').removeAttr('data-subtype');
	$('#pages_edit_container_<? print $rand  ?>').removeAttr('data-content-id');
  	 mw.load_module('content/edit_page','#pages_edit_container_<? print $rand  ?>');
}

mw.on.hashParam("action", function(){
  var arr = this.split(":");
  if(arr[0]==='editpage'){
      mw_select_page_for_editing(arr[1])
  }
});



















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
<div id="mw_edit_pages_content">
    <div class="left mw_edit_page_left" style="width: 25%">

    <div class="mw_edit_pages_nav">

        <h2 class="mw_tree_title">Website  Navigation</h2>

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


      <div class="mw_pages_posts_tree"  id="pages_tree_container_<? print $rand  ?>">
        <module data-type="pages_menu" include_categories="true" id="pages_tree_toolbar<? print $rand  ?>"  />
      </div>
    </div>


    <div class="left mw_edit_page_right" style="width: 70%">

         <div class="mw_edit_pages_nav">


         </div>
    

        <div id="pages_edit_container_<? print $rand  ?>"><module data-type="content/edit_page" id="edit_content_admin_<? print $rand  ?>"  /></div>
        
        
        
        
    </div>
</div>
</div>

