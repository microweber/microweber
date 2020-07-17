<?php

only_admin_access();
$layout = get_option('layout', $params['id']);
$url_to_like = get_option('url', $params['id']);
$url_to_like = get_option('url', $params['id']);
$color = get_option('color', $params['id']);
$show_faces = get_option('show_faces', $params['id']);
$size = get_option('size', $params['id']);

?>

<div class="module-live-edit-settings module-facebook-like-settings">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("URL to like"); ?></label>
        <input name="url" class="mw-ui-field mw_option_field  mw-full-width" type="text" placeholder="<?php _e("Current URL or type your own"); ?>" value="<?php print $url_to_like; ?>"/>
    </div>

    <div class="mw-flex-row">
        <div class="mw-flex-col-xs-3">
            <div class="mw-ui-field-holder">
                <label class="mw-ui-label"><?php _e("Layout"); ?></label>
                <select name="layout" class="mw-ui-field mw_option_field mw-full-width">
                    <option value="standard" <?php if ($layout == false or $layout == 'standard'): ?> selected="selected" <?php endif ?>><?php _e("Standard"); ?></option>
                    <option value="box_count" <?php if ($layout == 'box_count'): ?> selected="selected" <?php endif ?>><?php _e("Box count"); ?></option>
                    <option value="button_count" <?php if ($layout == 'button_count'): ?> selected="selected" <?php endif ?>><?php _e("Button count"); ?></option>
                    <option value="button" <?php if ($layout == 'button'): ?> selected="selected" <?php endif ?>><?php _e("Button"); ?></option>
                </select>
            </div>
        </div>

        <div class="mw-flex-col-xs-3">
            <div class="mw-ui-field-holder">
                <label class="mw-ui-label"><?php _e("Size"); ?></label>
                <select name="size" class="mw-ui-field mw_option_field mw-full-width">
                    <option value="small" <?php if ($size == false or $size == 'small'): ?> selected="selected" <?php endif ?>><?php _e("Small"); ?></option>
                    <option value="large" <?php if ($size == 'large'): ?> selected="selected" <?php endif ?>><?php _e("Large"); ?></option>
                </select>
            </div>
        </div>

        <div class="mw-flex-col-xs-3">
            <div class="mw-ui-field-holder">
                <label class="mw-ui-label"><?php _e("Color scheme"); ?></label>
                <select name="color" class="mw-ui-field mw_option_field mw-full-width">
                    <option value="light" <?php if ($color == false or $color == 'standard'): ?> selected="selected" <?php endif ?>><?php _e("Light"); ?></option>
                    <option value="dark" <?php if ($color == 'dark'): ?> selected="selected" <?php endif ?>><?php _e("Dark"); ?></option>
                </select>
            </div>
        </div>

        <div class="mw-flex-col-xs-3">
            <div class="mw-ui-field-holder">
                <label class="mw-ui-label"><?php _e("Show faces"); ?></label>
                <select name="show_faces" class="mw-ui-field mw_option_field  mw-full-width">
                    <option value="y" <?php if ($show_faces == false or $show_faces == 'y'): ?> selected="selected" <?php endif ?>><?php _e("Yes"); ?></option>
                    <option value="n" <?php if ($show_faces == 'n'): ?> selected="selected" <?php endif ?>><?php _e("No"); ?></option>
                </select>
            </div>
        </div>
    </div>

</div>