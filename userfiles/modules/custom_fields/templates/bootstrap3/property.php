<div class="col-md-<?php echo $settings['field_size']; ?>">
    <div class="form-group">

        <?php if($settings['show_label']): ?>
        <label class="control-label">
            <?php echo $data['name']; ?>
            <?php if ($settings['required']): ?>
                <span style="color: red;">*</span>
            <?php endif; ?>
        </label>
        <?php endif; ?>

        <input type="hidden" class="form-control" data-custom-field-id="<?php echo $data['id']; ?>" name="<?php echo $data['name_key']; ?>" value="<?php echo $data['value']; ?>"/>

        <?php if ($data['help']): ?>
            <span class="help-block"><?php echo $data['help']; ?></span>
        <?php endif; ?>

        <div class="controls">
            <?php echo $data["value"]; ?>
        </div>
    </div>
</div>
