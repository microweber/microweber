<div>

    <button type="button" wire:click="load" class="btn @if(!empty($selectedItem)) btn-primary @else btn-outline-primary @endif btn-sm icon-left">


        @if($itemValue)
            {{$name}}: {{$itemValue}}
        @else
            {{$name}}
        @endif

        <span class="mt-2">&nbsp;</span>

        <i class="ml-2 fa fa-arrow-down" style="font-size: 10px"></i>
    </button>

    <div class="badge-dropdown position-absolute" @if(!$showDropdown) style="display: none" @endif>

        <div class="mb-3 mb-md-0 input-group">
        <input type="text" class="form-control" placeholder="Fill the {{$name}}" wire:model.stop="itemValue">
        </div>

    </div>
</div>
