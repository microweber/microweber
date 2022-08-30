<div class="js-tree tree" style="display: block">
    <div class="form-group">
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input js-open-close-all-tree-elements"
               id="open-close-all-tree-elements" value="1"/>
        <label class="custom-control-label d-flex align-items-center" style="cursor:pointer"
               for="open-close-all-tree-elements"><small class="text-muted"><?php _e("Open"); ?>
                / <?php _e("Close"); ?></small></label>
    </div>
</div>

<div id="js-page-tree"></div>
</div>



<script>
    var someElement = document.getElementById('js-page-tree');
    var pagesTree;
    mw.admin.tree(someElement, {
        options: {
            sortable: false,
            selectable: false,
            singleSelect: true,
            saveState: true,
            searchInput: true
        },
        params: {
            is_shop: '1'
        }
    }).then(function (res) {
        pagesTree = res;
    });
</script>

<script>
    $(document).ready(function () {
        // Open close all tree elements
        $('.js-open-close-all-tree-elements').on('change', function () {
            if ($(this).is(':checked') == '1') {
                pagesTree.tree.openAll();
            } else {
                pagesTree.tree.closeAll();
            }
        });

        // Tree items
        setTimeout(function () {
            $('.mw-tree-item-title', pagesTree.list).on('click', function () {
                $('li.selected', pagesTree.list).not(mw.tools.firstParentWithTag(this, 'li')).each(function () {
                    pagesTree.tree.unselect(this)
                });
                var li = mw.tools.firstParentWithTag(this, 'li'),
                    data = li._data,
                    action;
                if (!$(li).hasClass('mw-tree-additional-item')) {
                    console.log(data);
                }
            });
        }, 1000);
    });
</script>

<div class="card style-1 mb-3">

    <div class="card-header d-flex col-12 align-items-center justify-content-between px-md-4">
        <div class="col d-flex justify-content-md-start justify-content-center align-items-center px-0">
            <h5 class="mb-0">
                <i class="mdi mdi-earth text-primary mr-md-3 mr-1 justify-contetn-center"></i>
                <strong class="d-xl-flex d-none">{{_e('Website')}}</strong>
            </h5>
            <a href="{{route('admin.page.create')}}"
               class="btn btn-outline-success btn-sm js-hide-when-no-items ml-md-2 ml-1">{{_e('Add Page')}}</a>
        </div>
    </div>

    <div class="card-body pt-3">
        <livewire:admin-pages-table/>
    </div>
</div>
