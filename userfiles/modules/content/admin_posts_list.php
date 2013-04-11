<?
// d($params);
$is_momodule_comments = is_module('comments');

$post_params = $params;

if (isset($post_params['id'])) {
    $paging_param = 'curent_page' . crc32($post_params['id']);
    unset($post_params['id']);
} else {
    $paging_param = 'curent_page';
}

if (isset($post_params['paging_param'])) {
	$paging_param = $post_params['paging_param'];
}


if (isset($params['curent_page'])) {
	$curent_page = $params['curent_page'];
}

if (isset($post_params['data-page-number'])) {

    $post_params['curent_page'] = $post_params['data-page-number'];
    unset($post_params['data-page-number']);
}



if (isset($post_params['data-category-id'])) {

    $post_params['category'] = $post_params['data-category-id'];
    unset($post_params['data-category-id']);
}






if (isset($params['data-paging-param'])) {

    $paging_param = $params['data-paging-param'];

}





$show_fields = false;
if (isset($post_params['data-show'])) {
    //  $show_fields = explode(',', $post_params['data-show']);

    $show_fields = $post_params['data-show'];
} else {
    $show_fields = get_option('data-show', $params['id']);
}

if ($show_fields != false and is_string($show_fields)) {
    $show_fields = explode(',', $show_fields);
}





if (!isset($post_params['data-limit'])) {
    $post_params['limit'] = get_option('data-limit', $params['id']);
}
$cfg_page_id = false;
if (isset($post_params['data-page-id'])) {
     $cfg_page_id =   intval($post_params['data-page-id']);
} else {
    $cfg_page_id = get_option('data-page-id', $params['id']);

}
$posts_parent_category = false;
if(isset($post_params['category'])){
	$posts_parent_category = $post_params['category'];
}
 
	if ($cfg_page_id != false and intval($cfg_page_id) > 0) {
					$sub_cats = array();
			
			if($posts_parent_category != false ){
			$page_categories = false;
			if(intval($cfg_page_id) != 0){
						$str0 = 'table=categories&limit=1000&data_type=category&what=categories&' . 'parent_id=[int]0&rel_id=' . $cfg_page_id;
					$page_categories = get($str0);
					// d($page_categories);
						if(isarr($page_categories)){
						foreach ($page_categories as $item_cat){
							//d($item_cat);
						$sub_cats[] = $item_cat['id'];
						$more =    get_category_children($item_cat['id']);
						if($more != false and isarr($more)){
							foreach ($more as $item_more_subcat){
								$sub_cats[] = $item_more_subcat;
							}
						}
					 
						}
					}
			} 
			
					if($posts_parent_category != false){
						if(isarr($page_categories)){
							$sub_cats = array();
							foreach ($page_categories as $item_cat){
								if(intval( $item_cat['id'] ) == intval($posts_parent_category) ){
									$sub_cats = array($posts_parent_category);
								}
							}
						} else {
								$sub_cats = array($posts_parent_category);
						}
					}
					
					
						if(isarr($sub_cats)){
						$post_params['category'] = $sub_cats;
						}
						
						
			}
						 $post_params['parent'] = $cfg_page_id;
			



	}





$tn_size = array('150');

if (isset($post_params['data-thumbnail-size'])) {
    $temp = explode('x', strtolower($post_params['data-thumbnail-size']));
    if (!empty($temp)) {
        $tn_size = $temp;
    }
} else {
    $cfg_page_item = get_option('data-thumbnail-size', $params['id']);
    if ($cfg_page_item != false) {
        $temp = explode('x', strtolower($cfg_page_item));

        if (!empty($temp)) {
            $tn_size = $temp;
        }
    }
}







// $post_params['debug'] = 'posts';
$post_params['content_type'] = 'post';
$content   =$data = get_content($post_params);
?>
<?
$post_params_paging = $post_params;
//$post_params_paging['count'] = true;




$post_params_paging['page_count'] = true;
 $pages = get_content($post_params_paging);

$paging_links = false;
$pages_count = intval($pages);
?>
<? if (intval($pages_count) > 1): ?>
<? $paging_links = paging_links(false, $pages_count, $paging_param, $keyword_param = 'keyword'); ?>
<? endif; ?>

<div class="manage-posts-holder" id="mw_admin_posts_sortable">
  <? if(isarr($data)): ?>
  <? foreach ($data as $item): ?>
  
  <?
  $pub_class = '';
				if(isset($item['is_active']) and $item['is_active'] == 'n'){
					$pub_class = ' content-unpublished';
				}
  
   ?>
  
  
  <div class="manage-post-item manage-post-item-<? print ($item['id']) ?> <? print $pub_class ?>">
    <div class="manage-post-itemleft">
      <label class="mw-ui-check left">
        <input name="select_posts_for_action" class="select_posts_for_action" type="checkbox" value="<? print ($item['id']) ?>">
        <span></span></label>
      <span class="ico iMove mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()"></span>
      <?
    	$pic  = get_picture(  $item['id']);


		 ?>
      <? if($pic == true ): ?>
      <a class="manage-post-image left" style="background-image: url('<? print thumbnail($pic, 108) ?>');"  onClick="mw.url.windowHashParam('action','editpost:<? print ($item['id']) ?>');return false;"></a>
      <? else : ?>
      <a class="manage-post-image manage-post-image-no-image left"  onClick="mw.url.windowHashParam('action','editpost:<? print ($item['id']) ?>');return false;"></a>
      <? endif; ?>

      <? $edit_link = admin_url('view:content#action=editpost:'.$item['id']);  ?>

      <div class="manage-post-main">
        <h3 class="manage-post-item-title"><a target="_top" href="<? print $edit_link ?>" onClick="mw.url.windowHashParam('action','editpost:<? print ($item['id']) ?>');return false;"><? print strip_tags($item['title']) ?></a></h3>
        <small><a  class="manage-post-item-link-small" target="_top"  href="<? print content_link($item['id']); ?>/editmode:y"><? print content_link($item['id']); ?></a></small>
        <div class="manage-post-item-description"> <? print character_limiter(strip_tags($item['description']), 60);
      ?> </div>
        <div class="manage-post-item-links"> <a target="_top"  href="<? print content_link($item['id']); ?>/editmode:y">Live edit</a> <a target="_top" href="<? print $edit_link ?>" onClick="javascript:mw.url.windowHashParam('action','editpost:<? print ($item['id']) ?>'); return false;">Edit</a> <a href="javascript:mw.delete_single_post('<? print ($item['id']) ?>');;">Delete</a> </div>
      </div>
      <div class="manage-post-item-author" title="<? print user_name($item['created_by']); ?>"><? print user_name($item['created_by'],'username') ?></div>
    </div>

<? if($is_momodule_comments == true): ?>
<?php $new = get_comments('count=1&is_moderated=n&content_id='.$item['id']); ?>
<?

if($new > 0){
  $have_new = 1;
} else {
  $have_new = 0;
  $new = get_comments('count=1&content_id='.$item['id']);
}
 ?>




        <? if($have_new): ?>

        <a href="<?php print admin_url('view:comments'); ?>/#content_id=<? print $item['id'] ?>" class="comment-notification"><? print($new); ?></a>

        <?php else:  ?>

        <span class="comment-notification comment-notification-silver "><? print($new); ?></span>
         <? endif;?>
        <? endif; ?>


  </div>
  <? endforeach; ?>
</div>
<div class="manage-toobar manage-toolbar-bottom"> <span class="mn-tb-arr-bottom"></span> <span class="posts-selector"> <span onclick="mw.check.all('#pages_edit_container')">Select All</span>/<span onclick="mw.check.none('#pages_edit_container')">Unselect All</span> </span> <a href="javascript:delete_selected_posts();" class="mw-ui-btn">Delete</a> </div>
<div class="mw-paging">
<?

        $numactive = 1;

     if(isset($params['data-page-number'])){
                $numactive   = intval($params['data-page-number']);
              } else if(isset($params['curent_page'])){
                $numactive   = intval($params['curent_page']);
              }



     if(isset($paging_links) and isarr($paging_links)):  ?>
<? $i=1; foreach ($paging_links as $item): ?>
<a  class="page-<? print $i; ?> <? if($numactive == $i): ?> active <? endif; ?>" href="#<? print $paging_param ?>=<? print $i ?>" onClick="mw.url.windowHashParam('<? print $paging_param ?>','<? print $i ?>');return false;"><? print $i; ?></a>
<? $i++; endforeach; ?>
<? endif; ?>
<? else: ?>
<div class="mw-no-posts-foot">
  <? if( isset($params['subtype']) and $params['subtype'] == 'product') : ?>
  <h2>No Products Here</h2>
  <!--  <a href="#?action=new:product" class="mw-ui-btn"><span class="ico iplus"></span><span class="ico iproduct"></span>Add New Product<b><? print $cat_name ?></b></a>
-->
  <? else: ?>
  <h2>No Posts Here</h2>
  <!--  <a href="#?action=new:post" class="mw-ui-btn"><span class="ico iplus"></span><span class="ico ipost"></span>Create New Post <b><? print $cat_name ?></b></a> </div>
-->
  <? endif; ?>
</div>
<? endif; ?>
