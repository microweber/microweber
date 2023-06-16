<div>

    <div wire:ignore>
        <script>
            function assign_selected_categories_to_category_exec() {
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
                            data.parent_id = item.id;
                        }
                    });
                    if (data.categories.length === 0) {
                        mw.notification.warning('Please select a category');
                        return;
                    }


                    console.log(data);
                    dialog.remove();
                });
            }

            $(document).ready(function() {
                $.get("<?php print  api_url('content/get_admin_js_tree_json'); ?>?is_blog=1", function (data) {
                    var btn = document.createElement('button');
                    btn.disabled = true;
                    btn.className = 'mw-ui-btn';
                    btn.innerHTML = mw.lang('Move categories');
                    btn.onclick = function (ev) {
                        assign_selected_categories_to_category_exec();
                    };
                    var dialog = mw.dialog({
                        height: 'auto',
                        autoHeight: true,
                        id: 'pick-categories',
                        footer: btn,
                        title: mw.lang('Move to selected category')
                    });
                    var tree = new mw.tree({
                        data: data,
                        element: dialog.dialogContainer,
                        sortable: false,
                        selectable:true,
                        singleSelect:true,

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
            });

        </script>



    </div>

</div>
