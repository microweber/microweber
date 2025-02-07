@if(!empty($fields_groups))
    @foreach($fields_groups as $fields)
        @if(!empty($fields))
            <div class="{{ get_template_form_row_class() }}">
                @foreach($fields as $field)
                    {!! $field['html'] !!}
                @endforeach
            </div>
        @endif
    @endforeach
@endif

@if(!isset($no_for_fields))
    <input type="hidden" name="for_id" value="{{ $for_id }}"/>
    <input type="hidden" name="for" value="{{ $for }}"/>
@endif

@if($form_has_upload)
    <script>
        /**
         * Add enctype="multipart/form-data"
         * add method="post"
         */
        (function () {
            var checkForm = $('#{{ $params['id'] }}').closest("form");
            checkForm.attr('enctype', 'multipart/form-data');
            checkForm.attr('method', 'post');
        })();
    </script>
@endif
