<script>mw.require('forms.js');</script>

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

        <input type="number" onKeyup="mw.form.typeNumber(this);" class="form-control" <?php if ($settings['required']): ?>required="true"<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" value="<?php echo $data['value']; ?>" name="<?php echo $data['name']; ?>" placeholder="<?php echo $data['placeholder']; ?>" />

        <?php if ($data['help']): ?>
            <span class="help-block"><?php echo $data['help']; ?></span>
        <?php endif; ?>
    </div>
</div>
