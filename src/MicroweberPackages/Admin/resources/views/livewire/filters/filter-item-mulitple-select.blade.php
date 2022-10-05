<div>

    <button type="button" class="btn btn-badge-dropdown js-dropdown-toggle-{{$this->id}} @if(!empty($selectedItems)) btn-secondary @else btn-outline-secondary @endif btn-sm icon-left">

    @if(!empty($selectedItems))
        {{$name}}: {{$firstItemName}} @if(count($selectedItems) > 1) ... @endif <span class="badge badge-filter-item mt-1">+{{count($selectedItems)}}</span>
        @else
            {{$name}} <span class="mt-2">&nbsp;</span>
        @endif

        <div class="d-flex actions">
            <div class="action-dropdown-icon"><i class="fa fa-chevron-down"></i></div>
            @if(!empty($selectedItems))
                <div class="action-dropdown-delete" wire:click="resetProperties"><i class="fa fa-times-circle"></i></div>
            @endif
        </div>

    </button>


    <div class="badge-dropdown position-absolute js-dropdown-content-{{$this->id}} @if($showDropdown) active @endif ">
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

        <ul class="list-group list-group-compact mt-4" id="js-filter-items-values-list" style="z-index: 200;max-height: 300px;overflow-x:hidden; overflow-y: scroll;">
            @if(!empty($data))
                @foreach($data as $item)
                    <li class="list-group-item list-group-item-action cursor-pointer">
                        <input class="form-check-input me-1" type="checkbox" wire:model="selectedItems" value="{{ $item['key'] }}" id="checkbox-{{ $item['key'] }}">
                        <label class="form-check-label stretched-link" for="checkbox-{{ $item['key'] }}">{{ $item['value'] }}</label>
                    </li>
                @endforeach
                <span class="cursor-pointer text-primary mt-2 mb-2" wire:click="loadMore">Load more</span>
            @endif
        </ul>

        <script>
            window.livewire.on('loadMoreExecuted', () => {
                document.getElementById("js-filter-items-values-list").scrollTop = 10000;
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
            <div class="col text-right">{{count($data)}} of {{$total}}</div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('body').on('click', function(e) {
                if (!mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target,['js-dropdown-toggle-{{$this->id}}','js-dropdown-content-{{$this->id}}'])) {
                    $('.js-dropdown-content-{{$this->id}}').removeClass('active');
                }
            });
            $('.js-dropdown-toggle-{{$this->id}}').click(function () {
                $('.js-dropdown-content-{{$this->id}}').toggleClass('active', function(){
                    if ($(this).hasClass('active')) {
                        
                    }
                });

            });
        });
    </script>
</div>
