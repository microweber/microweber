<div>

    <button type="button"  class="btn btn-badge-dropdown @if(!empty($selectedItem)) btn-primary @else btn-outline-primary @endif btn-sm icon-left">

        @if($selectedItem) {{$name}}: {{$selectedItem}} @else  {{$name}}  @endif <span class="mt-2">&nbsp;</span>

    </button>

    <div class="badge-dropdown position-absolute" @if(!$showDropdown) style="display: none" @endif>
        <span class="text-muted">Select Date</span>
        <input type="date" wire:model="selectedItem" class="form-control" />
    </div>

</div>
