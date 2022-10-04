<div>

    <button type="button"  class="btn btn-badge-dropdown js-dropdown-toggle-{{$this->id}} @if(!empty($selectedItem)) btn-secondary @else btn-outline-secondary @endif btn-sm icon-left">

        @if($selectedItem) {{$name}}: {{$selectedItem}} @else  {{$name}}  @endif <span class="mt-2">&nbsp;</span>


        <div class="d-flex actions">
            <div class="action-dropdown-icon"><i class="fa fa-chevron-down"></i></div>
            @if($selectedItem)
                <div class="action-dropdown-delete" wire:click="resetProperties"><i class="fa fa-times-circle"></i></div>
            @endif
        </div>


    </button>

    <div class="badge-dropdown position-absolute js-dropdown-content-{{$this->id}} @if($showDropdown) active @endif ">
        <span class="text-muted">Select Date</span>
        <input type="date" wire:model="selectedItem" class="form-control" />
    </div>


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
