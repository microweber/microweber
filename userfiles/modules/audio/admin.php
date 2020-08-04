<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <script>mw.require("files.js");</script>
        <script>
            $(document).ready(function () {
                $("#upload").on("click", function () {
                    mw.fileWindow({
                        element: mwd.getElementById('upload'),
                        types: 'media',
                        change: function (url) {
                            mw.$("#upload_value").val(url);

                            if (Prior.value != '1') {
                                Prior.value = '1';
                                $(Prior).trigger('change');
                            }
                            mw.$("#upload_value").trigger("change");
                        }
                    });
                })

                Prior = mwd.getElementById('prior');
                mw.$("#audio").keyup(function () {
                    if (Prior.value !== '2') {
                        Prior.value = '2';
                        $(Prior).trigger('change');
                    }
                });
            });
        </script>

        <div class="mw-modules-tabs">
            <div class="mw-accordion-item">
                <div class="mw-ui-box-header mw-accordion-title">
                    <div class="header-holder">
                        <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
                    </div>
                </div>
                <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                    <!-- Settings Content -->
                    <div class="module-live-edit-settings module-audio-settings">
                        <div class="mw-ui-field-holder">
                            <label class="mw-ui-label"><?php _e("Upload Audio file"); ?></label>
                            <input type="hidden" name="data-audio-upload" value="<?php print get_option('data-audio-upload', $params['id']) ?>" class="mw_option_field" id="upload_value"/>
                            <span class="mw-ui-btn mw-ui-btn-info relative" id="upload"><span class="fas fa-upload"></span> &nbsp; <?php _e("Upload File"); ?></span>
                        </div>

                        <div class="mw-ui-field-holder">
                            <label class="mw-ui-label"><?php _e("Paste URL"); ?></label>
                            <input name="data-audio-url" class="mw-ui-field mw-full-width mw_option_field" id="audio" type="text" value="<?php print get_option('data-audio-url', $params['id']) ?>"/>
                        </div>
                        <p><?php print _e('You can <strong>Upload your audio file</strong> or you can <strong>Paste URL</strong> to the file. It\'s possible to use <strong > only one option </strong >.'); ?></p>

                        <input type="text" class="semi_hidden mw_option_field" name="prior" id="prior" value="<?php print get_option('prior', $params['id']) ?>"/>
                    </div>
                </div>
                <!-- Settings Content - End -->
            </div>
        </div>

    </div>
</div>