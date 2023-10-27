<?php
$randomId = uniqid();
?>


<h6>{{$filter->controlName}}</h6>


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
