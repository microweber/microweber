<?php
only_admin_access();
$action = url_param('action');
$is_in_shop = false;
$rand = uniqid(); ?>
<?php $my_tree_id = crc32(mw()->url_manager->string()); ?>
<?php $active_content_id = '';
if (isset($_REQUEST['edit_content']) and $_REQUEST['edit_content'] != 0) {
    $active_content_id = $_REQUEST['edit_content'];
}

?>

<script type="text/javascript">

    mw.on.hashParam("search", function () {
        mw.$('#pages_edit_container').attr("data-type", 'content/manager');
        var dis = this;
        if (dis !== '') {
            mw.$('#pages_edit_container').attr("data-keyword", dis);
            mw.url.windowDeleteHashParam('pg')
            mw.$('#pages_edit_container').attr("data-page-number", 1);
        }
        else {
            mw.$('#pages_edit_container').removeAttr("data-keyword");
            mw.url.windowDeleteHashParam('search')
        }
        mw.reload_module('#pages_edit_container');
    });
    mw.on.moduleReload('#<?php print $params['id'] ?>');

</script>
<script type="text/javascript">


    <?php   include_once(mw_includes_path() . 'api/treerenderer.php');
    ?>


    $(document).ready(function () {
        mw.treeRenderer.appendUI();
        mw.treeRenderer.appendUI('.page_posts_list_tree');
        mw.on.hashParam("page-posts", function () {
            mw_set_edit_posts(this);
        });
        mw.on.moduleReload("pages_tree_toolbar", function (e) {
            mw.treeRenderer.appendUI();
            mw_make_pages_tree_sortable()
        });
        mw.on.moduleReload("pages_edit_container", function () {
            mw.treeRenderer.appendUI("#pages_edit_container .page_posts_list_tree");
        });
        $(mwd.body).ajaxStop(function () {
            $(this).removeClass("loading");
        });


        mw_make_pages_tree_sortable();


    });


    mw.contentAction = {
        manage: function (type, id) {

            var id = id || 0;
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
        if(!active_item_is_parent){
            active_item_is_parent  = $p_id;
        }
        var active_item = $('#pages_tree_container_<?php print $my_tree_id; ?> .pages_tree_item.item_'+active_item_is_parent).first();


        var active_item_is_category = active_item.attr('data-category-id');


        active_item.addClass('active-bg');

        mw.$('#pages_edit_container').removeAttr('data-parent-page-id');

        mw.$('.mw-admin-go-live-now-btn').attr('content-id', active_item_is_parent);
        mw.$('#pages_edit_container').attr('content_type', 'page');
        mw.$('#pages_edit_container').removeAttr('subtype');
        mw.$('#pages_edit_container').removeAttr('content_type_filter');
        mw.$('#pages_edit_container').removeAttr('subtype_filter');
        mw.$('#pages_edit_container').removeAttr('data-parent-category-id');
        mw.$('#pages_edit_container').removeAttr('categories_active_ids');
        mw.$('#pages_edit_container').removeAttr('data-categories_active_ids');
        mw.$('#pages_edit_container').removeAttr('data-active_ids');
        mw.$('#pages_edit_container').removeAttr('active_ids');





        if (active_item_is_category != undefined) {
            //   mw.$('#pages_edit_container').attr('data-parent-category-id', active_item_is_category);
            var active_item_parent_page = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').parents('.have_category').first();
            if (active_item_parent_page != undefined) {
                var active_item_is_page = active_item_parent_page.attr('data-page-id');
            }
            else {
                var active_item_parent_page = $('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg').parents('.is_page').first();
                if (active_item_parent_page != undefined) {
                    var active_item_is_page = active_item_parent_page.attr('data-page-id');
                }
            }
        }
        else {
            mw.$('#pages_edit_container').removeAttr('data-parent-category-id');
        }
        if (active_item_is_parent != undefined) {
           // mw.$(".pages_tree_item.active-bg").children().first().removeClass('active-bg');
          //  mw.$(".pages_tree_item.active-bg").removeClass('active-bg');
            mw.$(".is_page.pages_tree_item.item_" + active_item_is_parent).addClass('active-bg')
            mw.$(".is_page.pages_tree_item.item_" + active_item_is_parent).children().first().addClass('active')
            mw.$('#pages_edit_container').attr('data-parent-page-id', active_item_is_parent);
        }
        else {
            mw.$('#pages_edit_container').removeAttr('data-parent-page-id');
        }
        mw.$('#pages_edit_container').attr('data-page-id', $p_id);
        mw.$('#pages_edit_container').attr('data-type', 'content/edit');
        mw.$('#pages_edit_container').removeAttr('data-subtype');
        mw.$('#pages_edit_container').removeAttr('data-content-id');
        mw.$('#pages_edit_container').removeAttr('content-id');


        mw.$(".mw_edit_page_right").css("overflow", "hidden");
        edit_load('content/edit');
    }

    mw.on.hashParam("parent-page", function () {
        var act = mw.url.windowHashParam("action");
        if (act == 'new:page') {
            mw.contentAction.create('page');
        }
    });


    mw.on.hashParam("action", function () {

        if (this == false) {
            mw.$('#pages_edit_container').removeAttr('page-id');
            mw_clear_edit_module_attrs();
            mw.$(".fix-tabs").removeClass('fix-tabs');
        }


        mw.admin.CategoryTreeWidth(this);

        $(mwd.body).addClass("loading");
        window.scrollTo(0, 0);
        mw.$("#pages_edit_container").stop();
        mw.$('#pages_edit_container').removeAttr('mw_select_trash');
        mw.$(".mw_edit_page_right").css("overflow", "hidden");

        if (this == false) {
            mw.$(".mw_edit_page_right").css("overflow", "hidden");
            edit_load('content/manager');
            return false;
        }
        var arr = this.split(":");
        $(mwd.body).removeClass("action-Array");
        var cat_id = mw.url.windowHashParam("category_id");
        if (typeof cat_id != 'undefined') {
            mw.$('#pages_edit_container').attr('category_id', cat_id);
        }
        else {
            mw.$('#pages_edit_container').removeAttr('data-active-item');
        }
        if (arr[0] === 'new') {
            mw.contentAction.create(arr[1]);
        }
        else {


            mw.$(".active-bg").removeClass('active-bg');
            mw.$(".mw_action_nav").removeClass("not-active");
            var active_item = mw.$(".item_" + arr[1]);
            if (arr[0] == 'showposts') {
                var active_item = mw.$(".pages_tree_item.item_" + arr[1]);
            }
            else if (arr[0] == 'showpostscat') {
                var active_item = mw.$(".category_element.item_" + arr[1]);
            }
            //active_item.addClass('active-bg');
            //active_item.parents("li").addClass('active');
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
            }  else if (arr[0] === 'addsubcategory') {
                mw_select_add_sub_category(arr[1]);
            }
        }

    });


    edit_load = function (module) {
        var n = mw.url.getHashParams(window.location.hash)['new_content'];
        if (n == 'true') {
            var slide = false;
            mw.url.windowDeleteHashParam('new_content');
        }
        else {
            var slide = true;
        }
        var action = mw.url.windowHashParam('action');
        var holder = $('#pages_edit_container');

        mw.tools.loading('.fade-window', !!action);
        var time = !action ? 300 : 0;
        if(!action){
            mw.$('.fade-window').removeClass('active');
        }
            setTimeout(function () {
                mw.load_module(module, holder, function(){
                    mw.tools.loading('.fade-window', false);
                    mw.$('.fade-window').addClass('active')
                });
            }, time)



    }

    function mw_select_category_for_editing($p_id) {

        mw_clear_edit_module_attrs();

        mw.$(".pages_tree_item.active-bg").removeClass('active-bg');
        mw.$(".category_element.active-bg").removeClass('active-bg');



        var active_item = mw.$(".category_element.item_" + $p_id);
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
            var active_item = mw.$(".pages_tree_item.item_" + in_page);
            active_item.addClass('active-bg');
        }

        if (in_page != undefined && is_cat != undefined) {
            cont.attr('data-category-id', in_page);
            var active_item = mw.$(".category_element.item_" + in_page);
            active_item.addClass('active-bg');
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
            .removeAttr('category_id')
            .removeAttr('category_id')
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

            var active_bg = mwd.querySelector('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg');

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
            .removeAttr('category_id')
            .removeAttr('category_id')
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

            var active_bg = mwd.querySelector('#pages_tree_container_<?php print $my_tree_id; ?> .active-bg');

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

    function mw_make_pages_tree_sortable() {
        $("#pages_tree_toolbar .pages_tree").sortable({
            axis: 'y',
            items: '>.pages_tree_item',
            distance: 35,
            containment: "parent",
            update: function () {
                var obj = {ids: []}
                $(this).find('.pages_tree_item').each(function () {
                    var id = this.attributes['data-page-id'].nodeValue;
                    obj.ids.push(id);
                });
                $.post("<?php print api_link('content/reorder'); ?>", obj, function () {
                    mw.reload_module('#mw_page_layout_preview');
                });
            },
            start: function (a, ui) {

            },
            scroll: false
        });

        $("#pages_tree_toolbar .pages_tree .have_category").sortable({
            axis: 'y',
            items: '.category_element',
            distance: 35,
            update: function () {
                var obj = {ids: []}
                mw.$('.category_element', this).each(function () {
                    var id = this.attributes['data-category-id'].nodeValue;
                    obj.ids.push(id);
                });
                $.post("<?php print api_link('category/reorder'); ?>", obj, function () {
                    mw.reload_module('#mw_page_layout_preview');
                });
            },
            start: function (a, ui) {

            },
            scroll: false
        });
    }


</script>

<?php
$pages_container_params_str = " is_shop='n' ";
$pages_container_params_str = "   ";
if (isset($is_shop)) {
    $pages_container_params_str = " is_shop='{$is_shop}' ";
} elseif (isset($params['is_shop'])) {
    $pages_container_params_str = " is_shop='" . $params['is_shop'] . "' ";
} elseif ($action == 'products') {
    $pages_container_params_str = " is_shop='y' ";
}
if ($action == 'posts') {
    $pages_container_params_str = " is_shop='n'  skip-static-pages='true' ";
} elseif ($action == 'pages') {
    $pages_container_params_str = " content_type='page'  '   ";
} elseif ($action == 'categories') {
    $pages_container_params_str = " manage_categories='yes'    ";
}

?>


<div class="mw-ui-row-nodrop" id="edit-content-row">
    <?php if ($action != 'categories'): ?>

        <div class="mw-ui-col tree-column" <?php if ($action == 'posts'): ?><?php endif ?>>
            <div class="tree-column-holder">

                 <span class="mw-icon-app-more mobile-tree-menu"></span>
                <div class="fixed-side-column scroll-height-exception-master">

                    
                    <div class="fixed-side-column-container mw-tree" id="pages_tree_container_<?php print $my_tree_id; ?>">
                        <?php if ($action == 'pages'): ?>
                            <module data-type="pages" template="admin" active_ids="<?php print $active_content_id; ?>"
                                    active_class="active-bg" id="pages_tree_toolbar" view="admin_tree" home_first="true"/>
                        <?php elseif ($action == 'categories'): ?>

                            <module skip-static-pages="true" data-type="pages" template="admin"
                                    active_ids="<?php print $active_content_id; ?>" active_class="active-bg"
                                    include_categories="true" include_global_categories="true"
                                    id="pages_tree_toolbar" <?php print $pages_container_params_str ?> view="admin_tree"
                                    home_first="true"/>
                        <?php else: ?>
                            <module data-type="pages" template="admin" active_ids="<?php print $active_content_id; ?>"
                                    active_class="active-bg" include_categories="true" include_global_categories="true"
                                    id="pages_tree_toolbar" <?php print $pages_container_params_str ?> view="admin_tree"
                                    home_first="true"/>
                        <?php endif ?>
                        <?php event_trigger('admin_content_after_website_tree', $params); ?>






                        <?php $custom_title_ui = mw()->modules->ui('content.manager.tree.after'); ?>
                        <?php if (!empty($custom_title_ui)): ?>
                            <?php foreach ($custom_title_ui as $item): ?>
                                <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                                <?php $width = (isset($item['width'])) ? ($item['width']) : false; ?>
                                <div class="tree-column-holder-custom-item <?php print $class; ?>"
                                    <?php if ($width): ?> style="width: <?php print $width ?>;"  <?php endif; ?>
                                     title="<?php print $title; ?>"><?php print $html; ?></div>
                            <?php endforeach; ?>
                        <?php endif; ?>


                    </div>


                    <div class="tree-show-hide-nav scroll-height-exception">

                        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small"
                           onclick="mw.tools.tree.openAll(mwd.getElementById('pages_tree_container_<?php print $my_tree_id; ?>'));"><?php _e("Open All"); ?></a> <a class="mw-ui-btn mw-ui-btn-small" href="javascript:;"
                                                                                                                                                                    onclick="mw.tools.tree.closeAll(mwd.getElementById('pages_tree_container_<?php print $my_tree_id; ?>'));"><?php _e("Close All"); ?></a></div>


                </div>


            </div>
        </div>
    <?php endif ?>
    <div class="mw-ui-col main-content-column">
        <div class="mw-ui-col-container">
            <script>
                $(window).bind('load', function () {
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
    </div>
</div>
 