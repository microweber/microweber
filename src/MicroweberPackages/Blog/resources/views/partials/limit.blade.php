<div class="mb-3 d-flex align-items-center">
    <label class="form-label align-self-center me-2 mb-0"><?php _e('Limit');?></label>
    <select class="form-control js-filter-change-limit">
        <option value=""><?php _e('Select');?></option>
        @foreach($options as $option)
        <option value="{{$option->value}}" @if($option->active) selected="selected" @endif>{{$option->name}}</option>
        @endforeach
    </select>
</div>
