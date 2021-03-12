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
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Language settings are saved"); ?>.");
        }, function () {
            <?php if(!$isMultilanguageActivated): ?>
            var cooklie_lang = $("#user_lang").val();
            mw.cookie.set('lang', cooklie_lang);
            <?php endif; ?>
        });
    });
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
                                            <select id="user_lang" name="language" class="mw_option_field selectpicker" data-size="7" data-width="100%" option-group="website" data-also-reload="settings/group/language_edit">
                                                <option disabled="disabled"><?php _e('Select Language'); ?></option>
                                                <?php foreach ($langs as $languageName => $languageDetails): ?>
                                                    <option <?php if ($def_language == $languageDetails['locale']): ?> selected="" <?php endif; ?> value="<?php print $languageDetails['locale'] ?>"><?php print $languageName ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endif; ?>
                                                <?php endif;?>

                                            </div>
                                            <div class="col-md-5 text-right">

                                                <script>mw.require('admin_package_manager.js');</script>
                                                <script>

                                                    $(document).ready(function () {
                                                        mw.on('install_composer_package_success', function(response) {
                                                           window.location = window.location;
                                                        });
                                                    });

                                                    function openMultilangEditModal() {
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

                                                    <?php if ($hasMultilanguageModuleActivated): ?>
                                                        <a onclick="openMultilangEditModal()" class="btn btn-primary">
                                                    <?php _e('Manage Multilanguage'); ?>
                                                     </a>
                                                    <?php else: ?>
                                                     <?php if (is_module('multilanguage')): ?>
                                                    <a onclick="openMultilangEditModal()" class="btn btn-primary">
                                                        <?php _e('Activate Multilanguage Module'); ?>
                                                    </a>
                                                        <?php else: ?>
                                                            <a onclick="openMultilangEditModal()" class="btn btn-success">
                                                               <i class="fa fa-download"></i> <?php _e('Install Multilanguage Module'); ?>
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
        </div>
    </div>
    <hr class="thin mx-4"/>
    <module type="settings/group/language_edit" id="mw_lang_file_edit"  edit-lang="<?php print $def_language ?>"  />
</div>


