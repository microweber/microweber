<?php

$bgImage = get_option('bg-image', $params['id']);
?>

<div class="col-xs-6">
    <div class="mw-ui-field-holder">
        <span class="mw-ui-btn" id="bg-image"><span class="mw-icon-upload"></span><?php _e('Choose Skin Background image'); ?></span>
    </div>
</div>
<input type="hidden" class="mw_option_field" name="bg-image" id="bgimageval" value="<?php print $bgImage; ?>"/>

<script>
    $(document).ready(function () {
        var textImage = mw.uploader({
            filetypes: "images,videos",
            multiple: false,
            element: "#bg-image"
        });
        $(textImage).bind('FileUploaded', function (a, b) {
            mw.tools.preload(b.src);
            mw.$("#bgimageval").val(b.src).trigger('change');
        });
    });
</script>
