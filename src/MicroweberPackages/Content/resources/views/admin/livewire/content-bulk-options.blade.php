<div>


    <div wire:ignore>
        <script>
            function assign_selected_posts_to_category_exec() {
                mw.tools.confirm("Are you sure you want to move the selected data?", function () {
                    var dialog = mw.dialog.get('#pick-categories');
                    var tree = mw.tree.get('#pick-categories');
                    var selected = tree.getSelected();
                    var data = {
                        content_ids: {!! json_encode($multipleMoveToCategoryIds) !!},
                        categories: []
                    };
                    selected.forEach(function (item) {
                        if (item.type === 'category') {
                            data.categories.push(item.id);
                        } else if (item.type === 'page') {
                            data.parent_id = item.id;
                        }
                    });
                    $.post("<?php print api_link('content/bulk_assign'); ?>", data, function (msg) {
                        mw.notification.msg(msg);
                        window.livewire.emit('multipleMoveToCategoryExecute');
                        window.livewire.emit('refreshProductsListAndDeselectAll');
                        dialog.remove();
                    });
                });
            }
            function assign_selected_posts_to_category_show_tree() {
                $.get("<?php print  api_url('content/get_admin_js_tree_json'); ?>", function (data) {
                    var btn = document.createElement('button');
                    btn.disabled = true;
                    btn.className = 'mw-ui-btn';
                    btn.innerHTML = mw.lang('Move posts');
                    btn.onclick = function (ev) {
                        assign_selected_posts_to_category_exec();
                    };
                    var dialog = mw.dialog({
                        height: 'auto',
                        autoHeight: true,
                        id: 'pick-categories',
                        footer: btn,
                        title: mw.lang('Select categories')
                    });
                    var tree = new mw.tree({
                        data: data,
                        element: dialog.dialogContainer,
                        sortable: false,
                        selectable: true,
                        multiPageSelect: false

                    });
                    $(tree).on("selectionChange", function () {
                        btn.disabled = tree.getSelected().length === 0;

                        var selected = tree.getSelected();
                        if(selected.length){
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

        <script>
            Livewire.on('multipleMoveToCategoryShowModalOpen', function () {
                assign_selected_posts_to_category_show_tree()
            })
        </script>


    </div>

    @if($multipleMoveToCategoryShowModal)



    @endif


    @if($multiplePublishShowModal)
        <script>
            mw.tools.confirm("Are you sure you want to publish the selected data?", function () {
                window.livewire.emit('multiplePublishExecute');
            });
        </script>
    @endif


    @if($multipleUnpublishShowModal)
        <script>
            mw.tools.confirm("Are you sure you want to unpublish the selected data?", function () {
                window.livewire.emit('multipleUnpublishExecute');
            });
        </script>
    @endif

    @if($multipleDeleteShowModal)
        <script>
        mw.tools.confirm("Are you sure you want to delete the selected data?", function () {
            window.livewire.emit('multipleDeleteExecute');
        });
        </script>
    @endif


    @if($multipleUndeleteShowModal)
        <script>
        mw.tools.confirm("Are you sure you want to restore the selected data?", function () {
            window.livewire.emit('multipleUndeleteExecute');
        });
        </script>
    @endif


    @if($multipleDeleteForeverShowModal)
        <script>
        mw.tools.confirm("Are you sure you want to delete the selected data forever?", function () {
            window.livewire.emit('multipleDeleteForeverExecute');
        });
        </script>
    @endif


</div>
