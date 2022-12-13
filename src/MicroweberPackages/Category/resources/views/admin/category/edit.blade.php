<div class="pt-0">

    <script>
        mw.require('content.js', true);
    </script>

    <style>
        #js-page-tree{
            position: sticky;
            top: 70px;
            min-height: 200px;

        }

        .main .tree {
            display: block;
        }
    </style>

    <div>
        <div id="js-page-tree-wrapper" style="min-width:240px;">

            <div class="js-page-tree-skeleton">
                <div class="d-flex">
                    <div class="skeleton-loading skeleton-toggle-btn">
                        &nbsp;
                    </div>
                    <div class="skeleton-loading skeleton-toggle-label">
                        &nbsp;
                    </div>
                </div>

                <div class="skeleton-loading skeleton-search">
                    <div class="skeleton-search-label"></div>
                </div>

                <?php for ($isk=1; $isk<=12; $isk++):
                $marginLeft = 25;
                $randWidth = rand(130, 160);
                if ($isk>6) {
                    $marginLeft = 50;
                }
                if ($isk>9) {
                    $marginLeft = 25;
                }
                ?>

                <div class="skeleton-loading skeleton-item" style="width:<?php echo $randWidth; ?>px;margin-left:<?php echo $marginLeft; ?>px;">
                    <div class="d-flex">
                        <div class="skeleton-icon"></div>
                        <div class="skeleton-label" style="width:<?php echo ($randWidth-50); ?>px;"></div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>

            <div class="tree-show-hide-nav" style="display:none">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input js-open-close-all-tree-elements" id="open-close-all-tree-elements" value="1"/>
                        <label class="custom-control-label d-flex align-items-center" style="cursor:pointer" for="open-close-all-tree-elements"><small class="text-muted"><?php _e("Open"); ?> / <?php _e("Close"); ?></small></label>
                    </div>
                </div>
            </div>

            <div id="js-page-tree" style="display:none;"></div>
        </div>

        <script>
            var selectedCategories = [<?php echo $id; ?>];
            pagesTree = null;
            (function (){
                var treeNode = document.getElementById('js-page-tree');
                var treeNodeParent = treeNode.parentElement;
                treeNodeParent.classList.add('js-tree');
                treeNodeParent.classList.add('tree');
                mw
                    .element('.main > aside')
                    .after(treeNodeParent);


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
                        title: '<?php _ejs("Trash") ?>',
                        icon: 'mdi mdi-delete',
                        action: function () {

                        }
                    }
                ];
                var contextMenu =  [

                    {
                        title: '<?php _ejs("Edit"); ?>',
                        icon: 'mdi mdi-pencil',
                        action: function (element, data, menuitem) {
                            window.location.href='<?php print admin_url('category'); ?>/'+data.id+'/edit';
                        }
                    },
                    {
                        title: '<?php _ejs("Add subcategory"); ?>',
                        icon: 'mdi mdi-pencil',
                        action: function (element, data, menuitem) {
                            window.location.href='<?php print admin_url('category'); ?>/create?addsubcategory='+data.id;
                        }
                    },
                    {
                        title: '<?php _ejs("Delete"); ?>',
                        icon: 'mdi mdi-delete',
                        action: function (element, data, menuitem) {

                            mw.spinner({element: element, size: 15, color: 'red',decorate: true});

                            if (data.type === 'category') {
                                mw.content.deleteCategory(data.id, function () {
                                    $(element).fadeOut();
                                    mw.notification.success('<?php _e("Category deleted"); ?>');
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

                    $('.js-page-tree-skeleton').remove();
                    $('#js-page-tree').show();
                    $('.tree-show-hide-nav').show();

                    pagesTree = res.tree;

                    $(pagesTree).on("ready", function () {
                        if (selectedCategories.length > 0) {
                            $.each(selectedCategories, function (key, catId) {
                                pagesTree.select(catId, 'category', false);
                            });
                        }
                    });

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
                                window.location.href='<?php print admin_url('category'); ?>/'+item.id+'/edit';
                            }
                        });
                    });

                });
            })();
        </script>
    </div>


    <div class="module-content">
        <div class="row">
            <div class="col-md-8">
            <module type="categories/edit_category" category_id="{{$id}}"  @if(isset($isShop)) is_shop="1" @endif />
            </div>
            <div class="col-md-4">

                <div class="card style-1 mb-3">
                    <div class="card-body pt-3 pb-0">
                        <div class="row">
                            <div class="col-12">
                                <strong>Visibility</strong>
                            </div>
                        </div>

                        <div class="row my-3">
                            <div class="col-12"><input type="hidden" name="is_active" id="is_post_active" value="1"><div class="form-group">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="is_active_1" name="is_active" class="custom-control-input" value="1" checked=""><label class="custom-control-label" style="cursor:pointer" for="is_active_1">Published</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="is_active_0" name="is_active" class="custom-control-input" value="0"><label class="custom-control-label" style="cursor:pointer" for="is_active_0">Unpublished</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div></div></div>

            </div>
        </div>
    </div>

</div>
