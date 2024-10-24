@php
    $callback = false;
    if (isset($params['data-callback'])) {
        $callback = $params['data-callback'];
    }
@endphp

@if($callback)
    <script type="text/javascript">
        $(document).ready(function () {
            $('#captcha-{{ $params['id'] }}-input').on('input paste keyup blur', function () {
                var val = $(this).val();
                if (val.length > 0) {
                    {{ $callback }}(val);
                }
            })
        });
    </script>
@endif

<div class="form-group">
    <div class="row">
        <div class="col-auto">
            <a href="javascript:;" class="tip" data-tip="Refresh captcha" data-tipposition="top-center">
                <img onclick="mw.tools.refresh_image(this);" class="img-fluid" id="captcha-{{ $form_id }}" src="{{ api_link('captcha') }}?w=100&h=40&uid={{ uniqid($form_id) }}&rand={{ rand(1, 10000) }}&id={{ $params['id'] }}"/>
            </a>
        </div>
        <div class="col">
            <input name="captcha" type="text" required class="mw-captcha-input form-control" placeholder="@lang('Security code')"/>
        </div>
    </div>
</div>


