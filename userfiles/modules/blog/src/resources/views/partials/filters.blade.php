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

            <div id="js-filter-option-datepicker"></div>

        @endif
    </div>

@endforeach

<script>
    mw.lib.require("air_datepicker");
</script>

<script type="text/javascript">
    $(document).ready(function () {

        @php
            $filters = \Request::get('filters', false);
            $filtersFromDate = false;
            if (isset($filters['date'])) {
                $filtersFromDate = $filters['date'];
            }
        @endphp

        $('#js-filter-option-datepicker').datepicker({
            timepicker: true,
            range: true,
            multipleDates: true,
            multipleDatesSeparator: " - ",
            onRenderCell: function (d, cellType) {
                var currentDate = d.getFullYear() + "-"+ d.getMonth()  + "-"+ d.getDate();
                if (cellType == 'day' && currentDate == '{{$filtersFromDate}}') {
                    return {
                        html: '<div style="background:#20badd2e;border-radius:4px;color:#fff;padding:10px 11px;">'+d.getDate()+'</div>'
                    }
                }
            },
            onSelect: function (fd, d, picker) {
                // Do nothing if selection was cleared
                if (!d) return;

                var dateRange = d[0].getFullYear() + "-"+ d[0].getMonth()  + "-"+ d[0].getDate();

                var redirectFilterUrl = getUrlAsArray();

                var findFromDataRangeKey = false;
                for (var i=0; i< redirectFilterUrl.length; i++) {
                    if (redirectFilterUrl[i].key == 'filters[date]') {
                        redirectFilterUrl[i].value = dateRange;
                        findFromDataRangeKey = true;
                        break;
                    }
                }

                if (findFromDataRangeKey === false) {
                    redirectFilterUrl.push({key: 'filters[date]', value: dateRange});
                }

                window.location.href = "{{ URL::current() }}?" + encodeDataToURL(redirectFilterUrl);
            }
        });
    });
</script>

