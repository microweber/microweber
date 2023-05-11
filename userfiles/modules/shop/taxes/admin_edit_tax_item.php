<script>
    function mw_admin_edit_tax_item_submit_form(form) {

        var data = $(form).serialize()
        var url = mw.settings.api_url + 'shop/save_tax_item';

        $.post(url, data)
            .done(function (data) {

                if (data.errors) {

                    $.each(data.errors,function(field_name,error){
                        $(document).find('#js-field-'+field_name+'-errors').html('<span class="text-strong text-danger">' +error+ '</span>')
                    });

                } else {
                    mw.notification.success("<?php _ejs("Tax item is saved"); ?>");

                    mw.trigger("mw.admin.shop.tax.edit.item.saved");
                    mw.reload_module_everywhere('shop/taxes')
                    mw.reload_module_everywhere('shop/taxes/admin')
                    mw.reload_module_everywhere('shop/cart')

                }
            });

        return false;
    }
</script>

<?php
$default_item = array('id' => 0, 'name' => '', 'type' => 'percent', 'rate' => '');
$item = array();
if (isset($params['tax_item_id'])) {
    $get = mw()->tax_manager->get('single=true&id=' . $params['tax_item_id']);
    if ($get) {
        $item = $get;
    }
}

$values = array_merge($default_item, $item);
?>

<form id="mw_edit_tax_item" onsubmit="return mw_admin_edit_tax_item_submit_form(this)">
    <input type="hidden" name="id" value="<?php print $values['id']; ?>"/>

    <div class="form-group">
        <label class="form-label"><?php _e('Tax name'); ?></label>
        <small class="text-muted d-block mb-2"><?php _e('For example: VAT'); ?></small>
        <input name="name" type="text" class="form-control" required="required" value="<?php print $values['name']; ?>">

        <div id="js-field-name-errors"></div>

    </div>

    <div class="form-group">

        <label class="form-label"><?php _e('Tax rate'); ?></label>
        <small class="text-muted d-block mb-2"><?php _e('The amount of your tax. For example: 55.99'); ?></small>


        <script type='text/javascript'>
            $(document).ready(function () {
                $('.js-tax-type').change(function () {
                    var val = $(this).val();
                    if (val === 'percent') {
                        $('.js-tax-value-label').html('%');
                    } else {
                        $('.js-tax-value-label').html('<?php echo get_currency_symbol(); ?>');
                    }
                });
            });
        </script>

        <div class="row">

            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text js-tax-value-label">%</span>
                    <input name="rate" type="text" class="form-control" required="required" value="<?php print $values['rate']; ?>">
                </div>
            </div>

              <div class="col-md-6 d-flex align-items-center justify-content-end px-0">
            <div class="form-selectgroup px-0">
                <label class="form-selectgroup-item mx-0 pe-2">
                    <input type="radio" name="type" value="percent" class="form-selectgroup-input js-tax-type" <?php if ($values['type'] == 'percent'): ?>checked=""<?php endif; ?>>
                    <small class="text-muted form-selectgroup-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="24" viewBox="0 96 960 960" width="24"><path d="M300 536q-58 0-99-41t-41-99q0-58 41-99t99-41q58 0 99 41t41 99q0 58-41 99t-99 41Zm0-80q25 0 42.5-17.5T360 396q0-25-17.5-42.5T300 336q-25 0-42.5 17.5T240 396q0 25 17.5 42.5T300 456Zm360 440q-58 0-99-41t-41-99q0-58 41-99t99-41q58 0 99 41t41 99q0 58-41 99t-99 41Zm0-80q25 0 42.5-17.5T720 756q0-25-17.5-42.5T660 696q-25 0-42.5 17.5T600 756q0 25 17.5 42.5T660 816Zm-444 80-56-56 584-584 56 56-584 584Z"/></svg>
                        <span class="d-lg-inline-flex d-none">
                                <?php _e("Percentage"); ?>
                            </span>
                    </small>
                </label>
                <label class="form-selectgroup-item mx-0 px-0">
                    <input type="radio" name="type" value="fixed" class="form-selectgroup-input js-tax-type" <?php if ($values['type'] == 'fixed'): ?>checked=""<?php endif; ?>>
                    <small class="text-muted form-selectgroup-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="24" viewBox="0 96 960 960" width="24"><path d="M640 536q17 0 28.5-11.5T680 496q0-17-11.5-28.5T640 456q-17 0-28.5 11.5T600 496q0 17 11.5 28.5T640 536Zm-320-80h200v-80H320v80ZM180 936q-34-114-67-227.5T80 476q0-92 64-156t156-64h200q29-38 70.5-59t89.5-21q25 0 42.5 17.5T720 236q0 6-1.5 12t-3.5 11q-4 11-7.5 22.5T702 305l91 91h87v279l-113 37-67 224H480v-80h-80v80H180Zm60-80h80v-80h240v80h80l62-206 98-33V476h-40L620 336q0-20 2.5-38.5T630 260q-29 8-51 27.5T547 336H300q-58 0-99 41t-41 99q0 98 27 191.5T240 856Zm240-298Z"/></svg>
                        <span class="d-lg-inline-flex d-none">
                                  <?php _e("Fixed Amount"); ?>
                              </span>
                    </small>
                </label>
            </div>
        </div>

            <div id="js-field-rate-errors"></div>

         </div>
    </div>

    <div class="text-end text-right">
        <button type="submit" class="btn btn-success btn-sm" name="submit"><?php _e('Save'); ?></button>
    </div>
</form>
