@foreach($filters as $filterKey=>$filter)

    <div class="col-md-12 pb-3">

        @if ($filter->type == 'dropdown' || $filter->type == 'radio')

            <b>{{$filter->name}}</b>

            @foreach($filter->options as $options)
                <div class="form-check">
                    <input class="form-check-input js-filter-option-select" @if ($options->active) checked @endif type="checkbox" name="customFields[{{$filterKey}}][]" value="{{$options->value}}">
                    <label class="form-check-label">
                        {{ $options->value }}
                    </label>
                </div>
            @endforeach
        @endif
    </div>

@endforeach
