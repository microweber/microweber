{{--
 *
 * type: layout
 *
 * name: MW UI
 *
 * description: MW UI
 *
--}}

<div class="mw-flex-row">
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