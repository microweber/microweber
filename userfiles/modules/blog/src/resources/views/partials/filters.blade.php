
<script type="text/javascript">
    $(document).ready(function () {
        $('.js-filter-option-select').change(function () {
            var queryParams = [];
            $.each($(this).serializeArray(), function(k,filter) {
                queryParams.push({
                    key:filter.name,
                    value:filter.value
                });
            });
            submitQueryFilter('{{$moduleId}}', queryParams);
        });
    });
</script>

@foreach($filters as $filterKey=>$filter)
    @include('blog::partials.filters.' . $filter->controlType)
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

                var dateFromRange = d[0].getFullYear() + "-"+ numericMonth(d[0])  + "-"+ d[0].getDate();
                var dateToRange = d[1].getFullYear() + "-"+ numericMonth(d[1])  + "-"+ d[1].getDate();

                @if($filtersFromDate && $filtersToDate)
                if ((dateFromRange === '{{$filtersFromDate}}') && (dateToRange === '{{$filtersToDate}}')) {
                    return;
                }
                @endif

                var redirectFilterUrl = getUrlAsArray();

                redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, 'filters[from_date]', dateFromRange);
                redirectFilterUrl = findOrReplaceInObject(redirectFilterUrl, 'filters[to_date]', dateToRange);

               $('#{{$moduleId}}').attr('ajax_filter', encodeDataToURL(redirectFilterUrl));
               mw.reload_module('#{{$moduleId}}');
                window.history.pushState('{{ URL::current() }}', false, '?' + encodeDataToURL(redirectFilterUrl));

              // window.location.href = "{{ URL::current() }}?" + encodeDataToURL(redirectFilterUrl);
            }
        });

        @if($filtersFromDate && $filtersToDate)
        $('#js-filter-option-datepicker').data('datepicker').selectDate([new Date('{{$filtersFromDate}}'), new Date('{{$filtersToDate}}')]);
        @endif
    });
</script>

