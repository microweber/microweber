<?php
$randomId = uniqid();
?>

<div class="card-header bg-white px-1">
    <div data-toggle="collapse" data-target="#collapse_{{$randomId}}" aria-expanded="true" class="d-flex">
        <h4 class="title">{{$filter->controlName}}</h4>
        <i class="mdi mdi-plus ml-auto align-self-center"></i>
    </div>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body px-1 filter-max-scroll">
        <div class="form-group">
            <div data-toggle="filter-buttons">
                @foreach($filter->options as $option)
                    @php
                        $randIdForCheck = uniqid();
                    @endphp
                    <label class="btn px-0 mx-1 btn-sm border rounded-circle @if ($option->active) active @endif" for="{{$randIdForCheck}}" style="width: 30px; height: 30px; background:{{$option->value}};">
                        <input type="checkbox" autocomplete="off" class="js-filter-option-select hidden" id="{{$randIdForCheck}}" @if ($option->active) checked @endif name="filters[{{$filterKey}}][]" value="{{$option->value}}">
                    </label>
                @endforeach
            </div>
        </div>

    </div>
</div>
