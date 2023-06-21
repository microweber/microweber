<?php must_have_access(); ?>

<?php
$supported_languages = false;
$isMultilanguageActivated = false;
$hasMultilanguageModuleActivated = false;
if (is_module('multilanguage')) {
    if (\MicroweberPackages\Multilanguage\MultilanguageHelpers::multilanguageIsEnabled()) {
        $hasMultilanguageModuleActivated = true;

        $supported_languages = get_supported_languages(true);
        if ($supported_languages) {
            $isMultilanguageActivated = true;

        }
    }
}

$langs = \MicroweberPackages\Translation\LanguageHelper::getLanguagesWithDefaultLocale();
$langs_ready = [];
$supported_languages_ready = [];
if ($langs and $supported_languages) {
    foreach ($langs as $languageName => $languageDetails) {
        foreach ($supported_languages as $supportedLanguage) {
            if (isset($supportedLanguage['locale']) and isset($languageDetails['locale']) and $supportedLanguage['locale'] == $languageDetails['locale']) {
                $languageDetails['locales'] = null;
                $languageDetails['localesData'] = null;
                $langs_ready[$languageName] = $languageDetails;
            }
        }
    }
}
if($langs_ready){
    $langs = $langs_ready;
}

$def_language = get_option('language', 'website');

?>

<script type="text/javascript">
    function saveDefaultMultilanguage(locale) {

        mw.notification.success("<?php _ejs("Changing default language.."); ?>.");

        $.post(mw.settings.api_url + "save_option", {
            option_key: 'language',
            option_value: locale,
            option_group: 'website'
        }, function () {
            mw.notification.success("<?php _ejs("Language settings are saved"); ?>.");
            <?php if(!$isMultilanguageActivated): ?>
            aplyChangeLanguage(locale);
            <?php endif; ?>
        });
    }

    function aplyChangeLanguage(selectedLang) {
        mw.notification.success("<?php _ejs("Changing default language.."); ?>.");
        $.ajax({
            type: "POST",
            url: mw.settings.api_url + "apply_change_language",
            data: {lang: selectedLang, import_language_if_not_imported: true},
            success: function (data) {
                $.get(mw.settings.api_url + "clearcache", {}, function () {
                    mw.notification.success("<?php _ejs("Clear cache.."); ?>.");
                    location.reload();
                });
            }
        });
    }
</script>

<script>mw.require('admin_package_manager.js');</script>
<script>
    $(document).ready(function () {
        mw.on('install_composer_package_success', function (response) {
            addDefaultLanguageToMultilanguage();
        });
    });

    function addDefaultLanguageToMultilanguage() {
        mw.notification.success('Adding language...', 10000);
        $.post(mw.settings.api_url + "multilanguage/add_language", {
            locale: '<?php echo $def_language; ?>',
            language: '<?php echo $def_language; ?>'
        }).done(function (data) {
            mw.notification.success('Language added...', 10000);
            $.post(mw.settings.api_url + "save_option", {
                option_key: 'is_active',
                option_value: 'y',
                option_group: 'multilanguage_settings'
            }, function () {
                $.get(mw.settings.api_url + "clearcache", {}, function () {
                    location.reload();
                });
            });
        });
    }

    function openMultilangEditModal(action) {

        if (action == 'activate') {
            addDefaultLanguageToMultilanguage();
        } else if (action == 'addLanguage') {

            var data = {};
            var multilanguageAddLanguageModal = mw.tools.open_module_modal('multilanguage/admin_add_language', data, {
                overlay: true,
                skin: 'simple',
                width: '800px',
                height: '400px',
                title: 'Multi-language - Add new language'
            });

            mw.on('mw.multilanguage.admin.language_added', function () {
                multilanguageAddLanguageModal.modal.remove();
                mw.reload_module_everywhere('settings/group/language_multilanguage');
            });

        } else {

            <?php if (is_module('multilanguage')): ?>
            var data = {};
            data.show_settings_link = "true";
            mw.tools.open_module_modal('multilanguage/admin', data, {
                overlay: true,
                skin: 'simple',
                height: 'auto',
                width: 800,
                title: 'Multi-language Settings'
            });
            <?php else: ?>
            mw.admin.admin_package_manager.install_composer_package_by_package_name('microweber-modules/multilanguage', $(this).attr('vkey'), this);
            <?php endif; ?>
        }
    }
</script>


<h1 class="main-pages-title"><?php _e('Language'); ?></h1>


<div class="<?php print $config['module_class'] ?>">

    <div class="card mb-7">
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 mb-xl-0 mb-3">
                    <h5 class="font-weight-bold settings-title-inside"><?php _e("Language"); ?></h5>
                    <small class="text-muted"><?php _e('Set a default language for your website'); ?></small>
                </div>
                <div class="col-xl-9">
                    <div class="card bg-azure-lt  mb-1">
                        <div class=" ">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <label class="form-label"><?php _e("Website Language"); ?></label>
                                                <small
                                                    class="text-muted d-block mb-2"><?php _e("You can set the default language for your website."); ?></small>
                                            </div>
                                        </div>

                                        <div class="col-md-7 mt-4">

                                            <?php
                                            //  $langs = \MicroweberPackages\Translation\LanguageHelper::getLanguagesWithDefaultLocale();
                                            if ($def_language == false) {
                                                $def_language = 'en_US';
                                            }

                                            if ($langs) {
                                                $foundedTranslations = 0;
                                                $disableSwitchDefaultLanguage = false;
                                                if (Schema::hasTable('multilanguage_translations')) {
                                                    $foundedTranslations = \Illuminate\Support\Facades\DB::table('multilanguage_translations')->count();
                                                    if ($foundedTranslations > 0) {
                                                        $disableSwitchDefaultLanguage = true;
                                                    }
                                                }
                                                ?>

                                                <script type="text/javascript">
                                                    function confirmChangeDefaultLanguage(element, event) {
                                                        <?php if ($disableSwitchDefaultLanguage):?>
                                                        event.preventDefault();
                                                        mw.confirm('<?php echo $foundedTranslations; ?> <?php _e('translations from the multilnaguage module have been found in your database.'); ?><br /><?php _e('Warning! Changing the default language can break translations on your site.'); ?><br /><?php _e('Are you sure want to continue?'); ?>', function () {
                                                            saveDefaultMultilanguage($(element).val());
                                                        });
                                                        <?php else: ?>
                                                        saveDefaultMultilanguage($(element).val());
                                                        <?php endif; ?>
                                                    }
                                                </script>

                                                <select onchange="confirmChangeDefaultLanguage(this,event)"
                                                        class="form-select" data-size="7" data-live-search="true"
                                                        data-width="100%">
                                                    <option disabled="disabled"><?php _e('Select Language'); ?></option>
                                                    <?php foreach ($langs as $languageName => $languageDetails): ?>

                                                        <?php if ($languageDetails['locales'] and !empty($languageDetails['locales'])): ?>
                                                            <?php foreach ($languageDetails['locales'] as $languageName2 => $languageDetails2): ?>

                                                                <option <?php if ($def_language == $languageName2): ?> selected="" <?php endif; ?>
                                                                    value="<?php print $languageName2 ?>"><?php print $languageName ?>
                                                                    [<?php print $languageName2 ?>]
                                                                    (<?php print $languageDetails2 ?>)
                                                                </option>

                                                            <?php endforeach; ?>
                                                        <?php else : ?>
                                                            <option <?php if ($def_language == $languageDetails['locale']): ?> selected="" <?php endif; ?>
                                                                value="<?php print $languageDetails['locale'] ?>"><?php print $languageName ?>
                                                                [<?php print $languageDetails['locale'] ?>]
                                                            </option>

                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </select>


                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!$hasMultilanguageModuleActivated) { ?>
        <div class="card mb-7">
            <div class="card-body">
                <div class="row pt-3">
                    <div class="col-xl-3 mb-xl-0 mb-3">
                        <h5 class="font-weight-bold settings-title-inside"><?php _e("Multi-language"); ?></h5>
                        <small
                            class="text-muted"><?php _e('You can activate the Multi-language module to use multiple languages'); ?></small>
                    </div>
                    <div class="col-xl-9">
                        <div class="card bg-azure-lt  mb-1">
                            <div class=" ">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-4">
                                            <label class="form-label"><?php _e("Multi-language mode"); ?></label>
                                            <small
                                                class="text-muted d-block mb-2"><?php _e("Activate the multi-language mode to have multiple languages for your content."); ?></small>
                                            <div class="col-md-12 text-start">

                                                <?php if ($hasMultilanguageModuleActivated): ?>
                                                    <a onclick="openMultilangEditModal('manage')"
                                                       class="btn btn-outline-primary">
                                                        <i class="mdi mdi-cogs"></i> <?php _e('Manage Multi-language'); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <?php if (is_module('multilanguage')): ?>
                                                        <a onclick="openMultilangEditModal('activate')"
                                                           class="btn btn-outline-primary">
                                                            <i class="mdi mdi-enable"></i> <?php _e('Activate Multi-language Module'); ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <a onclick="openMultilangEditModal('install')"
                                                           class="btn btn-outline-primary">
                                                            <i class="mdi mdi-download"></i> <?php _e('Install Multi-language Module'); ?>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    <?php } else { ?>

        <module type="settings/group/language_multilanguage"/>

    <?php } ?>

    <module type="settings/group/language_edit" id="mw_lang_file_edit" edit-lang="<?php print $def_language ?>"/>
</div>


