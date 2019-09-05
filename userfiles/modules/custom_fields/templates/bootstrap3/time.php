<div class="col-md-<?php echo $settings['field_size']; ?>">
<div class="form-group">
	<label class="mw-ui-label"> 
	<?php echo $data['name']; ?>
	<?php if ($settings['required']): ?>
	<span style="color: red;">*</span>
	<?php endif; ?>
	</label>
	 <?php if ($data['help']): ?>
        <small class="mw-custom-field-help"><?php echo $data['help']; ?></small>
    <?php endif; ?>
	<div class="mw-ui-controls" id="datetimepicker3">
		<input type="text" class="form-control js-bootstrap3-timepicker" <?php if ($settings['required']): ?>required="true"<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" name="<?php echo $data['name']; ?>" placeholder="<?php echo $data['placeholder']; ?>"  autocomplete="off" />
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
                maxView: 1,
            });
        });
    </script>