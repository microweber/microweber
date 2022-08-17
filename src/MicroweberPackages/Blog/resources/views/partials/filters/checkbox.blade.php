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
    <div class="card-body px-1 filter-max-scroll">
        <div class="mb-3">
            @foreach($filter->options as $options)
                @php
                    $randIdForCheck = uniqid();
                @endphp

                        <div class="custom-control custom-checkbox ">
                            <input class="custom-control-input js-filter-option-select" type="checkbox" id="{{$randIdForCheck}}" @if ($options->active) checked @endif name="filters[{{$filterKey}}][]" value="{{$options->value}}">
                            <label class="custom-control-label" for="{{$randIdForCheck}}">
                                <span>{{ $options->value }}</span>
                            </label>
                        </div>

            @endforeach
        </div>

    </div>
</div>
