<?php
$paging_links = false;
$pages_count = intval($pages);

 

?>
<script  type="text/javascript">
mw.require('forms.js', true);
mw.require('content.js', true);

</script>
<script  type="text/javascript">
delete_selected_posts = function(){
  mw.tools.confirm("<?php _e("Are you sure you want to delete the selected posts"); ?>?", function(){
    var master = mwd.getElementById('<?php print $params['id']; ?>');
    var arr = mw.check.collectChecked(master);
    mw.post.del(arr, function(){
     mw.reload_module('#<?php print $params['id']; ?>', function(){
         $.each(arr,function( index ) {
			var fade = this;
			 mw.$(".manage-post-item-"+fade).fadeOut();
			});
     });
   });
  });
}

assign_selected_posts_to_category_exec = function(){
mw.tools.confirm("Are you sure you want to move the selected posts?", function(){	
	var selected_cats ;
	
	
	 var master = mwd.getElementById('<?php print $params['id']; ?>');
     var arr = mw.check.collectChecked(master);
	
	var data = {};
	data.content_ids = arr;
	
	var page = mwd.querySelector('#posts_bulk_assing_category_tree_resp input[type="radio"]:checked');
	
	if(page !== null){
		data.parent_id = page.value; 
	} 
	
	var categories = mwd.querySelectorAll('#posts_bulk_assing_category_tree_resp input[type="checkbox"]:checked'), l = categories.length, i = 0, arr = [] ;
	
	if(l > 0){
		
		for( ; i<l; i++){
			arr.push(categories[i].value);	
		}
		
		data.categories = arr;
		
	}
	
	
	 
	
	 $.post("<?php print api_link('content/bulk_assign'); ?>", data, function(msg){
		 mw.notification.msg(msg);
		  mw.reload_module('#<?php print $params['id']; ?>');
        // close modal
		
		
		CategoryAssignModal.remove();
	 });
			  
			  
			 });	  
	
	
	
}
assign_selected_posts_to_category = function(){
	
	CategoryAssignModal = mw.modal({
		content:'<div id="posts_bulk_assing_category" style="display:none"><div id="posts_bulk_assing_category_tree_resp"></div><button onclick="assign_selected_posts_to_category_exec()">Move posts</button></div>'	
	});
	mw.load_module('categories/selector', "#posts_bulk_assing_category_tree_resp", function(){
	mw.treeRenderer.appendUI('#posts_bulk_assing_category')
	 	
	});
	$('#posts_bulk_assing_category').show();
 

}

mw.delete_single_post = function(id){
   mw.tools.confirm("<?php _e("Do you want to delete this post"); ?>?", function(){
      var arr = id;
       mw.post.del(arr, function(){
        mw.$(".manage-post-item-"+id).fadeOut(function(){ $(this).remove() });
      });
   });
}

mw.manage_content_sort = function(){
  if(!mw.$("#mw_admin_posts_sortable").hasClass("ui-sortable")){
        mw.$("#mw_admin_posts_sortable").sortable({
             items: '.manage-post-item',
             axis:1,
             handle:'.mw_admin_posts_sortable_handle',
             update:function(){
               var obj = {ids:[]}
               $(this).find('.select_posts_for_action').each(function(){
                var id = this.attributes['value'].nodeValue;
                obj.ids.push(id);
              });
              $.post("<?php print api_link('content/reorder'); ?>", obj, function(){
        		   mw.reload_module('#mw_page_layout_preview');
				   mw.reload_module_parent('posts');
				   mw.reload_module_parent('content');
				   mw.reload_module_parent('shop/products');
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



mw.on.hashParam("pg", function(){
 
      var dis = $p_id = this;
      mw.$('#<?php print $params['id']; ?>').attr("paging_param", 'pg');
      if(dis!==''){
         mw.$('#<?php print $params['id']; ?>').attr("pg", dis);
         mw.$('#<?php print $params['id']; ?>').attr("data-page-number", dis);
      }
      var $p_id = $(this).attr('data-page-number');
      var $p_param = $(this).attr('data-paging-param');
      mw.$('#<?php print $params['id']; ?>').attr('data-page-number',$p_id);
      mw.$('#<?php print $params['id']; ?>').attr('data-page-param',$p_param);
      mw.$('#<?php print $params['id']; ?>').removeAttr('data-content-id');
	  
	 
      mw.reload_module('#<?php print $params['id']; ?>');
	  
	  
});
</script>  





<?php if (!isset($params['no_toolbar']) and isset($toolbar)): ?>
   
   
	  <?php print $toolbar; ?>
      
<?php else: ?>


 
        <div class="manage-toobar-content">
          <div class="mw-ui-link-nav"> <span class="mw-ui-link"
                                                           onclick="mw.check.all('#<?php print $params['id']; ?>')">
            <?php _e("Select All"); ?>
            </span> <span class="mw-ui-link" onclick="mw.check.none('#<?php print $params['id']; ?>')">
            <?php _e("Unselect All"); ?>
            </span> <span class="mw-ui-link" onclick="delete_selected_posts();">
            <?php _e("Delete"); ?>
            </span></div>
        </div>
       
<?php endif; ?>

<?php if (intval($pages_count) > 1): ?>
    <?php $paging_links = mw()->content_manager->paging_links(false, $pages_count, $paging_param, $keyword_param = 'keyword'); ?>
<?php endif; ?>





<div class="manage-posts-holder" id="mw_admin_posts_sortable">
    <div class="manage-posts-holder-inner">
        <?php if(is_array($data)): ?>
        <?php foreach ($data as $item): ?>
            <?php if (isset($item['id'])): ?>
                <?php
                $pub_class = '';
                $append = '';
                if (isset($item['is_active']) and $item['is_active'] == '0') {
                    $pub_class = ' content-unpublished';
                    $append = '<div class="post-un-publish"><span class="mw-ui-btn mw-ui-btn-yellow disabled unpublished-status">' . _e("Unpublished", true) . '</span><span class="mw-ui-btn mw-ui-btn-green publish-btn" onclick="mw.post.set(' . $item['id'] . ', \'publish\');">' . _e("Publish", true) . '</span></div>';
                }
                ?>
                <div
                    class="mw-ui-row-nodrop manage-post-item manage-post-item-<?php print ($item['id']) ?> <?php print $pub_class ?>">
                    <div class="mw-ui-col manage-post-item-col-1">
                        <label class="mw-ui-check">
                            <input name="select_posts_for_action" class="select_posts_for_action" type="checkbox"
                                   value="<?php print ($item['id']) ?>" onclick="mw.admin.showLinkNav();">
                            <span></span> </label>
        <span class="mw-icon-drag mw_admin_posts_sortable_handle"
              onmousedown="mw.manage_content_sort()"></span></div>
                    <div class="mw-ui-col manage-post-item-col-2">
                    


                        <?php  $pic = get_picture($item['id']); ?>
                        <?php if ($pic == true): ?>
                            <a class="manage-post-image"
                               style="background-image: url('<?php print thumbnail($pic, 108) ?>');"
                               onClick="mw.url.windowHashParam('action','editpage:<?php print ($item['id']) ?>');return false;"></a>
                        <?php else : ?>
                            <a
                                class="manage-post-image manage-post-image-no-image <?php if (isset($item['content_type'])) {
                                    print ' manage-post-image-' . $item['content_type'];
                                } ?><?php if (isset($item['is_shop']) and $item['is_shop'] == 1) {
                                    print ' manage-post-image-shop';
                                } ?><?php if (isset($item['subtype']) and $item['content_type'] == 'product') {
                                    print ' manage-post-image-product';
                                } ?>"
                                onclick="mw.url.windowHashParam('action','editpage:<?php print ($item['id']) ?>');return false;"></a>
                        <?php endif; ?>
                        <?php $edit_link = admin_url('view:content#action=editpage:' . $item['id']);  ?>
                    </div>
                    <div class="mw-ui-col manage-post-item-col-3 manage-post-main">
                        <div class="manage-item-main-top">
                            <h3 class="manage-post-item-title"><a target="_top" href="<?php print $edit_link ?>"
                                                                  onClick="mw.url.windowHashParam('action','editpage:<?php print ($item['id']) ?>');return false;">
                                    <?php if (isset($item['content_type']) and $item['content_type'] == 'page'): ?>
                                        <?php if (isset($item['is_shop']) and $item['is_shop'] == 1): ?>
                                            <span class="mw-icon-shop"></span>
                                        <?php else : ?>
                                            <span class="mw-icon-page"></span>
                                        <?php endif; ?>
                                    <?php elseif (isset($item['content_type']) and ($item['content_type'] == 'post' or $item['content_type'] == 'product')): ?>
                                        <?php if (isset($item['content_type']) and $item['content_type'] == 'product'): ?>
                                            <span class="mw-icon-product"></span>
                                        <?php else : ?>
                                            <span class="mw-icon-post"></span>
                                        <?php endif; ?>
                                    <?php else : ?>
                                    <?php endif; ?>
                                    <?php print strip_tags($item['title']) ?> </a></h3>
                            <?php mw()->event_manager->trigger('module.content.manager.item.title', $item) ?>

                            <a class="manage-post-item-link-small mw-small" target="_top"
                               href="<?php print content_link($item['id']); ?>?editmode:y"><?php print content_link($item['id']); ?></a>
                        </div>
                        <div class="manage-post-item-links"><a target="_top" href="<?php print $edit_link ?>"
                                                               onclick="javascript:mw.url.windowHashParam('action','editpage:<?php print ($item['id']) ?>'); return false;">
                                <?php _e("Edit"); ?>
                            </a> <a href="javascript:mw.delete_single_post('<?php print ($item['id']) ?>');">
                                <?php _e("Delete"); ?>
                            </a></div>
                    </div>
                    <div class="mw-ui-col manage-post-item-col-4"><span class="manage-post-item-author"
                                                                        title="<?php print user_name($item['created_by']); ?>"><?php print user_name($item['created_by'], 'username') ?></span>
                    </div>
                    <div class="mw-ui-col manage-post-item-col-5">
                     
                        <?php mw()->event_manager->trigger('module.content.manager.item', $item) ?>
                        <?php print $append; ?> </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<?php

$numactive = 1;

if (isset($params['data-page-number'])) {
    $numactive = intval($params['data-page-number']);
} else if (isset($params['current_page'])) {
    $numactive = intval($params['current_page']);
}


    if (isset($paging_links) and is_array($paging_links)):  ?>
        <div class="mw-paging" style="display: none">
            <?php $i = 1; foreach ($paging_links as $item): ?>
                <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>"
                   href="#<?php print $paging_param ?>=<?php print $i ?>"
                   onclick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $i ?>');return false;"><?php print $i; ?></a>
                <?php $i++; endforeach; ?>
        </div>
    <?php endif; ?>


    <?php  if (isset($paging_links) and is_array($paging_links)):  ?>
        <div class="mw-paging pull-right">
                <?php $count =  count($paging_links);  ?>
                <?php if($count < 6){ ?>
                  <?php $i = 1; foreach ($paging_links as $item): ?>
                  <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>"
                     href="#<?php print $paging_param ?>=<?php print $i ?>"
                     onclick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $i ?>');return false;"><?php print $i; ?></a>
                  <?php $i++; endforeach; ?>
                <?php } else { ?>
                    <?php if($numactive > 2){ ?>

                         <a class="page-1"
                            href="#<?php print $paging_param ?>=1"
                            onclick="mw.url.windowHashParam('<?php print $paging_param ?>','1');return false;">First</a>


                        <?php for($i = $numactive-2;$i<=$numactive+2;$i++){ ?>
                         <?php if($i<$count){ ?>
                        <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>"
                            href="#<?php print $paging_param ?>=<?php print $i ?>"
                            onclick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $i ?>');return false;"><?php print $i; ?></a>


                    <?php }}} else{ ?>

                     <?php  for($i=1;$i<=5;$i++){ ?>
                                  <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>"
                     href="#<?php print $paging_param ?>=<?php print $i ?>"
                     onclick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $i ?>');return false;"><?php print $i; ?></a>
                     <?php  }?>

                    <?php } ?>

                    <a class="page-<?php print $count; ?>"
                            href="#<?php print $paging_param .'='. ($count-1); ?>"
                            onclick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $count-1; ?>');return false;"><?php _e("Last"); ?></a>
                <?php } ?>
        </div>
    <?php endif; ?>


<?php else: ?>
    <div class="mw-no-posts-foot">
        <?php if (isset($params['content_type']) and $params['content_type'] == 'product') : ?>

            <span class="mw-no-posts-foot-label"><?php _e("No Products Here"); ?></span>

            <?php



            if (isset($post_params['category-id'])) {
                $url = "#action=new:product&amp;category_id=" . $post_params['category-id'];
            }
            elseif (isset($post_params['category'])) {
                $url = "#action=new:product&amp;category_id=" . $post_params['category'];
            }
            else if (isset($post_params['parent'])) {
                $url = "#action=new:product&amp;parent_page=" . $post_params['parent'];
            } else {
                $url = "#action=new:product";
            }

            ?>
            <a href="<?php print$url; ?>" class="mw-ui-btn mw-ui-btn-invert"><span class="mw-icon-plus"></span><span class="mw-icon-product"></span><?php _e("Add New Product"); ?></a>
        <?php else: ?>

                <span class="mw-no-posts-foot-label"><?php _e("No Posts Here"); ?></span>

            <?php
            if (isset($post_params['category-id'])) {
                $url = "#action=new:post&amp;category_id=" . $post_params['category-id'];

            }
            elseif (isset($post_params['category'])) {
                $url = "#action=new:post&amp;category_id=" . $post_params['category'];

            }
            else if (isset($post_params['parent'])) {
                $url = "#action=new:post&amp;parent_page=" . $post_params['parent'];

            }
            ?>
            <?php if (isset($url)): ?>
                <a href="<?php print $url; ?>"  class="mw-ui-btn mw-ui-btn-invert"><span class="mw-icon-plus"></span><span class="mw-icon-post"></span><span><?php _e("Add New Post"); ?></span></a>
               <?php endif; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
