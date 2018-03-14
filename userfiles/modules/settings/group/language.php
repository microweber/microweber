<?php only_admin_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _e("Language settings are saved"); ?>.");
            mw.reload_module('#mw_lang_file_edit');


        }, function () {
            var cooklie_lang = $("#user_lang").val();
            mw.cookie.set('lang', cooklie_lang);
        });
    });
</script>

<div class="mw-ui-row admin-section-bar">
    <div class="mw-ui-col">
        <h2><?php _e("Language"); ?></h2>
    </div>
</div>
<div class="admin-side-content">
<div class="<?php print $config['module_class'] ?>">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">
            <?php _e("Website Language"); ?>
            <br>
            <small>
                <?php _e("You can set the default language for your website."); ?>
            </small>
        </label>

        <?php
        $def_language = get_option('language', 'website');

        if ($def_language == false) {
            $def_language = 'en';
        }
        ?>
        <select id="user_lang" name="language" class="mw-ui-field mw_option_field" option-group="website"
                data-also-reload="settings/group/language_edit">
            <option disabled="disabled"><?php _e('Select Language...'); ?></option>
            <option value="en" <?php if ($def_language == 'en' or $def_language == false or $def_language == ''): ?> selected="" <?php endif; ?>>
                English
            </option>
            <option value="ar" <?php if ($def_language == 'ar' or $def_language == false): ?> selected="" <?php endif; ?> >
                Arabic - العربية
            </option>
            <option value="eu" <?php if ($def_language == 'eu' or $def_language == false): ?> selected="" <?php endif; ?> >
                Basque - Euskara
            </option>
            <option value="bg" <?php if ($def_language == 'bg' or $def_language == false): ?> selected="" <?php endif; ?> >
                Bulgarian
            </option>
            <option value="ca" <?php if ($def_language == 'ca' or $def_language == false): ?> selected="" <?php endif; ?>>
                Catalan - català
            </option>
            <option value="cs" <?php if ($def_language == 'cs' or $def_language == false): ?> selected="" <?php endif; ?>>
                Czech - Čeština
            </option>
            <option value="da" <?php if ($def_language == 'da' or $def_language == false): ?> selected="" <?php endif; ?>>
                Danish - Dansk
            </option>
            <option value="nl" <?php if ($def_language == 'nl' or $def_language == false): ?> selected="" <?php endif; ?>>
                Dutch - Nederlands
            </option>
            <option value="fa" <?php if ($def_language == 'fa' or $def_language == false): ?> selected="" <?php endif; ?>>
                Farsi - فارسی
            </option>
            <option value="fil" <?php if ($def_language == 'fil' or $def_language == false): ?> selected="" <?php endif; ?>>
                Filipino - Filipino
            </option>
            <option value="fi" <?php if ($def_language == 'fi' or $def_language == false): ?> selected="" <?php endif; ?>>
                Finnish - Suomi
            </option>
            <option value="fr" <?php if ($def_language == 'fr' or $def_language == false): ?> selected="" <?php endif; ?>>
                French - français
            </option>
            <option value="gl" <?php if ($def_language == 'gl' or $def_language == false): ?> selected="" <?php endif; ?>>
                Galician - Galego
            </option>
            <option value="de" <?php if ($def_language == 'de' or $def_language == false): ?> selected="" <?php endif; ?>>
                German - Deutsch
            </option>
            <option value="el" <?php if ($def_language == 'el' or $def_language == false): ?> selected="" <?php endif; ?>>
                Greek - Ελληνικά
            </option>
            <option value="he" <?php if ($def_language == 'he' or $def_language == false): ?> selected="" <?php endif; ?>>
                Hebrew - עִבְרִית
            </option>
            <option value="hi" <?php if ($def_language == 'hi' or $def_language == false): ?> selected="" <?php endif; ?>>
                Hindi - हिन्दी
            </option>
            <option value="hu" <?php if ($def_language == 'hu' or $def_language == false): ?> selected="" <?php endif; ?>>
                Hungarian - Magyar
            </option>
            <option value="id" <?php if ($def_language == 'id' or $def_language == false): ?> selected="" <?php endif; ?>>
                Indonesian - Bahasa Indonesia
            </option>
            <option value="it" <?php if ($def_language == 'it' or $def_language == false): ?> selected="" <?php endif; ?>>
                Italian - Italiano
            </option>
            <option value="ja" <?php if ($def_language == 'ja' or $def_language == false): ?> selected="" <?php endif; ?>>
                Japanese - 日本語
            </option>
            <option value="ko" <?php if ($def_language == 'ko' or $def_language == false): ?> selected="" <?php endif; ?>>
                Korean - 한국어
            </option>
            <option value="msa" <?php if ($def_language == 'msa' or $def_language == false): ?> selected="" <?php endif; ?>>
                Malay - Bahasa Melayu
            </option>
            <option value="no" <?php if ($def_language == 'no' or $def_language == false): ?> selected="" <?php endif; ?>>
                Norwegian - Norsk
            </option>
            <option value="pl" <?php if ($def_language == 'pl' or $def_language == false): ?> selected="" <?php endif; ?>>
                Polish - Polski
            </option>
            <option value="pt" <?php if ($def_language == 'pt' or $def_language == false): ?> selected="" <?php endif; ?>>
                Portuguese - Português
            </option>
            <option value="ro" <?php if ($def_language == 'ro' or $def_language == false): ?> selected="" <?php endif; ?>>
                Romanian - română
            </option>
            <option value="ru" <?php if ($def_language == 'ru' or $def_language == false): ?> selected="" <?php endif; ?>>
                Russian - Русский
            </option>
            <option value="zh-cn" <?php if ($def_language == 'zh-cn' or $def_language == false): ?> selected="" <?php endif; ?>>
                Simplified Chinese - 简体中文
            </option>
            <option value="slo" <?php if ($def_language == 'slo' or $def_language == false): ?> selected="" <?php endif; ?>>
                Slovenian - Slovenščina
            </option>
            <option value="es" <?php if ($def_language == 'es' or $def_language == false): ?> selected="" <?php endif; ?>>
                Spanish - Español
            </option>
            <option value="sv" <?php if ($def_language == 'sv' or $def_language == false): ?> selected="" <?php endif; ?>>
                Swedish - Svenska
            </option>
            <option value="th" <?php if ($def_language == 'th' or $def_language == false): ?> selected="" <?php endif; ?>>
                Thai - ภาษาไทย
            </option>
            <option value="zh-tw" <?php if ($def_language == 'zh-tw' or $def_language == false): ?> selected="" <?php endif; ?>>
                Traditional Chinese - 繁體中文
            </option>
            <option value="tr" <?php if ($def_language == 'tr' or $def_language == false): ?> selected="" <?php endif; ?>>
                Turkish - Türkçe
            </option>
            <option value="uk" <?php if ($def_language == 'uk' or $def_language == false): ?> selected="" <?php endif; ?>>
                Ukrainian - Українська мова
            </option>
            <option value="ur" <?php if ($def_language == 'ur' or $def_language == false): ?> selected="" <?php endif; ?>>
                Urdu - اردو
            </option>
            <option value="ps" <?php if ($def_language == 'ps' or $def_language == false): ?> selected="" <?php endif; ?>>
                Pashto - پښتو
            </option>
        </select>

        <?php


        /*
         <label class="mw-ui-label"><?php _e("Add new language"); ?> </label>
         <input  name="language" class="mw_option_field mw-ui-field"  type="text"   option-group="website" also-reload="#mw_lang_file_edit" />   */


        ?>
    </div>
    <div class="mw-ui-field-holder">
        <microweber module="settings/group/language_edit" id="mw_lang_file_edit"/>
    </div>
    </div>
