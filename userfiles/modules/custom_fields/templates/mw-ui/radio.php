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
    <?php 
    $i = 0; 
    foreach($data['values'] as $value):
    $i++;
    ?>
	<label class="mw-ui-check">
		<input type="radio" <?php if ($settings['required'] && $i==1): ?>required<?php endif; ?> data-custom-field-id="<?php echo $data['id']; ?>" value="<?php echo $value; ?>" name="<?php echo $data['name']; ?>"
			<?php if($data['value'] && $data['value'] == $value): ?>checked="checked"<?php endif; ?> />
		 <span></span>
		<span><?php echo $value; ?></span>
	</label>
	<?php endforeach; ?>
</div>
</div>