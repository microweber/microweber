<?php

/*

type: layout

name: Default

description: Default comments template

*/;


?>
<script>
    mw.lib.require('bootstrap3ns');
</script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<div class="bootstrap3ns">
    <div class="row">
        <div class="mw-captcha" style="max-width: 400px; margin: 15px;">
            <div class="form-group">
                <div class="captcha-holder">
                    <div class="col-xs-4">
                        <a href="javascript:;" class="tip" data-tip="Refresh captcha" data-tipposition="top-center"><img onclick="mw.tools.refresh_image(this);" class="mw-captcha-img" id="captcha-<?php print $form_id; ?>" src="<?php print api_link('captcha') ?>?w=100&h=20&uid=<?php print uniqid($form_id) ?>"  /></a>
                    </div>
                    <div class="col-xs-6">
                        <input name="captcha" type="text" required class="mw-captcha-input form-control" placeholder="<?php _e("Security code"); ?>"/>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</div>