<div class="">

    <div class="input-group">
        <input class="form-control"
           type="search"
           wire:model.debounce.500ms="query"
           wire:keydown.escape="resetProperties"
           wire:keydown.enter="refreshQueryData"
           wire:click="refreshQueryData"
           wire:blur="closeDropdown"

           @if($placeholderWithTags)
               placeholder="{{$placeholderWithTags}}"
           @else
              placeholder="{{$placeholder}}"
           @endif
        >

        @if($selectedItems)
            <button class="btn btn-sm bg-transparent" wire:click="resetProperties" style="margin-left: -40px; z-index: 100;">
                <i class="fa fa-times text-muted"></i>
            </button>
        @endif
    </div>

    <div wire:loading wire:target="query">
        {{$searchingText}}
    </div>

    @if($showDropdown)
        <ul class="list-group position-absolute" style="z-index: 200;max-height: 300px;overflow-x:hidden; overflow-y: scroll;">
            @if(!empty($data))
                @foreach($data as $item)
                    <li class="list-group-item list-group-item-action cursor-pointer">
                        <input class="form-check-input me-1" type="checkbox" wire:model="selectedItems" value="{{ $item['key'] }}" id="checkbox-{{ $item['key'] }}">
                        <label class="form-check-label stretched-link" for="checkbox-{{ $item['key'] }}">{{ $item['value'] }}</label>
                    </li>
                @endforeach
            @endif
        </ul>
    @endif

</div>
