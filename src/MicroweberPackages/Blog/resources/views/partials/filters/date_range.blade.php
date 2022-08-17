<?php
$randomId = uniqid();
?>

<div class="card-header bg-white px-1">
    <div data-bs-toggle="collapse" data-bs-target="#collapse_{{$randomId}}"  aria-expanded="true" class="d-flex">
        <h6>{{$filter->controlName}}</h6>
        <i class="mdi mdi-plus ms-auto align-self-center"></i>
    </div>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body px-1 filter-max-scroll w-100">

        <div id="js-filter-option-datepicker{{$randomId}}"></div>

        <script>
            filter.addDateRangePicker({
                id: 'js-filter-option-datepicker{{$randomId}}',
                filter: {!! json_encode($filter) !!},
                setup: {
                    language: 'en',
                    navTitles: {
                        days: 'MM yyyy'
                    },
                    prevHtml: '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="18" viewBox="0 0 11 18" fill="none"><g opacity="0.35"><path d="M9.54974 17.0485L1.69141 9.17153L9.54974 1.29456" stroke="#1D2128" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></g></svg>',
                    nextHtml: '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="18" viewBox="0 0 11 18" fill="none"><g opacity="0.35"><path d="M1.28906 1.29456L9.1474 9.17153L1.28906 17.0485" stroke="#1D2128" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>\</g></svg>',
                    range: true
                }
            });
        </script>

    </div>
</div>
