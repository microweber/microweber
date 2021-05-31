<div class="form-group">
<label><?php _e('Limit');?></label>
<select class="form-control js-filter-change-limit">
    <option value=""><?php _e('Select');?></option>
    @foreach($options as $option)
    <option value="{{$option->value}}" @if($option->active) selected="selected" @endif>{{$option->name}}</option>
    @endforeach
</select>
</div>
