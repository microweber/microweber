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
    <div class="card-body px-1">
        <div class="mb-3">

        <select class="form-control js-filter-option-select" name="filters[{{$filterKey}}][]">
            <option value="">{{_e('Select')}}</option>
            @foreach($filter->options as $options)
                <option @if ($options->active) selected="selected" @endif value="{{$options->value}}">{{ $options->value }}</option>
            @endforeach
        </select>

        </div>
    </div>
</div>
