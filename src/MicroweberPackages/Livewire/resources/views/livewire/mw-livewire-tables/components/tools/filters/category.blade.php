<input
    wire:model.stop="{{ $component->getTableName() }}.filters.{{ $filter->getKey() }}"
    wire:key="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
    id="{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}"
    type="hidden"
/>

<input
    wire:model.stop="{{ $component->getTableName() }}.filters.page"
    wire:key="{{ $component->getTableName() }}-filter-page"
    id="{{ $component->getTableName() }}-filter-page"
    type="hidden"
/>

<script type="text/javascript">

    pageElement_page = document.getElementById('{{ $component->getTableName() }}-filter-page');
    categoryElement_{{ $filter->getKey() }} = document.getElementById('{{ $component->getTableName() }}-filter-{{ $filter->getKey() }}');

    function removeItem(array, item){
        for(var i in array){
            if(array[i]==item){
                array.splice(i,1);
                break;
            }
        }
    }

    categoryFilterSelectTree = function (){

        var selectedPages =  pageElement_page.value.split(",");
        var selectedCategories =  categoryElement_{{ $filter->getKey() }}.value.split(",");

        var ok = mw.element('<button class="btn btn-primary">Apply</button>');
        var btn = ok.get(0);

        var dialog = mw.dialog({
            title: '<?php _ejs('Select categories'); ?>',
            footer: btn,
            onResult: function(result) {
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

                pageElement_page.value = selectedPages.join(",");
                pageElement_page.dispatchEvent(new Event('input'));

                categoryElement_{{ $filter->getKey() }}.value = selectedCategories.join(",");
                categoryElement_{{ $filter->getKey() }}.dispatchEvent(new Event('input'));
            }
        });

        var tree;

        mw.admin.tree(dialog.dialogContainer, {
            options: {
                sortable: false,
                singleSelect:false,
                selectable: true,
                multiPageSelect: false
            }
        }, 'treeTags').then(function (res){

            tree = res.tree;
            $(tree).on("selectionChange", function () {
                btn.disabled = tree.getSelected().length === 0;
            });
            $(tree).on("ready", function () {

                if (selectedPages.length) {
                    $.each(selectedPages, function (key,pageId) {
                        tree.select(pageId, 'page')
                    });
                }
                if (selectedCategories.length > 0) {
                    $.each(selectedCategories, function (key,catId) {
                        tree.select(catId, 'category');
                    });
                }

                dialog.center();
            });
        });

        ok.on('click', function(){
            dialog.result(tree.getSelected(), true)
        });
    }
</script>

<button class="btn btn-outline-primary btn-block" onclick="categoryFilterSelectTree()">
    <i class="fa fa-list"></i> Select Categories
</button>


