<?php must_have_access(); ?>

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
        <?php
        $pdf = get_option('pdf', $params['id']);
        $download = get_option('download', $params['id']);
        $border = get_option('border', $params['id']);
        ?>

        <div class="module-live-edit-settings module-pdf-settings">
            <div class="form-group">
                <label class="control-label d-block"><?php _e('Choose your PDF file to upload'); ?></label>
                <span class="btn btn-primary" id="pdf"><span class="fa fa-upload"></span> &nbsp; <?php _e('Select file'); ?></span>
            </div>

            <input type="hidden" class="mw_option_field" name="pdf" id="pdfval" value="<?php print $pdf; ?>"/>

            <div class="form-group">
                <label class="control-label"><?php _e('Set border color to PDF view'); ?></label>
                <input class="mw_option_field form-control" name="border" id="border" placeholder="<?php _e('Enter color hex..'); ?>">
            </div>

            <div class="form-group">
                <label class="control-label p-b-0"><?php _e('Allow users to download the PDF file'); ?></label>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="mw_option_field custom-control-input" id="download" name="download" value="true" <?= ($download == 'true' ? 'checked="checked"' : '') ?>>
                    <label class="custom-control-label" for="download"><?php _e('Allow download'); ?></label>
                </div>
            </div>
        </div>

        <script>
            mw.lib.require('colorpicker');
        </script>

        <script>
            $(document).ready(function () {
                var before = mw.uploader({
                    filetypes: "documents",
                    multiple: false,
                    element: "#pdf"
                });
                $(before).on('FileUploaded', function (a, b) {
                    mw.$("#pdfval").val(b.src).trigger('change');
                });
            });

            var myBorderTimeout;

            $(document).ready(function () {
                pickBorder = mw.colorPicker({
                    element: '#border',
                    position: 'top-right',
                    onchange: function (color) {
                        clearTimeout(myBorderTimeout);
                        myBorderTimeout = setTimeout(function () {
                            $("#border").css("background", color).trigger('change');
                        }, 1200);
                    }
                });

            });
        </script>
    </div>
</div>
