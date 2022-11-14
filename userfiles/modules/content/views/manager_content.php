<?php if (is_array($data) and !empty($data)): ?>
    <script>
        $(document).ready(function () {
            $('body > #mw-admin-container > .main').addClass('show-sidebar-tree');
        });
    </script>

    <div class="manage-posts-holder" id="mw_admin_posts_sortable">
        <div class="manage-posts-holder-inner muted-cards">

            <?php
            $edit_text = _e('Edit', true);
            $delete_text = _e('Delete', true);
            $live_edit_text = _e('Live Edit', true);
            ?>

            <?php if (is_array($data)): ?>
                <?php foreach ($data as $item): ?>
                    <?php if (isset($item['id'])): ?>
                        <?php
                        $pub_class = '';
                        $append = '';
                        if (isset($item['is_active']) and $item['is_active'] == '0') {
                            $pub_class = ' content-unpublished';
                            $append = '<div class="post-unpublished d-inline-flex align-items-center justify-content-center"><a href="javascript:;" class="btn btn-sm btn-link" onclick="mw.post.set(' . $item['id'] . ', \'publish\');">' . _e("Publish", true) . '</a> <span class="badge badge-warning">' . _e("Unpublished", true) . '</span></div>';
                        }
                        $content_link = content_link($item['id']);
                        ?>
                        <?php $pic = get_picture($item['id']); ?>
                        <div class="card card-product-holder mb-2 post-has-image-<?php print ($pic == true ? 'true' : 'false'); ?> manage-post-item-type-<?php print $item['content_type']; ?> manage-post-item manage-post-item-<?php print ($item['id']) ?> <?php print $pub_class ?>">
                            <div class="card-body">
                                <div class="row align-items-center flex-lg-box">
                                                <div class="col text-center manage-post-item-col-1" style="max-width: 40px;">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox custom-checkx-1">
                                                <input type="checkbox" class="custom-control-input select_posts_for_action" name="select_posts_for_action" id="select-content-<?php print ($item['id']) ?>" value="<?php print ($item['id']) ?>">
                                                <label class="custom-control-label" for="select-content-<?php print ($item['id']) ?>"></label>
                                            </div>
                                        </div>

                                        <span class="btn btn-link text-muted px-0 js-move mw_admin_posts_sortable_handle" onmousedown="mw.manage_content_sort()"><i class="mdi mdi-cursor-move"></i></span>
                                    </div>

                                    <div class="col manage-post-item-col-2" style="max-width: 120px;">
                                        <?php
                                        $type = $item['content_type'];
                                        $typeIcon = 'mdi-text';
                                        if ($type == 'product') {
                                            $typeIcon = 'mdi-shopping';
                                        } elseif ($type == 'post') {
                                            $typeIcon = 'mdi-text';
                                        } elseif ($type == 'page') {
                                            $typeIcon = 'mdi-file-document';
                                        }

                                        $target = '_self';

                                        if(isset($params['no_page_edit']) and $params['no_page_edit']){
                                            $target = '_top';

                                        }

                                        ?>

                                        <?php if ($pic == true): ?>
                                            <div class="mw-admin-product-item-icon text-muted">
                                                <i class="mdi <?php echo $typeIcon; ?> mdi-18px" data-bs-toggle="tooltip" title="<?php ucfirst($type); ?>"></i>
                                            </div>
                                        <?php endif; ?>

                                        <div class="img-circle-holder border-radius-0 border-0">
                                            <?php if ($pic == true): ?>
                                                <a href="javascript:;" onClick="mw.url.windowHashParam('action', 'editpage:<?php print ($item['id']) ?>');return false;">
                                                    <img src="<?php print thumbnail($pic, 120, 120, true) ?>"/>
                                                </a>
                                            <?php else : ?>
                                                <a href="javascript:;" onclick="mw.url.windowHashParam('action', 'editpage:<?php print ($item['id']) ?>');return false;">
                                                    <i class="mdi <?php echo $typeIcon; ?> mdi-48px text-muted text-opacity-5"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                        <?php
                                        $edit_link = route('admin.content.edit', $item['id']);
                                        if (Route::has('admin.'.$item['content_type'].'.edit')) {
                                            $edit_link = route('admin.' . $item['content_type'] . '.edit', $item['id']);
                                        }
                                        ?>
                                        <?php $edit_link_front = $content_link . '?editmode:y'; ?>
                                    </div>

                                    <div class="col item-title manage-post-item-col-3 manage-post-main">
                                        <div class="manage-item-main-top">
                                            <a target="<?php echo $target; ?>" href="<?php print $edit_link_front; ?>" class="btn btn-link p-0">
                                                <h5 class="text-dark text-break-line-1 mb-0 manage-post-item-title"><?php print strip_tags($item['title']) ?></h5>
                                            </a>
                                            <?php mw()->event_manager->trigger('module.content.manager.item.title', $item) ?>

                                            <?php $cats = content_categories($item['id']); ?>
                                            <?php $tags = content_tags($item['id'], false); ?>
                                            <?php if ($cats): ?>
                                                <span class="manage-post-item-cats-inline-list">
                                                    <?php foreach ($cats as $ck => $cat): ?>
                                                        <a href="#action=showpostscat:<?php print ($cat['id']); ?>" class="btn btn-link p-0 text-muted"><?php print $cat['title']; ?></a><?php if (isset($cats[$ck + 1])): ?>,<?php endif; ?>
                                                    <?php endforeach; ?>
                                                </span>
                                            <?php endif; ?>

                                            <?php if ($tags): ?>
                                                <br/>
                                                <?php foreach ($tags as $tag): ?>
                                                    <small class="bg-secondary rounded-lg px-2">#<?php echo $tag; ?></small>
                                                <?php endforeach; ?>
                                            <?php endif; ?>


                                            <a class="manage-post-item-link-small mw-medium d-none d-lg-block" target="<?php echo $target; ?>" href="<?php print $content_link; ?>?editmode:y">
                                                <small class="text-muted"><?php print $content_link; ?></small>
                                            </a>
                                        </div>

                                        <div class="manage-post-item-links">
                                            <?php
                                            if (user_can_access('module.content.edit')):
                                                ?>
                                                <a href="<?php echo $edit_link; ?>" class="btn btn-outline-success btn-sm">
                                                    <?php echo $edit_text; ?>
                                                </a>

                                                <a target="<?php echo $target; ?>" class="btn btn-outline-primary btn-sm" href="<?php print $content_link; ?>?editmode:y">
                                                    <?php echo $live_edit_text; ?>
                                                </a>
                                                <?php
                                            endif;
                                            ?>

                                            <?php
                                            if (user_can_access('module.content.destroy')):
                                                ?>
                                                <a class="btn btn-outline-danger btn-sm" href="javascript:mw.delete_single_post('<?php print ($item['id']) ?>');">
                                                    <?php echo $delete_text; ?>
                                                </a>
                                                <?php
                                            endif;
                                            ?>
                                            <?php if (isset($item['is_active']) AND $item['is_active'] == 1): ?>

                                            <?php else: ?>
                                                <span class="badge badge-warning font-weight-normal">Unpublished</span>
                                            <?php endif; ?>


                                        </div>
                                    </div>

                                    <div class="col item-author manage-post-item-col-4 d-xl-block d-none">
                                        <span class="text-muted" title="<?php print user_name($item['created_by']); ?>"><?php print user_name($item['created_by'], 'username') ?></span>
                                    </div>

                                   <?php

                                   /* <div class="col manage-post-item-col-5" style="max-width: 130px;">
                                        <?php if (isset($item['is_active']) AND $item['is_active'] == 1): ?>
                                            <!--                                            <span class="badge badge-success">Published</span>-->
                                        <?php else: ?>
                                            <span class="badge badge-warning font-weight-normal">Unpublished</span>
                                        <?php endif; ?>
                                    </div>
*/
                                   ?>

                                    <div class="col item-comments manage-post-item-col-5 d-none" style="max-width: 100px;">
                                        <?php mw()->event_manager->trigger('module.content.manager.item', $item) ?>
                                    </div>
                                </div>
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
        ?>

        <?php if (isset($paging_links) and is_array($paging_links)): ?>
            <div class="mw-paging" style="display: none">
                <?php $i = 1; ?>
                <?php foreach ($paging_links as $item): ?>
                    <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>" href="#<?php print $paging_param ?>=<?php print $i ?>" onclick="mw.url.windowHashParam('<?php print $paging_param ?>', '<?php print $i ?>');return false;"><?php print $i; ?></a>
                    <?php $i++; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($paging_links) and is_array($paging_links)): ?>
            <div class="mw-paging pull-right">
                <?php $count = count($paging_links); ?>
                <?php if ($count < 6): ?>
                    <?php $i = 1; ?>
                    <?php foreach ($paging_links as $item): ?>
                        <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>" href="#<?php print $paging_param ?>=<?php print $i ?>" onclick="mw.url.windowHashParam('<?php print $paging_param ?>', '<?php print $i ?>');return false;"><?php print $i; ?></a>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php if ($numactive > 2): ?>
                        <a class="page-1" href="#<?php print $paging_param ?>=1" onclick="mw.url.windowHashParam('<?php print $paging_param ?>', '1');return false;">First</a>

                        <?php for ($i = $numactive - 2; $i <= $numactive + 2; $i++): ?>
                            <?php if ($i < $count): ?>
                                <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>" href="#<?php print $paging_param ?>=<?php print $i ?>" onclick="mw.url.windowHashParam('<?php print $paging_param ?>', '<?php print $i ?>');return false;"><?php print $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                    <?php else: ?>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <a class="page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>" href="#<?php print $paging_param ?>=<?php print $i ?>" onclick="mw.url.windowHashParam('<?php print $paging_param ?>', '<?php print $i ?>');return false;"><?php print $i; ?></a>
                        <?php endfor; ?>
                    <?php endif; ?>

                    <a class="page-<?php print $count; ?>" href="#<?php print $paging_param . '=' . ($count - 1); ?>" onclick="mw.url.windowHashParam('<?php print $paging_param ?>', '<?php print $count - 1; ?>');return false;"><?php _e("Last"); ?></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
<?php else: ?>
    <?php
    $page_is_shop = false;
    if (isset($post_params["page-id"])) {
        $page_is_shop_check = get_content_by_id($post_params["page-id"]);
        if (isset($page_is_shop_check['is_shop']) and $page_is_shop_check['is_shop'] == 1) {
            $page_is_shop = true;
        }
    }

    if ((isset($post_params['content_type']) and $post_params['content_type'] == 'product') or (isset($params['content_type']) and $params['content_type'] == 'product') or $page_is_shop) :
        ?>

        <?php
        include (__DIR__.'/no_results_found_products.php');

        ?>



    <?php else: ?>
        <div class="no-items-found posts">
            <?php $url = "#action=new:post"; ?>

            <?php if (isset($post_params['content_type']) AND $post_params['content_type'] == 'page'): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="no-items-box">
                            <h4><?php _e('You don’t have pages'); ?></h4>
                            <p><?php _e('Create your first page right now.'); ?><br/>
                                <?php _e( 'You are able to do that in very easy way!'); ?></p>
                            <br/>
                            <a href="<?php print admin_url() . 'view:content#action=new:page'; ?>" class="btn btn-primary btn-rounded"><?php _e('Create a Page'); ?></a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-12">
                        <div class="no-items-box">
                            <h4><?php _e('You don’t have any posts yet'); ?></h4>
                            <p><?php _e('Create your first post right now.'); ?><br/>
                                <?php _e('You are able to do that in very easy way!'); ?> </p>
                            <br/>
                            <a href="<?php print$url; ?>" class="btn btn-primary btn-rounded"><?php _e('Create a Post'); ?></a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <script>
                $(document).ready(function () {
                    $('.js-hide-when-no-items').hide()
                    //                    $('body > #mw-admin-container > .main').removeClass('show-sidebar-tree');
                });
            </script>

            <script>
                $(document).ready(function () {
                    $('.manage-toobar').hide();
                    $('.top-search').hide();
                });
            </script>
        </div>
    <?php endif; ?>

<?php endif; ?>
