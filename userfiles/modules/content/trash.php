<?php only_admin_access();
// d($params);
$is_momodule_comments = is_module('comments');

$post_params = $params;


if (isset($post_params['paging_param'])) {
    $paging_param = $post_params['paging_param'];
}

$current_page = 1;
if (isset($params['current_page'])) {
    $current_page = $params['current_page'];
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
    $cfg_page_id = intval($post_params['data-page-id']);
} else {
    $cfg_page_id = get_option('data-page-id', $params['id']);

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


$post_params = array();
$post_params['is_deleted'] = 1;
$post_params['limit'] = 250;

$content = $data = get_content($post_params);

$post_params_paging = $post_params;
//$post_params_paging['count'] = true;


$post_params_paging['page_count'] = true;
//$pages = get_content($post_params_paging);
$pages_count = false;

$paging_links = false;
//$pages_count = intval($pages);
?>
<?php if (intval($pages_count) > 1): ?>
    <?php $paging_links = mw()->content_manager->paging_links(false, $pages_count, $paging_param, $keyword_param = 'keyword'); ?>
<?php endif; ?>

<div class="admin-manage-toolbar-holder">
    <div class="admin-manage-toolbar">
        <div class="admin-manage-toolbar-content">
            <div class="mw-ui-row" style="width: 100%;">
                <div class="mw-ui-col">
                    <div class="mw-ui-row admin-section-bar">
                        <div class="mw-ui-col">
                            <h2><span class="mai-bin"></span> <?php _e("Deleted content"); ?> </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="manage-toobar manage-toolbar-top">
    <div class="mw-ui-row">
        <div class="mw-ui-col">
            <script>
                $(document).ready(function () {
                    $('.mw-ui-btn', '.trash-select-all').on('click', function () {
                        $(this).parent().find('.mw-ui-btn-info').addClass('mw-ui-btn-outline');
                        $(this).removeClass('mw-ui-btn-outline');

                        if (mw.$(".select_delete_forever:checked").length === 0) {
                            mw.$("#manage-buttons").hide();
                        }
                        else {
                            mw.$("#manage-buttons").show();
                        }
                    })
                });
            </script>
            <span class="posts-selector pull-left trash-select-all">
                <a onclick="mw.check.all('#pages_delete_container')" class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-small"><?php _e("Select All"); ?></a>
              &nbsp;
                <a onclick="mw.check.none('#pages_delete_container')" class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-small"><?php _e("Unselect All"); ?></a>
          </span>
        </div>

        <div class="mw-ui-col">
            <div id="manage-buttons" class="pull-right" style="display: none">
                <span onclick="delete_selected_posts_forever();" class="mw-ui-btn mw-ui-btn-important mw-ui-btn-outline mw-ui-btn-small"><?php _e("Delete forever"); ?></span>
                <span onclick="restore_selected_posts();" style="margin-right: 12px;" class="mw-ui-btn mw-ui-btn-notification mw-ui-btn-outline mw-ui-btn-small"><?php _e("Restore selected"); ?></span>
            </div>
        </div>
    </div>
</div>

<div class="manage-posts-holder" id="pages_delete_container">
    <script>
        $(document).ready(function () {
            mw.$("#pages_delete_container .mw-ui-check, .manage-toobar .mw-ui-link").bind('click', function () {
                if (mw.$(".select_delete_forever:checked").length === 0) {
                    mw.$("#manage-buttons").hide();
                }
                else {
                    mw.$("#manage-buttons").show();
                }
            });
        });
    </script>

    <?php if (is_array($data)): ?>
    <?php foreach ($data as $item): ?>

        <?php
        $pub_class = '';
        if (isset($item['is_active']) and $item['is_active'] == 'n') {
            $pub_class = ' content-unpublished';
        }

        ?>


        <div class="manage-post-item mw-ui-row manage-post-item-<?php print ($item['id']) ?> <?php print $pub_class ?>">


            <div class="mw-ui-col manage-post-item-col-1">
                <label class="mw-ui-check">
                    <input name="select_delete_forever" class="select_delete_forever" type="checkbox" value="<?php print ($item['id']) ?>">
                    <span></span>
                </label>
            </div>

            <div class="mw-ui-col manage-post-item-col-2"><?php $pic = get_picture($item['id']); ?>
                <?php if ($pic == true): ?>
                    <a class="manage-post-image" style="background-image: url('<?php print thumbnail($pic, 108) ?>');" onClick="mw.url.windowHashParam('action','editpost:<?php print ($item['id']) ?>');return false;"></a>
                <?php else : ?>
                    <a class="manage-post-image manage-post-image-no-image" onClick="mw.url.windowHashParam('action','editpost:<?php print ($item['id']) ?>');return false;"></a>
                <?php endif; ?>
            </div>

            <?php $edit_link = admin_url('view:content#action=editpost:' . $item['id']); ?>

            <div class=" mw-ui-col manage-post-item-col-3 manage-post-main">
                <h3 class="manage-post-item-title"><a target="_top" href="<?php print $edit_link ?>" onClick="mw.url.windowHashParam('action','editpost:<?php print ($item['id']) ?>');return false;"><?php print strip_tags($item['title']) ?></a></h3>
                <small><a class="manage-post-item-link-small" target="_top" href="<?php print content_link($item['id']); ?>/?editmode:y"><?php print content_link($item['id']); ?></a></small>
                <div class="manage-post-item-description"> <?php print character_limiter(strip_tags($item['description']), 60);
                    ?> </div>
                <div class="manage-post-item-links"><a target="_top" href="<?php print content_link($item['id']); ?>/?editmode:y"><?php _e("Live edit"); ?></a> <a target="_top" href="<?php print $edit_link ?>"
                                                                                                                                                                  onClick="javascript:mw.url.windowHashParam('action','editpost:<?php print ($item['id']) ?>'); return false;"><?php _e("Edit"); ?></a> <a
                            href="javascript:delete_single_post_forever('<?php print ($item['id']) ?>');"><?php _e("Delete forever"); ?></a> <a href="javascript:restore_single_post_from_deletion('<?php print ($item['id']) ?>');"><?php _e("Restore"); ?></a></div>
            </div>
            <div class="mw-ui-col manage-post-item-col-4" title="<?php print user_name($item['created_by']); ?>"><span class="manage-post-item-author"><?php print user_name($item['created_by'], 'username') ?></span></div>


            <?php if ($is_momodule_comments == true): ?>
                <?php $new = get_comments('count=1&is_moderated=n&content_id=' . $item['id']); ?>
                <?php

                if ($new > 0) {
                    $have_new = 1;
                } else {
                    $have_new = 0;
                    $new = get_comments('count=1&content_id=' . $item['id']);
                }
                ?>


                <div class=" mw-ui-col manage-post-item-col-5">
                    <?php if ($have_new): ?>


                        <a href="<?php print admin_url('view:comments'); ?>/#content_id=<?php print $item['id'] ?>" class="comments-bubble"><span class="mw-icon-comment"></span><span class="comment-number"><?php print($new); ?></span></a>

                    <?php else: ?>

                        <a href="<?php print admin_url('view:comments'); ?>/#content_id=<?php print $item['id'] ?>" class="comments-bubble"><span class="mw-icon-comment"></span><span class="comment-number"><?php print($new); ?></span></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>


        </div>
    <?php endforeach; ?>
</div>


    <script type="text/javascript">
        mw.require('forms.js', true);
    </script>
    <script type="text/javascript">
        mw.post_del_forever = function (a, callback) {
            var arr = $.isArray(a) ? a : [a];
            var obj = {ids: arr, forever: true}
            $.post(mw.settings.site_url + "api/content/delete", obj, function (data) {
                typeof callback === 'function' ? callback.call(data) : '';
            });
        }
        mw.post_undelete = function (a, callback) {
            var arr = $.isArray(a) ? a : [a];
            var obj = {ids: arr, undelete: true}
            $.post(mw.settings.site_url + "api/content/delete", obj, function (data) {
                typeof callback === 'function' ? callback.call(data) : '';
            });
        }


    </script>


    <script type="text/javascript">
        delete_selected_posts_forever = function () {


            mw.tools.confirm("<?php _ejs("Are you sure you want to delete those pages forever"); ?>?", function () {
                var master = mwd.getElementById('pages_delete_container');
                var arr = mw.check.collectChecked(master);
                arr.forever = true;
                mw.post_del_forever(arr, function () {
                    mw.reload_module('#<?php print $params['id'] ?>', function () {

                    });
                });
            });


        }


        delete_single_post_forever = function (id) {
            mw.tools.confirm("<?php _ejs("Do you want to delete this content forever"); ?>?", function () {
                var arr = id;
                arr.forever = true;
                mw.post_del_forever(arr, function () {
                    mw.$(".manage-post-item-" + id).fadeOut(function () {
                        $(this).remove()
                    });
                    mw.notification.success("<?php _ejs('Content is deleted!'); ?>");
                });
            });
        }

        restore_selected_posts = function () {

            mw.tools.confirm("<?php _ejs("Are you sure you want restore the selected content"); ?>?", function () {
                var master = mwd.getElementById('pages_delete_container');
                var arr = mw.check.collectChecked(master);
                arr.forever = true;
                mw.post_undelete(arr, function () {
                    mw.reload_module("pages", function () {

                    });
                    mw.reload_module('#<?php print $params['id'] ?>', function () {
                        mw.notification.success("<?php _ejs('Content is restored!'); ?>");
                    });
                });
            });
        }

        restore_single_post_from_deletion = function (id) {
            mw.tools.confirm("<?php _ejs("Restore this content"); ?>?", function () {
                var arr = id;
                arr.forever = true;
                mw.post_undelete(arr, function () {
                    mw.$(".manage-post-item-" + id).fadeOut();

                    mw.reload_module("pages", function () {
                        mw.$(".mw_pages_posts_tree").removeClass("activated");
                        mw.notification.success("<?php _ejs('Content is restored!'); ?>");
                        mw.reload_module('#<?php print $params['id'] ?>', function () {

                        });
                    });


                });
            });


        }

    </script>


    <div class="mw-paging">
        <?php

        $numactive = 1;

        if (isset($params['data-page-number'])) {
            $numactive = intval($params['data-page-number']);
        } else if (isset($params['current_page'])) {
            $numactive = intval($params['current_page']);
        }
        if (isset($paging_links) and is_array($paging_links)): ?>
            <?php $i = 1;
            foreach ($paging_links as $item): ?>
                <a class="mw-ui-btn page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>" href="#<?php print $paging_param ?>=<?php print $i ?>" onClick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $i ?>');return false;"><?php print $i; ?></a>
                <?php $i++; endforeach; ?>
        <?php endif; ?>

    </div>

<?php else: ?>
    <div class="mw-no-posts-foot">
        <?php if (isset($params['subtype']) and $params['content_type'] == 'product') : ?>
            <h2><?php _e("No Products Here"); ?></h2>
        <?php else: ?>
            <h2><?php _e("No Posts Here"); ?></h2>
        <?php endif; ?>
    </div>
<?php endif; ?>
