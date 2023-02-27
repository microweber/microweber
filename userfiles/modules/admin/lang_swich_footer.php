<?php

use MicroweberPackages\Multilanguage\MultilanguageHelpers;

$selectedLang = current_lang();
if (isset($_COOKIE['lang'])) {
    $selectedLang = $_COOKIE['lang'];
}

$currentLang = current_lang();
if (MultilanguageHelpers::multilanguageIsEnabled()) {
    $supportedLanguages = get_supported_languages(true);
} else {
    $supportedLanguages = array();
    $supportedLanguagesFiles = \MicroweberPackages\Translation\TranslationPackageInstallHelper::getAvailableTranslations('json');


    //$getTranslationLocalesImported =  app()->translation_key_repostory->getImportedLocales();
     if($supportedLanguagesFiles){
        foreach ($supportedLanguagesFiles as $langKey => $langName) {
            $item = [];
            $item['locale'] = $langKey;
            $item['display_name'] = $langName;
            $supportedLanguages[] = $item;
        }
    }
}


if(empty($supportedLanguages)){
    $item = [];
    $item['locale'] = 'en_US';
    $item['display_name'] = 'English';
    $supportedLanguages[] = $item;
}


if ($supportedLanguages) {
?>

<div class="form-group text-center">
     <div class="plain-language-selector tip" data-tip="<?php _e('Admin language') ?>">
        <select
            name="lang"
            id="lang_selector_admin_footer"
            data-width="100%"
            data-title="<?php if ($currentLang != 'en_US' and $currentLang != 'undefined'): ?><?php print \MicroweberPackages\Translation\LanguageHelper::getDisplayLanguage($currentLang); ?><?php else: ?>English<?php endif; ?>">
            <?php foreach ($supportedLanguages as $language): ?>

            <?php

                $languageLocale = $language['locale'];
                $languageDisplayName = $language['display_name'] . ' [' . $language['locale'] . ']';
                ?>
                <option value="<?php print $languageLocale; ?>"
                    <?php if ($selectedLang == $languageLocale) { ?> selected="selected" <?php } ?>>
                    <?php echo $languageDisplayName; ?>
                </option>
            <?php endforeach; ?>

            <option value="edit_languages_redirect">&#9998; Edit languages...</option>
        </select>
    </div>
</div>
    <script>
        document.querySelector("#lang_selector_admin_footer").addEventListener("change", function () {

            if(this.value == 'edit_languages_redirect'){

               window.location.href = '<?php print admin_url('view:settings#option_group=language'); ?>';

            } else {
                mw.admin.language(this.value);

            }
        });
    </script>
<?php } ?>
