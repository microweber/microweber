<div class="mw-module-category-manager admin-side-content">
    <div class="card style-1 mb-3">
        <div class="card-header">
            <h5><i class="mdi mdi-folder text-primary mr-3"></i> <strong><?php _e("Categories"); ?></strong></h5>
            <div class="js-hide-when-no-items">
                <div class="d-flex">
                    <?php if (user_can_access('module.categories.edit')): ?>
                        <?php if (isset($params['is_shop'])): ?>
                            <a href="<?php echo route('admin.shop.category.create'); ?>" class="btn btn-primary btn-sm mr-2"><i class="mdi mdi-plus"></i> <?php _e("New category"); ?></a>
                        <?php else: ?>
                            <a href="<?php echo route('admin.category.create'); ?>" class="btn btn-primary btn-sm mr-2"><i class="mdi mdi-plus"></i> <?php _e("New category"); ?></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card-body pt-3">

            <?php if(!isset($params['show_add_post_to_category_button'])): ?>
                <div class="js-hide-when-no-items-selected mb-3" style="display:none;">
                    <button type="button" class="btn btn-outline-danger js-delete-selected-categories">
                      <i class="fa fa-trash"></i>
                        <?php _e('Delete '); ?>&nbsp;<span class="js-count-selected-categories"></span>
                    </button>
               <!--     <button type="button" class="btn btn-outline-info"><?php /*_e('Publish'); */?></button>
                    <button type="button" class="btn btn-outline-primary"><?php /*_e('Unpublish'); */?></button>-->
                </div>
            <?php endif; ?>


   <!--        <button type="button" class="btn btn-outline-primary js-show-checkboxes-on-tree">
               Bulk Actions
           </button>
-->
            <div id="mw-admin-categories-tree-manager"></div>
            <script>

                selectedPages = [];
                selectedCategories = [];
               // bulkOptionsOpened = true;

                // $(document).ready(function() {
                //     $('.js-show-checkboxes-on-tree').click(function() {
                //
                //         if (!bulkOptionsOpened) {
                //             bulkOptionsOpened = true;
                //
                //             $('#mw-admin-categories-tree-manager').empty();
                //             treeDataOpts.selectable = true;
                //             treeDataOpts.rowSelect = false;
                //             renderCategoryTree();
                //             $('.js-show-checkboxes-on-tree').attr('bulk-opened', '0');
                //
                //         } else {
                //             bulkOptionsOpened = false;
                //
                //             $('#mw-admin-categories-tree-manager').empty();
                //             treeDataOpts.selectable = false;
                //             renderCategoryTree();
                //             $('.js-show-checkboxes-on-tree').attr('bulk-opened', '1');
                //         }
                //
                //     });
                // });

                $('.js-delete-selected-categories').click(function() {

                    if (confirm('<?php echo _ejs('Are you sure you want to delete the selected categories?'); ?>')) {
                        $.ajax({
                            url: route('api.category.delete-bulk'),
                            type: 'DELETE',
                            data: {ids: selectedCategories},
                            success: function (data) {
                                mw.reload_module('categories/manage');
                                mw.notification.success('<?php _ejs("Categories are deleted."); ?>.');
                                mw.parent().trigger('pagesTreeRefresh');
                            }
                        });
                    }

                });

                <?php if(isset($params['show_add_post_to_category_button'])): ?>
                // this is for the post manage categories
                treeDataOpts = {

                    sortable: '>.type-category',
                    sortableHandle: '.mw-tree-item-content',
                    selectable: false,
                    singleSelect: true,
                    saveState: false,
                    searchInput: true,
                    skin: 'category-manager',
                    contextMenu: [

                        {
                            title: mw.lang('Select'),
                            icon: 'mdi mdi-check',
                            action: function (element, data, menuitem) {
                                mw.top().trigger("mwSelectToAddCategoryToContent", data.id);
                            },
                            filter: function (obj, node) {
                                return obj.type === 'category';
                            },

                            className: 'btn btn-outline-success btn-sm  '
                        }


                    ]
                };
                <?php else: ?>

                // this is for the main  manage categories page

                treeDataOpts = {
                    cantSelectTypes: ['page'],
                    sortable: '>.type-category',
                    sortableHandle: '.mw-tree-item-content',
                    selectable: true,
                    rowSelect : false,
                    singleSelect: false,
                    multiPageSelect: false,
                    allowPageSelect: false,
                    saveState: false,
                    searchInput: true,
                    skin: 'category-manager',
                    contextMenu: [

                        {
                            title: mw.lang('Edit'),
                            icon: 'mdi mdi-pencil',
                            action: function (element, data, menuitem) {
                                if (data.type === 'category') {
                                    self.location.href = "<?php print admin_url() ?>category/" + data.id + "/edit";
                                } else if (data.type === 'page') {
                                    self.location.href = "<?php print admin_url() ?>page/" + data.id + "/edit";
                                }
                            },
                            filter: function (obj, node) {
                                return obj.type === 'category';
                            },
                            className: 'btn btn-outline-primary btn-sm'
                        },
                        {
                            title: mw.lang('Delete'),
                            icon: 'mdi mdi-delete',
                            action: function (element, data, menuitem) {
                                if (data.type === 'category') {
                                    mw.content.deleteCategory(data.id, function () {
                                        $(element).fadeOut(function () {
                                            $(element).remove()
                                        })
                                    }, false);
                                }
                            },
                            filter: function (obj, node) {
                                return obj.type === 'category';
                            },
                            className: 'btn btn-outline-danger btn-sm'
                        }


                    ]
                };
                <?php endif; ?>

                function renderCategoryTree() {

                    categoryTree = mw.admin.tree(document.getElementById('mw-admin-categories-tree-manager'), {
                        options: treeDataOpts,
                        params: {
                            only_categories: 1,
                            no_limit: true,
                            <?php if(isset($params['is_shop'])): ?>
                            is_shop: 1,
                            <?php endif; ?>
                        }
                    }).then(function (res) {
                        res.tree.openAll();
                        $(res.tree).on('orderChange', function (e, obj) {
                            var items = res.tree.getSameLevelObjects(obj).filter(function (obj) {
                                return obj.type === 'category';
                            }).map(function (obj) {
                                return obj.id;
                            });
                            $.post("<?php print api_link('category/reorder'); ?>", {ids: items}, function () {
                                mw.notification.success('<?php _ejs("All changes are saved"); ?>.');
                                mw.parent().trigger('pagesTreeRefresh');
                            });
                        });
                        $(res.tree).on("selectionChange", function () {

                            res.tree.getSelected().length === 0 ? $('.js-hide-when-no-items-selected').hide() : $('.js-hide-when-no-items-selected').show();

                            if (res.tree.getSelected().length == 1) {
                                $('.js-count-selected-categories').html(res.tree.getSelected().length + ' <?php _ejs('category'); ?>');
                            }
                            if (res.tree.getSelected().length > 1) {
                                $('.js-count-selected-categories').html(res.tree.getSelected().length + ' <?php _ejs('categories'); ?>');
                            }

                            selectedCategories = [];
                            $.each(res.tree.getSelected(), function (key, item) {
                                if (item.type == 'category') {
                                    selectedCategories.push(item.id);
                                }
                                if (item.type == 'page') {
                                    selectedPages.push(item.id);
                                }
                            });

                        });
                    });
                }

                renderCategoryTree();

            </script>
        </div>
    </div>
</div>
