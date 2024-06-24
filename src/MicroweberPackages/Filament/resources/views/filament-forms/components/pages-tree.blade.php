@php
    use Filament\Support\Facades\FilamentView;

    $id = $getId();
    $statePath = $getStatePath();
@endphp
<div>


    @dump($id)
    @dump($statePath)

    @if($this->data)
        @dump($this->data)
    @endif


    <div x-data="{
           state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
        }"
         x-init="async () => {
                mw.initTree();
                if (state && state.length > 0) {


                }

                pagesTree = await mw.widget.tree('#mw-tree-edit-content');

                pagesTree.tree.on('selectionChange', e => {
                    const result = pagesTree.tree.getSelected();

                    console.log(result);

                    state = ['aaaaa', 'bbbbbb'];
                })





     }"
    >


        <div wire:ignore id="mw-tree-edit-content">
            drarvo


        </div>


        @script
        <script>
            mw.initTree = async function () {



            }

        </script>

        @endscript

    </div>
</div>


