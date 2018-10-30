<?php only_admin_access(); ?>

<?php
$before = get_option('before', $params['id']);
$after = get_option('after', $params['id']);

if ($before == false) {
    $before = module_url() . 'img/white-car.jpg';
}

if ($after == false) {
    $after = module_url() . 'img/blue-car.jpg';
}
?>

<style>
    .module-before-after-settings img {
        max-width: 100%;
    }
</style>

<script>mw.lib.require('font_awesome5');</script>

<div class="module-live-edit-settings module-before-after-settings">
    <input type="hidden" class="mw_option_field" name="before" id="beforeval" value="<?php print $before; ?>"/>
    <input type="hidden" class="mw_option_field" name="after" id="afterval" value="<?php print $after; ?>"/>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php print _e('Upload Before Image'); ?></label>
        <img src="<?php print $before; ?>" alt="before" class="js-before-image"/>
        <br/>
        <br/>
        <span class="mw-ui-btn mw-ui-btn-info" id="before"><span class="fas fa-upload"></span> &nbsp; <?php _e('Choose Before Image'); ?></span>
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php print _e('Upload After Image'); ?></label>
        <img src="<?php print $after; ?>" alt="after" class="js-after-image"/>
        <br/>
        <br/>
        <span class="mw-ui-btn mw-ui-btn-info" id="after"><span class="fas fa-upload"></span> &nbsp; <?php _e('Choose After Image'); ?></span>
    </div>
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
            mw.$(".js-before-image").attr('src', b.src);
        });

        var after = mw.uploader({
            filetypes: "images,videos",
            multiple: false,
            element: "#after"
        });
        $(after).bind('FileUploaded', function (a, b) {
            preload_image(b.src)
            mw.$("#afterval").val(b.src).trigger('change');
            mw.$(".js-after-image").attr('src', b.src);
        });
    });

    preload_image = function (src) {
        var elem = document.createElement("img");
        elem.setAttribute("src", src);
    }
</script>