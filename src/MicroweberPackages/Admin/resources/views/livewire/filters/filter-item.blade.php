<div class="js-filter-item-dropdown" @if($showDropdown) data-dropdown-show="1" @else data-dropdown-show="0" @endif>

    <button type="button" wire:click="load"

            class="btn @if(!empty($selectedItems)) btn-primary @else btn-outline-primary @endif btn-sm icon-left">

       @if($selectedItemText) {{$name}}: {{$selectedItemText}} @else  {{$name}}  @endif <span class="mt-2">&nbsp;</span>

        <i class="ml-2 fa fa-arrow-down" style="font-size: 10px"></i>
    </button>

    @if($showDropdown)

        <li class="badge-dropdown position-absolute">

        <div wire:loading wire:target="query">
            {{$searchingText}}
        </div>

            <div class="input-group">
                <input class="form-control"
                       type="search"
                       wire:click="showDropdown('{{$this->id}}')"
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
                    @foreach($data as $i=>$item)
                        <li class="list-group-item cursor-pointer">
                            <input class="form-check-input me-1" type="radio" wire:model="selectedItem" value="{{ $item['key'] }}" id="filterItemRadio{{$i}}">
                            <label class="form-check-label" for="filterItemRadio{{$i}}">
                                {{ $item['value'] }}
                            </label>
                        </li>
                    @endforeach
                    @if($total > count($data))
                    <span class="cursor-pointer text-primary mt-2 mb-2" wire:click="loadMore">Load more</span>
                    @endif
                @endif
            </ul>

            <script>
                window.livewire.on('loadMoreExecuted', () => {
                    document.getElementById("js-filter-items-values-list").scrollTop = 10000;
                });
            </script>

            <div class="d-flex pt-3" style="border-top: 1px solid #cfcfcf">
                @if($selectedItem)
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
