<?php
$paging_links = false;
$pages_count = intval($pages);
?>
<?php if (isset($toolbar)): ?>
    <?php print $toolbar; ?>
<?php endif; ?>
<?php if (intval($pages_count) > 1): ?>
    <?php $paging_links = mw('content')->paging_links(false, $pages_count, $paging_param, $keyword_param = 'keyword'); ?>
<?php endif; ?>

<div class="manage-posts-holder" id="mw_admin_posts_sortable">
    <div class="manage-posts-holder-inner">
        <?php if(is_array($data)): ?>
        <?php foreach ($data as $item): ?>
            <?php if (isset($item['id'])): ?>
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
                            <?php mw()->event->emit('module.content.manager.item.title', $item) ?>

                            <a class="manage-post-item-link-small mw-small" target="_top"
                               href="<?php print content_link($item['id']); ?>/editmode:y"><?php print content_link($item['id']); ?></a>
                        </div>
                        <div class="manage-post-item-links"><a target="_top" href="<?php print $edit_link ?>"
                                                               onclick="javascript:mw.url.windowHashParam('action','editpost:<?php print ($item['id']) ?>'); return false;">
                                <?php _e("Edit"); ?>
                            </a> <a href="javascript:mw.delete_single_post('<?php print ($item['id']) ?>');">
                                <?php _e("Delete"); ?>
                            </a></div>
                    </div>
                    <div class="mw-ui-col manage-post-item-col-4"><span class="manage-post-item-author"
                                                                        title="<?php print user_name($item['created_by']); ?>"><?php print user_name($item['created_by'], 'username') ?></span>
                    </div>
                    <div class="mw-ui-col manage-post-item-col-5">
                        <?php mw()->event->emit('module.content.manager.item', $item) ?>
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
 
        <?php if (isset($page_info['is_shop']) and $page_info['is_shop'] == 'y') : ?>
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
