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
        <div class="form-group js-slimscroll">
            @foreach($filter->options as $options)
                <div class="custom-control custom-radio">
                    @php
                        $randIdForCheck = uniqid();
                    @endphp
                    <input class="custom-control-input js-filter-option-select" type="radio" id="{{$randIdForCheck}}" @if ($options->active) checked @endif name="filters[{{$filterKey}}][]" value="{{$options->value}}">
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
</div>
