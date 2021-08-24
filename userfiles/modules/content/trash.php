<?php must_have_access();
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
$post_params_paging['page_count'] = true;
$pages_count = false;
$paging_links = false;
?>

<?php if (intval($pages_count) > 1): ?>
    <?php $paging_links = mw()->content_manager->paging_links(false, $pages_count, $paging_param, $keyword_param = 'keyword'); ?>
<?php endif; ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <h5>
            <i class="mdi mdi-trash-can module-icon-svg-fill"></i> <strong><?php _e("Deleted content"); ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <script>
            $(document).ready(function () {
                $('#check-all', '.trash-select-all').on('click', function () {
                    if ($(this).is(':checked')) {
                        mw.check.all('#pages_delete_container')
                    } else {
                        mw.check.none('#pages_delete_container')
                    }

                })
            });
        </script>
        <script>
            $(document).ready(function () {
                mw.$("#check-all, input[name='select_delete_forever']").on('click', function () {
                    if (mw.$(".select_delete_forever:checked").length === 0) {
                        mw.$("#manage-buttons").hide();
                    } else {
                        mw.$("#manage-buttons").show();
                    }
                });
            });
        </script>

        <div class="toolbar row mb-3">
            <div class="col-sm-6 d-flex align-items-center justify-content-center justify-content-sm-start my-2">
                <div class="custom-control custom-checkbox mb-0 trash-select-all">
                    <input type="checkbox" class="custom-control-input" id="check-all">
                    <label class="custom-control-label" for="check-all"><?php _e("Check all"); ?></label>
                </div>
            </div>

            <div class="col-sm-6 text-end d-flex justify-content-center justify-content-sm-end align-items-center">
                <div id="manage-buttons" style="display: none;">
                    <span onclick="delete_selected_posts_forever();" class="btn btn-outline-danger btn-sm"><?php _e("Delete forever"); ?></span>
                    <span onclick="restore_selected_posts();" class="btn btn-outline-success btn-sm"><?php _e("Restore selected"); ?></span>
                </div>
            </div>
        </div>

        <div class="manage-posts-holder" id="pages_delete_container">
            <?php if (is_array($data)): ?>
                <div class="muted-cards">
                    <?php foreach ($data as $item): ?>
                        <?php
                        $pub_class = '';
                        if (isset($item['is_active']) and $item['is_active'] == 'n') {
                            $pub_class = ' content-unpublished';
                        }
                        $edit_link = admin_url('view:content#action=editpost:' . $item['id']);
                        ?>

                        <div class="card card-product-holder mb-2 manage-post-item-<?php print $item['id']; ?> <?php print $pub_class ?>">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col text-center" style="max-width: 40px;">
                                        <div class="form-group mb-0">
                                            <div class="custom-control custom-checkbox mx-1">
                                                <input name="select_delete_forever" id="trash-item-<?php print ($item['id']) ?>" class="select_delete_forever custom-control-input" type="checkbox" value="<?php print ($item['id']) ?>"/>
                                                <label class="custom-control-label" for="trash-item-<?php print ($item['id']) ?>"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col item-image">
                                        <?php $pic = get_picture($item['id']); ?>
                                        <?php if ($pic == true): ?>
                                            <div class="img-circle-holder border-radius-0 border-0" xonClick="mw.url.windowHashParam('action','editpost:<?php print ($item['id']) ?>');return false;">
                                                <img src="<?php print thumbnail($pic, 120) ?>">
                                            </div>
                                        <?php else : ?>
                                            <div class="img-circle-holder border-radius-0 border-0" xonClick="mw.url.windowHashParam('action','editpost:<?php print ($item['id']) ?>');return false;">
                                                <img src="<?php print thumbnail('', 120) ?>">
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-auto item-title">
                                        <h5 class="text-dark text-break-line-1 mb-0"><a target="_top" href="<?php print $edit_link ?>" onClick="mw.url.windowHashParam('action','editpost:<?php print ($item['id']) ?>');return false;"><?php print strip_tags($item['title']) ?></a></h5>
                                        <br>
                                        <small class="text-muted"><?php print content_link($item['id']); ?></small>
                                        <div class="mt-2">
                                            <a class="btn btn-outline-primary btn-sm" target="_top" href="<?php print $edit_link ?>" onClick="javascript:mw.url.windowHashParam('action','editpost:<?php print ($item['id']) ?>'); return false;"><?php _e("Edit"); ?></a>
                                            <a class="btn btn-outline-success btn-sm" target="_top" href="<?php print content_link($item['id']); ?>/?editmode:y"><?php _e("Live edit"); ?></a>
                                            <a class="btn btn-outline-danger btn-sm" href="javascript:delete_single_post_forever('<?php print ($item['id']) ?>');"><?php _e("Delete forever"); ?></a>
                                            <a class="btn btn-info btn-sm" href="javascript:restore_single_post_from_deletion('<?php print ($item['id']) ?>');"><?php _e("Restore"); ?></a>
                                        </div>
                                    </div>

                                    <div class="col item-author"><span class="text-muted"><?php print user_name($item['created_by'], 'username') ?></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="d-none">
                            <?php print character_limiter(strip_tags($item['description']), 60); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
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
                            var master = document.getElementById('pages_delete_container');
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
                            var master = document.getElementById('pages_delete_container');
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
                    ?>
                    <?php if (isset($paging_links) and is_array($paging_links)): ?>
                        <?php $i = 1; ?>
                        <?php foreach ($paging_links as $item): ?>
                            <a class="mw-ui-btn page-<?php print $i; ?> <?php if ($numactive == $i): ?> active <?php endif; ?>" href="#<?php print $paging_param ?>=<?php print $i ?>" onClick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $i ?>');return false;"><?php print $i; ?></a>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="mw-no-posts-foot">
                    <?php if ((isset($params['subtype']) and $params['content_type'] == 'product') OR (isset($params['is_shop']) and $params['is_shop'] == 'y')) : ?>
                        <h2><?php _e("No Products Here"); ?></h2>
                    <?php else: ?>
                        <h2><?php _e("No Content Here"); ?></h2>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
