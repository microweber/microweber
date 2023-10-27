<?php
$randomId = uniqid();
?>


    <h6>{{$filter->controlName}}</h6>


<div class="card-body px-1 filter-max-scroll">
    <div class="mb-3">
        @foreach($filter->options as $options)
            @php
                $randIdForCheck = uniqid();
            @endphp

                    <div class="custom-control custom-checkbox ">
                        <input class="form-check-input js-filter-option-select" type="checkbox" id="{{$randIdForCheck}}" @if ($options->active) checked @endif name="filters[{{$filterKey}}][]" value="{{$options->value}}">
                        <label class="custom-control-label" for="{{$randIdForCheck}}">
                            <span>{{ $options->value }}</span>
                        </label>
                    </div>

        @endforeach
    </div>

</div>

