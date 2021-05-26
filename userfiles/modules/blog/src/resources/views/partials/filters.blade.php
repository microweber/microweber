@foreach($filters as $filterKey=>$filter)

    <div class="col-md-12 pb-3">

        @if ($filter->controlType == 'checkbox')
            <b>{{$filter->name}}</b>
            @foreach($filter->options as $options)
                @php
                    $randIdForCheck = uniqid();
                @endphp
                <div class="form-check">
                    <input class="form-check-input js-filter-option-select" type="checkbox" id="{{$randIdForCheck}}" @if ($options->active) checked @endif name="filters[{{$filterKey}}][]" value="{{$options->value}}">
                    <label class="form-check-label" for="{{$randIdForCheck}}">
                        {{ $options->value }}
                    </label>
                </div>
            @endforeach
        @endif

        @if ($filter->controlType == 'select')
            <b>{{$filter->name}}</b>
            <select class="form-control js-filter-option-select" name="filters[{{$filterKey}}][]">
                <option>{{_e('Select')}}</option>
                @foreach($filter->options as $options)
                    <option @if ($options->active) selected="selected" @endif value="{{$options->value}}">{{ $options->value }}</option>
                @endforeach
            </select>
        @endif

        @if ($filter->controlType == 'radio')
            <b>{{$filter->name}}</b>
            @foreach($filter->options as $options)
                    <div class="form-check">
                        @php
                            $randIdForCheck = uniqid();
                        @endphp
                        <input class="form-check-input js-filter-option-select" type="radio" id="{{$randIdForCheck}}" @if ($options->active) checked @endif name="filters[{{$filterKey}}][]" value="{{$options->value}}">
                        <label class="form-check-label" for="{{$randIdForCheck}}">
                            {{ $options->value }}
                        </label>
                    </div>
            @endforeach
        @endif

        @if ($filter->controlType == 'date_range')
            <b>{{$filter->name}}</b>

            @foreach($filter->options as $options)
            @endforeach

            <div id="js-filter-option-datepicker"></div>

        @endif
    </div>

@endforeach


@php
    $filters = \Request::get('filters', false);
    $filtersFromDate = false;
    if (isset($filters['from_date'])) {
        $filtersFromDate = $filters['from_date'];
    }
    $filtersToDate = false;
    if (isset($filters['to_date'])) {
        $filtersToDate = $filters['to_date'];
    }
@endphp

<script>
    mw.lib.require("air_datepicker");
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#js-filter-option-datepicker').datepicker({
            timepicker: true,
            range: true,
            multipleDates: true,
            multipleDatesSeparator: " - ",
           /* onRenderCell: function (d, cellType) {
                var currentDate = d.getFullYear() + "-"+ d.getMonth()  + "-"+ d.getDate();
                if (cellType == 'day' && currentDate == '') {
                    return {
                        html: '<div style="background:#20badd2e;border-radius:4px;color:#fff;padding:10px 11px;">'+d.getDate()+'</div>'
                    }
                }
            },*/
            onSelect: function (fd, d, picker) {
                // Do nothing if selection was cleared
                if (!d[0]) return;
                if (!d[1]) return;

                var dateFromRange = d[0].getFullYear() + "-"+ d[0].getMonth()  + "-"+ d[0].getDate();
                var dateToRange = d[1].getFullYear() + "-"+ d[1].getMonth()  + "-"+ d[1].getDate();

                @if($filtersFromDate && $filtersToDate)
                if ((dateFromRange === '{{$filtersFromDate}}') && (dateToRange === '{{$filtersToDate}}')) {
                    return;
                }
                @endif

                var redirectFilterUrl = getUrlAsArray();

                redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, 'filters[from_date]', dateFromRange);
                redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, 'filters[to_date]', dateToRange);

               window.location.href = "{{ URL::current() }}?" + encodeDataToURL(redirectFilterUrl);
            }
        });

        function findOrReplaceInObject(object, key, value) {
            var findKey = false;
            for (var i=0; i< object.length; i++) {
                if (object[i].key == key) {
                    object[i].value = value;
                    findKey = true;
                    break;
                }
            }
            if (findKey === false) {
                object.push({key: key, value: value});
            }
            return object;
        }

        @if($filtersFromDate && $filtersToDate)
        //$('#js-filter-option-datepicker').data('datepicker').selectDate([new Date('{{$filtersFromDate}}'), new Date('{{$filtersToDate}}')]);
        @endif
    });
</script>

