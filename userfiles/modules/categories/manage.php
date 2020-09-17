<div class="mw-module-category-manager admin-side-content">
    <div class="card style-1 mb-3">

        <div class="card-header">
            <h5><i class="mdi mdi-folder text-primary mr-3"></i> <strong><?php _e("Categories"); ?></strong></h5>
            <div class="d-flex">

                <?php
                if (user_can_access('module.categories.edit')):
                    ?>
                    <button type="button" onclick="mw.quick_cat_edit_create(0);" class="btn btn-primary btn-sm mr-2"><i class="mdi mdi-plus"></i> <?php _e("New category"); ?></button>
                <?php endif; ?>

                <div class="form-group mb-0">
                    <div class="input-group mb-0 prepend-transparent">
                        <div class="input-group-prepend">
                            <span class="input-group-text px-1"><i class="mdi mdi-magnify"></i></span>
                        </div>

                        <input type="text" class="form-control form-control-sm" aria-label="Search" placeholder="<?php _e('Search') ?>" oninput="categorySearch(this)">
                    </div>
                </div>
            </div>
        </div>


        <div class="card-body pt-3">


            <div class="mw-ui-category-selector mw-ui-manage-list m-0" id="mw-ui-category-selector-manage">

                <?php
                $field_name = "categories";
                $selected = 0;
                $mainFilterTree = array();
                $mainFilterTree['ul_class'] = 'mw-ui-category-tree';
                $mainFilterTree['li_class'] = 'sub-nav';
                $mainFilterTree['rel_type'] = 'content';

                if (isset($params['page-id']) and $params['page-id'] != false) {
                    $mainFilterTree['rel_id'] = intval($params['page-id']);
                }

                if (user_can_access('module.categories.edit')) {
                    $mainFilterTree['link'] = "<span class='mw-ui-category-tree-row' onclick='mw.quick_cat_edit({id})'><span class='mdi mdi-folder text-muted mdi-18px mr-2'></span>&nbsp;{title}<span class=\"btn btn-outline-primary btn-sm\"><span class=\"d-none d-md-block\">Edit</span></span></span>";
                } else {
                    $mainFilterTree['link'] = "<span class='mw-ui-category-tree-row'><span class='mdi mdi-folder text-muted mdi-18px mr-2'></span>&nbsp;{title}</span>";
                }
                ?>

                <?php
                $pages_with_cats = get_pages('no_limit=true');
                foreach ($pages_with_cats as $page):
                    $pageTreeFilter = $mainFilterTree;
                    $pageTreeFilter['rel_id'] = $page['id'];
                    ?>

                    <?php
                    $pageTreeFilter['return_data'] = true;
                    $categoryTree = category_tree($pageTreeFilter);
                    if (empty($categoryTree)) {
                        continue;
                    }
                    ?>
                    <div class="card border-0">
                        <div class="card-header pl-0">
                            <h5><i class="mdi mdi-post-outline text-primary mr-3"></i> <?php echo $page['title']; ?></h5>
                        </div>

                        <div class="card-body py-2">
                            <?php echo $categoryTree; ?>
                        </div>
                    </div>

                <?php endforeach; ?>

                <?php
                $mainFilterTree['return_data'] = true;
                $mainFilterTree['content_id'] = false;
                $otherCategories = category_tree($mainFilterTree);
                ?>

                <?php if (!empty($otherCategories)): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="card-header">
                                <h5>Other</h5>
                            </div>
                            <?php echo $otherCategories; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
            <script>
                mw.require('block-edit.js');

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
                    mw.load_module('categories/manage', '#mw_add_cat_live_edit', function () {

                    });
                }
                mw.quick_cat_edit = function (id) {
                    if (!!id) {
                        var modalTitle = '<?php _e('Edit category'); ?>';
                    } else {
                        var modalTitle = '<?php _e('Add category'); ?>';
                    }

                    /*mw_admin_edit_category_item_module_opened = mw.modal({
                     content: '<div id="mw_admin_edit_category_item_module"></div>',
                     title: modalTitle,
                     id: 'mw_admin_edit_category_item_popup_modal'
                     });*/


                    var params = {}
                    params['data-category-id'] = id;
                    params['no-toolbar'] = true;
                    /*mw.load_module('categories/edit_category', '#mw_admin_edit_category_item_module', null, params);*/

                    // mw.categoryEditor.moduleEdit('categories/edit_category', params)

                    mw.url.windowHashParam('action', 'editcategory:' + id)
                }

                mw.quick_cat_edit_create = mw.quick_cat_edit_create || function (id) {
                        return mw.quick_cat_edit(id);
                    }
                $(document).ready(function () {
                    mw.categoryEditor = new mw.blockEdit({
                        element: '#edit-content-row'
                    })
                })
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
                                    parent.mw.reload_module('categories');
                                }
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
                //mw.manage_cat_sort();

            </script>
        </div>
    </div>
    <div id="mw_edit_category_admin_holder"></div>
</div>
