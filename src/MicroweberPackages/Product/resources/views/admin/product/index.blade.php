<script>
    mw.require('content.js', true);
</script>

<style>
    #js-page-tree{
        position: sticky;
        top: 70px;
        min-height: 200px;
    }
</style>

<div class="main pt-0">
    <div>
        <div class="tree-show-hide-nav">

            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input js-open-close-all-tree-elements" id="open-close-all-tree-elements" value="1"/>
                    <label class="custom-control-label d-flex align-items-center" style="cursor:pointer" for="open-close-all-tree-elements"><small class="text-muted"><?php _e("Open"); ?> / <?php _e("Close"); ?></small></label>
                </div>
            </div>
        </div>
        <div id="js-page-tree"></div>

        <script>
             pagesTree = null;
            (function (){
                var treeNode = document.getElementById('js-page-tree');


                document
                    .querySelector('.js-open-close-all-tree-elements')
                    .addEventListener('change', function () {
                    if (this.checked) {
                        pagesTree.openAll();
                    } else {
                        pagesTree.closeAll();
                    }
                });

                var select = function (id, type) {
                    if (pagesTree) {
                        pagesTree.select({
                            id, type
                        });
                    }
                }
                var treeTail = [
                    {
                        title: '<?php _e("Trash") ?>',
                        icon: 'mdi mdi-delete',
                        action: function () {

                            window.livewire.emit('resetFilter');
                            window.livewire.emit('showTrashed', 1);


                        }
                    }
                ];
                var contextMenu =  [
                    {
                        title: 'Open',
                        icon: 'mdi mdi-open-in-new',
                        action: function (element, data) {

                            if (data.type === 'category') {
                                window.livewire.emit('showFromCategory', data.id);
                            }  else {
                                window.livewire.emit('showFromPage', data.id);
                            }
                        }
                    },
                    {
                        title: 'Edit',
                        icon: 'mdi mdi-pencil',
                        action: function (element, data, menuitem) {
                            window.location.href='<?php print admin_url('category'); ?>/'+data.id+'/edit';
                        }
                    },
                    {
                        title: 'Delete',
                        icon: 'mdi mdi-delete',
                        action: function (element, data, menuitem) {

                            mw.spinner({element: element, size: 15, color: 'red',decorate: true});

                            if (data.type === 'category') {
                                mw.content.deleteCategory(data.id, function () {
                                  $(element).fadeOut();
                                  mw.notification.success('<?php _e("Category deleted"); ?>');
                                });
                            }
                            else {
                                mw.content.deleteContent(data.id, function () {
                                    $(element).fadeOut();
                                    mw.notification.success('<?php _e("Content deleted"); ?>');
                                });
                            }



                        }
                    }
                ];

                var options = {
                    sortable: false,
                    selectable: false,
                    singleSelect: false,
                    selectableNodes: 'singleSelect',
                    saveState: true,
                    searchInput: true,
                    contextMenu: contextMenu,
                    searchInputPlaceholder: '<?php _e('Search categories'); ?>',
                    resizable: true,
                    resizableOn: 'treeParent',
                    append: treeTail,
                    id: 'admin-main-tree',
                };

                var params = {
                    is_shop: '1'
                };

                mw.admin.tree(treeNode, {
                    options: options,
                    params: params
                }, 'tree').then(function (res) {
                    pagesTree = res.tree;
                    // todo: remove
                    select(8, 'category');

                    var treeHolderSet = function (){
                        var treeHolder = mw.element('#admin-main-tree');
                        if(treeHolder) {
                            treeHolder.css({
                                'height': 'calc(100vh - 120px)',
                                'overflow': 'auto',
                                'minHeight': '200px',
                            });
                        }
                    }
                    addEventListener('load', treeHolderSet);
                    addEventListener('resize', treeHolderSet);
                    addEventListener('scroll', treeHolderSet);
                    treeHolderSet();

                    pagesTree.on('selectionChange', function (items){
                        $.each(items, function (key, item) {
                            if (item.type == 'category') {
                                window.livewire.emit('showFromCategory', item.id);
                            }
                            if (item.type == 'page') {
                                window.livewire.emit('showFromPage', item.id);
                            }
                            window.livewire.emit('setFirstPageProductsList');

                        });
                    });

                });
            })();
        </script>

        <script>

            Livewire.on('selectCategoryFromTableList', function (id) {
                pagesTree.unselectAll(false);
                pagesTree.show(id, 'category');
                pagesTree.select(id, 'category', true);
            //    pagesTree.get(id, 'category').scrollIntoView({behavior: "smooth", block: "center", inline: "center"});
            })
        </script>



    </div>
    <main class="module-content">
        <livewire:admin-products-list />
        <livewire:admin-content-bulk-options />
    </main>
</div>


<script>
    mw.delete_single_post = function (id) {
        mw.tools.confirm("<?php _e("Do you want to delete this post"); ?>?", function () {
            mw.post.del(id, function () {
                mw.$(".manage-post-item-" + id).fadeOut(function () {
                    $(this).remove()
                });
            });
        });
    }
</script>
