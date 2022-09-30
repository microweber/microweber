<livewire:admin-products-list/>
<script>

    // document.body.addEventListener("click", function (e) {
    //     if (mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['js-filter-item-dropdown'])) {
    //
    //         var elementTarget = mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target, ['js-filter-item-dropdown']);
    //     }
    // });

</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {

        Livewire.hook('element.updated', (el, component) => {
            var elementTargetId = $(el).attr('wire:id');
            if (typeof elementTargetId != 'undefined') {
                var elementTargetIdOpenendDropdown = $(el).attr('data-dropdown-show');
                $(".js-filter-item-dropdown[data-dropdown-show='1']").each(function () {
                    var elementTargetIdOther = $(this).attr('wire:id');
                    if (elementTargetIdOther) {
                        if (elementTargetId != elementTargetIdOther) {
                            window.livewire.emit('closeDropdown', elementTargetIdOther);
                        }
                    }
                });
            }
        })

    });
</script>
