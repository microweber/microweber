<?php $rand = uniqid(); ?>
<script  type="text/javascript">




 

$(document).ready(function(){



     $("#mw_edit_pages").css({
       width:window.innerWidth,
       height:window.innerHeight-50
     });



	 mw_append_pages_tree_controlls();





 mw.on.hashParam("page-posts", function(){
      mw_set_edit_posts(this);
 });
 


  mw.on.moduleReload("pages_tree_toolbar", function(){
 mw_append_pages_tree_controlls();
 });

 



});




 
 
 
function mw_delete_content($p_id){
	 $('#pages_edit_container_').attr('data-content-id',$p_id);
  	 mw.load_module('content/edit_post','#pages_edit_container_');
}


mw_edit_btns = function(type, id){
  if(type==='page'){

    return "\
    <span class='mw_del_tree_content' onclick='event.stopPropagation();mw.tools.tree().del("+id+");' title='<?php _e("Delete"); ?>'>\
          <?php _e("Delete"); ?>\
      </span>\
    <span class='mw_ed_tree_content' onclick='event.stopPropagation();mw.url.windowHashParam(\"action\", \"editpage:"+id+"\");return false;' title='<?php _e("Edit"); ?>'>\
          <?php _e("Edit"); ?>\
      </span>\
      ";

  }
  else if(type==='category'){
      return "\
        <span class='mw_del_tree_content' onclick='event.stopPropagation();mw.tools.tree().del("+id+");' title='<?php _e("Delete"); ?>'>\
              <?php _e("Delete"); ?>\
          </span>\
        <span class='mw_ed_tree_content' onclick='event.stopPropagation();mw.url.windowHashParam(\"action\", \"editcategory:"+id+"\");return false;' title='<?php _e("Edit"); ?>'>\
              <?php _e("Edit"); ?>\
          </span>\
      ";
  }
}


function mw_append_pages_tree_controlls(){


    mw.$('#pages_tree_toolbar a').each(function(){
        var el = this;
        el.href = 'javascript:void(0);';
        var html = el.innerHTML;
        var toggle = "";
        var show_posts = "";
        var attr = el.attributes;

        if($(el.parentNode).children('ul').length>0){
            var toggle = '<span class="mw_toggle_tree" onclick="mw.tools.tree(this.parentNode.parentNode, event).toggle();"></span>';
        }
        // type: page or category
        if(attr['data-page-id']!==undefined){
            var pageid = attr['data-page-id'].nodeValue;
            if($(el.parentNode).hasClass("have_category")){
               var show_posts = "<span class='mw_ed_tree_show_posts' onclick='event.stopPropagation();mw.url.windowHashParam(\"action\", \"showposts:"+pageid+"\")'></span>";
               el.setAttribute("onclick", "event.stopPropagation();mw.url.windowHashParam('action', 'showposts:"+pageid+"')");
            }
            el.innerHTML = '<span class="pages_tree_link_text">'+html+'</span>' + mw_edit_btns('page', pageid) + toggle + show_posts;

        }
        else if(attr['data-category-id']!==undefined){
            var pageid = attr['data-category-id'].nodeValue;
            var show_posts = "<span class='mw_ed_tree_show_posts' onclick='event.stopPropagation();mw.url.windowHashParam(\"action\", \"showposts:"+pageid+"\")'></span>";
            el.innerHTML = '<span class="pages_tree_link_text">'+html+'</span>' + mw_edit_btns('category', pageid) + toggle + show_posts;
            el.setAttribute("onclick", "event.stopPropagation();mw.url.windowHashParam('action', 'showposts:"+pageid+"')");
        }

    });


    mw.tools.tree().recall();


}


function mw_select_page_for_editing($p_id){
	$('#pages_edit_container_').attr('data-page-id',$p_id);

   $('#pages_edit_container_').attr('data-type','content/edit_page');


    $('#pages_edit_container_').removeAttr('data-subtype');




	$('#pages_edit_container_').removeAttr('data-content-id');
  	 mw.load_module('content/edit_page','#pages_edit_container_');
}

mw.on.hashParam("action", function(){
  var arr = this.split(":");
  if(arr[0]==='editpage'){
      mw_select_page_for_editing(arr[1])
  }
  else if(arr[0]==='showposts'){
    mw_set_edit_posts(arr[1])
  }
  else if(arr[0]==='editcategory'){
    mw_select_category_for_editing(arr[1])
  }


});
























function mw_select_category_for_editing($p_id){
	 $('#pages_edit_container_').attr('data-category-id',$p_id);
  	 mw.load_module('categories/edit_category','#pages_edit_container_');
}




function mw_set_edit_posts($in_page){



if($in_page != undefined){
 $('#pages_edit_container_').attr('data-page-id',$in_page);

	
} else {
	 $('#pages_edit_container_').removeAttr('data-page-id');

}

//	$('#pages_tree_container_').empty();
//	$('#pages_edit_container_').empty();
//	 $('#pages_tree_container_').attr('data-limit','10');






	 mw.load_module('posts_list','#pages_edit_container_');
	 $('#pages_edit_container_ .paging a').live('click',function() {
	 
	 $p_id = $(this).attr('data-page-number');
	 $p_param = $(this).attr('data-paging-param'); 
	 $('#pages_edit_container_').attr('data-page-number',$p_id);
	 $('#pages_edit_container_').attr('data-page-param',$p_param);
	 mw.load_module('posts','#pages_edit_container_');
		 return false;
	 });
	 
	 
	 

	 
	
}




function mw_select_post_for_editing($p_id){
	 $('#pages_edit_container_').attr('data-content-id',$p_id);
	 	 	 $('#pages_edit_container_').removeAttr('data-subtype', 'post');

  	 mw.load_module('content/edit_post','#pages_edit_container_');
}

function mw_add_product(){
	 $('#pages_edit_container_').attr('data-content-id',0);
	 $('#pages_edit_container_').attr('data-subtype','product');

  	 mw.load_module('content/edit_post','#pages_edit_container_');
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




       <?php /*  <button onclick="mw_set_edit_categories()">mw_set_edit_categories</button>
        <button onclick="mw_set_edit_posts()">mw_set_edit_posts</button>
 */ ?>


    </div>


      <div class="mw_pages_posts_tree"  id="pages_tree_container_">
        <module data-type="pages_menu" include_categories="true" id="pages_tree_toolbar"  />
      </div>
    </div>


    <div class="left mw_edit_page_right" style="width: 70%">

         <div class="mw_edit_pages_nav">


         </div>
    

        <div id="pages_edit_container_"><module data-type="content/edit_page" id="edit_content_admin_"  /></div>
        
        
        
        
    </div>
</div>
</div>

