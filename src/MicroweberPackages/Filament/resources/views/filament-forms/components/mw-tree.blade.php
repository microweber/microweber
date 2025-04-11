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




    <script>


        document.addEventListener('alpine:init', () => {

            Alpine.data('mwTreeFormComponent', ({state}) => ({
                state,
                async init() {
                    var skip = [];
                    var selectedData = [];
                    var options = {
                        selectable: true
                    };

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
                    })
                }
            }))

        })
    </script>


    <?php

    /*
    @if($this->data)
    {{json_encode($this->data['mw_parent_page_and_category_state'],JSON_PRETTY_PRINT)}}
    @endif
     */
    ?>


    <div


        x-data="mwTreeFormComponent({
                state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }}
            })"
    >

        <div wire:ignore id="mw-tree-edit-content-{{$suffix}}"> </div>

    </div>


</x-dynamic-component>


