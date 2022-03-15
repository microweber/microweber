<?php

/*

type: layout

name: Login admin

description: Admin login style

*/

?>

<?php

use MicroweberPackages\Translation\TranslationPackageInstallHelper;

$user = user_id();

$selectedLang = current_lang();
if (isset($_COOKIE['lang'])) {
    $selectedLang = $_COOKIE['lang'];
}

$currentLang = current_lang();
//$currentLang = app()->lang_helper->default_lang();


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
            mw.$("#lang_selector").on("change", function () {

                mw.cookie.set("lang", $(this).val());
            });
        });
    </script>

    <main class="w-100" style="min-height: 100vh;">
        <div class="row mb-5">
            <div class="col-12 col-sm-9 col-md-7 col-lg-5 col-xl-4 mx-auto">
                <div class="m-auto" style="max-width: 380px;">
                    <a href="<?php print $link; ?>" target="_blank" id="login-logo" class="mb-4 d-block text-center">
                        <img src="<?php print mw()->ui->admin_logo_login(); ?>" alt="Logo" style="max-width: 70%;"/>
                    </a>

                    <div class="card mb-3">
                        <div class="card-body py-4" id="admin_login">
                            <?php if ($user != false): ?>
                                <div><?php _e("Welcome") . ' ' . user_name(); ?></div>
                                <a href="<?php print site_url() ?>"><?php _e("Go to"); ?> &nbsp;
                                    <small><?php print site_url() ?></small>
                                </a>
                                <br/>
                                <a href="<?php print api_link('logout') ?>"><?php _e("Log Out"); ?></a>
                            <?php else: ?>
                                <?php if (get_option('enable_user_microweber_registration', 'users') == 'y' and get_option('microweber_app_id', 'users') != false and get_option('microweber_app_secret', 'users') != false): ?>
                                <?php endif; ?>

                                <?php event_trigger('mw.ui.admin.login.form.before'); ?>

                                <form autocomplete="on" method="post" id="user_login_<?php print $params['id'] ?>" action="<?php print api_link('user_login') ?>">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group mb-3">
                                                <label class="text-muted" for="username"><?php _e('Username'); ?>:</label>
                                                <input type="text" class="form-control" id="username" name="username" placeholder="<?php _e("Username or Email"); ?>" <?php if (isset($input['username']) != false): ?>value="<?php print $input['username'] ?>"<?php endif; ?> required />
                                            </div>

                                            <div class="form-group mb-3">
                                                <label class="text-muted" for="inputDefault"><?php _e('Password'); ?>:</label>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="<?php _e("Password"); ?>" <?php if (isset($input['password']) != false): ?>value="<?php print $input['password'] ?>"<?php endif; ?> required>
                                            </div>
                                        </div>

                                        <?php if (get_option('login_captcha_enabled', 'users') == 'y'): ?>
                                            <div class="col-12">
                                                <module type="captcha" template="admin"/>
                                            </div>
                                        <?php endif; ?>


                                        <div class="col-sm-6">
                                        <?php
                                        $supportedLanguages = TranslationPackageInstallHelper::getAvailableTranslations('json');
                                        if ($supportedLanguages !== null) {
                                        ?>
                                        <div class="form-group">
                                            <label class="text-muted"><?php _e("Language"); ?>:</label>

                                            <select class="selectpicker d-block" data-style="btn-sm" data-size="5" data-live-search="true" name="lang" id="lang_selector" data-width="100%" data-title="<?php if ($currentLang != 'en_US' and $currentLang != 'undefined'): ?><?php print \MicroweberPackages\Translation\LanguageHelper::getDisplayLanguage($currentLang); ?><?php else: ?>English<?php endif; ?>">

                                                <?php foreach ($supportedLanguages as $languageLocale=>$languageDisplayName): ?>
                                                    <option value="<?php print $languageLocale; ?>"
                                                        <?php if ($selectedLang == $languageLocale) { ?> selected="selected" <?php } ?>>
                                                        <?php echo $languageDisplayName; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                        </div>

                                        <div class="col-sm-6 text-center text-sm-right">
                                            <input type="hidden" name="where_to" value="admin_content"/>
                                            <?php if (isset($_GET['redirect'])): ?>
                                                <input type="hidden" value="<?php echo mw()->format->clean_xss($_GET['redirect']); ?>" name="redirect">
                                            <?php endif; ?>
                                            <div class="form-group">
                                                <label class="d-none d-sm-block">&nbsp;</label>
                                                <button class="btn btn-outline-primary btn-sm" dusk="login-button" type="submit"><?php _e("Login"); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <?php event_trigger('mw.ui.admin.login.form.after'); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-sm-12 d-md-flex align-items-center justify-content-between">
                            <a href="<?php print site_url() ?>" class="btn btn-link btn-sm"><i class="mdi mdi-arrow-left"></i> <?php _e("Back to My WebSite"); ?></a>

                            <a href="javascript:;" dusk="forgot-password-link" onClick="mw.load_module('users/forgot_password', '#admin_login', false, {template:'admin'});" class="btn btn-link btn-sm"><?php _e("Forgot my password"); ?>?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <module type="admin/copyright"/>
    </main>
</div>
