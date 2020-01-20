<?php only_admin_access(); ?>

<?php
$isMultilanguageActivated = false;
if (is_module('multilanguage')) {
    if (get_option('is_active', 'multilanguage_settings') == 'y') {
        $isMultilanguageActivated = true;
    }
}
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

<div class="mw-ui-row admin-section-bar">
    <div class="mw-ui-col">
        <h2><?php _e("Language"); ?></h2>
    </div>
</div>
<div class="admin-side-content">
<div class="<?php print $config['module_class'] ?>">


    <?php if(!$isMultilanguageActivated): ?>
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">
            <?php _e("Website Language"); ?>
            <br>
            <small>
                <?php _e("You can set the default language for your website."); ?>
            </small>
        </label>
        <?php
        $langs  = mw()->lang_helper->get_all_lang_codes();
        $def_language = get_option('language', 'website');

        if ($def_language == false) {
            $def_language = 'en';
        }
        ?>
        <?php if($langs) : ?>
        <select id="user_lang" name="language" class="mw-ui-field mw_option_field" option-group="website"  data-also-reload="settings/group/language_edit">
            <option disabled="disabled"><?php _e('Select Language...'); ?></option>
        <?php foreach($langs as $key=>$lang): ?>
            <option <?php if ($def_language == $key): ?> selected="" <?php endif; ?> value="<?php print $key ?>" ><?php print $lang ?></option>
        <?php endforeach; ?>
        </select>
        <?php endif; ?>
        <?php
        /*
         <label class="mw-ui-label"><?php _e("Add new language"); ?> </label>
         <input  name="language" class="mw_option_field mw-ui-field"  type="text"   option-group="website" also-reload="#mw_lang_file_edit" />   */
        ?>
    </div>
    <?php endif; ?>


    <?php if($isMultilanguageActivated): ?>
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">
            <?php _e("Website Language"); ?>
            <br>
            <small>
                <?php _e("You can switch to language to edit all fields."); ?>
            </small>
        </label>
        <module type="multilanguage" template="clean" />
    </div>
    <?php endif; ?>

    <div class="mw-ui-field-holder">
        <module type="settings/group/language_edit" id="mw_lang_file_edit" />
    </div>
    </div>
