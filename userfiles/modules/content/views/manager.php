<?php
$paging_links = false;
$pages_count = intval($pages);
$params_module = $params;
?>

<script type="text/javascript">
    mw.require('forms.js', true);
    mw.require('content.js', true);
</script>

<script type="text/javascript">
    publish_selected_posts = function () {
        mw.tools.confirm('<?php _e('Are you sure you want to publish this content?'); ?>', function () {
            var master = document.getElementById('<?php print $params['id']; ?>');
            var arr = mw.check.collectChecked(master);

            arr.forEach(function (item) {
                mw.post.publish(item);
            });

            mw.reload_module('#pages_edit_container_content_list');

            mw.notification.success(mw.msg.contentpublished);
        });
    }

    unpublish_selected_posts = function () {
        mw.tools.confirm('<?php _e('Are you sure you want to unpublish this content?'); ?>', function () {
            var master = document.getElementById('<?php print $params['id']; ?>');
            var arr = mw.check.collectChecked(master);

            arr.forEach(function (item) {
                mw.post.unpublish(item);
            });

            mw.reload_module('#pages_edit_container_content_list');
            mw.notification.warning(mw.msg.contentunpublished);
        });
    }

    delete_selected_posts = function () {
        mw.tools.confirm("<?php _ejs("Are you sure you want to delete the selected posts"); ?>?", function () {
            var master = document.getElementById('<?php print $params['id']; ?>');
            var arr = mw.check.collectChecked(master);
            mw.post.del(arr, function () {
                mw.reload_module('#<?php print $params['id']; ?>', function () {
                    $.each(arr, function (index) {
                        var fade = this;
                        mw.$(".manage-post-item-" + fade).fadeOut();
                    });
                });
            });
        });
    }

    assign_selected_posts_to_category_exec = function () {
        mw.tools.confirm("Are you sure you want to move the selected posts?", function () {
            var dialog = mw.dialog.get('#pick-categories');
            var tree = mw.tree.get('#pick-categories');
            var selected = tree.getSelected();
            var posts = mw.check.collectChecked(document.getElementById('<?php print $params['id']; ?>'));
            var data = {
                content_ids: posts,
                categories: []
            };
            selected.forEach(function (item) {
                if (item.type === 'category') {
                    data.categories.push(item.id);
                } else if (item.type === 'page') {
                    data.parent_id = item.id;
                }
            });
            $.post("<?php print api_link('content/bulk_assign'); ?>", data, function (msg) {
                mw.notification.msg(msg);
                mw.reload_module('#<?php print $params['id']; ?>');
                dialog.remove();
            });
        });
    };

    assign_selected_posts_to_category = function () {

            var btn = document.createElement('button');
            btn.disabled = true;
            btn.className = 'btn btn-primary';
            btn.innerHTML = mw.lang('Move posts');
            btn.onclick = function (ev) {
                assign_selected_posts_to_category_exec();
            };
            var dialog = mw.dialog({
                height: 'auto',
                autoHeight: true,
                id: 'pick-categories',
                footer: btn,
                title: mw.lang('Select categories')
            });

            mw.admin.tree(dialog.dialogContainer, {
                options: {
                    sortable: false,
                    selectable: true,
                    multiPageSelect: false
                }
            }, 'tree').then(function (res){
                var tree = res.tree;
                $(tree).on("selectionChange", function () {
                    btn.disabled = tree.getSelected().length === 0;
                });
                $(tree).on("ready", function () {
                    dialog.center();
                });
            });


    };

    mw.delete_single_post = function (id) {
        mw.tools.confirm("<?php _e("Do you want to delete this post"); ?>?", function () {
            mw.post.del(id, function () {
                mw.$(".manage-post-item-" + id).fadeOut(function () {
                    $(this).remove()
                });
            });
        });
    }

    mw.manage_content_sort = function () {
        if (!mw.$("#mw_admin_posts_sortable").hasClass("ui-sortable")) {
            mw.$("#mw_admin_posts_sortable").sortable({
                items: '.manage-post-item',
                axis: 'y',
                handle: '.mw_admin_posts_sortable_handle',
                update: function () {
                    var obj = {ids: []}
                    $(this).find('.select_posts_for_action').each(function () {
                        var id = this.attributes['value'].nodeValue;
                        obj.ids.push(id);
                    });
                    $.post("<?php print api_link('content/reorder'); ?>", obj, function () {
                        mw.reload_module('#mw_page_layout_preview');
                        mw.reload_module_parent('posts');
                        mw.reload_module_parent('content');
                        mw.reload_module_parent('shop/products');
                        mw.notification.success('<?php _e("All changes are saved"); ?>.');
                    });
                },
                start: function (a, ui) {
                    $(this).height($(this).outerHeight());
                    $(ui.placeholder).height($(ui.item).outerHeight())
                    $(ui.placeholder).width($(ui.item).outerWidth())
                },
                scroll: false
            });
        }
    }

    mw.on.hashParam("pg", function (pval) {
        var act = mw.url.windowHashParam("action");
        var dis = $p_id = pval;

        if (!!act) {
            var arr = act.split(":");
            var pos = arr[0].indexOf('edit');
            if (pos === 0) {
                dis = false;
            }
        }

        if (dis == false) {
            mw.$('#<?php print $params['id']; ?>').removeAttr('data-page-number');
            mw.$('#<?php print $params['id']; ?>').removeAttr('data-page-param');
            mw.$('#<?php print $params['id']; ?>').removeAttr("paging_param");

            return;
        }

        mw.$('#<?php print $params['id']; ?>').attr("paging_param", 'pg');
        if (dis !== '') {
            mw.$('#<?php print $params['id']; ?>').attr("pg", dis);
            mw.$('#<?php print $params['id']; ?>').attr("data-page-number", dis);
        }
        var $p_id = $(this).attr('data-page-number');
        var $p_param = $(this).attr('data-paging-param');
        mw.$('#<?php print $params['id']; ?>').attr('data-page-number', $p_id);
        mw.$('#<?php print $params['id']; ?>').attr('data-page-param', $p_param);
        mw.$('#<?php print $params['id']; ?>').removeAttr('data-content-id');

        mw.reload_module('#<?php print $params['id']; ?>');
    });
</script>
<div class="card style-1 mb-3 <?php if (isset($params['no_toolbar']) AND $params['no_toolbar']): ?>card-in-live-edit<?php endif; ?>">
    <?php if (!isset($params['no_toolbar']) and isset($toolbar)): ?>
        <?php print $toolbar; ?>
    <?php else: ?>
        <div class="manage-toobar-content my-3">
            <div class="mw-ui-link-nav">
                <span class="mw-ui-link" onclick="mw.check.all('#<?php print $params['id']; ?>')"><?php _e("Select All"); ?></span>
                <span class="mw-ui-link" onclick="mw.check.none('#<?php print $params['id']; ?>')"><?php _e("Unselect All"); ?></span>
                <span class="mw-ui-link" onclick="delete_selected_posts();"><?php _e("Delete"); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <div class="card-body pt-3">
        <?php
        $params_module['show_only_content'] = true;
        $params_module['wrap'] = true;
        $params_module['id'] = 'pages_edit_container_content_list';
        // print load_module('content/manager',$params_module);

        echo '<module ' . implode(' ', array_map(
                function ($k, $v) {
                    return $k . '="' . htmlspecialchars($v) . '"';
                },
                array_keys($params_module), $params_module
            )) . ' />';


        ?>
        <?php if (intval($pages_count) > 1): ?>
            <?php $paging_links = mw()->content_manager->paging_links(false, $pages_count, $paging_param, $keyword_param = 'keyword'); ?>
        <?php endif; ?>

        <?php
        $numactive = 1;

        if (isset($params['data-page-number'])) {
            $numactive = intval($params['data-page-number']);
        } else if (isset($params['current_page'])) {
            $numactive = intval($params['current_page']);
        }

        if (isset($paging_links) and is_array($paging_links)): ?>
            <ul class="mw-paging">
                <?php $i = 1; ?>
                <?php foreach ($paging_links as $item): ?>
                    <li class="page-item <?php if ($numactive == $i): ?> active <?php endif; ?>">
                        <a class="page-link page-<?php print $i; ?>" href="#<?php print $paging_param ?>=<?php print $i ?>" onclick="mw.url.windowHashParam('<?php print $paging_param ?>','<?php print $i ?>');return false;"><?php print $i; ?></a>
                    </li>
                    <?php $i++; ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</div>
