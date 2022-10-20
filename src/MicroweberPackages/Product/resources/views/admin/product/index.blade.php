<style>
    .badge-dropdown {
        background: #ffffff;
        padding: 20px;
        position: absolute;
        z-index: 15;
        box-shadow: 0px 4px 12px #00000054;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
        border-top-right-radius: 4px;
        border-top:4px solid #6c757d;
        width:300px;
        margin-top:10px;
        visibility:hidden;
        opacity:0;
        transition: 0.3s;
        transform: scale(0);
        transform-origin: center top;
    }
    .badge-dropdown.active {
        visibility:visible;
        transform: scale(1);
        opacity:1;
    }

    .badge-dropdown::after {
        content: ' ';
        width: 0px;
        height: 0px;
        border-style: solid;
        border-width: 0 10px 10px 10px;
        border-color: transparent transparent #6c757d transparent;
        display: inline-block;
        vertical-align: middle;
        position: absolute;
        top: -13px;
    }

   /* .btn-badge-dropdown::after {
        display: inline-block;
        margin-left: 0.255em;
        vertical-align: 0.255em;
        content: "";
        border-top: 0.3em solid;
        border-right: 0.3em solid transparent;
        border-bottom: 0;
        border-left: 0.3em solid transparent;
    }*/

     .btn-badge-dropdown .action-dropdown-icon {
         cursor: pointer;
         float: right;
         width: 16px;
         padding-top: 0px;
         margin-left: 5px;
         font-size:12px;
     }
     .btn-secondary .action-dropdown-icon {
         color: #ffffff91;
     }

    .btn-secondary:hover .action-dropdown-icon {
        color:#FFFFFF;
    }

    .btn-badge-dropdown .action-dropdown-delete {
       cursor: pointer;
       float:right;
       width:24px;
       height:15px;
       padding-left:8px;
    }

    .btn-secondary .action-dropdown-delete {
         color: #ffffff91;
     }

    .btn-secondary .action-dropdown-delete:hover {
        color:#FFFFFF;
    }

    .badge-filter-item {
        background: #fff;
        color: #757575;
        border-radius: 15px;
        padding: 5px 6px;
        margin: 0px 12px;
        font-size: 12px;
    }
</style>
<style>
    #js-page-tree{
        position: sticky;
        top: 70px;
        min-height: 200px;
    }
</style>

<div class="main">
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
            ;(function (){

                var treeNode = document.getElementById('js-page-tree');
                var pagesTree;

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
                            mw.url.windowHashParam('action', 'trash');
                        }
                    }
                ];
                var contextMenu =  [
                    {
                        title: 'Edit',
                        icon: 'mdi mdi-pencil',
                        action: function (element, data, menuitem) {


                        }
                    },
                    {
                        title: 'Move to trash',
                        icon: 'mdi mdi-delete',
                        action: function (element, data, menuitem) {
                            if (data.type === 'category') {

                            }
                            else {

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
                                window.livewire.emit('applyFilterItem', 'category', item.id);
                            }
                            if (item.type == 'page') {
                                window.livewire.emit('applyFilterItem', 'page', item.id);
                            }
                        });
                    });

                });
            })();
        </script>
    </div>
    <main class="module-content">
        <livewire:admin-products-list />
        <livewire:admin-content-bulk-options />
    </main>
</div>
