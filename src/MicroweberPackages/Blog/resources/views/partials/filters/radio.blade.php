<?php
$randomId = uniqid();
?>


    <h6>{{$filter->controlName}}</h6>


    <div class="card-body px-1 filter-max-scroll">
        <div class="mb-3 js-slimscroll">
            @foreach($filter->options as $options)
                <div class="custom-control custom-radio my-2">
                    @php
                        $randIdForCheck = uniqid();
                    @endphp
                    <input class="form-check-input js-filter-option-select" type="radio" id="{{$randIdForCheck}}" @if ($options->active) checked @endif name="filters[{{$filterKey}}][]" value="{{$options->value}}">
                    <label class="custom-control-label" for="{{$randIdForCheck}}">
                        {{ $options->value }}
                    </label>

                    @if ($options->active)
                        <div class="remove-control">
                            <a data-key="filters[{{$filterKey}}][]" data-value="{{$options->value}}" class="js-filter-picked"><i class="mdi mdi-close "></i></a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

    </div>

