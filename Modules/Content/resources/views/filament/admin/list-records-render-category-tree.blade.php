<div>
    <x-filament::button
        wire:click="$dispatch('showCategoriesSelectorPanel')"
        data-mw-category-selector="true"
        icon="heroicon-m-list-bullet"
        icon-position="before"
        tooltip="Show Categories"
        color="mw-secondary"
    >
        Categories
    </x-filament::button>


</div>

@push('styles')
@endpush

<style>
    /* Category filter drawer (opened by the "Categories" button on the
       content/products/posts list). Styled as a clean left drawer that sits
       next to the admin sidebar instead of overlapping it. */
    .mw-control-box.mw-cat-filter-box {
        top: 0;
        height: 100%;
        box-shadow: 0 0 24px rgba(0, 0, 0, 0.12);
        border-right: 1px solid rgba(0, 0, 0, 0.06);
    }
    @media (min-width: 1024px) {
        /* Sit to the right of the 240px admin sidebar + below the ~64px topbar. */
        .mw-control-box.mw-cat-filter-box {
            left: 240px;
            top: 64px;
            height: calc(100% - 64px);
        }
    }
    .mw-control-box.mw-cat-filter-box .mw-control-box-content {
        padding: 0;
        height: 100%;
    }
    .mw-cat-filter-panel {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 320px;
        max-width: 100%;
    }
    .mw-cat-filter-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 18px 44px 14px 20px; /* right padding clears the close (×) button */
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }
    .mw-cat-filter-title {
        font-size: 16px;
        font-weight: 600;
        color: #182433;
    }
    .mw-cat-filter-actions {
        padding: 14px 20px 6px;
    }
    .mw-cat-filter-add {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        width: 100%;
        justify-content: center;
        padding: 9px 14px;
        border-radius: 8px;
        background: #182433;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: opacity .15s ease;
    }
    .mw-cat-filter-add:hover {
        opacity: .9;
        color: #fff;
    }
    .mw-cat-filter-tree {
        flex: 1 1 auto;
        overflow-y: auto;
        padding: 10px 20px 20px;
    }
    html.dark .mw-cat-filter-title { color: #e5e7eb; }
    html.dark .mw-control-box.mw-cat-filter-box { box-shadow: 0 0 24px rgba(0, 0, 0, 0.5); }
</style>


@script
<script>

    document.addEventListener('livewire:initialized', () => {

        let treeControllBox = false, pagesTree;

        // Add-category target depends on the list we're on: product list ->
        // product categories, everything else -> content categories.
        const addCategoryUrls = {
            products: @js(\Illuminate\Support\Facades\Route::has('filament.admin.resources.shop-categories.create') ? route('filament.admin.resources.shop-categories.create') : null),
            content: @js(\Illuminate\Support\Facades\Route::has('filament.admin.resources.categories.create') ? route('filament.admin.resources.categories.create') : null),
        };

        Livewire.on('showCategoriesSelectorPanel', async () => {
            if (!treeControllBox) {
                const treeId = mw.id();
                const boxId = mw.id();

                const isProducts = window.location.pathname.indexOf('/products') !== -1;
                const addUrl = (isProducts && addCategoryUrls.products) ? addCategoryUrls.products : addCategoryUrls.content;

                const addButton = addUrl
                    ? `<div class="mw-cat-filter-actions"><a href="${addUrl}" class="mw-cat-filter-add">+ ${mw.lang('Add category')}</a></div>`
                    : '';

                treeControllBox = new mw.controlBox({
                    content: `
                        <div class="mw-cat-filter-panel">
                            <div class="mw-cat-filter-head">
                                <span class="mw-cat-filter-title">${mw.lang('Categories')}</span>
                            </div>
                            ${addButton}
                            <div id="${treeId}" class="mw-cat-filter-tree"></div>
                        </div>`,
                    position: 'left',
                    id: boxId,
                    closeButton: true
                });
                treeControllBox.show();

                // Tag the box so our scoped CSS (offset + drawer styling) applies.
                if (treeControllBox.box) {
                    treeControllBox.box.classList.add('mw-cat-filter-box');
                }

                pagesTree = await mw.widget.tree(`#${treeId}`, {
                    selectableNodes: false,
                    selectable: false,
                    singleSelect: true,
                }, 'tree');

                pagesTree.tree.on('selectionChange', e => {
                    const result = pagesTree.tree.getSelected();
                    let module = document.querySelector('.fi-page');

                    // var selected = tree.getSelected();
                    if (result) {

                        //get only categories

                        var cats = [];
                        result.forEach(function (item) {
                            if (item.type === 'category') {
                                cats.push(item);
                            }
                        });
                        if( cats.length === 0){
                            const component = Livewire.find(module.getAttribute('wire:id'));
                            if(component) {
                                component.set('tableFilters.category_id.value', '');
                                component.$refresh();
                            }
                            return;
                        }

                        const component = Livewire.find(module.getAttribute('wire:id'));
                        if(component) {
                            component.set('tableFilters.category_id.value', cats[0].id);
                            component.$refresh();
                        }

                    }

                })

            } else {
                treeControllBox.toggle();
            }


        });
    })
</script>

@endscript
