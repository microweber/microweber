<div>

    <button type="button" wire:click="load" class="btn @if(!empty($selectedItem)) btn-primary @else btn-outline-primary @endif btn-sm icon-left">



        @if($itemValue)
            {{$name}}

            @if($itemOperatorValue == '')
                equal to:
            @endif

            @if($itemOperatorValue == 'greater')
                greater than:
            @endif

            @if($itemOperatorValue == 'lower')
                lower than:
            @endif

            {{$itemValue}}
        @else
            {{$name}}
        @endif

        <span class="mt-2">&nbsp;</span>

        <i class="ml-2 fa fa-arrow-down" style="font-size: 10px"></i>
    </button>

    <div class="badge-dropdown position-absolute" @if(!$showDropdown) style="display: none" @endif>


        <div class="mb-3 mb-md-0 input-group">
            <select class="form-control" wire:model.stop="itemOperatorValue">
                <option value="">Equal to</option>
                <option value="greater">More than</option>
                <option value="lower">Lower than</option>
            </select>
            <input type="number" class="form-control" placeholder="{{$name}} count" wire:model.stop="itemValue">
        </div>


    </div>
</div>
