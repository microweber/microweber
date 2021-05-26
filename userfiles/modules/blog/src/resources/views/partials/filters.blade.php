<script>
    $(document).ready(function(){
        // Add minus icon for collapse element which is open by default
        $(".collapse.show").each(function(){
            $(this).prev(".card-header").find(".fa").addClass("fa-minus").removeClass("fa-plus");
        });

        // Toggle plus minus icon on show hide of collapse element
        $(".collapse").on('show.bs.collapse', function(){
            $(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
        }).on('hide.bs.collapse', function(){
            $(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
        });
    });
</script>
{{--
<div class="card">
    <div class="card-body">
    <div class="accordion" id="accordionExample">
        <div class="card-header" id="headingOne">
            <h2 class="mb-0">
                <a href="#" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"><i class="fa fa-plus"></i> What is HTML?</a>
            </h2>
        </div>
        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">

                <p>HTML stands for HyperText Markup Language. HTML is the standard markup language for describing the structure of web pages. <a href="https://www.tutorialrepublic.com/html-tutorial/" target="_blank">Learn more.</a></p>
            </div>
        </div>
    </div>
</div>--}}

@foreach($filters as $filterKey=>$filter)

    <div class="col-md-12 pb-3">

        @if ($filter->controlType == 'checkbox')
            <b>{{$filter->name}}</b>
            <div class="form-group">
            @foreach($filter->options as $options)
                @php
                    $randIdForCheck = uniqid();
                @endphp
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input js-filter-option-select" type="checkbox" id="{{$randIdForCheck}}" @if ($options->active) checked @endif name="filters[{{$filterKey}}][]" value="{{$options->value}}">
                    <label class="custom-control-label" for="{{$randIdForCheck}}">
                        {{ $options->value }}
                    </label>
                </div>
            @endforeach
            </div>
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
                <div class="form-group">
            @foreach($filter->options as $options)
                    <div class="custom-control custom-radio">
                        @php
                            $randIdForCheck = uniqid();
                        @endphp
                        <input class="custom-control-input js-filter-option-select" type="radio" id="{{$randIdForCheck}}" @if ($options->active) checked @endif name="filters[{{$filterKey}}][]" value="{{$options->value}}">
                        <label class="custom-control-label" for="{{$randIdForCheck}}">
                            {{ $options->value }}
                        </label>
                    </div>
            @endforeach
                </div>
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

               window.location.href = "{{ URL::current() }}?" + encodeDataToURL(redirectFilterUrl);
            }
        });

        function numericMonth(dt)
        {
            return (dt.getMonth() < 9 ? '0' : '') + (dt.getMonth() + 1);
        }

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
        $('#js-filter-option-datepicker').data('datepicker').selectDate([new Date('{{$filtersFromDate}}'), new Date('{{$filtersToDate}}')]);
        @endif
    });
</script>

