<?php foreach (get_mailerlite_api_fields() as $field): ?>
<div class="demobox">
	<label class="mw-ui-label"><?php echo $field['title']; ?></label> 
	<input type="text" value="" name="<?php echo $field['name']; ?>" class="mw-ui-field w100 mw_option_field">
</div>
<br />
<?php endforeach; ?>