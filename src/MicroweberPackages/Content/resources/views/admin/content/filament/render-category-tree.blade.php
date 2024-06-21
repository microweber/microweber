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


@script
<script>

    document.addEventListener('livewire:initialized', () => {

        let treeControllBox = false, pagesTree ;

        Livewire.on('showCategoriesSelectorPanel', async () => {
            if(!treeControllBox) {
                const id = mw.id();
                treeControllBox = new mw.controlBox({
                    content: `<div id="${id}" style="min-width: 250px;padding: 50px 0 0 30px"></div>`,
                    position:  'left',
                    id: `${id}`,
                    closeButton: true
                });
                treeControllBox.show()
                pagesTree = await mw.widget.tree(`#${id}`);

                pagesTree.on('selectionChange', e => {
                    const result = pagesTree.getSelected();

                    console.log(result);
                })

            } else {
                treeControllBox.toggle();
            }

            console.log(treeControllBox);
            console.log(pagesTree);




        });
    })
</script>

@endscript

