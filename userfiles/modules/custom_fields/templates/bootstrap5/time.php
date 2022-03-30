<div class="col-sm-<?php echo $settings['field_size_mobile']; ?> col-md-<?php echo $settings['field_size_tablet']; ?> col-lg-<?php echo $settings['field_size_desktop']; ?>">
	<div class="mw-text-start mb-3">

        <?php if($settings['show_label']): ?>
			<label class="control-label my-3">
				<?php echo $data['name']; ?>
				<?php if ($settings['required']): ?>
					<span style="color: red;">*</span>
				<?php endif; ?>
			</label>
        <?php endif; ?>

        <input type="text" class="form-control js-bootstrap4-timepicker" <?php if ($settings['required']): ?>required<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" name="<?php echo $data['name_key']; ?>" value="<?php echo $data['value']; ?>" placeholder="<?php echo $data['placeholder']; ?>" autocomplete="off"/>
        <div class="valid-feedback"><?php _e('Success! You\'ve done it.'); ?></div>
        <div class="invalid-feedback"><?php _e('Error! The value is not valid.'); ?></div>

        <?php if ($data['help']): ?>
            <small class="form-text text-muted"><?php echo $data['help']; ?></small>
        <?php endif; ?>
	</div>
</div>

<script>
    mw.lib.require("bootstrap_datetimepicker");
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.js-bootstrap4-timepicker').datetimepicker({
            pickDate: false,
            minuteStep: 15,
            pickerPosition: 'bottom-right',
            format: 'HH:ii p',
            autoclose: true,
            showMeridian: true,
            startView: 1,
            maxView: 1,
        });
    });
</script>
