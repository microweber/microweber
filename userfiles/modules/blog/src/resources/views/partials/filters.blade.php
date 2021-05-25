@foreach($filters as $filterKey=>$filter)

    <div class="col-md-12 pb-3">

        @if ($filter->type == 'dropdown' || $filter->type == 'radio')

            <b>{{$filter->name}}</b>

            @foreach($filter->options as $options)
                <div class="form-check">
                    <input class="form-check-input js-filter-option-select" @if ($options->active) checked @endif type="checkbox" name="filters[{{$filterKey}}][]" value="{{$options->value}}">
                    <label class="form-check-label">
                        {{ $options->value }}
                    </label>
                </div>
            @endforeach
        @endif

        @if ($filter->type == 'date')
            <b>{{$filter->name}}</b>

            @foreach($filter->options as $options)
            @endforeach

            <div id="datepicker-xx">x</div>

        @endif
    </div>

@endforeach

<script>
    mw.lib.require("air_datepicker");
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#datepicker-xx').daterangepicker({
            container: '#date-range12-container',
            inline:true,
            alwaysOpen:true,
            showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(moment().format('YYYY'), 10)
        }, function(start, end, label) {

        });
    });
</script>

