<div class="">
    <input class="form-control"
           type="search"
           wire:model.debounce.500ms="query"
           wire:keydown.escape="resetProperties"
           wire:keydown.enter="refreshQueryData"
           wire:click="refreshQueryData"

           placeholder="{{$placeholder}}">

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
