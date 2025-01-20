<div class="{{ get_template_row_class() }}">
    @if(!empty($fields_group))
        @foreach($fields_group as $fields)
            @if(!empty($fields))
                @foreach($fields as $field)
                    {!! $field['html'] !!}
                @endforeach
            @endif
        @endforeach
    @endif
</div>