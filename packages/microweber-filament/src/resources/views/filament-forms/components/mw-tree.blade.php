@php
    use Filament\Support\Facades\FilamentView;

    $id = $getId();
    $statePath = $getStatePath();
@endphp
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :has-inline-label="false"
>
    @php
        $suffix = '';
        $suffix = $this->getId();
    @endphp

    {{-- task-2026-10-03 — wire:ignore the whole widget. This field is ->live(),
         so selecting a parent (and the following Title sync) re-renders the form;
         without this, morphdom re-ran Alpine's init() and mw.widget.tree built a
         SECOND tree into the (preserved) container — the tree rendered twice. The
         inner container is also wire:ignore'd; the init() guard below is a belt-
         and-suspenders against any re-entry. --}}
    <div
        wire:ignore
        x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            async init() {
                var _box = document.getElementById('mw-tree-edit-content-{{$suffix}}');
                if (!_box || _box.dataset.mwTreeBuilt === '1') { return; }
                _box.dataset.mwTreeBuilt = '1';

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
