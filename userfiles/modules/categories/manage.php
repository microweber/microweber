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
            <div id="mw-admin-categories-tree-manager"></div>
            <script>

                      mw.admin.tree(document.getElementById('mw-admin-categories-tree-manager'), {
                        options: {
                            sortable: '>.type-category',
                            sortableHandle: '.mw-tree-item-content',
                            selectable: false,
                            singleSelect: true,
                            saveState: true,
                            searchInput: true,
                            skin: 'category-manager',
                            contextMenu: [
                                {
                                    title: mw.lang('Edit'),
                                    icon: 'mdi mdi-pencil',
                                    action: function (element, data, menuitem) {
                                        if (data.type === 'category') {
                                            top.location.href  = "<?php print admin_url() ?>category/" + data.id + "/edit";
                                        } else if (data.type === 'page') {
                                            top.location.href  = "<?php print admin_url() ?>page/" + data.id + "/edit";
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
                        },
                        params: {
                            no_limit: true,
                            <?php if(isset($params['is_shop'])): ?>
                                is_shop: 1,
                            <?php endif; ?>
                        }
                    }).then(function (res) {
                        $(res.tree).on('orderChange', function (e, obj){
                            var items = res.tree.getSameLevelObjects(obj).filter(function (obj) {
                                return obj.type === 'category';
                            }).map(function(obj){
                                return obj.id;
                            });
                            $.post("<?php print api_link('category/reorder'); ?>", {ids: items}, function () {
                                mw.notification.success('<?php _e("All changes are saved"); ?>.');
                                mw.parent().trigger('pagesTreeRefresh');
                            });
                        });
                    });
            </script>
        </div>
    </div>
</div>
