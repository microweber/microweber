<?php
if (!user_can_access('module.content.index')) {
    return;
}

$action = url_param('action');
$is_in_shop = false;
$rand = uniqid(); ?>
<?php $my_tree_id = crc32(mw()->url_manager->string()); ?>
<?php $active_content_id = '';
if (isset($_REQUEST['edit_content']) and $_REQUEST['edit_content'] != 0) {
    $active_content_id = $_REQUEST['edit_content'];
}

?>
<script>
    mw.require("content.js");
</script>
<script>

    mw.on.hashParam("search", function (pval) {
        var cont = mw.$('#pages_edit_container').attr("data-type", 'content/manager');
        if ( !!pval ) {
            cont.attr("data-keyword", pval);
            mw.url.windowDeleteHashParam('pg')
            cont.attr("data-page-number", 1);
        }
        else {
            cont.removeAttr("data-keyword");
            mw.url.windowDeleteHashParam('search')
        }
        var holder = document.querySelector('#content-view-search-bar') || document.querySelector('.main');
        mw.spinner({element: holder, size: 22, decorate: true}).show();
        mw.reload_module('#pages_edit_container', function () {
            mw.spinner({element: holder}).remove();
        });
    });
    mw.on.moduleReload('#<?php print $params['id'] ?>');


    var mainTreeSetActiveItems = function () {
        if (typeof(mw.adminPagesTree) != 'undefined') {

            var hp = mw.url.getHashParams(location.hash);

            if (hp.action) {

                var arr = hp.action.split(':');
                if (arr[0] !== 'new') {
                    mw.adminPagesTree.unselectAll();
                }
                var activeTreeItemIsPage = arr[0] === 'editpage' || arr[0] === 'showposts';
                var activeTreeItemIsCategory = arr[0] === 'editcategory' || arr[0] === 'showpostscat';

                if (activeTreeItemIsPage) {
                    mw.adminPagesTree.select({
                        id: arr[1],
                        type: 'page'
                    })
                }
                if (activeTreeItemIsCategory) {
                    mw.adminPagesTree.select({
                        id: arr[1],
                        type: 'category'
                    })
                }
            }
            else {
                mw.adminPagesTree.unselectAll();
            }
        }
    };


    $(document).ready(function () {

        mw.on.hashParam("page-posts", function (pval) {
            mw_set_edit_posts(pval);
        });
        mw.on.moduleReload("pages_tree_toolbar", function (e) {

        });
        mw.on.moduleReload("pages_edit_container", function () {
        });
        $(document.body).ajaxStop(function () {
            $(this).removeClass("loading");
        });

        mw.$(".mw-admin-go-live-now-btn").off('click');

    });


    mw.contentAction = {
        manage: function (type, id) {



            //   add_to_parent_page

            id = id || 0;
            if (type === 'page') {
                mw_select_page_for_editing(id);
            }
            else if (type === 'post') {
                mw_select_post_for_editing(id);
            }
            else if (type === 'category') {
                mw_select_category_for_editing(id);
            }
            else if (type === 'mw_backward_prod') {
                mw_add_product(id);
            } else if (type !== '') {
                mw_select_custom_content_for_editing(0, type)
            }
            mw.$(".mw_action_nav").addClass("not-active");
            mw.$(".mw_action_" + type).removeClass("not-active");


        },
        create: function (a) {
            return mw.contentAction.manage(a, 0);
        }
    }
    function mw_delete_content($p_id) {
        mw.$('#pages_edit_container').attr('data-content-id', $p_id);
        mw.load_module('content/edit', '#pages_edit_container');
    }

    function mw_select_page_for_editing($p_id) {

        mw.$(".pages_tree_item.active-bg").removeClass('active-bg');
        mw.$(".category_element.active-bg").removeClass('active-bg');

        //var active_item = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').first();
        var active_item_is_page = $p_id;
        var active_item_is_parent = mw.url.windowHashParam("parent-page");
        if (!active_item_is_parent) {
            active_item_is_parent = $p_id;
        }
        var active_item = $('#pages_tree_container_<?php print $my_tree_id; ?> .content-item-' + active_item_is_parent).first();


        var active_item_is_category = active_item.attr('data-category-id');


        active_item.addClass('active-bg');

        mw.$('.mw-admin-go-live-now-btn').attr('content-id', active_item_is_parent);
        mw.$('#pages_edit_container')
            .removeAttr('data-parent-page-id')
            .attr('content_type', 'page')
            .removeAttr('subtype')
            .removeAttr('content_type_filter')
            .removeAttr('subtype_filter')
            .removeAttr('data-parent-category-id')
            .removeAttr('categories_active_ids')
            .removeAttr('data-categories_active_ids')
            .removeAttr('data-active_ids')
            .removeAttr('active_ids');


        if (active_item_is_category ) {
            var active_item_parent_page = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').parents('.have_category').first();
            if (active_item_parent_page.length) {
                 active_item_is_page = active_item_parent_page.attr('data-page-id');
            }
            else {
                active_item_parent_page = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').parents('.is_page').first();
                if (active_item_parent_page.length) {
                     active_item_is_page = active_item_parent_page.attr('data-page-id');
                }
            }
        }
        else {
            mw.$('#pages_edit_container').removeAttr('data-parent-category-id');
        }

        mw.$('#pages_edit_container').removeAttr('data-parent-page-id');

        mw.$('#pages_edit_container').attr('data-page-id', $p_id);
        mw.$('#pages_edit_container').attr('data-type', 'content/edit');
        mw.$('#pages_edit_container').removeAttr('data-subtype');
        mw.$('#pages_edit_container').removeAttr('data-content-id');
        mw.$('#pages_edit_container').removeAttr('content-id');


        mw.$(".mw_edit_page_right").css("overflow", "hidden");
        edit_load('content/edit');
    }

    mw.on.hashParam("action", function (pval) {

        if (pval === false) {
            mw.tools.classNamespaceDelete(document.body, 'action-')
        }

        mainTreeSetActiveItems()

        if (pval === false) {
            mw.$('#pages_edit_container').removeAttr('page-id');
            mw_clear_edit_module_attrs();
            mw.$(".fix-tabs").removeClass('fix-tabs');
        }


        mw.$(".js-top-save").hide();

        window.scrollTo(0, 0);
        mw.$("#pages_edit_container").stop();
        mw.$('#pages_edit_container').removeAttr('mw_select_trash');
        mw.$(".mw_edit_page_right").css("overflow", "hidden");

        if (pval === false) {
            mw.$(".mw_edit_page_right").css("overflow", "hidden");
            edit_load('content/manager');
            return false;
        }
        var arr = pval.split(":");
        mw.tools.classNamespaceDelete(document.body, 'action-');
        if (arr[0] === 'new') {
            mw.contentAction.create(arr[1]);
            if (arr[0]) {
                $(document.body).addClass("action-"+arr[0] + '-' + arr[1]);
            }
        }
        else {

            mw.$(".active-bg").removeClass('active-bg');
            mw.$(".mw_action_nav").removeClass("not-active");
            var active_item = mw.$(".item_" + arr[1]);

            if (arr[0]) {
            $(document.body).addClass("action-"+arr[0]);
            }
            if (arr[0] == 'showposts') {
                active_item = mw.$(".content-item-" + arr[1]);
            }
            else if (arr[0] == 'showpostscat') {
                active_item = mw.$(".category-item-" + arr[1]);
            }

            if (arr[0] === 'editpage') {
                mw_select_page_for_editing(arr[1])
            }


            if (arr[0] === 'trash') {
                mw_select_trash(arr[0])
            }
            else if (arr[0] === 'showposts') {
                mw_set_edit_posts(arr[1])
            }
            else if (arr[0] === 'showpostscat') {
                mw_set_edit_posts(arr[1], true)
            }
            else if (arr[0] === 'editcategory') {
                mw_select_category_for_editing(arr[1])
            }
            else if (arr[0] === 'editpost') {

                mw_select_post_for_editing(arr[1]);


            } else if (arr[0] === 'addsubcategory') {
                mw_select_add_sub_category(arr[1]);
            }
        }

    });


    edit_load = function (module, callback) {
        if (mw.url.getHashParams(window.location.hash)['new_content'] === 'true') {
            var slide = false;
            mw.url.windowDeleteHashParam('new_content');
        }

        var action = mw.url.windowHashParam('action');
        var holder = $('#pages_edit_container');

        var time = 500;
        if (!action) {
            mw.$('.fade-window').removeClass('active');
        }

         edit_content_load_admin_spinner =  mw.spinner({
            element: '#mw-content-backend',
            size:40
        })


        setTimeout(function () {

            mw.load_module(module, holder, function () {
                mw.$('.fade-window').addClass('active')

                if (callback) callback.call();

            });
            edit_content_load_admin_spinner.remove()
        }, time)


    }

    function mw_select_category_for_editing($p_id) {

        mw_clear_edit_module_attrs();

        mw.$(".pages_tree_item.active-bg").removeClass('active-bg');
        mw.$(".category_element.active-bg").removeClass('active-bg');


        var active_item = mw.$(".category-item-" + $p_id);
        active_item.addClass('active-bg');


        mw.$('#pages_edit_container').removeAttr('parent_id');
        mw.$('#pages_edit_container').removeAttr('data-parent-category-id');
        mw.$('#pages_edit_container').attr('data-category-id', $p_id);
        mw.$(".mw_edit_page_right").css("overflow", "hidden");
        edit_load('categories/edit_category');
    }

    function mw_select_add_sub_category($p_id) {

        mw_clear_edit_module_attrs();


        mw.$('#pages_edit_container').removeAttr('parent_id');
        mw.$('#pages_edit_container').attr('data-category-id', 0);
        mw.$('#pages_edit_container').attr('data-parent-category-id', $p_id);
        mw.$(".mw_edit_page_right").css("overflow", "hidden");
        edit_load('categories/edit_category');
    }


    function mw_set_edit_posts(in_page, is_cat, c) {
        var is_cat = typeof is_cat === 'function' ? undefined : is_cat;
        var cont = mw.$('#pages_edit_container');
        cont
            .removeAttr('data-content-id')
            .removeAttr('data-page-id')
            .removeAttr('data-category-id')
            .removeAttr('data-selected-category-id')
            .removeAttr('data-parent-category-id')
            .removeAttr('subtype')
            .removeAttr('data-subtype')
            .removeAttr('data-content-id')
            .removeAttr('parent_id')
            .removeAttr('is_shop');
        //  .attr('content-id', in_page);
        mw.$('#pages_edit_container').removeAttr('content_type');
        mw.$('#pages_edit_container').removeAttr('subtype');
        mw.$('#pages_edit_container').removeAttr('subtype_value');
        mw.$('#pages_edit_container').removeAttr('content_type_filter');
        mw.$('#pages_edit_container').removeAttr('subtype_filter');
        mw.$('#pages_edit_container').removeAttr('categories_active_ids');
        mw.$('#pages_edit_container').removeAttr('data-categories_active_ids');

        mw.$('#pages_edit_container').removeAttr('data-active_ids');
        mw.$('#pages_edit_container').removeAttr('active_ids');
        mw.$('#pages_edit_container').removeAttr('content-id');
        mw.$('#pages_edit_container').removeAttr('category-id');


        mw.$(".pages_tree_item.active-bg").removeClass('active-bg');
        mw.$(".category_element.active-bg").removeClass('active-bg');


        if (in_page != undefined && is_cat == undefined) {
            cont.attr('data-page-id', in_page);
            var active_item = mw.$(".content-item-" + in_page);
            active_item.addClass('active-bg');
        }

        if (in_page != undefined && is_cat != undefined) {
            cont.attr('data-category-id', in_page);
            var active_item = mw.$(".category-item-" + in_page);
            active_item.addClass('active-bg');
        }

        var cat_id = mw.url.windowHashParam("category_id");
        if (cat_id) {
            cont.attr('data-category-id', cat_id);
        }


        mw.load_module('content/manager', '#pages_edit_container');
    }


    function mw_clear_edit_module_attrs() {
        var container = mw.$('#pages_edit_container');
        container
            .removeAttr('content_type')
            .removeAttr('subtype')
            .removeAttr('data-parent-category-id')
            .removeAttr('data-category-id')
            .removeAttr('data-category-id')
            .removeAttr('data-category-id')
            .removeAttr('content-id')
            .removeAttr('data-page-id')
            .removeAttr('content_type_filter')
            .removeAttr('subtype_filter');
    }

    function mw_select_trash(c) {
        var container = mw.$('#pages_edit_container');
        container.removeAttr('data-content-id')
            .removeAttr('data-page-id')
            .removeAttr('data-category-id')
            .removeAttr('data-selected-category-id')
            .removeAttr('data-keyword')
            .removeAttr('content_type_filter')
            .removeAttr('subtype_filter');

        mw.load_module('content/trash', '#pages_edit_container', function () {
            typeof c === 'function' ? c.call() : '';
        });
    }
    function mw_select_custom_content_for_editing($p_id, $type) {

        var active_item = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').first();
        var active_item_is_page = active_item.attr('data-page-id');

        mw.$('#pages_edit_container').removeAttr('content_type_filter');
        mw.$('#pages_edit_container').removeAttr('subtype_filter');

        $subtype = '';
        var res = $type.split(".");

        if (typeof(res[1]) == 'string') {

            $type = res[0];
            $subtype = res[1];

        }

        mw.$('.mw-admin-go-live-now-btn').attr('content-id', $p_id);


        var active_item_is_category = active_item.attr('data-category-id');
        if (active_item_is_category != undefined) {

            mw.$('#pages_edit_container').attr('data-parent-category-id', active_item_is_category);

            var active_bg = document.querySelector('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg');

            var active_item_parent_page = mw.tools.firstParentWithClass(active_bg, 'have_category');

            if (active_item_parent_page == false) {
                var active_item_parent_page = mw.tools.firstParentWithClass(active_bg, 'is_page');

            }

            if (active_item_parent_page == false) {
                var active_item_parent_page = mw.tools.firstParentWithClass(active_bg, 'pages_tree_item');

            }


            if (active_item_parent_page != false) {
                var active_item_is_page = active_item_parent_page.getAttribute('data-page-id');

            }

        } else {
            mw.$('#pages_edit_container').removeAttr('data-parent-category-id');

        }
        mw_clear_edit_module_attrs()

        if (active_item_is_page != undefined) {
            mw.$('#pages_edit_container').attr('data-parent-page-id', active_item_is_page);

        } else {
            mw.$('#pages_edit_container').removeAttr('data-parent-page-id');

        }
        mw.$('#pages_edit_container').removeAttr('data-subtype');
        mw.$('#pages_edit_container').removeAttr('is_shop');
        mw.$('#pages_edit_container').attr('data-content-id', $p_id);
        if ($subtype != undefined) {
            if ($subtype == 'product') {
                mw.$('#pages_edit_container').attr('is_shop', 'y');
            }

            mw.$('#pages_edit_container').attr('subtype', $subtype);
        } else {
            mw.$('#pages_edit_container').attr('subtype', 'post');
        }
        mw.$('#pages_edit_container').attr('content_type', $type);


        mw.$(".mw_edit_page_right").css("overflow", "hidden");
        edit_load('content/edit');
    }
    function mw_select_post_for_editing($p_id, $subtype) {

        var active_item = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').first();
        var active_item_is_page = active_item.attr('data-page-id');

        mw.$('#pages_edit_container')
            .removeAttr('data-parent-category-id')
            .removeAttr('data-category-id')
            .removeAttr('data-category-id')
            .removeAttr('data-category-id')
            .removeAttr('content-id')
            .removeAttr('content-id')
            .removeAttr('content-id')
            .removeAttr('subtype')
            .removeAttr('content_type_filter')
            .removeAttr('subtype_filter')
            .removeAttr('subtype_value')
            .removeAttr('data-page-id');


        mw.$('.mw-admin-go-live-now-btn').attr('content-id', $p_id);


        var active_item_is_category = active_item.attr('data-category-id');
        if (active_item_is_category != undefined) {

            mw.$('#pages_edit_container').attr('data-parent-category-id', active_item_is_category);

            var active_bg = document.querySelector('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg');

            var active_item_parent_page = mw.tools.firstParentWithClass(active_bg, 'have_category');

            if (active_item_parent_page == false) {
                var active_item_parent_page = mw.tools.firstParentWithClass(active_bg, 'is_page');

            }

            if (active_item_parent_page == false) {
                var active_item_parent_page = mw.tools.firstParentWithClass(active_bg, 'pages_tree_item');

            }


            if (active_item_parent_page != false) {
                var active_item_is_page = active_item_parent_page.getAttribute('data-page-id');

            }

        } else {
            mw.$('#pages_edit_container').removeAttr('data-parent-category-id');

        }


        if (active_item_is_page != undefined) {
            mw.$('#pages_edit_container').attr('data-parent-page-id', active_item_is_page);

        } else {
            mw.$('#pages_edit_container').removeAttr('data-parent-page-id');

        }
        mw.$('#pages_edit_container').removeAttr('data-subtype');
        mw.$('#pages_edit_container').removeAttr('is_shop');
        mw.$('#pages_edit_container').attr('data-content-id', $p_id);
        if ($subtype != undefined) {
            if ($subtype == 'product') {
                mw.$('#pages_edit_container').attr('is_shop', 'y');
            }

            mw.$('#pages_edit_container').attr('subtype', $subtype);
        } else {
            mw.$('#pages_edit_container').attr('subtype', 'post');
        }
        mw.$(".mw_edit_page_right").css("overflow", "hidden");
        edit_load('content/edit');
    }

    function mw_add_product() {
        mw_select_post_for_editing(0, 'product')
    }
</script>

<?php
$tree_url_endpoint = api_url('content/get_admin_js_tree_json');

$pages_container_params_str = " is_shop='n' ";
$pages_container_params_str = "   ";
if (isset($is_shop)) {
    $pages_container_params_str = " is_shop='{$is_shop}' ";
    $tree_url_endpoint = api_url('content/get_admin_js_tree_json?is_shop=1');

} elseif (isset($params['is_shop'])) {
    $pages_container_params_str = " is_shop='" . $params['is_shop'] . "' ";
    $tree_url_endpoint = api_url('content/get_admin_js_tree_json?is_shop=1');

} elseif ($action == 'products') {
    $pages_container_params_str = " is_shop='y' ";
    $tree_url_endpoint = api_url('content/get_admin_js_tree_json?is_shop=1');

}
if ($action == 'posts') {
    $pages_container_params_str = " is_shop='n'  skip-static-pages='true' ";
    $tree_url_endpoint = api_url('content/get_admin_js_tree_json?is_shop=0');

} elseif ($action == 'pages') {
    $pages_container_params_str = " content_type='page'  '   ";
} elseif ($action == 'categories') {
    $pages_container_params_str = " manage_categories='yes'    ";
}

?>

<?php if ($action == 'settings'): ?>


    <module type="settings/admin" group="website_group/index"/>




    <?php return; ?>
<?php endif ?>

<div id="edit-content-row">
    <?php if ($action != 'categories'): ?>

        <script>
            $(document).ready(function () {
                $('body > #mw-admin-container > .main').addClass('show-sidebar-tree');
                $(".js-tree").prependTo("body > #mw-admin-container .main.container");
                $(".js-tree").before($("body .main.container aside"));
            });
        </script>

        <div class="js-tree tree">
            <div class="tree-column-holder">

                <div class="fixed-side-column">
                    <div class="tree-show-hide-nav">

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input js-open-close-all-tree-elements" id="open-close-all-tree-elements" value="1"/>
                                <label class="custom-control-label d-flex align-items-center" style="cursor:pointer" for="open-close-all-tree-elements"><small class="text-muted"><?php _e("Open"); ?> / <?php _e("Close"); ?></small></label>
                            </div>
                        </div>

                        <script>
                            $(document).ready(function () {
                                $('.js-open-close-all-tree-elements').on('change', function () {
                                    if ($(this).is(':checked') == '1') {
                                        pagesTree.openAll();
                                    } else {
                                        pagesTree.closeAll();
                                    }
                                });
                            });
                        </script>

                        <div class="input-group mb-0 prepend-transparent">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white px-2"><i class="mdi mdi-magnify"></i></span>
                            </div>
                            <input type="text" class="form-control form-control-sm" aria-label="Search" id="main-tree-search" placeholder="<?php _e('Search'); ?>">
                        </div>
                    </div>

                    <div class="fixed-side-column-container mw-tree" id="pages_tree_container_<?php print $my_tree_id; ?>">
                        <script>
                            var pagesTree;

                            var pagesTreeRefresh = function () {

                                var request = new XMLHttpRequest();
                                request.open('GET', '<?php print $tree_url_endpoint; ?>', true);
                                request.send();
                                request.onload = function() {
                                    if (request.status >= 200 && request.status < 400) {

                                        var data = JSON.parse(request.responseText);
                                        if(!data || !data.length){
                                            data = [];
                                        }

                                            var treeTail = [
                                                {
                                                    title: '<?php _e("Trash") ?>',
                                                    icon: 'mdi mdi-delete',
                                                    action: function () {
                                                        mw.url.windowHashParam('action', 'trash');
                                                    }
                                                }
                                            ];

                                            pagesTree = new mw.tree({
                                                data: data,
                                                element: $("#pages_tree_container_<?php print $my_tree_id; ?>")[0],
                                                resizable: true,
                                                sortable: false,
                                                selectable: false,
                                                toggleSelect: false,
                                                id: 'admin-main-tree',
                                                append: treeTail,
                                                contextMenu: [
                                                    {
                                                        title: 'Edit',
                                                        icon: 'mdi mdi-pencil',
                                                        action: function (element, data, menuitem) {
                                                            if (data.type == 'category') {
                                                                window.location  = "<?php print admin_url() ?>category/" + data.id + "/edit";

                                                            } else if (data.type == 'page') {
                                                                window.location  = "<?php print admin_url() ?>page/" + data.id + "/edit";

                                                            }
                                                            else {
                                                                mw.url.windowHashParam("action", "edit" + data.type + ":" + data.id);

                                                            }

                                                        }
                                                    },
                                                    {
                                                        title: 'Move to trash',
                                                        icon: 'mdi mdi-close',
                                                        action: function (element, data, menuitem) {
                                                            if (data.type === 'category') {
                                                                mw.content.deleteCategory(data.id, function () {

                                                                    $('#' + pagesTree.options.id + '-' + data.type + '-' + data.id).fadeOut(function () {
                                                                        if (window.pagesTreeRefresh) {
                                                                            pagesTreeRefresh()
                                                                        }

                                                                    })
                                                                });
                                                            }
                                                            else {
                                                                mw.content.deleteContent(data.id, function () {
                                                                    $('#' + pagesTree.options.id + '-' + data.type + '-' + data.id, pagesTree.list).fadeOut(function () {
                                                                        if (window.pagesTreeRefresh) {
                                                                            pagesTreeRefresh()
                                                                        }
                                                                        ;
                                                                    })
                                                                });
                                                            }
                                                        }
                                                    }
                                                ]
                                            });
                                            mw.adminPagesTree = pagesTree;

                                            $(pagesTree).on("orderChange", function (e, item, data, old, local) {
                                                var obj = {ids: local};
                                                var url;
                                                if (item.type === 'category') {
                                                    url = "<?php print api_link('category/reorder'); ?>";
                                                }
                                                else {
                                                    url = "<?php print api_link('content/reorder'); ?>";
                                                }
                                                $.post(url, obj, function () {
                                                    mw.reload_module('#mw_page_layout_preview');
                                                    mw.notification.success('<?php _ejs("Changes are saved"); ?>')
                                                });
                                            });
                                            $(pagesTree).on("ready", function () {
                                                $('#main-tree-search').on('input', function () {
                                                    var val = this.value.toLowerCase().trim();
                                                    if (!val) {
                                                        pagesTree.showAll();
                                                    }
                                                    else {
                                                        pagesTree.options.data.forEach(function (item) {
                                                            if (item.title.toLowerCase().indexOf(val) === -1) {
                                                                pagesTree.hide(item);
                                                            }
                                                            else {
                                                                pagesTree.show(item);
                                                            }
                                                        });
                                                    }
                                                })

                                                $('.mw-tree-item-title', pagesTree.list).on('click', function () {
                                                    $('li.selected', pagesTree.list).not(mw.tools.firstParentWithTag(this, 'li')).each(function () {
                                                        pagesTree.unselect(this)
                                                    });
                                                    var li = mw.tools.firstParentWithTag(this, 'li'),
                                                        data = li._data,
                                                        action;
                                                    if (!$(li).hasClass('mw-tree-additional-item')) {
                                                        if (data.type === 'page') {
                                                            action = 'editpage';
                                                        }
                                                        if (data.subtype === 'dynamic' || data.subtype === 'shop') {
                                                            action = 'showposts';
                                                        }
                                                        if (data.type === 'category') {
                                                            action = 'showpostscat';
                                                        }
                                                        mw.url.windowHashParam("action", action + ":" + data.id);
                                                    }
                                                });
                                                mainTreeSetActiveItems()

                                                $("#edit-content-row .tree-column").resizable({
                                                    handles: "e",
                                                    resize: function (e, ui) {
                                                        var root = mw.$(ui.element);
                                                        mw.$('.fixed-side-column', root).width(root.width())
                                                    },
                                                    minWidth: 200
                                                })
                                            })
                                    }
                                };




                            };

                            if (window.pagesTreeRefresh) {
                                pagesTreeRefresh()
                            }


                        </script>

                        <?php event_trigger('admin_content_after_website_tree', $params); ?>

                        <?php $custom_title_ui = mw()->module_manager->ui('content.manager.tree.after'); ?>
                        <?php if (!empty($custom_title_ui)): ?>
                            <?php foreach ($custom_title_ui as $item): ?>
                                <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                                <?php $width = (isset($item['width'])) ? ($item['width']) : false; ?>
                                <div class="tree-column-holder-custom-item <?php print $class; ?>" <?php if ($width): ?> style="width: <?php print $width ?>;"  <?php endif; ?> title="<?php print $title; ?>"><?php print $html; ?></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>

    <script>
        $(window).on('load', function () {
            if (!mw.url.windowHashParam("action")) {
                edit_load('content/manager');
            }
            mw.on.hashParam('view', function () {
                edit_load('content/manager');
            })
        });
    </script>

    <div id="pages_edit_container" <?php print $pages_container_params_str; ?>></div>
</div>
