<x-filament-panels::page
    @class([
        'fi-resource-list-records-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    ])
>
    <div class="flex flex-col gap-y-6">
        {{-- <x-filament-panels::resources.tabs/> removed: component does not exist in Filament v5 --}}

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE, scopes: $this->getRenderHookScopes()) }}




        @php
            $suffix = '';

            $suffix = $this->getId();



        @endphp



        @script


        <script>


        (async function () {
            const tree = async (params = {} )=> {
                var skip = [];
                var selectedData = [];
                var options = {


                }

                @if(isset($singleSelect) and $singleSelect)

                    options.singleSelect = true;

                @endif

                @if(isset($selectedPage) and $selectedPage)

                selectedData.push({
                    id: '{{$selectedPage}}',
                    type: 'page'
                })

                @endif

                @if(isset($selectedCategories) and $selectedCategories)

                @foreach($selectedCategories as $selectedCategory)

                selectedData.push({
                    id: {{$selectedCategory}},
                    type: 'category'
                })


                @endforeach

                    @endif

                if (selectedData.length > 0) {
                    options.selectedData = selectedData;
                }
                if (skip.length > 0) {
                    options.skip = skip;
                }



                var opts = {
                    options,
                    params
                };


                const target = document.querySelector('#mw-tree-edit-content-{{$suffix}}');
                target.innerHTML = '';


                 mw.spinner({
                    element: target,
                    size: 30
                }).show();

                let pagesTree = await mw.admin.categoriesTree(target, opts);


                mw.spinner({
                    element: target,
                    size: 30
                }).remove();

                pagesTree.tree.on('selectionChange', e => {
                    let result = pagesTree.tree.getSelected();
                    this.state = result;
                })

                pagesTree.tree.openAll();

                // AI-1001 / task-2026-05-22 — wire expand-all / collapse-all buttons.
                const expandBtn = document.querySelector('#mw-tree-expand-all-{{$suffix}}');
                const collapseBtn = document.querySelector('#mw-tree-collapse-all-{{$suffix}}');
                if (expandBtn) {
                    expandBtn.addEventListener('click', () => pagesTree.tree.openAll());
                }
                if (collapseBtn) {
                    collapseBtn.addEventListener('click', () => pagesTree.tree.closeAll());
                }

            };





            document.addEventListener('livewire:initialized', async () => {
                    tree();


                Livewire.on('treeLanguageChanged', async (lang) => {

                    if(!lang.locale){
                        return;

                    }

                    const query = {
                        lang: lang.locale
                    };
                    tree(query);
                });

            });







        })();
        </script>

        @endscript

        {{-- AI-1001 / task-2026-05-22 — expand-all / collapse-all tree toolbar. --}}
        <div class="flex gap-2 mb-2">
            <button type="button" id="mw-tree-expand-all-{{$suffix}}"
                class="fi-btn fi-btn-size-sm fi-color-gray fi-btn-color-gray py-1 px-2 text-xs rounded"
                style="border:1px solid #e5e7eb;background:#f9fafb;cursor:pointer;">
                ＋ Expand all
            </button>
            <button type="button" id="mw-tree-collapse-all-{{$suffix}}"
                class="fi-btn fi-btn-size-sm fi-color-gray fi-btn-color-gray py-1 px-2 text-xs rounded"
                style="border:1px solid #e5e7eb;background:#f9fafb;cursor:pointer;">
                − Collapse all
            </button>
        </div>

            <div wire:ignore class="mw-edit-categories-list" id="mw-tree-edit-content-{{$suffix}}"></div>






        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER, scopes: $this->getRenderHookScopes()) }}
    </div>
</x-filament-panels::page>
