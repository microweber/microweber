<?php only_admin_access(); ?>

<?php
$before = get_option('before', $params['id']);
$after = get_option('after', $params['id']);
?>

<div class="module-live-edit-settings">

    <div class="mw-ui-box mw-ui-box-content">
        <label class="mw-ui-label"><?php _e('Upload 2 images'); ?></label>
        <div class="mw-ui-field-holder">
            <span class="mw-ui-btn" id="before"><span class="mw-icon-upload"></span><?php _e('Before'); ?></span>
            vs.
            <span class="mw-ui-btn" id="after"><span class="mw-icon-upload"></span><?php _e('After'); ?></span>
        </div>
    </div>


    <input type="hidden" class="mw_option_field" name="before" id="beforeval" value="<?php print $before; ?>"/>
    <input type="hidden" class="mw_option_field" name="after" id="afterval" value="<?php print $after; ?>"/>

</div>

<script>
    $(document).ready(function () {
        var before = mw.uploader({
            filetypes: "images,videos",
            multiple: false,
            element: "#before"
        });
        $(before).bind('FileUploaded', function (a, b) {
            preload_image(b.src)

            mw.$("#beforeval").val(b.src).trigger('change');
        });
        var after = mw.uploader({
            filetypes: "images,videos",
            multiple: false,
            element: "#after"
        });
        $(after).bind('FileUploaded', function (a, b) {
            preload_image(b.src)
            mw.$("#afterval").val(b.src).trigger('change');
        });
    });

    preload_image = function (src) {
        var elem = document.createElement("img");
        elem.setAttribute("src", src);
    }
</script>