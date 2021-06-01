<?php
$randomId = uniqid();
?>

<div class="card-header">
    <a href="#" data-toggle="collapse" data-target="#collapse_{{$randomId}}" aria-expanded="true" class="">
        <h6 class="title">{{$filter->name}}</h6>
        <i class="fa fa-chevron-down" style="float:right;margin-top:-18px;"></i>
    </a>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body filter-max-scroll">

        <div class="form-group">
            @foreach($filter->options as $options)
                @php
                    $randIdForCheck = uniqid();
                @endphp
                <div class="control-wrapper">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input js-filter-option-select" type="checkbox" id="{{$randIdForCheck}}" @if ($options->active) checked @endif name="filters[{{$filterKey}}][]" value="{{$options->value}}">
                        <label class="custom-control-label" for="{{$randIdForCheck}}">
                            {{ $options->value }}
                        </label>


                        @if ($options->active)
                            <div class="remove-control">
                                <a href="#" data-key="filters[{{$filterKey}}][]" data-value="{{$options->value}}" class="js-filter-active-filters"><i class="fa fa-times-circle"></i></a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
