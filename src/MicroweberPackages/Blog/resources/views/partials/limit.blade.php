<div class="mb-3 d-flex">
    <label class="control-label align-self-center me-2"><?php _e('Limit');?></label>
    <select class="form-control js-filter-change-limit">
        <option value=""><?php _e('Select');?></option>
        @foreach($options as $option)
        <option value="{{$option->value}}" @if($option->active) selected="selected" @endif>{{$option->name}}</option>
        @endforeach
    </select>
</div>
