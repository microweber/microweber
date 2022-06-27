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
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings', "modules/audio"); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="settings">
                <script>mw.require("files.js");</script>
                <script>
                    $(document).ready(function () {
                        $("#upload").on("click", function () {
                            mw.fileWindow({
                                element: document.getElementById('upload'),
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

                        Prior = document.getElementById('prior');
                        mw.$("#audio").keyup(function () {
                            if (Prior.value !== '2') {
                                Prior.value = '2';
                                $(Prior).trigger('change');
                            }
                        });
                    });
                </script>

                <!-- Settings Content -->
                <div class="module-live-edit-settings module-audio-settings">
                    <div class="form-group">
                        <label class="control-label d-block"><?php _lang("Upload Audio file", "modules/audio"); ?></label>
                        <input type="hidden" name="data-audio-upload" value="<?php print get_option('data-audio-upload', $params['id']) ?>" class="mw_option_field" id="upload_value"/>
                        <span class="btn btn-primary btn-rounded relative" id="upload"><span class="fa fa-upload"></span> &nbsp; <?php _lang("Upload File", "modules/audio"); ?></span>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _lang("Paste URL", "modules/audio"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _lang('You can <strong>Upload your audio file</strong> or you can <strong>Paste URL</strong> to the file. It\'s possible to use <strong > only one option</strong>.', "modules/audio"); ?></small>
                        <input name="data-audio-url" class="mw_option_field form-control" id="audio" type="text" value="<?php print get_option('data-audio-url', $params['id']) ?>"/>
                    </div>

                    <input type="text" class="semi_hidden mw_option_field form-control" name="prior" id="prior" value="<?php print get_option('prior', $params['id']) ?>"/>
                </div>
            </div>
            <!-- Settings Content - End -->
            </div>
        </div>
    </div>
</div>
