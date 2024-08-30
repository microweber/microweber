<script type="text/javascript">
    categorySearch = function (el) {
        var val = el.value.trim().toLowerCase();
        if (!val) {
            $(".mw-ui-category-selector a").show()
        }
        else {
            $(".mw-ui-category-selector a").each(function () {
                var currel = $(this);
                var curr = currel.text().trim().toLowerCase();
                if (curr.indexOf(val) !== -1) {
                    currel.show()
                }
                else {
                    currel.hide()
                }
            })
        }
    }


    mw.live_edit_load_cats_list = function () {
        mw.load_module('categories/manage_sidebar', '#mw_add_cat_live_edit', function () {

        });
    }

    mw.quick_cat_edit = function (id) {
        if (!!id) {
            var modalTitle = '<?php _e('Edit'); ?>';
        } else {
            var modalTitle = '<?php _e('Add'); ?>';
        }


        mw.dialog({
            content: '<div id="mw_admin_edit_category_item_module"></div>',
            title: modalTitle,
            id: 'mw_admin_edit_category_item_popup_modal'
        });

        var params = {}
        params['data-category-id'] = id;
        params['no-toolbar'] = true;
        mw.load_module('categories/edit_category', '#mw_admin_edit_category_item_module', null, params);

    }

    mw.quick_cat_edit_create = function (id) {
        return mw.quick_cat_edit(id);
        <?php if(isset($params['page-id']) and $params['page-id'] != false): ?>
        //mw.$("#mw_edit_category_admin_holder").attr("page-id", '<?php print $params['page-id'] ?>');
        <?php endif; ?>
    }
</script>
<script type="text/javascript">


    mw.on.moduleReload("<?php print $params['id'] ?>", function () {
        mw.manage_cat_sort();
        $(".mw-ui-category-selector a").append('<span class="category-edit-label">' + mw.msg.edit + ' ' + mw.msg.category + '</span>')
    });


    mw.manage_cat_sort = function () {


        mw.$("#<?php print $params['id'] ?>").sortable({
            items: '.category_element',
            axis: 'y',
            handle: 'a',
            update: function () {
                var obj = {ids: []}
                $(this).find('.category_element').each(function () {
                    var id = this.attributes['value'].nodeValue;
                    obj.ids.push(id);
                });
                $.post("<?php print api_link('category/reorder'); ?>", obj, function () {
                    if (self !== parent && !!parent.mw) {
                        mw.parent().reload_module('categories');
                    }
                    mw.top().trigger('pagesTreeRefresh')

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


</script>



<div class="">
    <div class="mw-ui-field-holder add-new-button text-end text-right">
        <a class="btn btn-primary mw-ui-btn-outline mw-ui-btn-rounded m-b-10" href="'javascript:mw.quick_cat_edit(0)"><i class="fa fa-plus-circle"></i> &nbsp;Add New</a>
    </div>

    <div>



        <div class="mw-ui-category-selector mw-ui-manage-list m-0" id="mw-ui-category-selector-manage">
            <?php
            $field_name = "categories";
            $selected = 0;
            $tree = array();
            $tree['ul_class'] = 'pages_tree cat_tree_live_edit';
            $tree['li_class'] = 'sub-nav';
            $tree['rel_type'] = 'content';

            if (isset($params['page-id']) and $params['page-id'] != false) {
                $tree['rel_id'] = intval($params['page-id']);
            }

            $tree['link'] = "<a href='javascript:mw.quick_cat_edit({id})'><span class='mw-icon-category'></span>&nbsp;{title}</a>";
            category_tree($tree);
            ?>
        </div>



    </div>


</div>
<div id="mw_edit_category_admin_holder"></div>
