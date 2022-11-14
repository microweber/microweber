<?php must_have_access(); ?>

<?php
$isMultilanguageActivated = false;
$hasMultilanguageModuleActivated = false;
if (is_module('multilanguage')) {
    if (get_option('is_active', 'multilanguage_settings') == 'y' and function_exists('get_supported_languages')) {
        $hasMultilanguageModuleActivated = true;

        $supported_languages = get_supported_languages(true);
        if($supported_languages){
            $isMultilanguageActivated = true;

        }
    }
}
 $def_language = get_option('language', 'website');
?>

<script type="text/javascript">
       function saveDefaultMultilanguage(locale) {

           mw.notification.success("<?php _ejs("Changing default language.."); ?>.");

           $.post(mw.settings.api_url + "save_option", {option_key:'language', option_value:locale, option_group:'website'}, function() {
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
        mw.on('install_composer_package_success', function(response) {
            addDefaultLanguageToMultilanguage();
        });
    });

    function addDefaultLanguageToMultilanguage()
    {
        mw.notification.success('Adding language...',10000);
        $.post(mw.settings.api_url + "multilanguage/add_language", {locale: '<?php echo $def_language; ?>', language: '<?php echo $def_language; ?>'}).done(function (data) {
            mw.notification.success('Language added...',10000);
            $.post(mw.settings.api_url + "save_option", {option_key:'is_active', option_value:'y', option_group:'multilanguage_settings'}, function() {
                $.get(mw.settings.api_url + "clearcache", {}, function () {
                    location.reload();
                });
            });
        });
    }

    function openMultilangEditModal(action) {

        if (action == 'activate') {
            addDefaultLanguageToMultilanguage();
        }

        <?php if (is_module('multilanguage')): ?>
        var data = {};
        data.show_settings_link = "true";
        openMultilangEditModaleditModal = mw.tools.open_module_modal('multilanguage/admin', data, {
            overlay: true,
            skin: 'simple',
            height: 'auto',
            width: 750,
            title: 'Edit'
        });
        <?php else: ?>
        mw.admin.admin_package_manager.install_composer_package_by_package_name('microweber-modules/multilanguage', $(this).attr('vkey'), this);
        <?php endif; ?>
    }
</script>




<div class="<?php print $config['module_class'] ?>">
    <div class="card bg-none style-1 mb-0 card-settings">
        <div class="card-header px-0">
            <h5><i class="mdi mdi-translate text-primary mr-3"></i> <strong><?php _e("Language"); ?></strong></h5>
            <div>

            </div>
        </div>

        <div class="card-body pt-3 pb-0 px-0">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="font-weight-bold"><?php _e("Language"); ?></h5>
                    <small class="text-muted"><?php _e('Set a language for your website and admin panel.'); ?></small>
                </div>
                <div class="col-md-9">
                    <div class="card bg-light style-1 mb-1">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-4">
                                        <label class="control-label"><?php _e("Website Language"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("You can set the default language for your website."); ?></small>
                                        <div class="row">
                                            <div class="col-md-7">

                                            <?php if ($hasMultilanguageModuleActivated): ?>
                                            <module type="multilanguage" template="admin" show_settings_link="true" />
                                            <?php else: ?>
                                            <?php
                                            $langs = \MicroweberPackages\Translation\LanguageHelper::getLanguagesWithDefaultLocale();

                                            if ($def_language == false) {
                                                $def_language = 'en_US';
                                            }
                                            ?>
                                        <?php if ($langs) : ?>
                                            <?php
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
                                            function confirmChangeDefaultLanguage(element,event) {
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

                                                    <select onchange="confirmChangeDefaultLanguage(this,event)" class="selectpicker" data-size="7" data-live-search="true" data-width="100%">
                                                        <option disabled="disabled"><?php _e('Select Language'); ?></option>
                                                        <?php foreach ($langs as $languageName => $languageDetails): ?>

                                                        <?php if($languageDetails['locales'] and !empty($languageDetails['locales'])): ?>
                                                            <?php foreach ($languageDetails['locales'] as $languageName2 => $languageDetails2): ?>

                                                           <option <?php if ($def_language == $languageName2): ?> selected="" <?php endif; ?> value="<?php print $languageName2 ?>"><?php print $languageName ?> [<?php print $languageName2 ?>] (<?php print $languageDetails2 ?>)</option>

                                                                <?php endforeach; ?>
                                                        <?php else : ?>
                                                            <option <?php if ($def_language == $languageDetails['locale']): ?> selected="" <?php endif; ?> value="<?php print $languageDetails['locale'] ?>"><?php print $languageName ?> [<?php print $languageDetails['locale'] ?>]</option>

                                                        <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </select>

                                                    <?php if ($disableSwitchDefaultLanguage):?>
                                                    <small class="text-muted">
                                                        <?php _e('Warning! The changing default language maybe will break your site.'); ?>
                                                    </small>
                                                    <?php endif;?>
                                        <?php endif; ?>
                                                <?php endif;?>

                                            </div>
                                            <div class="col-md-5 text-end text-right">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




            </div>


            <?php if(!$hasMultilanguageModuleActivated and app()->module_manager->exists('multilanguage')   ): ?>
            <div class="row pt-3">
                <div class="col-md-3">
                    <h5 class="font-weight-bold"><?php _e("Multi-Language"); ?></h5>
                    <small class="text-muted"><?php _e('You can activate the Multi-language module to use multiple languages'); ?></small>
                </div>
                <div class="col-md-9">
                    <div class="card bg-light style-1 mb-1">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-4">
                                        <label class="control-label"><?php _e("Multi language mode"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Activate the multi-language mode to have multiple languages for your content."); ?></small>
                                        <div class="row">

                                            <div class="col-md-12 text-start">

                                                <?php if ($hasMultilanguageModuleActivated): ?>
                                                    <a onclick="openMultilangEditModal('manage')" class="btn btn-outline-primary">
                                                        <i class="mdi mdi-cogs"></i> <?php _e('Manage Multilanguage'); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <?php if (is_module('multilanguage')): ?>
                                                        <a onclick="openMultilangEditModal('activate')" class="btn btn-outline-primary">
                                                            <i class="mdi mdi-enable"></i> <?php _e('Activate Multilanguage Module'); ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <a onclick="openMultilangEditModal('install')" class="btn btn-outline-primary">
                                                            <i class="mdi mdi-download"></i> <?php _e('Install Multilanguage Module'); ?>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




            </div>

<?php endif; ?>

        </div>
    </div>
    <hr class="thin mx-4"/>
    <module type="settings/group/language_edit" id="mw_lang_file_edit"  edit-lang="<?php print $def_language ?>"  />
</div>


