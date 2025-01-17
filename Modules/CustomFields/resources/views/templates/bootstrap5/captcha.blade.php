<div class="col-sm-<?php echo $settings['field_size_mobile']; ?> col-md-<?php echo $settings['field_size_tablet']; ?> col-lg-<?php echo $settings['field_size_desktop']; ?>">
    <div class="mw-text-start my-2">

        <?php if($settings['show_label']): ?>
        <label class="form-label my-3"><?php echo $data["name"]; ?>
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
