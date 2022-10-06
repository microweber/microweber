<div>


    <button type="button" @if($searchable) wire:click="load({{$this->id}})" @endif class="btn btn-badge-dropdown js-dropdown-toggle-{{$this->id}} @if($selectedItemText) btn-secondary @else btn-outline-secondary @endif btn-sm icon-left">

       @if($selectedItemText) {{$name}}: {{$selectedItemText}} @else  {{$name}}  @endif <span class="mt-2">&nbsp;</span>


       <div class="d-flex actions">
           <div class="action-dropdown-icon"><i class="fa fa-chevron-down"></i></div>
        {{--   @if($selectedItemText)
               <div class="action-dropdown-delete" wire:click="resetProperties"><i class="fa fa-times-circle"></i></div>
           @endif--}}
           <div class="action-dropdown-delete" wire:click="hideFilterItem('{{$this->id}}')"><i class="fa fa-times-circle"></i></div>
       </div>

    </button>

    <div class="badge-dropdown position-absolute js-dropdown-content-{{$this->id}} @if($showDropdown) active @endif ">

        @if($searchable)
        <div class="input-group mb-4">
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
        @endif

        <ul class="list-group list-group-compact" id="js-filter-items-values-list"  @if($searchable) style="z-index: 200;max-height: 300px;overflow-x:hidden; overflow-y: scroll;" @endif>
            @if(!empty($data))
                @foreach($data as $i=>$item)
                    <li class="list-group-item cursor-pointer">
                        <label class="form-check-label" for="filterItemRadio{{$i}}-{{$this->id}}">
                            <input class="form-check-input me-1" type="radio" wire:model="selectedItem" value="{{ $item['key'] }}" id="filterItemRadio{{$i}}-{{$this->id}}">
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
            <div class="d-flex pt-3" style="border-top: 1px solid #cfcfcf">
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
