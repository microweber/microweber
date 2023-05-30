<div>


    <button type="button" @if($searchable) wire:click="load('{{$this->id}}')" @endif class="btn btn-badge-dropdown btn-outline-dark js-dropdown-toggle-{{$this->id}} @if($selectedItemText) btn-secondary @else btn-outline-secondary @endif btn-sm icon-left">

       @if($selectedItemText) {{$name}}: {{$selectedItemText}} @else  {{$name}}  @endif


       <div class="d-flex actions">
           <div class="action-dropdown-icon"><svg fill="currentColor"  xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M480-344 240-584l43-43 197 197 197-197 43 43-240 240Z"/></svg></div>
        {{--   @if($selectedItemText)
               <div class="action-dropdown-delete" wire:click="resetProperties"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z"/></svg></div>
           @endif--}}
           <div class="action-dropdown-delete" wire:click.stop="hideFilterItem('{{$this->id}}')">
               <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z"/></svg>
           </div>
       </div>

    </button>

    <div class="badge-dropdown position-absolute js-dropdown-content-{{$this->id}} @if($showDropdown) active @endif ">

        @if($searchable)
        <div class="input-group mb-4">
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
        @endif

        <ul class="list-group list-group-compact" id="js-filter-items-values-list"  @if($searchable) style="z-index: 200;max-height: 300px;overflow-x:hidden; overflow-y: scroll;" @endif>
            @if(!empty($data))
                @foreach($data as $i=>$item)
                    <li class="list-group-item cursor-pointer">
                        <label class="form-check-label" for="filterItemRadio{{$i}}-{{$this->id}}">
                            <input class="form-check-input me-2" type="radio" wire:model="selectedItem" value="{{ $item['key'] }}" id="filterItemRadio{{$i}}-{{$this->id}}">
                            {{ $item['value'] }}
                        </label>
                    </li>
                @endforeach
                @if($total > count($data))
                <span class="cursor-pointer text-primary mt-2 mb-2" wire:click="loadMore">Load more</span>
                @endif
            @endif
        </ul>

        @if($selectedItem || $searchable)
            <div class="d-flex pt-3"   >
                @if($selectedItem)
                    <div class="col">
                        <span class="cursor-pointer text-muted" wire:click="resetProperties">
                            Clear selection
                        </span>
                    </div>
                @endif

                @if($searchable)
                 <div class="col text-right">{{count($data)}} of {{$total}}</div>
                @endif
            </div>
        @endif

    </div>


<div wire:ignore>
    <script>
        window.livewire.on('loadMoreExecuted', () => {
            document.getElementById("js-filter-items-values-list").scrollTop = 10000;
        });
    </script>

    <script>
        $(document).ready(function() {
           $('body').on('click', function(e) {
                if (!mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target,['js-dropdown-toggle-{{$this->id}}','js-dropdown-content-{{$this->id}}'])) {
                    $('.js-dropdown-content-{{$this->id}}').removeClass('active');
                }
            });
            $('.js-dropdown-toggle-{{$this->id}}').click(function () {
                $('.js-dropdown-content-{{$this->id}}').toggleClass('active');
            });
        });
    </script>
</div>
</div>
