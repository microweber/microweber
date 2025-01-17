<div>
    <?php
    $defaultImageWidth = '100'; // Default width in pixels
    $imageWidth = get_option('imageWidth', $params['id']);
    if ($imageWidth == false) {
        $imageWidth = $defaultImageWidth;
    }
    ?>

    <div class="form-control-live-edit-label-wrapper mb-3">
        <label class="live-edit-label">
            <?php _e("Image Width"); ?>
        </label>
        <input type="number" name="imageWidth" option-group="{{ $params['id'] }}" class="form-control-live-edit-input mw_option_field" value="<?php print $imageWidth; ?>">
    </div>
</div>

