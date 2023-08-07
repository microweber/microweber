<div>

    <div wire:ignore>
        <script>
            function categoryBulkMoveExec(selectedIds) {
                mw.tools.confirm("Are you sure you want to move the selected data?", function () {
                    var dialog = mw.dialog.get('#pick-categories');
                    var tree = mw.tree.get('#pick-categories');
                    var selected = tree.getSelected();
                    var data = {
                        categories: []
                    };
                    selected.forEach(function (item) {
                        if (item.type === 'category') {
                            data.categories.push(item.id);
                        } else if (item.type === 'page') {
                            data.rel_id = item.id;
                        }
                    });

                    $.ajax({
                        url: route('api.category.move-bulk'),
                        type: 'POST',
                        data: {
                            ids: selectedIds,
                            moveToParentIds: data.categories,
                            moveToRelId: data.rel_id
                        },
                        success: function (data) {
                            mw.reload_module('categories/manage');
                            mw.notification.success('<?php _ejs("Categories are moved."); ?>.');
                            mw.parent().trigger('pagesTreeRefresh');
                            dialog.remove();
                        }
                    });

                });
            }

            function categoryBulkMoveModal(selectedIds) {

                @php
                  $jsTreeEndPoint = api_url('content/get_admin_js_tree_json');
                    if ($isShop) {
                        $jsTreeEndPoint .= '?is_shop=1';
                    } else {
                        $jsTreeEndPoint .= '?is_blog=1';
                    }
                @endphp

                $.get("{{$jsTreeEndPoint}}", function (data) {
                    var btn = document.createElement('button');
                    btn.disabled = true;
                    btn.className = 'mw-ui-btn';
                    btn.innerHTML = mw.lang('Move categories');
                    btn.onclick = function (ev) {
                        categoryBulkMoveExec(selectedIds);
                    };
                    var dialog = mw.dialog({
                        height: 'auto',
                        autoHeight: true,
                        id: 'pick-categories',
                        footer: btn,
                        title: mw.lang('Move to selected category')
                    });
                    var treeSettings = {
                        data: data,
                        element: dialog.dialogContainer,
                        sortable: false,
                        selectable: true,
                        singleSelect: true,
      			        searchInput: true,
                    }
                    var tree = new mw.tree(treeSettings);
                    $(tree).on("selectionChange", function () {
                         
                        btn.disabled = tree.getSelected().length === 0;

                        var selected = tree.getSelected();
                        if(tree.options.singleSelect === false && treeSettings.selected.length){
                            var hasPage = selected.find(function (item){
                                return item.type === 'page';
                            });

                            if(typeof hasPage === 'undefined'){
                                var category = selected[0];
                                 tree.select(category.parent_id, 'page', true);
                            }
                        }
                    });

                    $(tree).on("ready", function () {
                        dialog.center();
                    })
                });
            }

        </script>



    </div>

</div>
