<x-filament-panels::page
    @class([
        'fi-resource-list-records-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    ])
>
    <div class="flex flex-col gap-y-6">
        <x-filament-panels::resources.tabs/>

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

                 console.log(pagesTree)

                pagesTree.tree.on('selectionChange', e => {
                    let result = pagesTree.tree.getSelected();
                    this.state = result;
                })
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



            <div wire:ignore class="mw-edit-categories-list" id="mw-tree-edit-content-{{$suffix}}"></div>






        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER, scopes: $this->getRenderHookScopes()) }}
    </div>
</x-filament-panels::page>
