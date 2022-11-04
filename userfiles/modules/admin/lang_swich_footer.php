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
    if($supportedLanguagesFiles){
        foreach ($supportedLanguagesFiles as $langKey => $langName) {
            $item = [];
            $item['locale'] = $langKey;
            $item['display_name'] = $langName;
            $supportedLanguages[] = $item;
        }
    }
}

if ($supportedLanguages) {
?>

<div class="form-group text-center">
     <div class="plain-language-selector tip" data-tip="<?php _e('Admin language') ?>">
        <select name="lang" id="lang_selector_admin_footer" data-width="100%" data-title="<?php if ($currentLang != 'en_US' and $currentLang != 'undefined'): ?><?php print \MicroweberPackages\Translation\LanguageHelper::getDisplayLanguage($currentLang); ?><?php else: ?>English<?php endif; ?>">
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
        </select>
    </div>
</div>
    <script>
        document.querySelector("#lang_selector_admin_footer").addEventListener("change", function () {
            mw.admin.language(this.value);
        });
    </script>
<?php } ?>
