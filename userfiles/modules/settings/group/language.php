<?php must_have_access(); ?>

<?php
$isMultilanguageActivated = false;
if (is_module('multilanguage')) {
    if (get_option('is_active', 'multilanguage_settings') == 'y') {
        $isMultilanguageActivated = true;
    }
}


$def_language = get_option('language', 'website');



?>

<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Language settings are saved"); ?>.");
            mw.reload_module('#mw_lang_file_edit');
        }, function () {
            <?php if(!$isMultilanguageActivated): ?>
            var cooklie_lang = $("#user_lang").val();
            mw.cookie.set('lang', cooklie_lang);
            window.location.reload();
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
                                    <?php if (!$isMultilanguageActivated): ?>

                                        <div class="form-group mb-4">
                                            <label class="control-label"><?php _e("Website Language"); ?></label>
                                            <small class="text-muted d-block mb-2"><?php _e("You can set the default language for your website."); ?></small>
                                            <?php
                                            $langs = mw()->lang_helper->get_all_lang_codes();

                                            if ($def_language == false) {
                                                $def_language = 'en';
                                            }
                                            ?>
                                            <?php if ($langs) : ?>
                                                <select id="user_lang" name="language" class="mw_option_field selectpicker" data-size="7" data-width="100%" option-group="website" data-also-reload="settings/group/language_edit">
                                                    <option disabled="disabled"><?php _e('Select Language...'); ?></option>
                                                    <?php foreach ($langs as $key => $lang): ?>
                                                        <option <?php if ($def_language == $key): ?> selected="" <?php endif; ?> value="<?php print $key ?>"><?php print $lang ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>


                                    <module type="multilanguage" template="admin" show_settings_link="true" />

                                    <?php endif; ?>

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


