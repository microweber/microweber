<div>
    <button type="button" class="btn btn-badge-dropdown js-dropdown-toggle-{{$this->id}} @if($itemValue) btn-secondary @else btn-outline-secondary @endif btn-sm icon-left">

        @if($itemValue)
            {{$name}}: {{str_replace(',',' - ',$itemValue)}}
        @else
            {{$name}}
        @endif

        <span class="mt-2">&nbsp;</span>

        <div class="d-flex actions">
            <div class="action-dropdown-icon"><i class="fa fa-chevron-down"></i></div>
         {{--   @if($itemValue)
                <div class="action-dropdown-delete" wire:click="resetProperties"><i class="fa fa-times-circle"></i></div>
            @endif--}}
            <div class="action-dropdown-delete" wire:click="hideFilterItem('{{$this->id}}')"><i class="fa fa-times-circle"></i></div>
        </div>

    </button>

    <div class="badge-dropdown position-absolute js-dropdown-content-{{$this->id}} @if($showDropdown) active @endif ">


        <input type="hidden" id="js-date-range" wire:model.stop="itemValue">

        <div class="mb-3 mb-md-0 input-group">
            <input id="js-date-range-picker" class="form-control" type="text" autocomplete="off" />
        </div>

    </div>
    <div wire:ignore>
        <script>
            mw.lib.require("air_datepicker");


            var dateRangePickerInstance = $('#js-date-range-picker').datepicker({
                language: 'en',
                timepicker: false,
                range: true,
                multipleDates: false,
                dateFormat: '',
                multipleDatesSeparator: " - ",
                onSelect: function (fd, d, picker) {

                    var dateRangeElement = document.getElementById('js-date-range');
                    var dateRange = fd;
                    dateRange = dateRange.replace(' - ', ',');

                    dateRangeElement.value = dateRange;
                    dateRangeElement.dispatchEvent(new Event('input'));

                }
            }).data('datepicker');

            document.addEventListener('livewire:load', function () {
                if (dateRangeElement) {
                    const dateRangeExp = dateRangeElement.value.split(",");
                    if (dateRangeExp && dateRangeExp[0] && dateRangeExp[1]) {
                        dateRangePickerInstance.selectDate([new Date(dateRangeExp[0]), new Date([dateRangeExp[1]])]);
                    }
                }
            });
        </script>
        <script>
            $(document).ready(function() {
                $('body').on('mousedown touchstart', function(e) {

                    var skipCloseDatePickerModal = false;
                    if (mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target, ['js-dropdown-toggle-{{$this->id}}', 'js-dropdown-content-{{$this->id}}'])) {
                        skipCloseDatePickerModal = true;
                    }


                    if (mw.tools.hasParentsWithClass(e.target, 'datepicker')) {
                        skipCloseDatePickerModal = true;
                    }
                    if (mw.tools.hasParentsWithClass(e.target, 'datepickers-container')) {
                        skipCloseDatePickerModal = true;
                    }

                    if (!skipCloseDatePickerModal) {
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
