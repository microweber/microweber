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

        <div id="datetimepicker3">
            <input type="text" class="form-control js-bootstrap3-timepicker" <?php if ($settings['required']): ?>required<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" name="<?php echo $data['name_key']; ?>" value="<?php echo $data['value']; ?>" placeholder="<?php echo $data['placeholder']; ?>" autocomplete="off"/>
        </div>

        <?php if ($data['help']): ?>
            <span class="help-block"><?php echo $data['help']; ?></span>
        <?php endif; ?>
    </div>
</div>

<script>
	mw.lib.require("bootstrap_datetimepicker");
</script>

<script type="text/javascript">
	$(document).ready(function () {
		$('.js-bootstrap3-timepicker').datetimepicker({
			pickDate: false,
			minuteStep: 15,
			pickerPosition: 'bottom-right',
			format: 'HH:ii p',
			autoclose: true,
			showMeridian: true,
			startView: 1,
			maxView: 1
		});
	});
</script>
