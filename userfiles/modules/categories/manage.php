<div class="col-xl-9 mx-auto mw-module-category-manager admin-side-content">
    <div class="card-body mb-3">
        <div class="card-header d-flex align-items-center justify-content-between mb-5">
            <h1 class="main-pages-title mb-0"><?php _e("Categories"); ?></h1>


            <div class="ms-4 input-icon col-xl-5 col-sm-5 col-12  ">
                <input type="text" value="" class="form-control" placeholder="Search…" id="category-tree-search">
                <span class="input-icon-addon">
                  <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                </span>
            </div>

            <div class="js-hide-when-no-items">
                <div class="d-flex align-items-center">
                    <?php if (user_can_access('module.categories.edit')): ?>
                        <?php if (isset($params['is_shop']) && $params['is_shop'] == 1): ?>
                            <a href="<?php echo route('admin.shop.category.create'); ?>" class="btn btn-dark"><?php _e("New Category"); ?></a>
                        <?php else: ?>
                            <a href="<?php echo route('admin.category.create'); ?>" class="btn btn-dark"> <?php _e("New Category"); ?></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class=" ">

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
                    searchInput: document.getElementById('category-tree-search'),
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
                    searchInput: document.getElementById('category-tree-search'),
                    skin: 'category-manager',
                    contextMenu: [

                        {
                            title: '<svg class="me-1 ms-0" fill="currentColor" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="Edit" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="18px" viewBox="0 0 24 24" width="18px"><g><rect fill="none" height="24" width="24"></rect></g><g><g><g><path d="M3,21l3.75,0L17.81,9.94l-3.75-3.75L3,17.25L3,21z M5,18.08l9.06-9.06l0.92,0.92L5.92,19L5,19L5,18.08z"></path></g><g><path d="M18.37,3.29c-0.39-0.39-1.02-0.39-1.41,0l-1.83,1.83l3.75,3.75l1.83-1.83c0.39-0.39,0.39-1.02,0-1.41L18.37,3.29z"></path></g></g></g></svg>',

                            icon: 'd-none',
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
                            className: ''
                        },
                        {
                            title: '<svg class=" me-1 ms-0 text-danger" fill="currentColor" data-bs-toggle="tooltip" aria-label="Delete" data-bs-original-title="Delete" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>',
                            icon: 'd-none',
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
                            className: ''
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
                            <?php if(isset($params['is_shop']) && $params['is_shop'] == 1): ?>
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
