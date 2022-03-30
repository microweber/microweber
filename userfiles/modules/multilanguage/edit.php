<?php
only_admin_access();

$localeId = $params['locale_id'];
$getSupportedLocale = get_supported_locale_by_id($localeId);
if (!$getSupportedLocale) {
    return;
}

$displayLocale = $getSupportedLocale['display_locale'];
$displayName = $getSupportedLocale['display_name'];
$displayIcon = $getSupportedLocale['display_icon'];
?>

<script>
    $(document).ready(function () {
        var uploader = mw.uploader({
            filetypes: "images,videos",
            multiple: false,
            element: "#mw_uploader"
        });

        $(uploader).bind("FileUploaded", function (event, data) {
            mw.$("#mw_uploader_loading").hide();
            mw.$("#mw_uploader").show();
            mw.$("#upload_info").html("");
            $('.js-display-icon').attr('src', data.src);
            $('.js-display-icon-url').attr('value', data.src);
            $('.js-display-icon-remove').show();
        });

        $(uploader).bind('progress', function (up, file) {
            mw.$("#mw_uploader").hide();
            mw.$("#mw_uploader_loading").show();
            mw.$("#upload_info").html(file.percent + "%");
        });

        $(uploader).bind('error', function (up, file) {
            mw.notification.error("The file is not uploaded.");
        });

        $('.js-display-icon-remove').click(function (e) {
            $('.js-display-icon').attr('src', '');
            $('.js-display-icon-url').attr('value', '');
            $('.js-admin-supported-locale-edit-form').submit();
            $('.js-display-icon-remove').hide();
        });

        $('.js-admin-supported-locale-edit-form').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: mw.settings.api_url + 'multilanguage/edit_locale',
                type: 'post',
                data: $(this).serialize(),
                success: function (data) {
                    if (data.success) {
                        mw.notification.success('<?php _e('Supported locale is saved!');?>');
                        $('.js-admin-supported-locale-edit-messages').html('<div class="alert alert-success"><?php _e('Supported locale is saved!'); ?></div>');
                    } else if (data.message) {
                        mw.notification.error(data.message);
                        $('.js-admin-supported-locale-edit-messages').html('<div class="alert alert-danger">' + data.message + '</div>');
                    } else {
                        mw.notification.error('<?php _e('Please, fill all fields.'); ?>');
                        $('.js-admin-supported-locale-edit-messages').html('<div class="alert alert-danger"><?php _e('Please, fill all fields.'); ?></div>');
                    }
                    mw.reload_module_everywhere('multilanguage/list');
                    mw.reload_module_everywhere('multilanguage/change_language');
                }
            });
        });
    });
</script>

<form method="post" class="js-admin-supported-locale-edit-form">
    <div class="js-admin-supported-locale-edit-messages mt-3"></div>

    <div class="form-group">
        <label class="control-label"><?php _e("Display Locale"); ?>:</label>
        <small class="text-muted d-block mb-2"><?php _e("Define how the slug in the url will be shown"); ?></small>
        <input type="text" name="display_locale" value="<?php echo $displayLocale; ?>" class="form-control"/>

        <?php if (empty($displayLocale)): ?>
        <span class="text-muted">
            Recomended display locale: <b><?php echo $getSupportedLocale['locale']; ?></b>
        </span>
        <?php endif ?>

    </div>

    <div class="form-group">
        <label class="control-label"><?php _e("Display Name"); ?>:</label>
        <small class="text-muted d-block mb-2"><?php _e("Translation name in the website switcher"); ?></small>
        <input type="text" name="display_name" value="<?php echo $displayName; ?>" class="form-control"/>

        <?php if (empty($displayName)): ?>
        <span class="text-muted">
             Recomended display language: <b><?php echo $getSupportedLocale['language'];  ?></b>
        </span>
        <?php endif ?>

    </div>

    <div class="form-group">
        <label class="control-label"><?php _e("Display Icon"); ?>:</label>
        <small class="text-muted d-block mb-2"><?php _e("Change the flag with your own image"); ?></small>

        <div class="row">
            <div class="col-auto">
                <div style="width: 120px;">
                    <div class="dropable-zone small-zone square-zone bg-white" id="mw_uploader">
                        <div class="holder">
                            <div class="dropable-zone-img img-media mb-2"></div>

                            <button type="button" class="btn btn-link py-1" id="upload_info"><?php _e("Add media"); ?></button>
                            <p><?php _e("or drop a file"); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-auto">
                <img src="<?php echo $displayIcon; ?>" class="js-display-icon" style="max-width:120px; max-height: 120px;">
            </div>
        </div>

        <button type="button" class="btn btn-link px-0 text-danger js-display-icon-remove" <?php if (empty($displayIcon)): ?>style="display: none;"<?php endif; ?>><?php _e("Remove"); ?></button>
        <input type="hidden" name="display_icon" value="<?php echo $displayIcon; ?>" class="form-control js-display-icon-url"/>
    </div>

    <div class="form-group">
        <input type="hidden" name="locale_id" value="<?php echo $localeId; ?>">
        <button type="submit" class="btn btn-success btn-sm"><?php _e("Save"); ?></button>
    </div>
</form>
