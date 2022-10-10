<div>

    <button type="button" class="btn btn-badge-dropdown js-dropdown-toggle-{{$this->id}} @if($itemValue) btn-secondary @else btn-outline-secondary @endif btn-sm icon-left">

        @if($itemValue)
            {{$name}}: {{$itemValue}}
        @else
            {{$name}}
        @endif

        <span class="mt-2">&nbsp;</span>

        <div class="d-flex actions">
            <div class="action-dropdown-icon"><i class="fa fa-chevron-down"></i></div>
          {{--  @if($itemValue)
                <div class="action-dropdown-delete" wire:click="resetProperties"><i class="fa fa-times-circle"></i></div>
            @endif--}}
            <div class="action-dropdown-delete" wire:click="hideFilterItem('{{$this->id}}')"><i class="fa fa-times-circle"></i></div>
        </div>

    </button>

    <div class="badge-dropdown position-absolute js-dropdown-content-{{$this->id}} @if($showDropdown) active @endif ">
        <div class="mb-3 mb-md-0">
            <label>{{$name}} </label>
            <input type="text" class="form-control" placeholder="Fill the {{$name}}"

                   wire:keydown.enter="closeDropdown('{{$this->id}}')"
                   wire:model.stop="itemValue">
        </div>
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
                $('.js-dropdown-content-{{$this->id}}').toggleClass('active');
            });
        });
    </script>
</div>
