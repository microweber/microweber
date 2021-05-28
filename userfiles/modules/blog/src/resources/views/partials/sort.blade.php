<div class="form-group">
    <label><?php _e('Sort');?></label>
    <select class="form-control js-filter-change-sort">
        <option disabled="disabled"><?php _e('Select');?></option>
        @foreach($options as $option)
            <option data-sort="{{$option->sort}}" data-order="{{$option->order}}" @if($option->active) selected="selected" @endif>{{$option->name}}</option>
        @endforeach
    </select>
</div>

<script type="text/javascript">
    $('body').on('change' , '.js-filter-change-sort' , function() {

        var sort = $(".js-filter-change-sort").children('option:selected').attr('data-sort');
        var order = $(".js-filter-change-sort").children('option:selected').attr('data-order');

        var queryParams = [];
        queryParams.push({
            key:'sort',
            value:sort
        });
        queryParams.push({
            key:'order',
            value:order
        });

        submitQueryFilter('{{$moduleId}}', queryParams);

    });
</script>
