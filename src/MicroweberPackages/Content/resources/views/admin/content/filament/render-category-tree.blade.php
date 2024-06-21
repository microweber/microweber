<div>
    <x-filament::button
        wire:click="$dispatch('showCategoriesSelectorPanel')"
        data-mw-category-selector="true"
        icon="heroicon-m-list-bullet"
        icon-position="before"
        tooltip="Show Categories">
        Categories
    </x-filament::button>


</div>

<div class="mw-tree-container">

</div>
@script
<script>

    document.addEventListener('livewire:initialized', () => {

        Livewire.on('showCategoriesSelectorPanel', () => {


            var pagesTree = mw.widget.tree('.mw-tree-container');

            console.log(pagesTree)


        });
    })
</script>

@endscript

