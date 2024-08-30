<div class="mw-flex-col-md-<?php echo $settings['field_size']; ?>">
<div class="mw-ui-field-holder">

    <?php if($settings['show_label']): ?>
	<label class="mw-ui-label">
	<?php echo $data['name']; ?>
	<?php if ($settings['required']): ?>
	<span style="color: red;">*</span>
	<?php endif; ?>
	</label>
    <?php endif; ?>

	 <?php if ($data['help']): ?>
        <small class="mw-custom-field-help"><?php echo $data['help']; ?></small>
    <?php endif; ?>
    <?php $fieldId = uniqid('field'); ?>
	<div class="mw-ui-controls">
		<input
            type="time"
            class="mw-ui-field"
            id="<?php print $fieldId ?>"
            <?php if ($settings['required']): ?>required<?php endif; ?>
            data-custom-field-id="<?php echo $data['id']; ?>"
            name="<?php echo $data['name_key']; ?>"
            value="<?php echo $data['value']; ?>"
            placeholder="<?php echo $data['placeholder']; ?>" />
	</div>
</div>
</div>

<script>
	mw.lib.require("datetimepicker");
</script>

<script type="text/javascript">
$(function () {
	$('#<?php print $fieldId ?>').datetimepicker({
        datepicker: false,
        format:'H:i'
	});
});
</script>
