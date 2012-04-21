 <script type="text/javascript">
function ops_list($kw){
   
   
   
   data1 = {}
   data1.module = 'admin/settings/config';
   if(($kw == false) || ($kw == '') || ($kw == undefined)){
	$kw = null;  
	
   } else {
	data1.keyword = $kw;
 
	
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
       ops_list(this.value);
  });
   $("#content_search_btn").click(function(){
       ops_list($("#content_search").val());
  });
  
   


});
</script>
 <div class="box radius">
  <div class="box_header radius_t"  style="margin-bottom:0px !important;">
    <table width="100%" border="0">
      <tr>
        <td><h2>Site Config</h2></td>
        <td><div class="right">
             <input type="text" default="Search options"  class="content_search_wide" id="content_search"  />
            <a href="#" id="content_search_btn" class="btn3 hovered">Search</a> </div></td>
        <td><a href="javascript:show_opt_edit_form('.post.item'); show_opt_edit_form('#optitem_'); show_opt_edit_form('#opt');"  class="sbm right">Add new option</a></td>
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
  <div class="cat_lis_holder" >
    <div class="cat_lis">
     
    </div>
  </div>
  <div id="posts_sidebar">
    <div class="posts_list_bar_info">
      <table width="80%" border="0" align="center">
        <tr>
          <td><img src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/admin/tree.png" border="0" /></td>
          <td><strong>Options Groups</strong> <br />
            </td>
        </tr>
      </table>
    </div>
    <div class="admin_options_sidebar"><!--<a href="<? print site_url('admin/action:categories') ?>" class="blue">Edit categories</a>-->
  
  <?
	  
	  $gr =  $this->core_model->optionsGetGroups();
	  //p( $gr);
$s_gr = url_param('option_group');
  ?>
			<ul id="menu">
				  <? foreach($gr as $item): ?>
                <li>
					<a  <? if($s_gr  ==$item): ?> class="active" <? endif; ?> href="<? print site_url('admin/action:options/option_group:') ?><? print $item; ?>" class=""><? print ucwords(  str_replace('_', ' ', $item)); ?></a></li>
                  <? endforeach; ?>
                
          
				
			</ul></div>
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
       
     
      <div class="post_info">
        <!--   <div class="post_views post_info_inner"><img src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/admin/comment.png" border="0" /></div>-->
        <div class="post_comments post_info_inner"><img src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/admin/comment.png" title="Comments" border="0" /></div>
        <div class="post_author post_info_inner"><img src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/admin/pen.png" title="Author" border="0" /></div>
      </div>
    </div>
     <div id="posts_list_content">
  <mw module="admin/settings/config"  <? if($s_gr  != false): ?> option_group="<? print $s_gr; ?>" <? endif; ?> />
     </div>
  </div>
</div>


