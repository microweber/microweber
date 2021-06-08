<b>{{$filter->controlName}}</b>
<select class="form-control js-filter-option-select" name="filters[{{$filterKey}}][]">
    <option>{{_e('Select')}}</option>
    @foreach($filter->options as $options)
        <option @if ($options->active) selected="selected" @endif value="{{$options->value}}">{{ $options->value }}</option>
    @endforeach
</select>
