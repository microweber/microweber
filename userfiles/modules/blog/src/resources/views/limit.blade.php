<div class="form-group">
<label><?php _e('Limit');?></label>
<select class="form-control" onchange="if (this.value != '') window.location.href=this.value">
    <option value=""><?php _e('Select');?></option>
    @foreach($options as $option)
    <option value="{{$option->link}}" @if($option->active) selected="selected" @endif>{{$option->name}}</option>
    @endforeach
</select>
</div>
