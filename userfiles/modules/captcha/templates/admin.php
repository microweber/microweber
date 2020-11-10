<div class="form-group">
    <div class="row">
        <div class="col-auto">
            <a href="javascript:;" class="tip" data-tip="Refresh captcha" data-tipposition="top-center">
                <img onclick="mw.tools.refresh_image(this);" class="img-fluid" id="captcha-<?php print $form_id; ?>" src="<?php print api_link('captcha') ?>?w=100&h=40&uid=<?php print uniqid($form_id) ?>&rand=<?php print rand(1, 10000) ?>&id=<?php print $params['id'] ?>"/>
            </a>
        </div>
        <div class="col">
            <input name="captcha" type="text" required class="mw-captcha-input form-control" placeholder="<?php _e("Security code"); ?>"/>
        </div>
    </div>
</div>


