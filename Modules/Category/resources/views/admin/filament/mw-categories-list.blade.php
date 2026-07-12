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

            // task-2026-05-28-2f5a6c / AI-1092 — server-side category-count gate.
            // /admin/categories renders via a custom JS-rendered tree (mw.admin.categoriesTree),
            // NOT a standard Filament table, so a Resource-level ->emptyState() wiring
            // would never render. Instead, count categories server-side and render
            // either the tree (count > 0) or the empty-state chrome (count === 0)
            // with a heading + CTA pointing at the category-create route.
            $mwCatListCategoryCount = \Modules\Category\Models\Category::count();

        @endphp


        @if($mwCatListCategoryCount > 0)

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

        @else
            {{-- task-2026-05-28-2f5a6c / AI-1092 — empty-state chrome for /admin/categories.
                 Mirrors the shared-empty-state blade shape (heading + CTA-wrap + CTA)
                 used by Content / Order / Customer / Invoice / Product branches in
                 Modules/Content/resources/views/filament/admin/empty-state.blade.php,
                 but lands here because the categories list bypasses Filament's table. --}}
            <div class="text-center you-dont-have-any d-flex justify-content-center mt-5 flex-column align-items-center" style="min-height: 300px;">
                {{-- Empty-state icon — the shared empty-state blade shows an illustration for
                     Orders/Customers/etc.; Categories had none. A muted category (stacked)
                     glyph gives it the same visual anchor without a heavy inline SVG. --}}
                <x-filament::icon icon="heroicon-o-rectangle-stack"
                    class="mw-admin-empty-state-icon"
                    aria-hidden="true" />
                <h2 style="font-weight: 600;" class="mw-admin-empty-state-heading text-center mt-4">
                    You do not have any categories yet.
                </h2>
                <p class="text-center mt-2" style="max-width: 480px;">
                    Categories group related pages, posts, and products together so customers can browse your content. Add your first category to get started.
                </p>
                <div class="text-center mw-table-empty-cta-wrap mt-3">
                    <a href="{{ route('filament.admin.resources.categories.create') }}" class="mw-table-empty-cta" aria-label="Add category">
                        + Add category
                    </a>
                </div>
            </div>
        @endif






        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER, scopes: $this->getRenderHookScopes()) }}
    </div>
</x-filament-panels::page>
