<div class="form-group">
<label><?php _e('Limit');?></label>
<select class="form-control js-filter-change-limit">
    <option value=""><?php _e('Select');?></option>
    @foreach($options as $option)
    <option value="{{$option->value}}" @if($option->active) selected="selected" @endif>{{$option->name}}</option>
    @endforeach
</select>
</div>

<script type="text/javascript">
    $('body').on('change' , '.js-filter-change-limit' , function() {

        var limit = $(".js-filter-change-limit").val();
        var queryParams = [];
        queryParams.push({
            key:'limit',
            value:limit
        });

        submitQueryFilter('{{$moduleId}}', queryParams);

    });
</script>
