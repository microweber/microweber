<?php
$paging_links = false;
$pages_count = intval($pages);

$params_module  =$params;

// for toolbar

$cat_page = false;
if (isset($params['category-id']) and $params['category-id']) {
    $cat_page = get_page_for_category($params['category-id']);
}

$url_param_action = url_param('action', true);
$url_param_view = url_param('view', true);


$type = 'page';
if (isset($page_info)){
    if (is_array($page_info)){
        if ($page_info['is_shop'] == 1) {
            $type = 'shop';
        } elseif ($page_info['subtype'] == 'dynamic') {
            $type = 'dynamicpage';
        } else if (isset($page_info ['layout_file']) and stristr($page_info ['layout_file'], 'blog')) {
            $type .= 'blog';
        } else {
            $type = 'page';
        }
    }
}

$url_param_type = 'page';


if ($type == 'shop' or $url_param_view == 'shop' or $url_param_action == 'products') {
    $url_param_type = 'product';
} else if ($cat_page and isset($cat_page['is_shop']) and intval($cat_page['is_shop']) != 0) {
    $url_param_type = 'product';
} else if ($url_param_action == 'categories' or $url_param_view == 'category') {
    $url_param_type = 'category';
} else if ($url_param_action == 'showposts' or $url_param_action == 'posts' or $type == 'dynamicpage') {
    $url_param_type = 'post';
} else if ($cat_page and isset($cat_page['subtype']) and ($cat_page['subtype'])  == 'dynamic') {
    $url_param_type = 'product';
}
$add_new_btn_url = admin_url('view:content#action=new:') . $url_param_type;

// end of for toolbar



/* js-toolbar-add-new-content-button
 *    <a href="<?php print $add_new_btn_url ?>"
                                   class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-medium pull-left m-l-10"
                                   style="margin-top: 2px;">
                                    <?php print _e('Add new ' . $url_param_type); ?>
                                </a>
 */

?>
<script type="text/javascript">
    mw.require('forms.js', true);
    mw.require('content.js', true);
</script>
<script type="text/javascript">
    delete_selected_posts = function () {
        mw.tools.confirm("<?php _ejs("Are you sure you want to delete the selected posts"); ?>?", function () {
            var master = mwd.getElementById('<?php print $params['id']; ?>');
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
            var posts = mw.check.collectChecked(mwd.getElementById('<?php print $params['id']; ?>'));
            var data = {
                content_ids: posts,
                categories: []
            };
            selected.forEach(function(item){
                if(item.type === 'category') {
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
        $.get("<?php print  api_url('content/get_admin_js_tree_json'); ?>", function(data){
            var btn = document.createElement('button');
            btn.disabled = true;
            btn.className = 'mw-ui-btn';
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
            var tree = new mw.tree({
                data:data,
                element:dialog.dialogContainer,
                sortable:false,
                selectable:true,
                multiPageSelect: false
            });
            $(tree).on("selectionChange", function(){
                btn.disabled = tree.getSelected().length === 0;
            });
            $(tree).on("ready", function(){
                dialog.center();
            })

        });
    };

    mw.delete_single_post = function (id) {
        mw.tools.confirm("<?php _ejs("Do you want to delete this post"); ?>?", function () {
            var arr = id;
            mw.post.del(arr, function () {
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


    mw.on.hashParam("pg", function () {

        var dis = $p_id = this;
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

    mw.admin.showLinkNav = function () {
        var all = mwd.querySelector('.select_posts_for_action:checked');
        if (all === null) {
            mw.$('.mw-ui-link-nav').hide();
        }
        else {
            mw.$('.mw-ui-link-nav').show();
        }
    }

</script>

<style>
    .mw-post-item-tag {
        border-radius: 14px;
        color: #3b3b3b;
        background: #f5f5f5;
        padding-left: 10px;
        padding-right: 10px;
        margin-right: 5px;
        padding-top: 2px;
        padding-bottom: 2px;
    }
</style>


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
            </span>
        </div>
    </div>
<?php endif; ?>




<?php if (intval($pages_count) > 1): ?>
    <?php $paging_links = mw()->content_manager->paging_links(false, $pages_count, $paging_param, $keyword_param = 'keyword'); ?>
<?php endif; ?>




<?php
$params_module['show_only_content'] = true;
$params_module['wrap'] = true;
$params_module['id'] = 'pages_edit_container_content_list';
// print load_module('content/manager',$params_module);

echo '<module '. implode(' ', array_map(
        function ($k, $v) { return $k .'="'. htmlspecialchars($v) .'"'; },
        array_keys($params_module), $params_module
    )) .' />';



return;
?>
