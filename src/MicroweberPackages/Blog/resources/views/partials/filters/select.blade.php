<?php
$randomId = uniqid();
?>

<div class="card-header bg-white">
    <a href="#" data-toggle="collapse" data-target="#collapse_{{$randomId}}" aria-expanded="true" class="d-flex">
        <h6 class="title">{{$filter->controlName}}</h6>
        <i class="mdi mdi-plus ml-auto"   ></i>
    </a>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body">
        <div class="form-group">

        <select class="form-control js-filter-option-select" name="filters[{{$filterKey}}][]">
            <option value="">{{_e('Select')}}</option>
            @foreach($filter->options as $options)
                <option @if ($options->active) selected="selected" @endif value="{{$options->value}}">{{ $options->value }}</option>
            @endforeach
        </select>

        </div>
    </div>
</div>
