<div>
    @php
        use Filament\Support\Facades\FilamentView;

        $id = $getId();
        $statePath = $getStatePath();
    @endphp

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
                    if (skip.length > 0) {
                        options.skip = skip;
                    }

                    console.log(options);


                    var opts = {
                        options
                    };

                    let pagesTree = await mw.widget.tree('#mw-tree-edit-content-{{$suffix}}', opts);
                    pagesTree.tree.on('selectionChange', e => {
                        let items = pagesTree.tree.getSelected();
                        // console.log(this.state)
                        let selectedCategories = [];
                        let selectedParentPage = 0;
                        $.each(items, function (key, item) {
                            if (item.type == 'category') {
                                selectedCategories.push(item.id)
                            }
                            if (item.type == 'page') {
                                selectedParentPage = (item.id)
                            }
                        });

                        //if(selectedParentPage){
                        this.state.parent = selectedParentPage;
                        // }

                        this.state.categoryIds = selectedCategories;


                        //this.state = result;
                        //  Livewire.dispatch('mwTreeSelectionChange', items);
                        //Livewire.set('parent','2')
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

        <div wire:ignore id="mw-tree-edit-content-{{$suffix}}">Loading...</div>

    </div>


</div>
