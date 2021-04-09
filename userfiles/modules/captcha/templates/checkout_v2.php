<?php
/*

type: layout

name: chekout_v2

description: Captcha skin for checkout

*/


?>

<div class="mw-ui-row">
    <div class="mw-captcha">
        <div class="form-group mb-0">
            <div class="captcha-holder d-flex">
                <div class="col-3 px-0">
                    <a href="javascript:;" class="tip" data-tip="Refresh captcha" data-tipposition="top-center">
                        <img onclick="mw.tools.refresh_image(this);" class="mw-captcha-img" id="captcha-<?php print $form_id; ?>" src="<?php print api_link('captcha') ?>?w=100&h=60&uid=<?php print uniqid($form_id) ?>&rand=<?php print rand(1, 10000) ?>&id=<?php print $params['id'] ?>"/>
                    </a>
                </div>
                <div class="col-5 align-self-center px-0">
                    <input name="captcha" type="text" required class="mw-captcha-input form-control" placeholder="<?php _e("Security code"); ?>"/>
                </div>
            </div>
        </div>
    </div>
</div>
