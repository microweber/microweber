<script type="text/javascript">
function content_list($kw, $category_id){
   
   
   
   data1 = {}
   data1.module = 'admin/posts/list';
   if(($kw == false) || ($kw == '') || ($kw == undefined)){
	$kw = null;  
	
   } else {
	data1.keyword = $kw;
	data1.curent_page = 1;
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

      async:true,

  success: function(resp) {
 
   $('#posts_list_content').html(resp);
   
	
	//$('#results_holder_title').html("Search results for: "+ $kw);


  }
    });
   
 
}

$(document).ready(function() {
				
		content_list_h =  $("#content_list").height();
			posts_sidebar_h =  $("#posts_sidebar").height();
				
				if(content_list_h > posts_sidebar_h){
					$("#posts_sidebar").height(content_list_h);
					
				}
						   
						   

  $("#content_search").onStopWriting(function(){
       content_list(this.value);
  });
   $("#content_search_btn").click(function(){
       content_list($("#content_search").val());
  });
  
  $(".choose_cats").click(function(){
        mw.modal.init({
          html:$(".cat_lis_holder"),
          width:600,
          height:530,
          id:'categories_popup',
          oninit:function(){
           // $(".cat_lis li input").uncheck();
            $(".cat_lis li span").removeClass("active");
          }
        })
  });

  $(".cat_lis li span").click(function(){
     var input = $(this).find("input:first");
     if(input.is(":checked")){
      // input.uncheck();
       $(this).removeClass("active")
     }
     else{
        input.check();
        $(this).addClass("active")
     }

  });

  $(".cat_lis_apply").click(function(){
    var ul = document.createElement('ul');
    ul.className = "cat_lis";
    $(".cat_lis li input:checked").each(function(){
        var id = $(this).val();
        var clone = $(this).parent().clone(true);
        var li = document.createElement('li');
        $(li).append(clone)
        $(ul).append(li);
    });
    $("#list_of_selected_categories").empty().append(ul);
    mw.modal.close('categories_popup');

  });


});


tree_ctrl_close = function(){
 $(".tree_ctrl:visible").not('.tree_ctrl_plus').click();
}
tree_ctrl_open = function(){
// $(".tree_ctrl_plus").click();
 $(".tree_ctrl").click();
}
 

movement_selector = function(elem, id){

}

</script>
<?

	  $pages_with_cats =  CI::model ( 'content' )->contentGetPagesWithCategories() ;
	 // p( $pages_with_cats);
?>

<div class="box radius">
  <div class="box_header radius_t"  style="margin-bottom:0px !important;">
    <table width="100%" border="0">
      <tr>
        <td><h2>My Posts</h2></td>
        <td><div class="right">
            <input type="text" default="Search content"  class="content_search_wide" id="content_search"  />
            <a href="#" id="content_search_btn" class="btn3 hovered">Search</a> </div></td>
        <td><a href="<? print site_url('admin/action:post_edit/id:0') ?>"  class="sbm right">Add new post</a></td>
      </tr>
    </table>
    <? if(intval($form_values['id']) != 0): ?>
    <? endif; ?>
  </div>
  <!--<div class="shop_nav_main">
    <h2 class="box_title">Posts</h2>
    <ul class="shop_nav">
      <li><a href="<? print site_url('admin/action:posts') ?>" class="view_posts_btn">View posts</a></li>
      <li><a href="<? print site_url('admin/action:post_edit/id:0') ?>" class="add_post_btn">Add posts</a></li>
      <li><a href="<? print site_url('admin/action:categories') ?>" class="categories_btn">Categories</a></li>
    </ul>
  </div>-->
  <div class="cat_lis_holder" style="display: none">
    <div class="cat_lis">
      <?
	  
	  
	  


 $tree_params = array(); 

$tree_params['link'] = '<span><input type="checkbox" value="{id}" /><strong>{taxonomy_value}</strong></span>';

//$params['link'] = '<a href="{id}"><span><strong>{taxonomy_value}</strong></span></a>';



category_tree( $tree_params ) ; ?>
    </div>
  </div>
  <div id="posts_sidebar">
    <div class="posts_list_bar_info">
      <table width="80%" border="0" align="center">
        <tr>
          <td><img src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/admin/tree.png" border="0" /></td>
          <td><strong>Categories tree</strong> <br />
            <a href="javascript:tree_ctrl_open()">Open all</a> | <a href="javascript:tree_ctrl_close()">Close all</a></td>
        </tr>
      </table>
    </div>
    <div class="posts_list_bar"><a href="<? print site_url('admin/action:categories') ?>" class="blue">Edit categories</a></div>
    <? foreach($pages_with_cats as $pages_with_cat): ?>
    <div class="cat_tree"> <a href="<? print site_url('admin/action:posts') .'/category:'.$pages_with_cat['content_subtype_value'] ?>" class="main_page"><? print $pages_with_cat['content_title'] ?></a>
      <?

 $tree_params = array();

// $tree_params['link'] = '<a href="javascript:content_list(\'\',{id});">{taxonomy_value}</a>';
$url123 = site_url('admin/action:posts') .'/category:{id}';


 

 $tree_params['content_parent'] =$pages_with_cat['content_subtype_value'];;
 $tree_params['link'] ="<a href='{$url123}'>{taxonomy_value}</a>";


category_tree( $tree_params ) ; ?>
    </div>
    <? endforeach; ?>
  </div>
  <div id="content_list">
    <div class="posts_list_bar_info">
      <table width="80%" border="0" align="center">
        <tr>
          <td></td>
          <td align="right"><br />
            <!--From here you can add posts in diferent categories. The posts are the dynamic content of your website. Such as blog posts, products, news entries, etc.--></td>
        </tr>
      </table>
    </div>
    <div class="posts_list_bar">
      <? $curent_cat = url_param('category');
	
	$curent_cat  = get_category($curent_cat );
	//p($curent_cat);
	
	?>
      <? $curent_cat = url_param('category');
	if($curent_cat  !=  false){
	$curent_cat  = get_category($curent_cat );
	 $add_post_link = site_url('admin/action:post_edit/id:0').'/add_to_category:'.$curent_cat['id'];
	} else {
	 $add_post_link = site_url('admin/action:post_edit/id:0');	
		
	}
	?>
      <a class="xbtn" href="<? print $add_post_link ; ?>"> <span class="btn_plus">&nbsp;</span> New post <? if($curent_cat['taxonomy_value']): ?>in <strong><? print character_limiter($curent_cat['taxonomy_value'], 15) ?></strong> <?  endif ?> </a> <a class="xbtn" href="javascript:void(0)"> <span class="folder_edit">&nbsp;</span> Edit category </a> <a class="xbtn" href="javascript:void(0)"> <span class="folder_add">&nbsp;</span> Add sub-category </a>
      <div class="post_info">
        <!--   <div class="post_views post_info_inner"><img src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/admin/comment.png" border="0" /></div>-->
        <div class="post_comments post_info_inner"><img src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/admin/comment.png" title="Comments" border="0" /></div>
        <div class="post_author post_info_inner"><img src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/admin/pen.png" title="Author" border="0" /></div>
      </div>
    </div>
     <div id="posts_list_content">
    <mw module="admin/posts/list"  />
     </div>
  </div>
</div>
<div style="display:none;">
  <div  style="text-align: center;padding: 15px 0 0 0;">&nbsp; <a href="#" class="btn2" id="cat_lis_apply" style="margin-right: 10px;">Apply</a> <a href="#" class="btn2" onclick="mw.modal.close('categories_popup');">Cancel</a> </div>
  <div class="select_all">
    <input type="checkbox" onclick="posts_categorize_all(this);" class="select_all_posts" />
    <strong><span>Select all</span> content</strong> </div>
  <div id="posts_cats_controller">
    <table cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td valign="top"><div id="selected_posts"></div></td>
        <td width="33px;">&nbsp;</td>
        <td width="48%"><div id="selected_posts_cats">
            <div id="list_of_selected_categories"> </div>
            <div class="c" style="padding-bottom: 9px;"></div>
            <a href="#" class="btn2 choose_cats">Select category</a> <a href="#" class="btn2">Move item to this category</a> </div></td>
      </tr>
    </table>
  </div>
</div>
