<?php only_admin_access(); ?>



<?php
$pdf = get_option('pdf', $params['id']);
$download = get_option('download', $params['id']);
$border = get_option('border', $params['id']);
?>

<div class="module-live-edit-settings">

    <div class="mw-ui-box mw-ui-box-content">
        <label class="mw-ui-label"><?php _e('Upload PDF'); ?></label>
        <div class="mw-ui-field-holder">
            <span class="mw-ui-btn" id="pdf"><span class="mw-icon-upload"></span><?php _e('Select PDF'); ?></span>
        </div>
    </div>


    <input type="hidden" class="mw_option_field" name="pdf" id="pdfval" value="<?php print $pdf; ?>"/>

    <br/>

    <input class="mw-ui-field mw_option_field" name="border" id="border" placeholder="<?php _e('Eneter color hex..'); ?>">

    <br/><br/>

    <ul class="mw-ui-inline-list">
        <li><span><?php _e('Download option:'); ?></span></li>
        <li>
            <label class="mw-ui-check">
                <input type="checkbox" class="mw_option_field" name="download"
                       value="true" <?= ($download == 'true' ? 'checked="checked"' : '') ?>/><span></span>
                <span><?php _e('Yes'); ?></span>
            </label>
        </li>
    </ul>

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
            position: 'bottom-left',
            onchange: function (color) {
                clearTimeout(myBorderTimeout);
                myBorderTimeout = setTimeout(function () {
                    $("#border").css("background", color).trigger('change');
                }, 1200);
            }
        });
    });
</script>