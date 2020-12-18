<script type="text/javascript">
    
    zoommap = function (val) {
        mw.$("#zoom_level").val(val).trigger("change");
    }
</script>

<div class="module-live-edit-settings  module-google-maps-settings">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Enter Your Address"); ?></label>
        <input name="data-address" class="mw-ui-field mw_option_field mw-full-width" id="addr" type="text" value="<?php print get_option('data-address', $params['id']) ?>" placeholder="<?php print _e('Example: Bulgaria, Sofia, bul. Cherni Vrah 47'); ?>"/>
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Zoom Level"); ?></label>
        <div class="range-slider">
            <input name="data-zoom" class="mw-ui-field-range mw_option_field mw-full-width" max="21" min="0" type="range" id="zoom_level" value="<?php print get_option('data-zoom', $params['id']) ?>"/>
        </div>
    </div>
</div>
