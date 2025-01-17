<div>
    <?php
    $click_image_event = 'fullscreen';
    $get_click_image_event = get_option('click_image_event', $params['id']);
    if ($get_click_image_event != false) {
        $click_image_event = $get_click_image_event;
    }
    ?>
    <div class="form-group">
        <label class="form-label">
            <?php _e("Click on image event"); ?>
        </label>
        <select name="click_image_event" option-group="<?php echo $params['id'] ?>" class="form-control mw_option_field">
            <option
                <?php if ($click_image_event == 'fullscreen') {
                    echo ' selected="selected" ';
                } ?>
                value="fullscreen">Open Image on Fullscreen
            </option>
            <option
                <?php if ($click_image_event == 'link') {
                    echo ' selected="selected" ';
                } ?>
                value="link">Open Link
            </option>
            <option
                <?php if ($click_image_event == 'link_target_blank') {
                    echo ' selected="selected" ';
                } ?>
                value="link_target_blank">Open Link (On target blank)
            </option>
        </select>
    </div>
</div>
