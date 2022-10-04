<div>

    <button type="button" class="btn js-dropdown-toggle-{{$this->id}} @if(!empty($selectedItem)) btn-primary @else btn-outline-primary @endif btn-sm icon-left">

        @if($itemValue)
            {{$name}}: {{$itemValue}}
        @else
            {{$name}}
        @endif

        <span class="mt-2">&nbsp;</span>

        <i class="ml-2 fa fa-arrow-down" style="font-size: 10px"></i>
    </button>

    <div class="badge-dropdown position-absolute js-dropdown-content-{{$this->id}}" @if(!$showDropdown) style="display: none" @endif>
        <div class="mb-3 mb-md-0">
            <label>{{$name}} </label>
            <input type="text" class="form-control" placeholder="Fill the {{$name}}" wire:model.stop="itemValue">
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('body').on('click', function(e) {
                if (!mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target,['js-dropdown-toggle-{{$this->id}}','js-dropdown-content-{{$this->id}}'])) {
                    $('.js-dropdown-content-{{$this->id}}').hide();
                }
            });
            $('.js-dropdown-toggle-{{$this->id}}').click(function () {
                $('.js-dropdown-content-{{$this->id}}').toggle();
            });
        });
    </script>
</div>
