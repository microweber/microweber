@php
    use Filament\Support\Facades\FilamentView;

    $id = $getId();
    $statePath = $getStatePath();
@endphp
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :has-inline-label="$hasInlineLabel"
>
    @php
        $suffix = '';
        $suffix = $this->getId();
    @endphp

    <div
        x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            async init() {
                var skip = [];
                var selectedData = [];
                var options = {
                    selectable: true
                    @if(isset($singleSelect) && $singleSelect)
                    , singleSelect: true
                    @endif
                };

                @if(isset($selectedPage) && $selectedPage)
                    selectedData.push({
                        id: '{{$selectedPage}}',
                        type: 'page'
                    });
                @endif

                @if(isset($selectedCategories) && $selectedCategories)
                    @foreach($selectedCategories as $selectedCategory)
                        selectedData.push({
                            id: {{$selectedCategory}},
                            type: 'category'
                        });
                    @endforeach
                @endif

                if (selectedData.length > 0) {
                    options.selectedData = selectedData;
                }
                if(skip.length > 0){
                    options.skip = skip;
                }

                var opts = {
                    options
                };

                let pagesTree = await mw.widget.tree('#mw-tree-edit-content-{{$suffix}}', opts);
                pagesTree.tree.on('selectionChange', e => {
                    let result = pagesTree.tree.getSelected();
                    this.state = result;
                });
            }
        }"
    >
        <div wire:ignore id="mw-tree-edit-content-{{$suffix}}"></div>
    </div>

</x-dynamic-component>
