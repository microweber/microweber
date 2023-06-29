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
    $data['discount_type'] = 'percentage';
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

        <div class="row">
            <div class="col-sm-9 col-6">
             <label class="form-label"><?php _e("Coupon name"); ?></label>
            <small class="text-muted d-block mb-2"><?php _e("Enter the name of your coupone code."); ?></small>
            </div>
            <div class="col-sm-3 col-6 d-flex align-items-center justify-content-end">
                <div style="width:80px">
                    <label class="form-check form-switch" x-data="{ couponIsActive: <?php if ($data['is_active'] == 1): ?>true<?php else: ?>false<?php endif; ?> }">
                        <input class="form-check-input" x-model="couponIsActive" type="checkbox" id="is_active" name="is_active">
                        <template x-if="couponIsActive">
                            <span class="form-check-label ms-1">
                                <?php _e("Active"); ?>
                            </span>
                        </template>
                        <template x-if="!couponIsActive">
                            <span class="form-check-label ms-1">
                                <?php _e("Inactive"); ?>
                            </span>
                        </template>
                    </label>
                </div>
            </div>
        </div>

        <div>
            <input type="text" name="coupon_name" required="required" class="form-control js-coupon-name js-validation" value="<?php print $data['coupon_name'] ?>"/>
        </div>

       <div class="js-field-message"></div>
    </div>

    <div class="form-group">
        <label class="form-label"><?php _e("Code"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("Enter the discount code or generate it from the button bellow."); ?></small>

        <div class="input-group">
        <input type="text" name="coupon_code" required="required" class="form-control js-coupon-code js-validation" value="<?php print $data['coupon_code'] ?>"/>
        <button type="button" class="btn btn-outline-primary btn-sm js-generate-new-promo-code ms-2"><?php _e("Generate Promo Code"); ?></button>
        </div>

        <div class="js-field-message"></div>
    </div>


<!--    <div class="mb-4">
        <label class="form-label"><?php _e("Discount Type"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("Choose the type of discount which can be fixed price or percentage of the price."); ?></small>

        <div class="form-selectgroup">
            <label class="form-selectgroup-item">
                <input type="radio" name="discount_type" value="percentage" class="form-selectgroup-input js-discount-type" <?php if ($data['discount_type'] == 'percentage'): ?>checked=""<?php endif; ?>>
                <span class="form-selectgroup-label">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="24" viewBox="0 96 960 960" width="24"><path d="M300 536q-58 0-99-41t-41-99q0-58 41-99t99-41q58 0 99 41t41 99q0 58-41 99t-99 41Zm0-80q25 0 42.5-17.5T360 396q0-25-17.5-42.5T300 336q-25 0-42.5 17.5T240 396q0 25 17.5 42.5T300 456Zm360 440q-58 0-99-41t-41-99q0-58 41-99t99-41q58 0 99 41t41 99q0 58-41 99t-99 41Zm0-80q25 0 42.5-17.5T720 756q0-25-17.5-42.5T660 696q-25 0-42.5 17.5T600 756q0 25 17.5 42.5T660 816Zm-444 80-56-56 584-584 56 56-584 584Z"/></svg>
                    <?php _e("Percentage"); ?>
                </span>
            </label>
            <label class="form-selectgroup-item">
                <input type="radio" name="discount_type" value="fixed_amount" class="form-selectgroup-input js-discount-type" <?php if ($data['discount_type'] == 'fixed_amount'): ?>checked=""<?php endif; ?>>
                <span class="form-selectgroup-label">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="24" viewBox="0 96 960 960" width="24"><path d="M640 536q17 0 28.5-11.5T680 496q0-17-11.5-28.5T640 456q-17 0-28.5 11.5T600 496q0 17 11.5 28.5T640 536Zm-320-80h200v-80H320v80ZM180 936q-34-114-67-227.5T80 476q0-92 64-156t156-64h200q29-38 70.5-59t89.5-21q25 0 42.5 17.5T720 236q0 6-1.5 12t-3.5 11q-4 11-7.5 22.5T702 305l91 91h87v279l-113 37-67 224H480v-80h-80v80H180Zm60-80h80v-80h240v80h80l62-206 98-33V476h-40L620 336q0-20 2.5-38.5T630 260q-29 8-51 27.5T547 336H300q-58 0-99 41t-41 99q0 98 27 191.5T240 856Zm240-298Z"/></svg>
                      <?php _e("Fixed Amount"); ?>
                </span>
            </label>
        </div>
    </div>-->

    <script type='text/javascript'>
        $(document).ready(function () {
            $('.js-discount-type').change(function () {
                var val = $(this).val();
                if (val === 'percentage') {
                    $('.js-discount-value-label').html('%');
                } else {
                    $('.js-discount-value-label').html('<?php echo get_currency_symbol(); ?>');
                }
            });
        });
    </script>

    <div class="form-group">
        <label class="form-label"><?php _e("Discount"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("Enter the value of your discount."); ?></small>
        <div class="row mb-2">
            <div class="col-lg-5 col-8">
                <div class="input-group">
                    <span class="input-group-text js-discount-value-label">%</span>
                    <input type="text" name="discount_value" class="form-control js-validation js-validation-float-number" value="<?php print $data['discount_value'] ?>" />
                </div>
            </div>
            <div class="col-lg-7 col-4 d-flex align-items-center justify-content-end px-0">
                <div class="form-selectgroup px-0">
                    <label class="form-selectgroup-item mx-0 pe-2">
                        <input type="radio" name="discount_type" value="percentage" class="form-selectgroup-input js-discount-type" <?php if ($data['discount_type'] == 'percentage'): ?>checked=""<?php endif; ?>>
                        <small class="text-muted form-selectgroup-label">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="24" viewBox="0 96 960 960" width="24"><path d="M300 536q-58 0-99-41t-41-99q0-58 41-99t99-41q58 0 99 41t41 99q0 58-41 99t-99 41Zm0-80q25 0 42.5-17.5T360 396q0-25-17.5-42.5T300 336q-25 0-42.5 17.5T240 396q0 25 17.5 42.5T300 456Zm360 440q-58 0-99-41t-41-99q0-58 41-99t99-41q58 0 99 41t41 99q0 58-41 99t-99 41Zm0-80q25 0 42.5-17.5T720 756q0-25-17.5-42.5T660 696q-25 0-42.5 17.5T600 756q0 25 17.5 42.5T660 816Zm-444 80-56-56 584-584 56 56-584 584Z"/></svg>
                            <span class="d-lg-inline-flex d-none">
                                <?php _e("Percentage"); ?>
                            </span>
                        </small>
                    </label>
                    <label class="form-selectgroup-item mx-0 px-0">
                        <input type="radio" name="discount_type" value="fixed_amount" class="form-selectgroup-input js-discount-type" <?php if ($data['discount_type'] == 'fixed_amount'): ?>checked=""<?php endif; ?>>
                        <small class="text-muted form-selectgroup-label">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="24" viewBox="0 96 960 960" width="24"><path d="M640 536q17 0 28.5-11.5T680 496q0-17-11.5-28.5T640 456q-17 0-28.5 11.5T600 496q0 17 11.5 28.5T640 536Zm-320-80h200v-80H320v80ZM180 936q-34-114-67-227.5T80 476q0-92 64-156t156-64h200q29-38 70.5-59t89.5-21q25 0 42.5 17.5T720 236q0 6-1.5 12t-3.5 11q-4 11-7.5 22.5T702 305l91 91h87v279l-113 37-67 224H480v-80h-80v80H180Zm60-80h80v-80h240v80h80l62-206 98-33V476h-40L620 336q0-20 2.5-38.5T630 260q-29 8-51 27.5T547 336H300q-58 0-99 41t-41 99q0 98 27 191.5T240 856Zm240-298Z"/></svg>
                              <span class="d-lg-inline-flex d-none">
                                  <?php _e("Fixed Amount"); ?>
                              </span>
                        </small>
                    </label>
                </div>
            </div>
        </div>
        <div class="js-field-message"></div>
    </div>

    <div class="form-group">
        <label class="form-label"><?php _e("Minimum Order Amount"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("Apply the discount when the cart amount is more than the value of the coupone code."); ?></small>

        <div class="input-group mb-2">
          <span class="input-group-text"><?php echo get_currency_symbol(); ?></span>
         <input type="text" name="total_amount" class="form-control js-validation js-validation-float-number" value="<?php print $data['total_amount'] ?>"/>
        </div>

        <div class="js-field-message"></div>
    </div>

    <div class="form-group">
        <label class="form-label"><?php _e("Uses Per Coupon"); ?></label>
        <small class="text-muted d-block mb-2" title=""><?php _e("How many times can this coupone can be used."); ?></small>
        <input type="text" name="uses_per_coupon" class="form-control js-validation js-validation-number" value="<?php print $data['uses_per_coupon'] ?>"/>
        <div class="js-field-message"></div>
    </div>

    <div class="form-group">
        <label class="form-label"><?php _e("Uses Per Customer"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("How many times every client can use the coupone code."); ?></small>
        <input type="text" name="uses_per_customer" class="form-control js-validation js-validation-number" value="<?php print $data['uses_per_customer'] ?>"/>
        <div class="js-field-message"></div>
    </div>


    <div class="coupon-button-actions">
        <?php if (!$addNew) { ?>
<!--            <div>-->
<!--                <a class="btn btn-outline-danger" href="javascript:deleteCoupon('--><?php //print $data['id'] ?><!--')">--><?php //_e("Delete"); ?><!--</a>-->
<!--            </div>-->
        <?php } ?>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary" onclick="mw.dialog.get(this).remove()"><?php _e("Cancel"); ?></button>
            <button type="button" class="btn btn-success js-save-coupon"><?php _e("Save"); ?></button>
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
