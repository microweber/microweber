<div class="js-filter-item-wrapper-{{$this->id}}">

    <button type="button" @if(empty($data)) wire:click="load('{{$this->id}}')" @endif class="btn btn-badge-dropdown btn-outline-dark js-dropdown-toggle-{{$this->id}} @if(!empty($selectedItems)) btn-secondary @else btn-outline-secondary @endif btn-sm icon-left">

    @if(!empty($selectedItems))
        {{$name}}: {{$firstItemName}} @if(count($selectedItems) > 1) ... @endif <span class="badge badge-filter-item mt-1">+{{count($selectedItems)}}</span>
        @else
            {{$name}}
        @endif

        <div class="d-flex actions">
            <div class="action-dropdown-icon"><svg fill="currentColor"  xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M480-344 240-584l43-43 197 197 197-197 43 43-240 240Z"/></svg></div>
            {{--@if(!empty($selectedItems))
                <div class="action-dropdown-delete" wire:click="resetProperties"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z"/></svg></div>
            @endif--}}
            <div class="action-dropdown-delete" wire:click.stop="hideFilterItem('{{$this->id}}')"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z"/></svg></div>
        </div>

    </button>


    <div class="badge-dropdown position-absolute js-dropdown-content-{{$this->id}} @if($showDropdown) active @endif ">
        <div wire:loading wire:target="query">
        {{$searchingText}}
    </div>

        <div class="input-group">
            <input class="form-control"
                   type="search"
                   wire:keydown.enter="closeDropdown('{{$this->id}}')"
                   wire:click="showDropdown('{{$this->id}}')"
                   wire:model.debounce.500ms="query"
                   placeholder="{{$placeholder}}"
            >
        </div>

        <div wire:loading wire:target="query">
            {{$searchingText}}
        </div>

        @if(empty($data))
            <div class="p-3">
                {{_e('No '.strtolower($name).' found')}}
            </div>
        @else
        <ul class="list-group list-group-compact mt-4" id="js-filter-items-values-list" style="z-index: 200;max-height: 300px;overflow-x:hidden; overflow-y: scroll;">
            @foreach($data as $item)
                <li class="list-group-item list-group-item-action cursor-pointer d-flex">
                    <input class="form-check-input me-2" type="checkbox" wire:model="selectedItems" value="{{ $item['key'] }}" id="checkbox-{{ $item['key'] }}">
                    <label class="form-check-label stretched-link" for="checkbox-{{ $item['key'] }}">{{ $item['value'] }}</label>
                </li>
            @endforeach
            @if(count($data) != $total)
            <span class="cursor-pointer text-primary mt-2 mb-2" wire:click="loadMore">Load more</span>
            @endif
        </ul>
        @endif

        <script>
            window.livewire.on('loadMoreExecuted', () => {
                document.getElementById("js-filter-items-values-list").scrollTop = 10000;
            });
        </script>

        <div class="d-flex pt-3"   >
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

<div wire:ignore>
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
</div>
