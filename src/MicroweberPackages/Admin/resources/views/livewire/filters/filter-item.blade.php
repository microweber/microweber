<div>

    <button type="button"

            wire:click="refreshQueryData"

            class="btn btn-primary icon-left dropdown-toggle">
        Author @if($query): {{$query}} @endif
    </button>

    @if($showDropdown)
        <div class="badge-dropdown position-absolute">

        <div wire:loading wire:target="query">
            {{$searchingText}}
        </div>

            <div class="input-group">
                <input class="form-control"
                       type="search"
                       wire:model.debounce.500ms="query"
                       wire:keydown.escape="resetProperties"
                       wire:keydown.enter="refreshQueryData"
                       wire:click="refreshQueryData"
                       wire:blur="closeDropdown"

                       placeholder="{{$placeholder}}"
                >
            </div>

            <div wire:loading wire:target="query">
                {{$searchingText}}
            </div>

            @if($showDropdown)
                <ul class="list-group list-group-compact mt-4" style="z-index: 200;max-height: 300px;overflow-x:hidden; overflow-y: scroll;">
                    @if(!empty($data))
                        @foreach($data as $item)
                            <li class="list-group-item list-group-item-action cursor-pointer">
                                <input class="form-check-input me-1" type="checkbox" wire:model="selectedItems" value="{{ $item['key'] }}" id="checkbox-{{ $item['key'] }}">
                                <label class="form-check-label stretched-link" for="checkbox-{{ $item['key'] }}">{{ $item['value'] }}</label>
                            </li>
                        @endforeach
                    @endif
                </ul>

            <div class="d-flex">
                <button type="button" class="btn btn-link mt-2">Load more</button>

                @if($selectedItems)
                    <button class="btn btn-link text-danger" wire:click="resetProperties">
                        <i class="fa fa-times text-muted"></i> Clear all
                    </button>
                @endif
            @endif
            </div>

        </div>
    @endif

</div>
