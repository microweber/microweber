<?php

$selectedLang = current_lang();
if (isset($_COOKIE['lang'])) {
    $selectedLang = $_COOKIE['lang'];
}

$currentLang = current_lang();


$supportedLanguages = \MicroweberPackages\Translation\TranslationPackageInstallHelper::getAvailableTranslations('json');
if ($supportedLanguages !== null) {
?>





<div class="form-group text-center">
     <div class="plain-language-selector tip" data-tip="<?php _e('Admin language') ?>">
        <select name="lang" id="lang_selector_admin_footer" data-width="100%" data-title="<?php if ($currentLang != 'en_US' and $currentLang != 'undefined'): ?><?php print \MicroweberPackages\Translation\LanguageHelper::getDisplayLanguage($currentLang); ?><?php else: ?>English<?php endif; ?>">
            <?php foreach ($supportedLanguages as $languageLocale=>$languageDisplayName): ?>
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
