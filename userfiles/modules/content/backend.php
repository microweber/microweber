<?php
    only_admin_access();
    $action = url_param('action');
?>


<div class="tree" id="mw-admin-content-tree">

</div>

<?php

    $tree_url_endpoint = api_url('content/get_admin_js_tree_json');
    $pages_container_params_str = " is_shop='n' ";
    $pages_container_params_str = "   ";
    if (isset($is_shop)) {
        $pages_container_params_str = " is_shop='{$is_shop}' ";
        $tree_url_endpoint = api_url('content/get_admin_js_tree_json?is_shop=1');
    } elseif (isset($params['is_shop'])) {
        $pages_container_params_str = " is_shop='" . $params['is_shop'] . "' ";
        $tree_url_endpoint = api_url('content/get_admin_js_tree_json?is_shop=1');

    } elseif ($action == 'products') {
        $pages_container_params_str = " is_shop='y' ";
        $tree_url_endpoint = api_url('content/get_admin_js_tree_json?is_shop=1');
    }
    if ($action == 'posts') {
        $pages_container_params_str = " is_shop='n'  skip-static-pages='true' ";
        $tree_url_endpoint = api_url('content/get_admin_js_tree_json?is_shop=0');
    } elseif ($action == 'pages') {
        $pages_container_params_str = " content_type='page'  '   ";
    } elseif ($action == 'categories') {
        $pages_container_params_str = " manage_categories='yes'    ";
    }

?>

<script>

    var pagesTree;

    var pagesTreeRefresh = function(){
        $.get("<?php print $tree_url_endpoint; ?>", function(data){
            var treeTail = [
                {
                    title: '<?php _lang("Trash") ?>',
                    icon:'mdi mdi-delete',
                    action:function(){
                        mw.url.windowHashParam('action', 'trash');
                    }
                }
            ];

            pagesTree = new mw.tree({
                data:data,
                element: mw.$("#mw-admin-content-tree")[0],
                sortable:true,
                selectable:false,
                id:'admin-main-tree',
                append:treeTail,
                contextMenu:[
                    {
                        title:'Edit',
                        icon:'mdi mdi-pencil',
                        action:function(element, data, menuitem){
                            mw.url.windowHashParam("action", "edit"+data.type+":"+data.id);
                        }
                    },
                    {
                        title:'Move to trash',
                        icon:'mdi mdi-close',
                        action:function(element, data, menuitem){
                            if(data.type  === 'category' ){
                                mw.content.deleteCategory(data.id, function(){

                                    $('#' + pagesTree.options.id + '-' + data.type + '-' + data.id).fadeOut(function(){
                                        if(window.pagesTreeRefresh){pagesTreeRefresh()};
                                    })
                                });
                            }
                            else{
                                mw.content.deleteContent(data.id, function(){
                                    $('#' + pagesTree.options.id + '-' + data.type + '-' + data.id, pagesTree.list).fadeOut(function(){
                                        if(window.pagesTreeRefresh){pagesTreeRefresh()};
                                    })
                                });
                            }
                        }
                    }
                ]
            });
            mw.adminPagesTree = pagesTree;
            $(pagesTree).on("orderChange", function(e, item, data, old, local){
                var obj = {ids: local};
                var url;
                if(item.type === 'category'){
                    url = "<?php print api_link('category/reorder'); ?>" ;
                }
                else {
                    url = "<?php print api_link('content/reorder'); ?>";
                }
                $.post(url, obj, function () {
                    mw.reload_module('#mw_page_layout_preview');
                    mw.notification.success('<?php _ejs("Changes are saved"); ?>')
                });
            });
            $(pagesTree).on("ready", function(){

                $('#main-tree-search').on('input', function(){
                    var val = this.value.toLowerCase().trim();
                    if(!val){
                        pagesTree.showAll();
                    }
                    else{
                        pagesTree.options.data.forEach(function(item){
                            if(item.title.toLowerCase().indexOf(val) === -1){
                                pagesTree.hide(item);
                            }
                            else{
                                pagesTree.show(item);
                            }
                        });
                    }
                })

                $('.mw-tree-item-title', pagesTree.list).on('click', function(){
                    $('li.selected', pagesTree.list).each(function(){
                        pagesTree.unselect(this)
                    });
                    var li = mw.tools.firstParentWithTag(this, 'li'),
                        data = li._data,
                        action;
                    //pagesTree.select(li);
                    if(!$(li).hasClass('mw-tree-additional-item')){
                        if(data.type == 'page'){
                            action = 'editpage';
                        }
                        if(data.subtype == 'dynamic' || data.subtype == 'shop'){
                            action = 'showposts';
                        }
                        if(data.type == 'category'){
                            action = 'showpostscat';
                        }


                        mw.url.windowHashParam("action", action+":"+data.id);
                    }


                });
                mainTreeSetActiveItems()
                $("#edit-content-row .tree-column").resizable({
                    handles: "e",
                    resize: function (e, ui) {
                        var root = mw.$(ui.element);
                        mw.$('.fixed-side-column', root).width(root.width())
                    },
                    minWidth: 200
                })

            })

        });
    };
    if(window.pagesTreeRefresh){pagesTreeRefresh()};
