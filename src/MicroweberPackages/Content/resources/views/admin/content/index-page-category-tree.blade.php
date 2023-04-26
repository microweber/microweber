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
    #pages-tree-wrapper{
        max-width: 0;
        transition: .4s cubic-bezier(0.0, 0.0, 0.2, 1);;
    }
    #pages-tree-wrapper.active{
        max-width: 600px;
    }
    #pages-tree-container .ui-resizable{
        max-width: 100%
    }
    #pages-tree-container{
        max-width: 0;
        overflow:hidden;
        transition: .4s cubic-bezier(0.0, 0.0, 0.2, 1);;
        opacity: .5;
        visibility: hidden;
     
    }
    #pages-tree-wrapper.active #pages-tree-container{
        max-width: 600px;
        opacity: 1;
        visibility: visible;
    }

</style>




 
<div id="pages-tree-wrapper"  > 
<button type="button" class="btn mw-admin-toggle-tree-navigation">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout-sidebar-left-expand" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
    <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
    <path d="M9 4v16"></path>
    <path d="M14 10l2 2l-2 2"></path>
    </svg>
 
</button>
<div id="pages-tree-container" > 

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
            <div class="form-check form-switch d-flex ps-0 justify-content-between align-items-center" style="width: 100%;">
                <label class="form-check-label " style="cursor:pointer" for="open-close-all-tree-elements"><small class="text-muted"><?php _e("Show all"); ?></small>
                </label>
                    <input type="checkbox" class="form-check-input js-open-close-all-tree-elements" id="open-close-all-tree-elements" value="1"/>
            </div>

        </div>

        <div id="js-page-tree" style="display:none;"></div>
    </div>
    </div>


    <script>
 
        const treeContainer = document.getElementById('pages-tree-wrapper');
        var state = mw.storage.get('mw-tree-navigation-visible');
        treeContainer.classList[state ? 'add' : 'remove']('active')
        document.querySelector('.mw-admin-toggle-tree-navigation').addEventListener('click', function(){
            treeContainer.classList.toggle('active')
            mw.storage.set('mw-tree-navigation-visible', treeContainer.classList.contains('active'));
        });

     

</script>
            
    <script>
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
                    action: function () {

                        window.livewire.emit('resetFilter');
                        window.livewire.emit('showTrashed', 1);


                    }
                }
            ];
            var contextMenu =  [
                {
                    title: '<?php _ejs("Edit"); ?>',
                    icon: 'edit-category-icon-tree',
                    action: function (element, data, menuitem) {
                        if (data.type === 'category') {
                            window.location.href = '<?php print admin_url('category'); ?>/' + data.id + '/edit';
                        } else  if (data.type === 'page') {
                            window.location.href = '<?php print admin_url('page'); ?>/' + data.id + '/edit';
                        } else  if (data.type === 'post') {
                            window.location.href = '<?php print admin_url('post'); ?>/' + data.id + '/edit';
                        } else {
                            window.location.href = '<?php print admin_url('content'); ?>/' + data.id + '/edit';
                        }

                    }
                }
            ];

            contextMenu.push({
                title: '<?php _ejs("Add post"); ?>',
                icon: 'add-post-icon-tree',
                action: function (element, data, menuitem) {

                    if(data.parent_page &&  data.parent_page.subtype === 'dynamic' && data.parent_page.is_shop === 0){
                        window.location.href = '<?php print admin_url('post'); ?>/create?recommended_category_id=' + data.id + '&recommended_content_id=' + data.parent_page.id;
                    } else {
                        window.location.href = '<?php print admin_url('post'); ?>/create?recommended_content_id=' + data.id;
                    }


                },
                filter: function(data) {
                    if (data.type === 'page' && data.subtype === 'dynamic' && data.is_shop === 0) {
                        return true;
                    }
                    if (data.type === 'category'
                        && data.parent_type
                        && data.parent_type === 'page'
                        && data.parent_id != 0
                        && data.parent_page
                        && data.parent_page.id
                        && data.parent_page.subtype
                        && data.parent_page.subtype === 'dynamic'
                        && data.parent_page.is_shop === 0){
                        return true;
                    }
                    return false;
                }
            });

            <?php if(is_shop_module_enabled_for_user()): ?>
            contextMenu.push({
                title: '<?php _ejs("Add product"); ?>',
                icon: 'add-product-icon-tree',
                action: function (element, data, menuitem) {
                    if(data.parent_page && data.parent_page.subtype === 'dynamic' && data.parent_page.is_shop === 1){
                        window.location.href = '<?php print admin_url('shop/product'); ?>/create?recommended_category_id=' + data.id + '&recommended_content_id=' + data.parent_page.id;
                    } else {
                        window.location.href = '<?php print admin_url('shop/product'); ?>/create?recommended_content_id=' + data.id;
                    }
                },
                filter: function(data) {
                    if (data.type === 'page' && data.subtype === 'dynamic' && data.is_shop === 1) {
                        return true;
                    }
                    if (data.type === 'category'
                        && data.parent_type
                        && data.parent_type === 'page'
                        && data.parent_id != 0
                        && data.parent_page
                        && data.parent_page.id
                        && data.parent_page.subtype
                        && data.parent_page.subtype === 'dynamic'
                        && data.parent_page.is_shop === 1){
                        return true;
                    }

                    return false;
                }
            });
            <?php endif; ?>


            contextMenu.push({
                title: '<?php _ejs("Add subcategory"); ?>',
                icon: 'add-subcategory-icon-tree',
                action: function (element, data, menuitem) {
                    window.location.href = '<?php print admin_url('category'); ?>/create?addsubcategory=' + data.id;
                },
                filter: function(data) {
                    if (data.type === 'category') {
                        return true;
                    }
                    if (data.type === 'page' && data.subtype === 'dynamic') {
                            return true;
                    }
                    return false;
                }
            });

            contextMenu.push({
                title: '<?php _ejs("Add subpage"); ?>',
                icon: 'add-subpage-icon-tree',
                action: function (element, data, menuitem) {
                    window.location.href = '<?php print admin_url('page'); ?>/create?recommended_content_id=' + data.id;
                },
                filter: function(data) {
                    if (data.type === 'page') {
                        return true;
                    }
                    return false;
                }
            });

            contextMenu.push({
                    title: '<?php _ejs("Delete"); ?>',
                    icon: 'delete-category-icon-tree',
                    action: function (element, data, menuitem) {

                        mw.spinner({element: element, size: 15, color: 'red', decorate: true});

                        if (data.type === 'category') {
                            mw.content.deleteCategory(data.id, function () {
                                $(element).fadeOut();
                                mw.notification.success('<?php _e("Category deleted"); ?>');
                            });
                        } else {
                            mw.content.deleteContent(data.id, function () {
                                $(element).fadeOut();
                                mw.notification.success('<?php _ejs("Content deleted"); ?>');
                            });
                        }
                    }
                }
            );

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

            var params = {};

            @if(isset($is_shop))
                params.is_shop = 1;
            @endif

            @if(isset($is_blog))
                params.is_blog = 1;
            @endif

            mw.admin.tree(treeNode, {
                options: options,
                params: params
            }, 'tree').then(function (res) {

                $('.js-page-tree-skeleton').remove();
                $('#js-page-tree').show();
                $('.tree-show-hide-nav').show();

                pagesTree = res.tree;

                var treeHolderSet = function (){
                    var treeHolder = mw.element('#admin-main-tree');
                    if(treeHolder) {
                        treeHolder.css({
                            'height': 'calc(100vh - 355px)',
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
                        window.livewire.emit('setFirstPageContentList');

                    });
                });

            });
        })();
    </script>
    <script>

        Livewire.on('deselectAllCategories', function () {
            pagesTree.unselectAll(false);
            window.livewire.emit('showFromCategory', false);
        });
        Livewire.on('selectCategoryFromTableList', function (id) {
            pagesTree.unselectAll(false);
            pagesTree.show(id, 'category');
            pagesTree.select(id, 'category', true);
            //    pagesTree.get(id, 'category').scrollIntoView({behavior: "smooth", block: "center", inline: "center"});
        })
    </script>
 