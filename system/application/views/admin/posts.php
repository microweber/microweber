<script type="text/javascript">
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

$(document).ready(function() {

  $(".content_search").onStopWriting(function(){
       content_list(this.value);
  });
  $(".choose_cats").click(function(){
        mw.modal.init({
          html:$("#cat_lis_holder"),
          width:600,
          height:530,
          id:'categories_popup',
          oninit:function(){
            $("#cat_lis li input").uncheck();
            $("#cat_lis li span").removeClass("active");
          }
        })
  });

  $("#cat_lis li span").click(function(){
     var input = $(this).find("input:first");
     if(input.is(":checked")){
       input.uncheck();
       $(this).removeClass("active")
     }
     else{
        input.check();
        $(this).addClass("active")
     }

  });

  $("#cat_lis_apply").click(function(){
    var ul = document.createElement('ul');
    ul.className = "cat_lis";
    $("#cat_lis li input:checked").each(function(){
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



movement_selector = function(elem, id){

}

</script>

<div class="box radius">
 <div class="shop_nav_main">
  <h2 class="box_title">Posts</h2>
  <ul class="shop_nav">
    <li><a href="<? print site_url('admin/action:posts') ?>" class="view_posts_btn">View posts</a></li>
    <li><a href="<? print site_url('admin/action:post_edit/id:0') ?>" class="add_post_btn">Add posts</a></li>
    <li><a href="<? print site_url('admin/action:categories') ?>" class="categories_btn">Categories</a></li>
  </ul>
     </div>
<div class="c">&nbsp;</div>

<div id="cat_lis_holder" style="display: none">
  <div id="cat_lis">
    <?

 $params = array();

 $params['link'] = '<span><input type="checkbox" value="{id}" /><strong>{taxonomy_value}</strong></span>';


category_tree( $params ) ; ?>
  </div>
  <div  style="text-align: center;padding: 15px 0 0 0;">&nbsp; <a href="#" class="btn2" id="cat_lis_apply" style="margin-right: 10px;">Apply</a> <a href="#" class="btn2" onclick="mw.modal.close('categories_popup');">Cancel</a> </div>
</div>
<div id="d_bar" class="">
  <div class="left">
    <h2>Products</h2>
    <div class="drop drop_white"> <span class="drop_arr"></span> <span class="val">All categories</span>
      <div class="drop_list">
        <?

 $params = array();

 $params['link'] = '<a href="javascript:content_list(\'\',{id});">{taxonomy_value}</a>';


category_tree( $params ) ; ?>
      </div>
    </div>
  </div>
  <div class="right"> <a href="#" class="btn3 hovered">Go search</a>
    <input type="text" default="Search a product"  class="content_search"  />
  </div>
</div>
<div class="select_all">
  <input type="checkbox" onclick="posts_categorize_all(this);" class="select_all_posts" />
  <strong><span>Select all</span> products</strong> </div>
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
<div id="content_list">
  <mw module="admin/posts/list"  />
</div>
</div>
