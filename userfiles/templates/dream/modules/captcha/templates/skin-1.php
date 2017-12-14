<?php

/*

type: layout

name: Default

description: Default comments template

*/


?>

<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<div class="row">
    <div class="mw-captcha" style="margin: 0;">
        <div class="captcha-holder" style="margin: 0;">
            <div class="col-xs-4">
                <a href="javascript:;" class="tip" data-tip="Refresh captcha" data-tipposition="top-center"><img onclick="mw.tools.refresh_image(this);" class="img-responsive" id="captcha-<?php print $form_id; ?>" src="<?php print api_link('captcha') ?>?w=70&h=48"/></a>
            </div>
            <div class="col-xs-8" style="margin-top: 0;">
                <input name="captcha" type="text" required class="mw-captcha-input form-control" placeholder="<?php _e("Security code"); ?>"/>
            </div>
        </div>
    </div>
</div>
