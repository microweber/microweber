<?php only_admin_access(); ?>

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
        <?php
        $pdf = get_option('pdf', $params['id']);
        $download = get_option('download', $params['id']);
        $border = get_option('border', $params['id']);
        ?>

        <script>
            mw.lib.require('colorpicker');
        </script>

        <div class="module-live-edit-settings module-pdf-settings">

            <div class="mw-ui-field-holder">
                <label class="mw-ui-label"><?php print _e('Choose your PDF file to upload'); ?></label>
                <span class="mw-ui-btn mw-ui-btn-info mw-full-width" id="pdf"><span class="fas fa-upload"></span> &nbsp; <?php _e('Select file'); ?></span>
            </div>


            <input type="hidden" class="mw_option_field" name="pdf" id="pdfval" value="<?php print $pdf; ?>"/>

            <div class="mw-ui-field-holder">
                <label class="mw-ui-label"><?php print _e('Set border color to PDF view'); ?></label>
                <input class="mw-ui-field mw_option_field mw-full-width" name="border" id="border" placeholder="<?php _e('Eneter color hex..'); ?>">
            </div>

            <div class="mw-ui-field-holder">
                <label class="mw-ui-label p-b-0">Allow users to download the PDF file</label>
            </div>

            <div class="mw-ui-field-holder">
                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" name="download" value="true" <?= ($download == 'true' ? 'checked="checked"' : '') ?>/><span></span><span><?php _e('Allow download'); ?></span>
                </label>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                var before = mw.uploader({
                    filetypes: "documents",
                    multiple: false,
                    element: "#pdf"
                });
                $(before).bind('FileUploaded', function (a, b) {
                    mw.$("#pdfval").val(b.src).trigger('change');
                });
            });

            var myBorderTimeout;

            $(document).ready(function () {
                pickBorder = mw.colorPicker({
                    element: '#border',
                    method: 'inline',

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