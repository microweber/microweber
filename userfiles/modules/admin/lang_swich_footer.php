<?php

$selectedLang = current_lang();
if (isset($_COOKIE['lang'])) {
    $selectedLang = $_COOKIE['lang'];
}

$currentLang = current_lang();


$supportedLanguages = \MicroweberPackages\Translation\TranslationPackageInstallHelper::getAvailableTranslations('json');
if ($supportedLanguages !== null) {
?>

    <script>

        $(document).ready(function () {

            mw.$("#lang_selector_admin_footer").on("change", function () {
var lang_selector_admin_footer_val =  $(this).val();

                mw.admin.language(lang_selector_admin_footer_val);

            });
        });
    </script>



<div class="form-group">
    <?php _e("Language"); ?>
    <select class="d-block selectpicker" data-style="btn-sm" data-size="5" data-live-search="true" name="lang" id="lang_selector_admin_footer" data-width="100%" data-title="<?php if ($currentLang != 'en_US' and $currentLang != 'undefined'): ?><?php print \MicroweberPackages\Translation\LanguageHelper::getDisplayLanguage($currentLang); ?><?php else: ?>English<?php endif; ?>">

        <?php foreach ($supportedLanguages as $languageLocale=>$languageDisplayName): ?>
            <option value="<?php print $languageLocale; ?>"
                <?php if ($selectedLang == $languageLocale) { ?> selected="selected" <?php } ?>>
                <?php echo $languageDisplayName; ?> - <?php print $languageLocale; ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
<?php } ?>
