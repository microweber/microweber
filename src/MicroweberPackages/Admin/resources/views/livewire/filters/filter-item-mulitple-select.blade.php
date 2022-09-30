<div>

    <button type="button"

            wire:click="refreshQueryData"
            wire:blur="closeDropdown"

            class="btn @if(!empty($selectedItems)) btn-primary @else btn-outline-primary @endif btn-sm icon-left">

            @if(!empty($selectedItems))
        {{$name}}: {{$firstItemName}} @if(count($selectedItems) > 1) ... @endif <span class="badge badge-filter-item mt-1">+{{count($selectedItems)}}</span>
        @else
            {{$name}} <span class="mt-2">&nbsp;</span>
        @endif

        <i class="ml-2 fa fa-arrow-down" style="font-size: 10px"></i>
    </button>

    @if($showDropdown)
        <div class="badge-dropdown position-absolute" wire:blur="closeDropdown">

        <div wire:loading wire:target="query">
            {{$searchingText}}
        </div>

            <div class="input-group">
                <input class="form-control"
                       type="search"
                       wire:click="showDropdown"
                       wire:model.debounce.500ms="query"
                       placeholder="{{$placeholder}}"
                >
            </div>

            <div wire:loading wire:target="query">
                {{$searchingText}}
            </div>

            @if($showDropdown)

            <ul class="list-group list-group-compact mt-4" id="js-filter-items-values-list" style="z-index: 200;max-height: 300px;overflow-x:hidden; overflow-y: scroll;">
                @if(!empty($data))
                    @foreach($data as $item)
                        <li class="list-group-item list-group-item-action cursor-pointer">
                            <input class="form-check-input me-1" type="checkbox" wire:model.debounce.1000ms="selectedItems" value="{{ $item['key'] }}" id="checkbox-{{ $item['key'] }}">
                            <label class="form-check-label stretched-link" for="checkbox-{{ $item['key'] }}">{{ $item['value'] }}</label>
                        </li>
                    @endforeach
                    <span class="cursor-pointer text-primary mt-2 mb-2" wire:click="loadMore">Load more</span>
                @endif
            </ul>

            <script>
                window.livewire.on('loadMoreExecuted', () => {
                //    document.getElementById("js-filter-items-values-list").scrollTop = 10000;
                });
            </script>

            <div class="d-flex pt-3" style="border-top: 1px solid #cfcfcf">
                @if($selectedItems)
                    <div class="col">
                        <span class="cursor-pointer text-muted" wire:click="resetProperties">
                            Clear selection
                        </span>
                    </div>
                @endif
            @endif
                <div class="col text-right">{{count($data)}} of {{$total}}</div>
            </div>

        </div>
    @endif

</div>
