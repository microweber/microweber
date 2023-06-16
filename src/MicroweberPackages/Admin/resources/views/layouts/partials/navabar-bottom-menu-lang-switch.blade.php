<?php
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

if (!MultilanguageHelpers::multilanguageIsEnabled()) {
    ?>

    @include('admin::layouts.partials.navabar-bottom-menu-lang-switch-native')

<?php
    return;
}

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
<div class="dropdown-divider"></div>
<div class="form-group text-center mt-2 mb-0">
    <div class="plain-language-selector tip" data-tip="<?php _e('Admin language') ?>">
        <select class="form-select tomselect"
                name="lang"
                id="lang_selector_admin_footer"
                data-width="100%"

                data-title="<?php if ($currentLang != 'en_US' and $currentLang != 'undefined'): ?><?php print \MicroweberPackages\Translation\LanguageHelper::getDisplayLanguage($currentLang); ?><?php else: ?>English<?php endif; ?>">
                <?php foreach ($supportedLanguages as $language): ?>

                <?php
                if (!isset($language['language'])) {
                    continue;
                }
                if(!isset($language['locale'])){
                    continue;
                }
                if(trim($language['locale']) == ''){
                    continue;
                }
                $languageLocale = $language['locale'];
                $languageId = explode("_", $languageLocale);
                if(isset($languageId[1])){
                    $languageId = $languageId[1]  ;
                    $languageId = strtolower($languageId);
                } else if(isset($languageId[0])){
                $languageId = $languageId[0]  ;
                $languageId = strtolower($languageId);
                }   else {
                    $languageId = 'us';
                }




                $languageDisplayName = $language['language'];
                ?>
            <option
            value="<?php print $languageLocale; ?>"
            data-custom-properties="<?php print '%3Cspan%20class%3D%22me-1 flag%20flag-country-'.$languageId.'%22%3E%3C%2Fspan%3E' . $languageDisplayName; ?>"
                    <?php if ($selectedLang == $languageLocale) { ?> selected="selected" <?php } ?>>

            </option>
            <?php endforeach; ?>

            <option value="edit_languages_redirect">Edit languages</option>
        </select>
    </div>
</div>
<script>
    document.querySelector("#lang_selector_admin_footer").addEventListener("change", function () {

        if(this.value == 'edit_languages_redirect'){

            window.location.href = '<?php print admin_url('settings?group=language'); ?>';

        } else {
            mw.admin.language(this.value);

        }
    });
</script>
<?php } ?>

