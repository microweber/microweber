<div>

    <button type="button"  class="btn btn-badge-dropdown btn-outline-dark js-dropdown-toggle-{{$this->id}} @if(!empty($selectedItem)) btn-secondary @else btn-outline-secondary @endif btn-sm icon-left">

        @if($selectedItem) {{$name}}: {{$selectedItem}} @else  {{$name}}  @endif


        <div class="d-flex actions">
            <div class="action-dropdown-icon"><svg fill="currentColor"  xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M480-344 240-584l43-43 197 197 197-197 43 43-240 240Z"/></svg></div>
         {{--   @if($selectedItem)
                <div class="action-dropdown-delete" wire:click="resetProperties"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z"/></svg></div>
            @endif--}}
            <div class="action-dropdown-delete" wire:click.stop="hideFilterItem('{{$this->id}}')"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z"/></svg></div>
        </div>


    </button>

    <div class="badge-dropdown position-absolute js-dropdown-content-{{$this->id}} @if($showDropdown) active @endif ">
        <span class="text-muted">Select Date</span>
        <input type="date" wire:keydown.enter="closeDropdown('{{$this->id}}')" wire:model="selectedItem" class="form-control" />
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
                $('.js-dropdown-content-{{$this->id}}').toggleClass('active');
            });
        });
    </script>

</div>
</div>
