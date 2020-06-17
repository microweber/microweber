<?php

/*

type: layout

name: Login admin

description: Admin login style

*/

?>

<?php
$user = user_id();
$selected_lang = 'en';

if (isset($_COOKIE['lang'])) {
    $selected_lang = $_COOKIE['lang'];
}

$current_lang = current_lang();


if (!isset(mw()->ui->admin_logo_login_link) or mw()->ui->admin_logo_login_link == false) {
    $link = site_url();

} else {
    $link = mw()->ui->admin_logo_login_link;
}
?>
<div class="main container my-3" id="mw-login">
    <script>mw.require("session.js");</script>
    <script>
        mw.session.checkPauseExplicitly = true;
        $(document).ready(function () {
            mw.tools.dropdown();
            mw.session.checkPause = true;
            mw.$("#lang_selector").bind("change", function () {
                mw.cookie.set("lang", $(this).getDropdownValue());
            });
        });
    </script>

    <main class="w-100">
        <div class="row mb-5">
            <div class="col-12 col-sm-9 col-md-7 col-lg-6 col-xl-5 mx-auto">
                <a href="<?php print $link; ?>" target="_blank" id="login-logo" class="mb-4 d-block text-center">
                    <img src="<?php print mw()->ui->admin_logo_login(); ?>" alt="Logo" style="max-width: 300px;"/>
                </a>

                <div class="card style-1 bg-light mb-3">
                    <div class="card-body pt-3">
                        <?php if ($user != false): ?>
                            <div><?php _e("Welcome") . ' ' . user_name(); ?></div>
                            <a href="<?php print site_url() ?>"><?php _e("Go to"); ?><?php print site_url() ?></a>
                            <a href="<?php print api_link('logout') ?>"><?php _e("Log Out"); ?></a>
                        <?php else: ?>
                            <?php if (get_option('enable_user_microweber_registration', 'users') == 'y' and get_option('microweber_app_id', 'users') != false and get_option('microweber_app_secret', 'users') != false): ?>
                            <?php endif; ?>

                            <?php event_trigger('mw.ui.admin.login.form.before'); ?>

                            <form autocomplete="on" method="post" id="user_login_<?php print $params['id'] ?>" action="<?php print api_link('user_login') ?>">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <label class="col-form-label" for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="<?php _e("Username or Email"); ?>" <?php if (isset($input['username']) != false): ?>value="<?php print $input['username'] ?>"<?php endif; ?> autofocus=""/>
                                        </div>

                                        <div class="form-group mb-0">
                                            <label class="col-form-label" for="inputDefault">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="<?php _e("Password"); ?>" <?php if (isset($input['password']) != false): ?>value="<?php print $input['password'] ?>"<?php endif; ?> required>
                                        </div>
                                    </div>

                                    <?php if (isset($login_captcha_enabled) and $login_captcha_enabled): ?>
                                        <div class="col-12">
                                            <div class="form-group mb-0">
                                                <label class="col-form-label" for="captcha-field-<?php print $params['id']; ?>">Captcha</label>

                                                <div class="input-group mb-3 prepend-transparent">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text p-0 overflow-hidden">
                                                            <img onclick="mw.tools.refresh_image(this);" id="captcha-<?php print $params['id']; ?>" src="<?php print api_link('captcha') ?>" style="max-height: 38px;"/>
                                                        </span>
                                                    </div>

                                                    <input name="captcha" type="text" required class="form-control" placeholder="<?php _e("Security code"); ?>" id="captcha-field-<?php print $params['id']; ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label"><?php _e("Language"); ?></label>
                                            <select class="selectpicker d-block" data-size="5" data-live-search="true" id="lang_selector" data-value="" data-width="100%" data-title="<?php if ($current_lang != 'en' AND $current_lang != 'undefined'): ?><?php print strtoupper($current_lang); ?><?php else: ?>EN<?php endif; ?>">
                                                <?php
                                                $langs = get_available_languages(); ?>
                                                <?php foreach ($langs as $lang): ?>
                                                    <option value="<?php print $lang; ?>" <?php if ($selected_lang == $lang) { ?> selected <?php } ?>><?php print strtoupper($lang); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 text-center text-sm-right">
                                        <input type="hidden" name="where_to" value="admin_content"/>
                                        <?php if (isset($_GET['redirect'])): ?>
                                            <input type="hidden" value="<?php echo $_GET['redirect']; ?>" name="redirect">
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <label class="col-form-label d-none d-sm-block">&nbsp;</label>
                                            <button class="btn btn-outline-primary" type="submit"><?php _e("Login"); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <?php event_trigger('mw.ui.admin.login.form.after'); ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row text-center">
                    <div class="col-sm-6">
                        <a href="<?php print site_url() ?>" class="btn btn-link text-dark"><i class="mdi mdi-arrow-left"></i> <?php _e("Back to My WebSite"); ?></a>
                    </div>
                    <div class="col-sm-6">
                        <a href="javascript:;" onClick="mw.load_module('users/forgot_password', '#admin_login', false, {template:'admin'});" class="btn btn-link"><?php _e("Forgot my password"); ?>?</a>
                    </div>
                </div>
            </div>
        </div>

        <module type="admin/copyright"/>
    </main>
</div>