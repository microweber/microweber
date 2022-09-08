<div>
    @if($multipleMoveToCategoryShowModal)
    <script>
        assign_selected_posts_to_category_exec = function () {
            mw.tools.confirm("Are you sure you want to move the selected data?", function () {
                var dialog = mw.dialog.get('#pick-categories');
                var tree = mw.tree.get('#pick-categories');
                var selected = tree.getSelected();
                var posts = mw.check.collectChecked(document.getElementById('<?php print $params['id']; ?>'));
                var data = {
                    content_ids: posts,
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
                    mw.reload_module('#<?php print $params['id']; ?>');
                    dialog.remove();
                });
            });
        };

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
            });
            $(tree).on("ready", function () {
                dialog.center();
            })
        });
    </script>
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

</div>
