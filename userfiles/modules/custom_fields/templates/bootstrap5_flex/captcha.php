<div class="col-sm-<?php echo $settings['field_size_mobile']; ?> col-md-<?php echo $settings['field_size_tablet']; ?> col-lg-<?php echo $settings['field_size_desktop']; ?>">
    <div class="mb-3 d-flex">

        <?php if($settings['show_label']): ?>
        <label class="control-label me-2 align-self-center mb-0"><?php echo $data["name"]; ?>
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
