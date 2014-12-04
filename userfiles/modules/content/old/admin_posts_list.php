<?php

$is_momodule_comments = is_module('comments');

$post_params = $params;

if (isset($post_params['id'])) {
    $paging_param = 'current_page' . crc32($post_params['id']);
    unset($post_params['id']);
} else {
    $paging_param = 'current_page';
}

if (isset($post_params['paging_param'])) {
    $paging_param = $post_params['paging_param'];
}


if (isset($params['current_page'])) {
    $current_page = $params['current_page'];
}

if (isset($post_params['data-page-number'])) {

    $post_params['current_page'] = $post_params['data-page-number'];
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
    $cfg_page_id = intval($post_params['data-page-id']);
} else {
    $cfg_page_id = get_option('data-page-id', $params['id']);

}
$posts_parent_category = false;
if (isset($post_params['category'])) {
    $posts_parent_category = $post_params['category'];
}

if ($cfg_page_id != false and intval($cfg_page_id) > 0) {
    $sub_cats = array();

    if ($posts_parent_category != false) {
        $page_categories = false;
        if (intval($cfg_page_id) != 0) {
            $str0 = 'table=categories&limit=1000&data_type=category&what=categories&' . 'parent_id=0&rel_id=' . $cfg_page_id;
            $page_categories = get($str0);
            // d($page_categories);
            if (is_array($page_categories)) {
                foreach ($page_categories as $item_cat) {
                    //d($item_cat);
                    $sub_cats[] = $item_cat['id'];
                    $more = get_category_children($item_cat['id']);
                    if ($more != false and is_array($more)) {
                        foreach ($more as $item_more_subcat) {
                            $sub_cats[] = $item_more_subcat;
                        }
                    }

                }
            }
        }

        if ($posts_parent_category != false) {
            if (is_array($page_categories)) {
                $sub_cats = array();
                foreach ($page_categories as $item_cat) {
                    if (intval($item_cat['id']) == intval($posts_parent_category)) {
                        $sub_cats = array($posts_parent_category);
                    }
                }
            } else {
                $sub_cats = array($posts_parent_category);
            }
        }
        if (is_array($sub_cats)) {
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


if (!isset($post_params['content_type']) and isset($post_params['page-id']) and $post_params['page-id'] != 'global') {
    $post_params['content_type'] = 'post';
} elseif (isset($post_params['page-id']) and $post_params['page-id'] == 'global') {
    $post_params['orderby'] = 'updated_at desc';
}
if (isset($post_params['type'])) {
    unset($post_params['type']);
}

//d($post_params);
if (!isset($post_params['subtype'])) {
   // $post_params['subtype'] = 'post';
}

$content = $data = get_content($post_params);

$post_params_paging = $post_params;
//$post_params_paging['count'] = true;

$post_params_paging['page_count'] = true;
$pages = get_content($post_params_paging);

$paging_links = false;
$pages_count = intval($pages);
?>
<?php if (intval($pages_count) > 1): ?>
<?php $paging_links = mw()->content_manager->paging_links(false, $pages_count, $paging_param, $keyword_param = 'keyword'); ?>
<?php endif; ?>

<div class="manage-posts-holder" id="mw_admin_posts_sortable">
  <div class="">
    <?php if(is_array($data)): ?>
    <?php foreach ($data as $item): ?>
    <?php
            $pub_class = '';
            $append = '';
            if (isset($item['is_active']) and $item['is_active'] == 'n') {
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
                           onClick="mw.url.windowHashParam('action','editpost:<?php print ($item['id']) ?>');return false;"></a>
        <?php else : ?>
        <a
                            class="manage-post-image manage-post-image-no-image <?php if (isset($item['content_type'])) {
                                print ' manage-post-image-' . $item['content_type'];
                            } ?><?php if (isset($item['is_shop']) and $item['is_shop'] == 'y') {
                                print ' manage-post-image-shop';
                            } ?><?php if (isset($item['subtype']) and $item['subtype'] == 'product') {
                                print ' manage-post-image-product';
                            } ?>"
                            onclick="mw.url.windowHashParam('action','editpost:<?php print ($item['id']) ?>');return false;"></a>
        <?php endif; ?>
        <?php $edit_link = admin_url('view:content#action=editpost:' . $item['id']);  ?>
      </div>
      <div class="mw-ui-col manage-post-item-col-3 manage-post-main">
        <div class="manage-item-main-top">
          <h3 class="manage-post-item-title"><a target="_top" href="<?php print $edit_link ?>"
                                                              onClick="mw.url.windowHashParam('action','editpost:<?php print ($item['id']) ?>');return false;">
            <?php if (isset($item['content_type']) and $item['content_type'] == 'page'): ?>
            <?php if (isset($item['is_shop']) and $item['is_shop'] == 'y'): ?>
            <span class="mw-icon-shop"></span>
            <?php else : ?>
            <span class="mw-icon-page"></span>
            <?php endif; ?>
            <?php elseif (isset($item['content_type']) and $item['content_type'] == 'post'): ?>
            <?php if (isset($item['subtype']) and $item['subtype'] == 'product'): ?>
            <span class="mw-icon-product"></span>
            <?php else : ?>
            <span class="mw-icon-post"></span>
            <?php endif; ?>
            <?php else : ?>
            <?php endif; ?>
            <?php print strip_tags($item['title']) ?> </a></h3>
          <a class="manage-post-item-link-small mw-small" target="_top"
                           href="<?php print content_link($item['id']); ?>/editmode:y"><?php print content_link($item['id']); ?></a> </div>
        <div class="manage-post-item-links"><a target="_top" href="<?php print $edit_link ?>"
                                                           onclick="javascript:mw.url.windowHashParam('action','editpost:<?php print ($item['id']) ?>'); return false;">
          <?php _e("Edit"); ?>
          </a> <a href="javascript:mw.delete_single_post('<?php print ($item['id']) ?>');">
          <?php _e("Delete"); ?>
          </a></div>
      </div>
      <div class="mw-ui-col manage-post-item-col-4"><span class="manage-post-item-author"
                                                                    title="<?php print user_name($item['created_by']); ?>"><?php print user_name($item['created_by'], 'username') ?></span> </div>
      <?php if($is_momodule_comments == true and function_exists('get_comments')): ?>
      <?php $new = get_comments('count=1&is_moderated=n&content_id=' . $item['id']); ?>
      <?php

                if ($new > 0) {
                    $have_new = 1;
                } else {
                    $have_new = 0;
                    $new = get_comments('count=1&content_id=' . $item['id']);
                }
                ?>
      <div class="mw-ui-col manage-post-item-col-5">
        <?php if ($have_new): ?>
        <a href="<?php print admin_url('view:comments'); ?>/#content_id=<?php print $item['id'] ?>"
                           class="comments-bubble"><span class="mw-icon-comment"></span><span
                                class="comment-number"><?php print($new); ?></span></a>
        <?php else: ?>
        <a href="<?php print admin_url('view:comments'); ?>/#content_id=<?php print $item['id'] ?>"
                           class="comments-bubble" title="<?php print($new); ?> <?php _e("Comments"); ?>"><span
                                class="mw-icon-comment"></span><span class="comment-number"><?php print($new); ?></span></a>
        <?php endif;?>
        <?php endif; ?>
        <?php print $append; ?> </div>
    </div>
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
<div class="mw-paging">
  <?php $i = 1; foreach ($paging_links as $item): ?>
  <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>"
                   href="#<?php print $paging_param ?>=<?php print $i ?>"
                   onClick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $i ?>');return false;"><?php print $i; ?></a>
  <?php $i++; endforeach; ?>
</div>
<?php endif; ?>
<?php else: ?>
<div class="mw-no-posts-foot">
  <?php if (isset($params['subtype']) and $params['subtype'] == 'product') : ?>
  <h2 class="left">
    <?php _e("No Products Here"); ?>
  </h2>
  <?php
            if (isset($post_params['category-id'])) {
                $url = "#action=new:product&amp;category_id=" . $post_params['category-id'];

            } else if (isset($post_params['parent'])) {
                $url = "#action=new:product&amp;parent_page=" . $post_params['parent'];
            } else {
                $url = "#action=new:product";
            }

            ?>
  <a href="<?php print   $url; ?>" class="add-new-master"> <span class="add-new-master-icon"></span> <span>
  <?php _e("Add New Product"); ?>
  </span> </a>
  <?php else: ?>
  <h2 class="left">
    <?php _e("No Posts Here"); ?>
  </h2>
  <?php
           if (isset($post_params['category-id'])) {
                $url = "#action=new:post&amp;category_id=" . $post_params['category-id'];

            } else if (isset($post_params['parent'])) {
                $url = "#action=new:post&amp;parent_page=" . $post_params['parent'];

            }
            ?>
  <?php if (isset($url)): ?>
  <a href="<?php print $url; ?>"  class="add-new-master"> <span class="add-new-master-icon"></span>
  <?php endif; ?>
  <span>
  <?php _e("Add New Post"); ?>
  </span> </a>
  <?php endif; ?>
</div>
<?php endif; ?>
