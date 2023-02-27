<div class="ms-0 ms-md-2 mb-3 mb-md-0 mt-2">

    <input wire:model.stop="filters.category" id="js-filter-category" type="hidden" />
    <input wire:model.stop="filters.page" id="js-filter-page" type="hidden" />

    <button class="btn btn-badge-dropdown btn-secondary  btn-sm icon-left" onclick="categoryFilterSelectTree()">
        <i class="fa fa-list"></i> Categories

        ...
        <span class="badge badge-filter-item mt-1">+2</span>

        <div class="d-flex actions">
            <div class="action-dropdown-icon"><i class="fa fa-chevron-down"></i></div>
            <div class="action-dropdown-delete"><i class="fa fa-times-circle"></i></div>
        </div>

    </button>

</div>


<script id="js-category-filter-select-tree-<?php echo time(); ?>">

    function categoryFilterSelectTree() {

        var pageElement = document.getElementById('js-filter-page');
        var categoryElement = document.getElementById('js-filter-category');

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

                window.livewire.emit('setFirstPageContentList');
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
</script>
