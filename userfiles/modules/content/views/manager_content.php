
<?php if (is_array($data) and !empty($data)): ?>
    <div class="manage-posts-holder" id="mw_admin_posts_sortable">






        <div class="manage-posts-holder-inner">
            <?php if (is_array($data)): ?>
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
                        <?php $pic = get_picture($item['id']); ?>
                        <div class="mw-ui-row-nodrop post-has-image-<?php print ($pic == true ? 'true' : 'false'); ?> manage-post-item-type-<?php print $item['content_type']; ?> manage-post-item manage-post-item-<?php print ($item['id']) ?> <?php print $pub_class ?>">
                            <div class="mw-ui-col manage-post-item-col-1">
                                <label class="mw-ui-check">
                                    <input name="select_posts_for_action" class="select_posts_for_action" type="checkbox"
                                           value="<?php print ($item['id']) ?>">
                                    <span></span>
                                </label>
                                <span class="mw-icon-drag mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()"></span>
                            </div>
                            <div class="mw-ui-col manage-post-item-col-2">
                                <?php if ($pic == true): ?>
                                    <a class="manage-post-image"
                                       style="background-image: url('<?php print thumbnail($pic, 108) ?>');"
                                       onClick="mw.url.windowHashParam('action','editpage:<?php print ($item['id']) ?>');return false;"></a>
                                <?php else : ?>
                                    <a class="manage-post-image manage-post-image-no-image <?php if (isset($item['content_type'])) {
                                        print ' manage-post-image-' . $item['content_type'];
                                    } ?><?php if (isset($item['is_shop']) and $item['is_shop'] == 1) {
                                        print ' manage-post-image-shop';
                                    } ?><?php if (isset($item['subtype']) and $item['content_type'] == 'product') {
                                        print ' manage-post-image-product';
                                    } ?>"
                                       onclick="mw.url.windowHashParam('action','editpage:<?php print ($item['id']) ?>');return false;"></a>
                                <?php endif; ?>
                                <?php $edit_link = admin_url('view:content#action=editpage:' . $item['id']); ?>
                                <?php $edit_link_front = content_link($item['id']) . '?editmode:y'; ?>
                            </div>

                            <div class="mw-ui-col manage-post-item-col-3 manage-post-main">
                                <div class="manage-item-main-top">
                                    <h3 class="manage-post-item-title">
                                        <a target="_top" href="<?php print $edit_link_front; ?>" onxClick="mw.url.windowHashParam('action','editpage:<?php print ($item['id']) ?>');return false;">
                                            <?php if (isset($item['content_type']) and $item['content_type'] == 'page'): ?>
                                                <?php if (isset($item['is_shop']) and $item['is_shop'] == 1): ?>
                                                    <span class="mai-shop"></span>
                                                <?php else : ?>
                                                    <span class="mai-page"></span>
                                                <?php endif; ?>
                                            <?php elseif (isset($item['content_type']) and ($item['content_type'] == 'post' or $item['content_type'] == 'product')): ?>
                                                <?php if (isset($item['content_type']) and $item['content_type'] == 'product'): ?>
                                                    <span class="mai-product"></span>
                                                <?php else : ?>
                                                    <span class="mai-post"></span>
                                                <?php endif; ?>
                                            <?php else : ?>
                                            <?php endif; ?>
                                            <?php print strip_tags($item['title']) ?>
                                        </a>
                                    </h3>
                                    <?php mw()->event_manager->trigger('module.content.manager.item.title', $item) ?>


                                    <?php $cats = content_categories($item['id']); ?>
                                    <?php $tags = content_tags($item['id'], false); ?>
                                    <?php if ($cats) { ?>
                                        <span class="manage-post-item-cats-inline-list">
                                              <span class="mw-icon-category"></span>
                                            <?php foreach ($cats as $ck => $cat): ?>
                                            <a href="#action=showpostscat:<?php print ($cat['id']); ?>" class=" label label-primary">
                                                <?php print $cat['title']; ?></a><?php if (isset($cats[$ck + 1])): ?>,<?php endif; ?>


                                            <?php endforeach; ?>
                                      </span>
                                    <?php } ?>

                                    <?php if ($tags) { ?>
                                        <?php foreach ($tags as $tag): ?>
                                            <span class="mw-post-item-tag"># <?php echo $tag; ?></span>
                                        <?php endforeach; ?>
                                    <?php } ?>
                                    <div></div>

                                    <a class="manage-post-item-link-small mw-medium" target="_top" href="<?php print content_link($item['id']); ?>?editmode:y"><?php print content_link($item['id']); ?></a>
                                </div>

                                <div class="manage-post-item-links">
                                    <a target="_top" class="mw-ui-btn mw-ui-btn-default mw-ui-btn-medium" href="<?php print $edit_link ?>" onclick="javascript:mw.url.windowHashParam('action','editpage:<?php print ($item['id']) ?>'); return false;">
                                        <?php _e("Edit"); ?>
                                    </a>

                                    <a target="_top" class="mw-ui-btn mw-ui-btn-default mw-ui-btn-medium" href="<?php print content_link($item['id']); ?>?editmode:y">
                                        <?php _e("Live Edit"); ?>
                                    </a>

                                    <a class="mw-ui-btn mw-ui-btn-default mw-ui-btn-medium" href="javascript:mw.delete_single_post('<?php print ($item['id']) ?>');">
                                        <?php _e("Delete"); ?>
                                    </a>
                                </div>
                            </div>
                            <div class="mw-ui-col manage-post-item-col-4">
                                <span class="manage-post-item-author" title="<?php print user_name($item['created_by']); ?>"><?php print user_name($item['created_by'], 'username') ?></span>
                            </div>

                            <div class="mw-ui-col manage-post-item-col-5">
                                <?php mw()->event_manager->trigger('module.content.manager.item', $item) ?>
                                <?php print $append; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>


        <?php
        $numactive = 1;

        if (isset($params['data-page-number'])) {
            $numactive = intval($params['data-page-number']);
        } else if (isset($params['current_page'])) {
            $numactive = intval($params['current_page']);
        }

        if (isset($paging_links) and is_array($paging_links)): ?>
            <div class="mw-paging" style="display: none">
                <?php $i = 1;
                foreach ($paging_links as $item): ?>
                    <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>"
                       href="#<?php print $paging_param ?>=<?php print $i ?>"
                       onclick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $i ?>');return false;"><?php print $i; ?></a>
                    <?php $i++; endforeach; ?>
            </div>
        <?php endif; ?>


        <?php if (isset($paging_links) and is_array($paging_links)): ?>
            <div class="mw-paging pull-right">
                <?php $count = count($paging_links); ?>
                <?php if ($count < 6) { ?>
                    <?php $i = 1;
                    foreach ($paging_links as $item): ?>
                        <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>"
                           href="#<?php print $paging_param ?>=<?php print $i ?>"
                           onclick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $i ?>');return false;"><?php print $i; ?></a>
                        <?php $i++; endforeach; ?>
                <?php } else { ?>
                    <?php if ($numactive > 2) { ?>

                        <a class="page-1"
                           href="#<?php print $paging_param ?>=1"
                           onclick="mw.url.windowHashParam('<?php print $paging_param ?>','1');return false;">First</a>


                        <?php for ($i = $numactive - 2; $i <= $numactive + 2; $i++) { ?>
                            <?php if ($i < $count) { ?>
                                <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>"
                                   href="#<?php print $paging_param ?>=<?php print $i ?>"
                                   onclick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $i ?>');return false;"><?php print $i; ?></a>


                            <?php }
                        }
                    } else { ?>

                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                            <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>"
                               href="#<?php print $paging_param ?>=<?php print $i ?>"
                               onclick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $i ?>');return false;"><?php print $i; ?></a>
                        <?php } ?>

                    <?php } ?>

                    <a class="page-<?php print $count; ?>"
                       href="#<?php print $paging_param . '=' . ($count - 1); ?>"
                       onclick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $count - 1; ?>');return false;"><?php _e("Last"); ?></a>
                <?php } ?>
            </div>
        <?php endif; ?>

    </div>
<?php else: ?>

    <div class="mw-no-posts-foot">

        <?php
        $page_is_shop = false;
        if (isset($post_params["page-id"])) {
            $page_is_shop_check = get_content_by_id($post_params["page-id"]);
            if (isset($page_is_shop_check['is_shop']) and $page_is_shop_check['is_shop'] == 1) {
                $page_is_shop = true;
            }

        }

        if ((isset($post_params['content_type']) and $post_params['content_type'] == 'product') or (isset($params['content_type']) and $params['content_type'] == 'product') or $page_is_shop) : ?>
            <div class="no-items-found">
                <img src="<?php print modules_url(); ?>/microweber/img/no_products.svg" class="no-posts-img"/>


                <span class="mw-no-posts-foot-label"><?php _e("No Products Here"); ?></span>

                <?php


                /*  if (isset($post_params['category-id'])) {
                      $url = "#action=new:product&amp;category_id=" . $post_params['category-id'];
                  } elseif (isset($post_params['category'])) {
                      $url = "#action=new:product&amp;category_id=" . $post_params['category'];
                  } else if (isset($post_params['parent'])) {
                      $url = "#action=new:product&amp;parent_page=" . $post_params['parent'];
                  } else {
                      $url = "#action=new:product";
                  }*/
                $url = "#action=new:product";

                ?>
                <a href="<?php print$url; ?>" class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline m-t-20 m-b-20"><i class="mai-product"></i> <span><?php _e("Add New Product"); ?></span></a>

                <script>
                    $(document).ready(function () {
                        $('.manage-toobar').hide();
                        $('.top-search').hide();
                    });
                </script>
            </div>
        <?php else: ?>
            <div class="no-items-found posts">
                <img src="<?php print modules_url(); ?>/microweber/img/no_posts.svg" class="no-posts-img"/>

                <span class="mw-no-posts-foot-label"><?php _e("No Posts Here"); ?></span>

                <?php
                //                if (isset($post_params['category-id'])) {
                //                    $url = "#action=new:post&amp;category_id=" . $post_params['category-id'];
                //
                //                } elseif (isset($post_params['category'])) {
                //                    $url = "#action=new:post&amp;category_id=" . $post_params['category'];
                //
                //                } else if (isset($post_params['parent'])) {
                //                    $url = "#action=new:post&amp;parent_page=" . $post_params['parent'];
                //
                //                }

                $url = "#action=new:post"
                ?>

                <?php if (isset($url)): ?>
                    <a href="<?php print $url; ?>" class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline m-t-20 m-b-20"><i class="mai-post"></i> <span><?php _e("Add New Post"); ?></span></a>
                <?php endif; ?>


                <script>
                    $(document).ready(function () {
                        $('.manage-toobar').hide();
                        $('.top-search').hide();
                    });
                </script>
            </div>
        <?php endif; ?>

    </div>
<?php endif; ?>
