{{--
type: layout
name: Bootstrap 3
description: Bootstrap 3
--}}

<div class="row">
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