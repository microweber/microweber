<?php


if(isset($params['manage_categories'])){

include __DIR__ . DS . '../categories/manage.php'; 
return;
	
}
//d($params);


$posts_mod = array();
$posts_mod['type'] = 'content/admin_posts_list';
if(isset($params['page-id'])){
  $posts_mod['data-page-id'] =$params['page-id'];
}
if(isset($params['keyword'])){
 $posts_mod['search_by_keyword'] =$params['keyword'];
}


if(isset($params['content_type']) and $params['content_type']!=false){
 $posts_mod['content_type'] = $params['content_type'];
}

if(isset($params['subtype']) and $params['subtype']!=false){
 $posts_mod['subtype'] = $params['subtype'];
}
if(isset($params['is_shop']) and $params['is_shop']=='y'){
 $posts_mod['subtype'] = 'product';
}
 
if(!isset($params['category-id']) and isset($params['page-id']) and $params['page-id']!='global'){
  $check_if_excist = get_content_by_id($params['page-id']);
  if(is_array($check_if_excist)){
    if(isset($check_if_excist['is_shop']) and trim($check_if_excist['is_shop']) == 'y'){
     $posts_mod['subtype'] = 'product';
   }
 }
}

if(isset($params['category-id']) and $params['category-id']!='global'){
  $check_if_excist = get_page_for_category($params['category-id']);
  if(is_array($check_if_excist)){
    if(isset($check_if_excist['is_shop']) and trim($check_if_excist['is_shop']) == 'y'){
     $posts_mod['subtype'] = 'product';
   }
 }
}
$posts_mod['wrap'] =1;
$posts_mod['paging_param'] ='pg';
$posts_mod['orderby'] ='position desc';

if(isset($params['pg'])){
    $posts_mod['current_page'] =$params['pg'];
}
$posts_mod['id'] = 'mw_admin_posts_manage';
if(isset($params['data-category-id'])){
    $posts_mod['category-id'] = $params['data-category-id'];
}
$posts = array();

?>
<?php  if(isset($params['page-id'])):  ?>
<?php
    if($params['page-id'] == 'global'){
       if(isset($params['is_shop']) and $params['is_shop'] == 'y'){
         $page_info = get_content('limit=1&one=1&content_type=page&is_shop=y');
       }
       else {
         $page_info = false;
       }
    }
    else {
        $page_info = get_content_by_id($params['page-id']);
    }

?>
<script type="text/javascript">
    $(document).ready(function(){
		var prev_frame_attrs = {};
		mw.$('#mw_page_layout_preview').attr('show-page-id-layout',"<?php print ($page_info['id'])?>");
        mw.$('#mw_page_layout_preview').attr('data-page-id',"<?php print ($page_info['id'])?>");
        mw.$('#mw_page_layout_preview').attr('edit_page_id',"<?php print ($page_info['id'])?>");
        mw.$('#mw_page_layout_preview').attr('autoload',"1");
        mw.$('#mw_page_layout_preview').attr('data-small',"1");
    });
</script>

   


<?php 
//d($page_info);
if(isset($page_info) and is_array($page_info)): ?>

<?php if(isset($page_info['subtype']) and $page_info['subtype'] =='static'): ?>
<?php return include __DIR__ . DS . 'edit.php'; 
 
	 ?>
<?php endif; ?>

<?php include __DIR__ . DS . 'admin_toolbar.php'; ?>


<div class="mw-admin-page-preview-holder">
  <div class="mw-admin-page-preview-page">
    <?php if(is_array($page_info) and isset($page_info['title'])): ?>
    <?php if($page_info['is_shop'] == 'y'){ $type='shop'; } elseif($page_info['subtype'] == 'dynamic'){ $type='dynamicpage'; } else{ $type='page';  }; ?>
  <?php endif; ?>
    <div id="mw_page_layout_preview"></div>
  </div>
  <div class="mw-admin-page-preview-page-info"></div>
</div>
<?php else: ?>
<?php include __DIR__ . DS . 'admin_toolbar.php'; ?>
<?php endif; ?>

<?php elseif(isset($params["data-category-id"])):?>
<?php  $cat_info = get_category_by_id($params["data-category-id"]); ?>
	<?php if(isset($cat_info['title']) and $cat_info['title'] != ''): ?>
        <h2 class="section-title"><span class="mw-icon-category"></span>
          <?php print $cat_info['title']; ?>
        </h2>
        <?php else: ?>
        <h2 class="section-title"><span class="mw-icon-category"></span>
          <?php _e("Category"); ?>
        </h2>
    <?php endif; ?>
<?php else: ?>
<?php include __DIR__ . DS . 'admin_toolbar.php'; ?>
<?php endif; ?>




<script  type="text/javascript">
mw.require('forms.js', true);

delete_selected_posts = function(){
  mw.tools.confirm("<?php _e("Are you sure you want to delete the selected posts"); ?>?", function(){
    var master = mwd.getElementById('pages_edit_container');
    var arr = mw.check.collectChecked(master);
    mw.post.del(arr, function(){
     mw.reload_module('#<?php print $params['id'] ?>', function(){
       toggle_cats_and_pages()
     });
   });
  });
}

mw.delete_single_post = function(id){
   mw.tools.confirm("<?php _e("Do you want to delete this post"); ?>?", function(){
      var arr = id;
       mw.post.del(arr, function(){
        mw.$(".manage-post-item-"+id).fadeOut();
      });
   });
}

mw.manage_content_sort = function(){
  if(!mw.$("#mw_admin_posts_sortable").hasClass("ui-sortable")){
        mw.$("#mw_admin_posts_sortable").sortable({
             items: '.manage-post-item',
             axis:'y',
             handle:'.mw_admin_posts_sortable_handle',
             update:function(){
               var obj = {ids:[]}
               $(this).find('.select_posts_for_action').each(function(){
                var id = this.attributes['value'].nodeValue;
                obj.ids.push(id);
              });
              $.post("<?php print api_link('content/reorder'); ?>", obj, function(){
        		   mw.reload_module('#mw_page_layout_preview');
        	  });
             },
             start:function(a,ui){
              $(this).height($(this).outerHeight());
              $(ui.placeholder).height($(ui.item).outerHeight())
              $(ui.placeholder).width($(ui.item).outerWidth())
            },
            scroll:false
        });
   }
}

</script>


<?php   print $posts = module( $posts_mod);  ?>
<script type="text/javascript">
mw.on.hashParam("pg", function(){
      var dis = $p_id = this;
      mw.$('#mw_admin_posts_manage').attr("paging_param", 'pg');
      if(dis!==''){
         mw.$('#mw_admin_posts_manage').attr("pg", dis);
         mw.$('#mw_admin_posts_manage').attr("data-page-number", dis);
      }
      var $p_id = $(this).attr('data-page-number');
      var $p_param = $(this).attr('data-paging-param');
      mw.$('#mw_admin_posts_manage').attr('data-page-number',$p_id);
      mw.$('#mw_admin_posts_manage').attr('data-page-param',$p_param);
      mw.$('#mw_admin_posts_manage').removeAttr('data-content-id');
      mw.reload_module('#mw_admin_posts_manage');
});
mw.on.hashParam("search", function(){
   mw.$('#mw_admin_posts_manage').attr("data-type",'content/admin_posts_list');
   var dis = this;
   if( dis!=='' ){
     mw.$('#mw_admin_posts_manage').attr("data-keyword", dis);
     mw.url.windowDeleteHashParam('<?php print $posts_mod['paging_param'] ?>')
  	   mw.$('#mw_admin_posts_manage').attr("data-page-number", 1);
     }
     else{
      mw.$('#mw_admin_posts_manage').removeAttr("data-keyword");
      mw.url.windowDeleteHashParam('search')
    }
    mw.reload_module('#mw_admin_posts_manage');
});
mw.on.moduleReload('#<?php print $params['id'] ?>');

</script> 
