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

# Social login pill
$social_login_pill = get_option('allow_socials_login', 'users') == 'y';

# Login Providers
$facebook = get_option('enable_user_fb_registration', 'users') == 'y';
$twitter = get_option('enable_user_twitter_registration', 'users') == 'y';
$linkedIn = get_option('enable_user_linkedin_registration', 'users') == 'y';
$google = get_option('enable_user_google_registration', 'users') == 'y';
$windows = get_option('enable_user_windows_live_registration', 'users') == 'y';
$github = get_option('enable_user_github_registration', 'users') == 'y';
$microweber_login = get_option('enable_user_microweber_registration', 'users') == 'y';


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

<div class="container container-tight">
   <div class="col-lg-10">
       <div class="text-center mb-4">
           <a href="<?php print $link; ?>" target="_blank" id="login-logo" class="navbar-brand navbar-brand-autodark">
               <img width="250" height="auto" src="<?php print mw()->ui->admin_logo_login(); ?>" alt="Logo"/>
           </a>
       </div>
       <div class="card card-md">
           <div class="card-body pb-3" id="admin_login">

               <?php if ($user != false): ?>
                   <div><?php _e("Welcome") . ' ' . user_name(); ?></div>
                   <a href="<?php print site_url() ?>"><?php _e("Go to"); ?> &nbsp;
                       <small><?php print site_url() ?></small>
                   </a>
                   <br/>
                   <a href="<?php print logout_url() ?>"><?php _e("Log Out"); ?></a>
               <?php else: ?>
                   <?php if ($microweber_login and get_option('microweber_app_id', 'users') != false and get_option('microweber_app_secret', 'users') != false): ?>
                   <?php endif; ?>

                   <?php event_trigger('mw.ui.admin.login.form.before'); ?>

                   <form autocomplete="on" method="post" id="user_login_<?php print $params['id'] ?>" action="<?php print api_link('user_login') ?>">

                       <div class="col-12">

                           <div class="mb-3">
                               <label class="form-label" for="username"><?php _e('Username'); ?>:</label>
                               <input type="text" class="form-control" id="username" name="username" tabindex="1" placeholder="<?php _e("Username or Email"); ?>" <?php if (isset($input['username']) != false): ?>value="<?php print $input['username'] ?>"<?php endif; ?> required />
                           </div>

                           <div class="mb-3">
                               <label class="form-label" for="inputDefault"><?php _e('Password'); ?>:

                               </label>
                               <input type="password" class="form-control" id="password" name="password" tabindex="2" placeholder="<?php _e("Password"); ?>" <?php if (isset($input['password']) != false): ?>value="<?php print $input['password'] ?>"<?php endif; ?> required>


                           </div>
                       </div>

                       <?php if (get_option('login_captcha_enabled', 'users') == 'y'): ?>
                           <div class="col-12">
                               <module type="captcha" template="admin"/>
                           </div>
                       <?php endif; ?>



                       <?php
                       $supportedLanguages = TranslationPackageInstallHelper::getAvailableTranslations('json');
                       if (\MicroweberPackages\Multilanguage\MultilanguageHelpers::multilanguageIsEnabled()) {
                            $supportedLanguagesMultilang = \MicroweberPackages\Multilanguage\MultilanguageHelpers::getSupportedLanguages();
                            if($supportedLanguagesMultilang){
                                $supportedLanguages = [];
                                foreach ($supportedLanguagesMultilang as $langItem){
                                    if(isset($langItem['locale']) and isset($langItem['language'])){
                                    $supportedLanguages[$langItem['locale']] = $langItem['language'];
                                    }
                                }
                            }

                        }

                       if (!empty($supportedLanguages)) {
                           ?>
                           <div class="form-group">
                               <label class="text-muted"><?php _e("Language"); ?>:</label>

                               <select class="form-select d-block" data-style="btn-sm" data-size="5" data-live-search="true" name="lang" id="lang_selector" data-width="100%" data-title="<?php if ($currentLang != 'en_US' and $currentLang != 'undefined'): ?><?php print \MicroweberPackages\Translation\LanguageHelper::getDisplayLanguage($currentLang); ?><?php else: ?>English<?php endif; ?>">

                                   <?php foreach ($supportedLanguages as $languageLocale=>$languageDisplayName): ?>
                                       <option value="<?php print $languageLocale; ?>"
                                           <?php if ($selectedLang == $languageLocale) { ?> selected="selected" <?php } ?>>
                                           <?php echo $languageDisplayName; ?>
                                           [<?php echo $languageLocale; ?>]
                                       </option>
                                   <?php endforeach; ?>
                               </select>
                           </div>
                           <?php
                       }
                       ?>

                       <div class="form-footer">
                           <input type="hidden" name="where_to" value="admin_content"/>
                           <?php if (isset($_GET['redirect'])): ?>
                               <input type="hidden" value="<?php echo mw()->format->clean_xss($_GET['redirect']); ?>" name="redirect">
                           <?php endif; ?>
                           <div class="form-group">
                               <label class="d-none d-sm-block">&nbsp;</label>
                               <button class="btn btn-primary w-100" tabindex="3" dusk="login-button" type="submit"><?php _e("Login"); ?></button>
                           </div>
                       </div>


                   </form>

                   <?php event_trigger('mw.ui.admin.login.form.after'); ?>
               <?php endif; ?>
           </div>


           <?php if ($social_login_pill): ?>
               <div class="hr-text"><?php _e("or"); ?></div>
               <div class="card-body pb-0">
                   <div class="d-flex flex-wrap flex-column">

                       <?php if ($facebook): ?>
                           <div class="col my-1">
                               <a href="#" class="btn w-100">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-facebook" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                       <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3"></path>
                                   </svg>
                                   <?php _e("Login with Facebook"); ?>
                               </a>
                           </div>
                       <?php endif; ?>

                       <?php if ($twitter): ?>
                           <div class="col my-1">
                               <a href="#" class="btn w-100">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-twitter" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                       <path d="M22 4.01c-1 .49 -1.98 .689 -3 .99c-1.121 -1.265 -2.783 -1.335 -4.38 -.737s-2.643 2.06 -2.62 3.737v1c-3.245 .083 -6.135 -1.395 -8 -4c0 0 -4.182 7.433 4 11c-1.872 1.247 -3.739 2.088 -6 2c3.308 1.803 6.913 2.423 10.034 1.517c3.58 -1.04 6.522 -3.723 7.651 -7.742a13.84 13.84 0 0 0 .497 -3.753c0 -.249 1.51 -2.772 1.818 -4.013z"></path>
                                   </svg>
                                   <?php _e("Login with Twitter"); ?>

                               </a>
                           </div>
                       <?php endif; ?>

                       <?php if ($github): ?>
                           <div class="col my-1">
                               <a href="#" class="btn w-100">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="icon text-github" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5"></path></svg>
                                   <?php _e("Login with Github"); ?>
                               </a>
                           </div>
                       <?php endif; ?>

                       <?php if ($linkedIn): ?>
                           <div class="col my-1">
                               <a href="#" class="btn w-100">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-linkedin" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                       <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                                       <path d="M8 11l0 5"></path>
                                       <path d="M8 8l0 .01"></path>
                                       <path d="M12 16l0 -5"></path>
                                       <path d="M16 16v-3a2 2 0 0 0 -4 0"></path>
                                   </svg>
                                   <?php _e("Login with Linked In"); ?>
                               </a>
                           </div>
                       <?php endif; ?>

                       <?php if ($google): ?>
                           <div class="col my-1">
                               <a href="#" class="btn w-100">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-google" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                       <path d="M17.788 5.108a9 9 0 1 0 3.212 6.892h-8"></path>
                                   </svg>
                                   <?php _e("Login with Google"); ?>
                               </a>
                           </div>
                       <?php endif; ?>
                   </div>
               </div>

           <?php endif; ?>

           <div class="card-body pt-0 border-0">

               <div class="col-sm-12 d-flex align-items-center justify-content-sm-between gap-2 flex-wrap justify-content-center">
                   <a href="<?php print site_url() ?>" class="btn btn-link"><i class="mdi mdi-arrow-left"></i> <?php _e("Back to My WebSite"); ?></a>

                   <a href="javascript:;" dusk="forgot-password-link" onClick="mw.load_module('users/forgot_password', '#admin_login', false, {template:'admin'});" class="btn btn-link"><?php _e("Forgot my password"); ?>?</a>
               </div>

           </div>

       </div>
        <module type="admin/copyright"/>
   </div>
</div>
