<div>
    <button type="button" class="btn btn-badge-dropdown btn-outline-dark js-dropdown-toggle-{{$this->id}} @if($itemValue) btn-secondary @else btn-outline-secondary @endif btn-sm icon-left">

        @if($itemValue)
            {{$name}}: {{str_replace(',',' - ',$itemValue)}}
        @else
            {{$name}}
        @endif



        <div class="d-flex actions">
            <div class="action-dropdown-icon"><svg fill="currentColor"  xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M480-344 240-584l43-43 197 197 197-197 43 43-240 240Z"/></svg></div>
         {{--   @if($itemValue)
                <div class="action-dropdown-delete" wire:click="resetProperties"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z"/></svg></div>
            @endif--}}
            <div class="action-dropdown-delete" wire:click.stop="hideFilterItem('{{$this->id}}')"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z"/></svg></div>
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
                if (typeof dateRangeElement !== 'undefined') {

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
