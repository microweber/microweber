<?php //$rand = uniqid(); ?>
<script  type="text/javascript">




$(document).ready(function(){
	


mw.$('#pages_tree_toolbar a').live('click',function() {
    $p_id = $(this).parent().attr('data-page-id');
    mw_select_page_for_editing($p_id);
    return false;
});
   
});




function mw_select_page_for_editing($p_id){
	return false;
	mw.$('#edit_page_toolbar').attr('data-page-id',$p_id);
  mw.reload_module('#edit_page_toolbar');
	
}



















function mw_set_edit_categories{rand}(){
	mw.$('#holder_temp_{rand}').empty();
	mw.$('#holder_temp2_{rand}').empty();
	 mw.load_module('categories','#holder_temp_{rand}');
	 
	 
	 mw.$('#holder_temp_{rand} a').live('click',function() { 

	$p_id = $(this).parent().attr('data-category-id');

 	mw_select_category_for_editing($p_id);
 

 return false;});
	
}




function mw_select_category_for_editing($p_id){
	 mw.$('#holder_temp2_{rand}').attr('data-category-id',$p_id);
  	 mw.load_module('categories/edit_category','#holder_temp2_{rand}');

	
}




function mw_set_edit_posts{rand}(){
	mw.$('#holder_temp_{rand}').empty();
	mw.$('#holder_temp2_{rand}').empty();
	 mw.$('#holder_temp_{rand}').attr('data-limit','10');
	 mw.load_module('posts','#holder_temp_{rand}');
	 mw.$('#holder_temp_{rand} .paging a').live('click',function() { 
	 
	 $p_id = $(this).attr('data-page-number');
	 $p_param = $(this).attr('data-paging-param'); 
	 mw.$('#holder_temp_{rand}').attr('data-page-number',$p_id);
	 mw.$('#holder_temp_{rand}').attr('data-page-param',$p_param);
	 mw.load_module('posts','#holder_temp_{rand}');
		 return false;
	 });
	 
	 
	 
	  mw.$('#holder_temp_{rand} .content-list a').live('click',function() { 
	 $p_id = $(this).parents('.content-item:first').attr('data-content-id');
	  
	 mw_select_post_for_editing($p_id);
	 
	 
		 return false;
	 });
	 
	 
	 
	
}




function mw_select_post_for_editing($p_id){
	 mw.$('#holder_temp2_{rand}').attr('data-content-id',$p_id);
  	 mw.load_module('content/edit_post','#holder_temp2_{rand}');

	
}
</script>

<button onclick="mw_set_edit_categories{rand}()">mw_set_edit_categories{rand}</button>
<button onclick="mw_set_edit_posts{rand}()">mw_set_edit_posts{rand}</button>
<table  border="1" id="pages_temp_delete_me" style="z-index:9999999999; background-color:#efecec; position:absolute;" >
  <tr>
    <td><div id="holder_temp_{rand}">
        <module data-type="pages" id="pages_tree_toolbar"  />
        <button onclick="mw_select_page_for_editing(0)">new page</button>
      </div></td>
    <td><div id="holder_temp2_{rand}">
         
      </div></td>
  </tr>
</table>
