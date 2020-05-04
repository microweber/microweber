<?php
    only_admin_access();
    $action = url_param('action');
    $active_content_id = '';
    if (isset($_REQUEST['edit_content']) and $_REQUEST['edit_content'] != 0) {
        $active_content_id = $_REQUEST['edit_content'];
    }
?>


<div class="tree" id="mw-admin-content-tree"></div>

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

    mw.$(document).ready(function(){
        pagesTreeRefresh()
    });

    var mainTreeSetActiveItems = function(){
        if(typeof(mw.adminPagesTree) != 'undefined'){
            var hp = mw.url.getHashParams(location.hash);
            if(hp.action){
                var arr = hp.action.split(':');
                if(arr[0] !== 'new'){
                    mw.adminPagesTree.unselectAll();
                }
                var activeTreeItemIsPage = arr[0] === 'editpage' || arr[0] === 'showposts';
                var activeTreeItemIsCategory = arr[0] === 'editcategory' || arr[0] === 'showpostscat';

                if(activeTreeItemIsPage){
                    mw.adminPagesTree.select({
                        id:arr[1],
                        type:'page'
                    })
                }
                if(activeTreeItemIsCategory){
                    mw.adminPagesTree.select({
                        id:arr[1],
                        type:'category'
                    })
                }
            }
            else{
                mw.adminPagesTree.unselectAll();
            }
        }
    };


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

    </script>
<?php event_trigger('admin_content_after_website_tree', $params); ?>


<script>
    $(window).on('load', function () {
        if (!mw.url.windowHashParam("action")) {
            edit_load('content/manager');
        }
        mw.on.hashParam('view', function () {
            edit_load('content/manager');
        })
    });

    edit_load = function (module, callback) {
        mw.tools.loading(true)
        var n = mw.url.getHashParams(window.location.hash)['new_content'];
        if (n == 'true') {
            var slide = false;
            mw.url.windowDeleteHashParam('new_content');
        }
        else {
            var slide = true;
        }
        var action = mw.url.windowHashParam('action');
        var holder = $('#pages_edit_container');
        var time = !action ? 300 : 0;
        if (!action) {
            mw.$('.fade-window').removeClass('active');
        }
        setTimeout(function () {
            mw.load_module(module, holder, function () {
                mw.tools.loading(false);
                mw.$('.fade-window').addClass('active')
                if (callback) callback.call();
                mw.tools.loading(false)
            });
        }, time)
    }

</script>
<main>
    <div id="pages_edit_container" class="card style-1 mb-3" <?php print $pages_container_params_str; ?>></div>

</main>

