<div class="col-<?php echo $settings['field_size']; ?>">
    <div class="mb-3">

        <?php if($settings['show_label']): ?>
        <label class="control-label mb-3"><?php echo $data["name"]; ?>
            <?php if ($settings["required"]): ?>
                <span style="color:red;">*</span>
            <?php endif; ?>
        </label>
        <?php endif; ?>


        <div class="mw-custom-field-form-controls">
            <module type="captcha"/>
        </div>
    </div>
</div>
