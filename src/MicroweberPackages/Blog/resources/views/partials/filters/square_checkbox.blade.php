<?php
$randomId = uniqid();
?>


    <h6>{{$filter->controlName}}</h6>



    <div class="card-body px-1 filter-max-scroll">

        <div class="mb-3">
            <div data-bs-toggle="filter-buttons">
            @foreach($filter->options as $options)
                @php
                    $randIdForCheck = uniqid();
                @endphp
                <label class="btn btn-square-checkbox @if ($options->active) active @endif" for="{{$randIdForCheck}}">
                    <input type="checkbox" autocomplete="off" class="js-filter-option-select hidden" id="{{$randIdForCheck}}" @if ($options->active) checked @endif name="filters[{{$filterKey}}][]" value="{{$options->value}}">  {{ $options->value }}
                </label>
            @endforeach
        </div>
        </div>

    </div>

