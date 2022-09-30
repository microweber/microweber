<div class="js-filter-item-dropdown">

    <button type="button" wire:click="showDropdown" class="btn @if(!empty($selectedItem)) btn-primary @else btn-outline-primary @endif btn-sm icon-left">

        {{$name}} <span class="mt-2">&nbsp;</span>

        <i class="ml-2 fa fa-arrow-down" style="font-size: 10px"></i>
    </button>

    <div class="badge-dropdown position-absolute" @if(!$showDropdown) style="display: none" @endif>
        <span class="text-muted">Select Date</span>
        <input type="date" class="form-control" />
    </div>

</div>

<script>
    document.body.addEventListener("click", function(e) {
        let filterItemDate= document.getElementsByClassName('js-filter-item-dropdown')[0];
        if (!filterItemDate.contains(e.target)) {
            window.livewire.emit('closeDropdown');
        }
    });
</script>
