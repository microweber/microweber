<div class=" col-12 col-sm-6 col-md-4 col-lg-4 mb-4">

    <input type="hidden" id="js-date-range" wire:model.stop="filters.dateBetween">

    <label class="d-block">
        Orders Date Range
    </label>

    <div class="mb-3 mb-md-0 input-group">
        <input id="js-date-range-picker" class="form-control" type="text" />
    </div>

</div>

<script>
    mw.lib.require("air_datepicker");

    let dateRangeElement = document.getElementById('js-date-range');
    var dateRangePickerInstance = $('#js-date-range-picker').datepicker({
        language: 'en',
        timepicker: false,
        range: true,
        multipleDates: true,
        dateFormat: '',
        multipleDatesSeparator: " - ",
        onSelect: function (fd, d, picker) {

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
