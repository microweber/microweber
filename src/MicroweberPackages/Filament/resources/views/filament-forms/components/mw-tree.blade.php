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
    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.data('mwTreeFormComponent', ({state}) => ({
                state,
                async init() {
                    let pagesTree = await mw.widget.tree('#mw-tree-edit-content');
                    pagesTree.tree.on('selectionChange', e => {
                        let result = pagesTree.tree.getSelected();
                        this.state = result;
                    })
                }
            }))

        })
    </script>

    @if($this->data) 
        {{json_encode($this->data['mw_parent_page_and_category_state'],JSON_PRETTY_PRINT)}}
    @endif

    <div
        x-data="mwTreeFormComponent({
                state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }}
            })"
    >

        <div wire:ignore id="mw-tree-edit-content">Loading...</div>

    </div>


</x-dynamic-component>


