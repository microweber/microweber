<livewire:admin-products-list />
<script>

    document.body.addEventListener("click", function (e) {
        if (mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['js-filter-item-dropdown'])) {

            setTimeout(function() {
                var elementTarget = mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target, ['js-filter-item-dropdown']);
                var clickedDropdownId = $(elementTarget).attr('wire:id');
                console.log(elementTarget);

                /* $('.js-filter-item-dropdown').each(function (item, element) {
                     if ($(element).attr('data-dropdown-show') == '1') {
                         console.log(clickedDropdownId);
                         //  window.livewire.emit('closeDropdown','1');
                     }
                 });*/
            }, 1000);

        }
    });

</script>
