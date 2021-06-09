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
    <div class="card-body filter-max-scroll">

        <div class="form-group">
            <div data-toggle="filter-buttons">
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
