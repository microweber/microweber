<?php
$form_fields_from_template = template_dir() . 'modules/shop/payments/gateways/lib/omnipay/cc_form_fields.php';
// dd($form_fields_from_template);
if (is_file($form_fields_from_template)) {

	include ($form_fields_from_template);
	return;
}

$templateFormRowClass = template_form_row_class();
$templateFormGroupClass = template_form_group_class();
$templateInputFieldClass = template_input_field_class();
$templateFormGroupLabelClass = template_form_group_label_class();

// Set space aroud diffrent classes
if (!empty($templateFormGroupClass)) { 
	$templateFormGroupClass = ' '. $templateFormGroupClass;  
}
?>

<div class="<?php echo $templateFormRowClass; ?>">
	<div class="<?php echo template_field_size_class(6); ?><?php echo $templateFormGroupClass; ?>">
		<label class="<?php echo $templateFormGroupLabelClass; ?>">
            <?php _e("First Name"); ?>
        </label> 
        <input name="cc_first_name" type="text" class="<?php echo $templateInputFieldClass; ?>" value="" />
	</div>
	<div class="<?php echo template_field_size_class(6); ?><?php echo $templateFormGroupClass; ?>">
		<label class="<?php echo $templateFormGroupLabelClass; ?>">
            <?php _e("Last Name"); ?>
        </label> 
        <input name="cc_last_name" type="text" class="<?php echo $templateInputFieldClass; ?>" value="" />
	</div>
</div>

<div class="<?php echo $templateFormRowClass; ?>">
	<div class="<?php echo template_field_size_class(12); ?><?php echo $templateFormGroupClass; ?>">
		<label class="<?php echo $templateFormGroupLabelClass; ?>">
            <?php _e("Credit Card"); ?>
        </label> 
        <select name="cc_type" class="<?php echo $templateInputFieldClass; ?>">
			<option value="Visa" selected>
                <?php _e("Visa"); ?>
            </option>
			<option value="MasterCard">
                <?php _e("MasterCard"); ?>
            </option>
			<option value="Discover">
                <?php _e("Discover"); ?>
            </option>
			<option value="Amex">
                <?php _e("American Express"); ?>
            </option>
		</select>
	</div>

	<div class="<?php echo template_field_size_class(12); ?><?php echo $templateFormGroupClass; ?>">
		<label class="<?php echo $templateFormGroupLabelClass; ?>">
            <?php _e("Credit Card Number"); ?>
        </label> 
        <input name="cc_number" type="text" value="" class="<?php echo $templateInputFieldClass; ?>" />
	</div>
</div>


<div class="<?php echo $templateFormRowClass; ?>">

	<div class="<?php echo template_field_size_class(4); ?><?php echo $templateFormGroupClass; ?>">
		<label class="<?php echo $templateFormGroupLabelClass; ?>">
	            <?php _e("CVC"); ?>
	    </label> 
	        <input name="cc_verification_value" type="text" value="" class="<?php echo $templateInputFieldClass; ?>"  placeholder="<?php _e("ex. 311"); ?>" />
		<div class="cc_process_error"></div>
	</div>

	<div class="<?php echo template_field_size_class(4); ?><?php echo $templateFormGroupClass; ?>">
		<label class="<?php echo $templateFormGroupLabelClass; ?>">
            <?php _e("Expiration"); ?>
        </label> 
        <input name="cc_month" placeholder="<?php _e("MM"); ?>" type="text" value="" class="<?php echo $templateInputFieldClass; ?>" />
	</div>

	<div class="<?php echo template_field_size_class(4); ?><?php echo $templateFormGroupClass; ?>">
		<label class="<?php echo $templateFormGroupLabelClass; ?>">&nbsp;</label> 
		<input name="cc_year" placeholder="<?php _e("YYYY"); ?>" type="text" value="" class="<?php echo $templateInputFieldClass; ?>" />
	</div>

</div>
