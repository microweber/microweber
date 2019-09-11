<div class="col-<?php echo $settings['field_size']; ?>">
    <div class="form-group">

        <label class="col-form-label"><?php echo $data["name"]; ?>
            <?php if ($settings["required"]): ?>
                <span style="color:red;">*</span>
            <?php endif; ?>
        </label>

        <div class="mw-custom-field-form-controls">
            <module type="captcha"/>
        </div>
    </div>
</div>