<div class="module-live-edit-settings module-ants-settings">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Number of ants"); ?></label>
        <div class="range-slider">
            <input name="number_of_ants" data-refresh="ants" class="mw-ui-field-range mw_option_field mw-full-width" type="range" min="3" max="100" value="<?php print get_option('number_of_ants', $params['id']) ?>">
        </div>
    </div>
</div>