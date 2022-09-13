<div class="col-12 col-sm-6 col-md-3 col-lg-3 mb-4">
    <label class="d-block">
        Category
    </label>
    <input wire:model.stop="filters.category" id="js-filter-category" type="hidden" />
    <input wire:model.stop="filters.page" id="js-filter-page" type="hidden" />

    <script type="text/javascript">
        document.addEventListener('livewire:load', function () {
            pageElement = document.getElementById('js-filter-page');
            categoryElement = document.getElementById('js-filter-category');

            categoryFilterSelectTree = function () {

                var selectedPages = pageElement.value.split(",");
                var selectedCategories = categoryElement.value.split(",");

                var ok = mw.element('<button class="btn btn-primary">Apply</button>');
                var btn = ok.get(0);

                var dialog = mw.dialog({
                    title: '<?php _ejs('Select categories'); ?>',
                    footer: btn,
                    onResult: function (result) {
                        selectedPages = [];
                        selectedCategories = [];
                        $.each(result, function (key, item) {
                            if (item.type == 'category') {
                                selectedCategories.push(item.id);
                            }
                            if (item.type == 'page') {
                                selectedPages.push(item.id);
                            }
                        });

                        pageElement.value = selectedPages.join(",");
                        pageElement.dispatchEvent(new Event('input'));

                        categoryElement.value = selectedCategories.join(",");
                        categoryElement.dispatchEvent(new Event('input'));
                    }
                });

                var tree;

                mw.admin.tree(dialog.dialogContainer, {
                    options: {
                        sortable: false,
                        singleSelect: false,
                        selectable: true,
                        multiPageSelect: true
                    }
                }, 'treeTags').then(function (res) {

                    tree = res.tree;
                    $(tree).on("selectionChange", function () {
                        btn.disabled = tree.getSelected().length === 0;
                    });
                    $(tree).on("ready", function () {

                        if (selectedPages.length) {
                            $.each(selectedPages, function (key, pageId) {
                                tree.select(pageId, 'page')
                            });
                        }
                        if (selectedCategories.length > 0) {
                            $.each(selectedCategories, function (key, catId) {
                                tree.select(catId, 'category');
                            });
                        }

                        dialog.center();
                    });
                });

                ok.on('click', function () {
                    dialog.result(tree.getSelected(), true);
                });
            }
        });
    </script>

    <button class="btn btn-outline-primary btn-block" onclick="categoryFilterSelectTree()">
        <i class="fa fa-list"></i> Select Categories
    </button>

</div>
