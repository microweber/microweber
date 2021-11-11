<?php must_have_access(); ?>

<?php
if ($params['coupon_id'] !== 'false') {
    $addNew = false;
    $data = coupon_get_by_id($params['coupon_id']);
} else {
    $addNew = true;

    $data['id'] = '';
    $data['coupon_name'] = '';
    $data['coupon_code'] = '';
    $data['cupon_name'] = '';
    $data['discount_type'] = '';
    $data['discount_value'] = '';
    $data['total_amount'] = '';
    $data['uses_per_coupon'] = '';
    $data['uses_per_customer'] = '';
    $data['is_active'] = 1;
}
?>

<script>
    // SET GLOBAL MULTILANGUAGE TEXTS
    var TEXT_FIELD_MUST_BE_FLOAT_NUMBER = "<?php _ejs('The field must be float number.');?>";
    var TEXT_FIELD_MUST_BE_NUMBER = "<?php _ejs('The field must be number.');?>";
    var TEXT_SUCCESS_SAVE = "<?php _ejs('Coupon are saved success!');?>";
    var TEXT_FIELD_CANNOT_BE_EMPTY = "<?php _ejs('This field cannot be empty.');?>";
    var TEXT_FILL_ALL_FIELDS = "<?php _ejs('Please fill all fields correct.');?>";
</script>


<div class="js-validation-messages"></div>

<form class="js-edit-coupon-form" action="<?php print api_url('coupons_save_coupon'); ?>">
    <input type="hidden" name="id" value="<?php print $data['id'] ?>"/>

    <div class="form-group">
        <label class="control-label"><?php _e("Coupon name"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("Enter the name of your coupone code."); ?></small>
        <input type="text" name="coupon_name" class="form-control js-coupon-name js-validation" value="<?php print $data['coupon_name'] ?>"/>
        <div class="js-field-message"></div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php _e("Code"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("Enter the discount code or generate it from the button bellow."); ?></small>
        <input type="text" name="coupon_code" class="form-control js-coupon-code js-validation" value="<?php print $data['coupon_code'] ?>"/>
        <div class="js-field-message"></div>
        <br/>
        <button type="button" class="btn btn-outline-primary btn-sm js-generate-new-promo-code"><?php _e("Generate New Promo Code"); ?></button>
    </div>

    <div class="form-group">
        <label class="control-label"><?php _e("Discount Type"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("Choose the type of discount which can be fixed price or percentage of the price."); ?></small>
        <select name="discount_type" class="js-discount-type selectpicker" data-width="100%">
            <option value="percentage"><?php _e("Percentage"); ?></option>
            <option value="fixed_amount"><?php _e("Fixed Amount"); ?></option>
        </select>
    </div>

    <div class="form-group">
        <label class="control-label"><?php _e("Discount"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("Enter the value of your discount."); ?></small>
        <input type="text" name="discount_value" class="form-control js-validation js-validation-float-number" value="<?php print $data['discount_value'] ?>"/>
        <div class="js-field-message"></div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php _e("Minimum Order Amount"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("Apply the discount when the cart amount is more than the value of the coupone code."); ?></small>
        <input type="text" name="total_amount" class="form-control js-validation js-validation-float-number" value="<?php print $data['total_amount'] ?>"/>
        <div class="js-field-message"></div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php _e("Uses Per Coupon"); ?></label>
        <small class="text-muted d-block mb-2" title=""><?php _e("How many times can this coupone can be used."); ?></small>
        <input type="text" name="uses_per_coupon" class="form-control js-validation js-validation-number" value="<?php print $data['uses_per_coupon'] ?>"/>
        <div class="js-field-message"></div>
    </div>

    <div class="form-group">
        <label class="control-label"><?php _e("Uses Per Customer"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("How many times every client can use the coupone code."); ?></small>
        <input type="text" name="uses_per_customer" class="form-control js-validation js-validation-number" value="<?php print $data['uses_per_customer'] ?>"/>
        <div class="js-field-message"></div>
    </div>

    <div class="form-group">
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" data-value-checked="1" data-value-unchecked="0" <?php if ($data['is_active'] == 1): ?>checked<?php endif; ?>>
            <label class="custom-control-label" for="is_active"><?php _e("Active"); ?></label>
        </div>
        <small class="text-muted d-block mb-2" title=""><?php _e("Is the discount active or not."); ?></small>
    </div>

    <hr class="thin">

    <div class="d-flex justify-content-between">
        <?php if (!$addNew) { ?>
            <div>
                <a class="btn btn-outline-danger btn-sm" href="javascript:deleteCoupon('<?php print $data['id'] ?>')"><?php _e("Delete"); ?></a>
            </div>
        <?php } ?>

        <div>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="editModal.modal.remove()"><?php _e("Cancel"); ?></button>
            <button type="button" class="btn btn-success btn-sm js-save-coupon"><?php _e("Save"); ?></button>
        </div>
    </div>
</form>

<script src="<?php print $config['url_to_module']; ?>js/edit-coupon.js"/>

<script type='text/javascript'>

    $(document).ready(function () {

        <?php if($addNew): ?>
        $('.js-coupon-code').val(uniqueId());
        <?php endif; ?>

        <?php if (isset($data['discount_type'])): ?>
        $(".js-discount-type").val("<?php echo $data['discount_type']; ?>").change();
        <?php endif; ?>
    });

</script>
