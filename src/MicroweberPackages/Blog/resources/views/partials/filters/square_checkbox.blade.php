<?php
$randomId = uniqid();
?>

<div class="card-header bg-white px-1">
    <div data-bs-toggle="collapse" data-bs-target="#collapse_{{$randomId}}"  aria-expanded="true" class="d-flex">
        <h6>{{$filter->controlName}}</h6>
        <i class="mdi mdi-plus ms-auto align-self-center"   ></i>
    </div>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
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
</div>
