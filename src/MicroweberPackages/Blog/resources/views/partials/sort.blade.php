<div class="mb-3 d-flex">
    <label class="control-label align-self-center me-2"><?php _e('Sort');?></label>
    <select class="form-control js-filter-change-sort">
        <option disabled="disabled"><?php _e('Select');?></option>
        @foreach($options as $option)
            <option data-sort="{{$option->sort}}" data-order="{{$option->order}}" @if($option->active) selected="selected" @endif>{{$option->name}}</option>
        @endforeach
    </select>
</div>
