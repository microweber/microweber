<div>

    <button type="button"  class="btn btn-badge-dropdown js-dropdown-toggle-{{$this->id}} @if(!empty($selectedItem)) btn-secondary @else btn-outline-secondary @endif btn-sm icon-left">

        {{$name}} <span class="mt-2">&nbsp;</span>


        <div class="d-flex actions">
            <div class="action-dropdown-icon"><i class="fa fa-chevron-down"></i></div>
         {{--   @if($selectedItem)
                <div class="action-dropdown-delete" wire:click="resetProperties"><i class="fa fa-times-circle"></i></div>
            @endif--}}
            <div class="action-dropdown-delete" wire:click="hideFilterItem('{{$this->id}}')"><i class="fa fa-times-circle"></i></div>
        </div>


    </button>

    <div class="badge-dropdown position-absolute js-dropdown-content-{{$this->id}} @if($showDropdown) active @endif " style="width:400px;">


        <input wire:model.stop="category" id="js-filter-category" type="hidden" />
        <input wire:model.stop="page" id="js-filter-page" type="hidden" />

        <div id="js-filter-item-tree"></div>


        <script id="js-category-filter-select-tree-<?php echo time(); ?>">

            var pageElement = document.getElementById('js-filter-page');
            var categoryElement = document.getElementById('js-filter-category');

            var selectedPages = pageElement.value.split(",");
            var selectedCategories = categoryElement.value.split(",");

            var ok = mw.element('<button class="btn btn-primary">Apply</button>');
            var btn = ok.get(0);

           /* var dialog = mw.dialog({
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

                    window.livewire.emit('setFirstPageProductsList');
                }
            });*/

            var tree;

            mw.admin.tree(document.getElementById('js-filter-item-tree'), {
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

                   // dialog.center();
                });
            });

            ok.on('click', function () {
               // dialog.result(tree.getSelected(), true);
            });
        </script>



    </div>


    <script>
        $(document).ready(function() {
            $('body').on('click', function(e) {
                if (!mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target,['js-dropdown-toggle-{{$this->id}}','js-dropdown-content-{{$this->id}}'])) {
                    $('.js-dropdown-content-{{$this->id}}').removeClass('active');
                }
            });
            $('.js-dropdown-toggle-{{$this->id}}').click(function () {
                $('.js-dropdown-content-{{$this->id}}').toggleClass('active');
            });
        });
    </script>

</div>
