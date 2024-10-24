{{-- 
type: layout

name: chekout_v2

description: Captcha skin for checkout
--}}

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

<div class="mw-ui-row">
    <div class="mw-captcha">
        <div class="form-group mb-0">
            <div class="captcha-holder d-flex">
                <div class="col-3 px-0">
                    <a href="javascript:;" class="tip" data-tip="Refresh captcha" data-tipposition="top-center">
                        <img onclick="mw.tools.refresh_image(this);" class="mw-captcha-img" id="captcha-{{ $form_id }}" src="{{ api_link('captcha') }}?w=100&h=60&uid={{ uniqid($form_id) }}&rand={{ rand(1, 10000) }}&id={{ $params['id'] }}"/>
                    </a>
                </div>
                <div class="col-5 align-self-center px-0">
                    <input name="captcha" id="captcha-{{ $params['id'] }}-input" type="text" required class="mw-captcha-input form-control" placeholder="@lang('Security code')"/>
                </div>
            </div>
        </div>
    </div>
</div>
