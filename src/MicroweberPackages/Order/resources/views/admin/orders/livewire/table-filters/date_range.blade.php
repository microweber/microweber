


<div class=" col-12 col-sm-6 col-md-4 col-lg-4 mb-4">

    <input type="text" id="js-date-range" wire:model.stop="filters.dateBetween">

    <label class="d-block">
        Order Date Range
    </label>
    <div class="mb-3 mb-md-0 input-group">
        <input id="js-date-range-picker" class="form-control" type="text"/>
    </div>

</div>

<script>
    mw.lib.require("air_datepicker");

    var myDataPicker = $('#js-date-range-picker').datepicker({
        language: 'en',
        timepicker: false,
        range: true,
        multipleDates: true,
        dateFormat: '',
        multipleDatesSeparator: " - ",
        selectedDates: [new Date()],
        onSelect: function (fd, d, picker) {

            var dateRange = fd;
            dateRange = dateRange.replace(' - ', ',');

            let dateRangeElement = document.getElementById('js-date-range');
            dateRangeElement.value = dateRange;
            dateRangeElement.dispatchEvent(new Event('input'));

        }
    });
</script>
