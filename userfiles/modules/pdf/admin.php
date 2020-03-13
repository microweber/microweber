<?php only_admin_access(); ?>

<?php
$pdf = get_option('pdf', $params['id']);
$download = get_option('download', $params['id']);
$border = get_option('border', $params['id']);
?>

<script>
    mw.lib.require('font_awesome5');
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
            method:'inline',

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