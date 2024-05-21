<div>

    <button type="button"  class="btn btn-badge-dropdown btn-outline-dark js-dropdown-toggle-{{$this->getId()}} @if(!empty($selectedItem)) btn-secondary @else btn-outline-secondary @endif btn-sm icon-left">

        @if($selectedItem) {{$name}}: {{$selectedItem}} @else  {{$name}}  @endif


        <div class="d-flex actions">
            <div class="action-dropdown-icon"><svg fill="currentColor"  xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M480-344 240-584l43-43 197 197 197-197 43 43-240 240Z"/></svg></div>
         {{--   @if($selectedItem)
                <div class="action-dropdown-delete" wire:click="resetProperties"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z"/></svg></div>
            @endif--}}
            <div class="action-dropdown-delete" wire:click="hideFilterItem('{{$this->getId()}}')"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z"/></svg></div>
        </div>


    </button>

    <div class="badge-dropdown position-absolute js-dropdown-content-{{$this->getId()}} @if($showDropdown) active @endif ">
        <span class="text-muted">Select Date</span>
        <input type="date" wire:keydown.enter="closeDropdown('{{$this->getId()}}')" wire:model.live="selectedItem" class="form-control" />
    </div>

<div wire:ignore>
    <script>
        $(document).ready(function() {
            $('body').on('click', function(e) {
                if (!mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target,['js-dropdown-toggle-{{$this->getId()}}','js-dropdown-content-{{$this->getId()}}'])) {
                    $('.js-dropdown-content-{{$this->getId()}}').removeClass('active');
                }
            });
            $('.js-dropdown-toggle-{{$this->getId()}}').click(function () {
                $('.js-dropdown-content-{{$this->getId()}}').toggleClass('active');
            });
        });
    </script>

</div>
</div>
