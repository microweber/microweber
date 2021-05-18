<div class="js-content-filter-{{$pageId}}">
    <form method="get" action="" class="form-horizontal">
        <div class="row">

            <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
            <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
            <script type="text/javascript" src="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
            <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

            @include('contentFilter::filters/limit')
            @include('contentFilter::filters/order')

            @foreach($filters as $filterKey=>$filter)

            <div class="col-md-12 pb-3">
                <b>{{$filter['name']}}</b>
               @if ($filter['type'] == 'date')

                    <div class="form-check">
                        <input type="text" name="page[{{$pageId}}][customFields][dateRange]" class="form-control" value="@if (!empty($customFields['dateRange'])){{$customFields['dateRange']}}@endif" />
                    </div>
                    <script>
                        $(function() {
                            $('input[name="page[{{$pageId}}][customFields][dateRange]"]').daterangepicker({
                                singleDatePicker: true,
                                showDropdowns: true,
                                minYear: 1901,
                                maxYear: parseInt(moment().format('YYYY'),10)
                            }, function(start, end, label) {

                            });
                        });
                    </script>
               @endif

               @if ($filter['type'] == 'dropdown' || $filter['type'] == 'radio')

                <b>{{$filter['name']}}</b>

                @foreach($filter['options'] as $options)
                @php
                $checked = false;
                if (!empty($customFields)) {
                    foreach ($customFields as $customFieldKey=>$customFieldValues) {
                        foreach($customFieldValues as $customFieldValue) {
                            if ($customFieldKey == $filterKey && $customFieldValue == $options['value']) {
                                $checked = true;
                            }
                        }
                    }
                }
                @endphp

                <div class="form-check">
                    <input class="form-check-input" @if ($checked) checked @endif type="checkbox" name="page[{{$pageId}}][customFields][{{$filterKey}}][]" value="{{$options['value']}}" id="customFieldFilterCheck{{$options['id']}}">
                    <label class="form-check-label" for="customFieldFilterCheck{{ $options['id'] }}">
                        {{ $options['value'] }}
                    </label>
                </div>
                @endforeach
                @endif
            </div>

            @endforeach

            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block mt-2"><i class="fa fa-filter"></i> Filter</button>
            </div>
        </div>
    </form>
</div>
