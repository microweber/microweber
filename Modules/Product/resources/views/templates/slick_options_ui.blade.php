<div class="mw-flex-row">
    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label">Extra Small &lt; 576px</label>
            <select name="slides-xs" class="mw-ui-field mw_option_field mw-full-width" data-option-group="<?php print $params['id']; ?>" data-columns="xs">
                <option value="1" <?php if ($slides_xs == '1'): ?>selected<?php endif; ?>>1 slide</option>
                <option value="2" <?php if ($slides_xs == '2'): ?>selected<?php endif; ?>>2 slides</option>
                <option value="3" <?php if ($slides_xs == '3'): ?>selected<?php endif; ?>>3 slides</option>
                <option value="4" <?php if ($slides_xs == '4'): ?>selected<?php endif; ?>>4 slides</option>
                <option value="5" <?php if ($slides_xs == '5'): ?>selected<?php endif; ?>>5 slides</option>
                <option value="6" <?php if ($slides_xs == '6'): ?>selected<?php endif; ?>>6 slides</option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label">Small ≥ 576px</label>
            <select name="slides-sm" class="mw-ui-field mw_option_field mw-full-width" data-option-group="<?php print $params['id']; ?>" data-columns="sm">
                <option value="1" <?php if ($slides_sm == '1'): ?>selected<?php endif; ?>>1 slide</option>
                <option value="2" <?php if ($slides_sm == '2'): ?>selected<?php endif; ?>>2 slides</option>
                <option value="3" <?php if ($slides_sm == '3'): ?>selected<?php endif; ?>>3 slides</option>
                <option value="4" <?php if ($slides_sm == '4'): ?>selected<?php endif; ?>>4 slides</option>
                <option value="5" <?php if ($slides_sm == '5'): ?>selected<?php endif; ?>>5 slides</option>
                <option value="6" <?php if ($slides_sm == '6'): ?>selected<?php endif; ?>>6 slides</option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label">Medium ≥ 768px</label>
            <select name="slides-md" class="mw-ui-field mw_option_field mw-full-width" data-option-group="<?php print $params['id']; ?>" data-columns="md">
                <option value="1" <?php if ($slides_md == '1'): ?>selected<?php endif; ?>>1 slide</option>
                <option value="2" <?php if ($slides_md == '2'): ?>selected<?php endif; ?>>2 slides</option>
                <option value="3" <?php if ($slides_md == '3'): ?>selected<?php endif; ?>>3 slides</option>
                <option value="4" <?php if ($slides_md == '4'): ?>selected<?php endif; ?>>4 slides</option>
                <option value="5" <?php if ($slides_md == '5'): ?>selected<?php endif; ?>>5 slides</option>
                <option value="6" <?php if ($slides_md == '6'): ?>selected<?php endif; ?>>6 slides</option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label">Large ≥ 992px</label>
            <select name="slides-lg" class="mw-ui-field mw_option_field mw-full-width" data-option-group="<?php print $params['id']; ?>" data-columns="lg">
                <option value="1" <?php if ($slides_lg == '1'): ?>selected<?php endif; ?>>1 slide</option>
                <option value="2" <?php if ($slides_lg == '2'): ?>selected<?php endif; ?>>2 slides</option>
                <option value="3" <?php if ($slides_lg == '3'): ?>selected<?php endif; ?>>3 slides</option>
                <option value="4" <?php if ($slides_lg == '4'): ?>selected<?php endif; ?>>4 slides</option>
                <option value="5" <?php if ($slides_lg == '5'): ?>selected<?php endif; ?>>5 slides</option>
                <option value="6" <?php if ($slides_lg == '6'): ?>selected<?php endif; ?>>6 slides</option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label">Extra large ≥ 1200px</label>
            <select name="slides-lg" class="mw-ui-field mw_option_field mw-full-width" data-option-group="<?php print $params['id']; ?>" data-columns="xl">
                <option value="1" <?php if ($slides_xl == '1'): ?>selected<?php endif; ?>>1 slide</option>
                <option value="2" <?php if ($slides_xl == '2'): ?>selected<?php endif; ?>>2 slides</option>
                <option value="3" <?php if ($slides_xl == '3'): ?>selected<?php endif; ?>>3 slides</option>
                <option value="4" <?php if ($slides_xl == '4'): ?>selected<?php endif; ?>>4 slides</option>
                <option value="5" <?php if ($slides_xl == '5'): ?>selected<?php endif; ?>>5 slides</option>
                <option value="6" <?php if ($slides_xl == '6'): ?>selected<?php endif; ?>>6 slides</option>
            </select>
        </div>
    </div>
</div>

<hr/>

<div class="mw-flex-row slider-options">
    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _lang("Pager", 'template/big'); ?></label>
            <select name="pager" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                <option value="false" <?php if ($pager == 'false'): ?> selected="selected" <?php endif ?>><?php _lang("False", 'template/big'); ?></option>
                <option value="true" <?php if ($pager == 'true'): ?> selected="selected" <?php endif ?>><?php _lang("True", 'template/big'); ?></option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _lang("Controls", 'template/big'); ?></label>
            <select name="controls" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                <option value="false" <?php if ($controls == 'false'): ?> selected="selected" <?php endif ?>><?php _lang("False", 'template/big'); ?></option>
                <option value="true" <?php if ($controls == 'true'): ?> selected="selected" <?php endif ?>><?php _lang("True", 'template/big'); ?></option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _lang("Loop", 'template/big'); ?></label>
            <select name="loop" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                <option value="false" <?php if ($loop == 'false'): ?> selected="selected" <?php endif ?>><?php _lang("False", 'template/big'); ?></option>
                <option value="true" <?php if ($loop == 'true'): ?> selected="selected" <?php endif ?>><?php _lang("True", 'template/big'); ?></option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _lang("Adaptive Height", 'template/big'); ?></label>
            <select name="adaptive_height" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                <option value="false" <?php if ($adaptiveHeight == 'false'): ?> selected="selected" <?php endif ?>><?php _lang("False", 'template/big'); ?></option>
                <option value="true" <?php if ($adaptiveHeight == 'true'): ?> selected="selected" <?php endif ?>><?php _lang("True", 'template/big'); ?></option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _lang("Autoplay Speed", 'template/big'); ?></label>
            <input type="text" value="<?php print $speed; ?>" name="speed" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>"/>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _lang("Pause on hover", 'template/big'); ?></label>
            <select name="pause_on_hover" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                <option value="false" <?php if ($pauseOnHover == 'false'): ?> selected="selected" <?php endif ?>><?php _lang("False", 'template/big'); ?></option>
                <option value="true" <?php if ($pauseOnHover == 'true'): ?> selected="selected" <?php endif ?>><?php _lang("True", 'template/big'); ?></option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _lang("Autoplay", 'template/big'); ?></label>
            <select name="autoplay" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                <option value="false" <?php if ($autoplay == 'false'): ?> selected="selected" <?php endif ?>><?php _lang("False", 'template/big'); ?></option>
                <option value="true" <?php if ($autoplay == 'true'): ?> selected="selected" <?php endif ?>><?php _lang("True", 'template/big'); ?></option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _lang("Draggable", 'template/big'); ?></label>
            <select name="draggable" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                <option value="false" <?php if ($draggable == 'false'): ?> selected="selected" <?php endif ?>><?php _lang("False", 'template/big'); ?></option>
                <option value="true" <?php if ($draggable == 'true'): ?> selected="selected" <?php endif ?>><?php _lang("True", 'template/big'); ?></option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _lang("Fade", 'template/big'); ?>
                <small>(work only for 1 slide)</small>
            </label>
            <select name="fade" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                <option value="false" <?php if ($fade == 'false'): ?> selected="selected" <?php endif ?>><?php _lang("False", 'template/big'); ?></option>
                <option value="true" <?php if ($fade == 'true'): ?> selected="selected" <?php endif ?>><?php _lang("True", 'template/big'); ?></option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _lang("Focus On Select", 'template/big'); ?></label>
            <select name="focus_on_select" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                <option value="false" <?php if ($focusOnSelect == 'false'): ?> selected="selected" <?php endif ?>><?php _lang("False", 'template/big'); ?></option>
                <option value="true" <?php if ($focusOnSelect == 'true'): ?> selected="selected" <?php endif ?>><?php _lang("True", 'template/big'); ?></option>
            </select>
        </div>
    </div>

    <div class="mw-flex-col-xs-4 ">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label"><?php _lang("Center Padding", 'template/big'); ?></label>
            <input name="center_padding" class="mw-ui-field mw_option_field mw-full-width" data-option-group="<?php print $params['id']; ?>" value="<?php print $centerPadding; ?>" placeholder="40px"/>
        </div>
    </div>
</div>
