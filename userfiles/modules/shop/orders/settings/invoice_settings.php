<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" name="enable_invoices" id="enable_invoices" data-option-group="shop" data-value-checked="1" data-value-unchecked="0" class="mw_option_field custom-control-input" <?php if (true): ?>checked<?php endif; ?>>
                <label class="custom-control-label" for="enable_invoices"><?php _e("Enable invoicing"); ?></label>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label"><?php _e("Company Logo:"); ?> </label>

            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="mw_option_field custom-file-input" id="invoice_company_logo" data-option-group="shop" name="invoice_company_logo" />
                    <label class="custom-file-label" for="invoice_company_logo"><?php _e("Choose file"); ?></label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label"><?php _e("Company Name:"); ?> </label>
            <input type="text" class="mw_option_field form-control" data-option-group="shop" name="invoice_company_name" placeholder="" value="">
        </div>

        <div class="form-group">
            <label class="control-label"><?php _e("Company Country:"); ?></label>

            <select name="invoice_company_country" class="mw_option_field selectpicker" data-size="5" data-width="100%" data-option-group="shop">
                <?php if (countries_list()): ?>
                    <?php foreach (countries_list() as $country): ?>
                        <option value="<?php print $country; ?>" selected="selected"><?php print $country; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div>
            <div class="form-group">
                <label class="control-label"><?php _e("Company City:"); ?> </label>
                <input type="text" class="mw_option_field form-control" data-option-group="shop" name="invoice_company_city" placeholder="" value="">
            </div>

            <div class="form-group">
                <label class="control-label"><?php _e("Company Address:"); ?> </label>
                <input type="text" class="mw_option_field form-control" data-option-group="shop" name="invoice_company_address" placeholder="" value="">
            </div>

            <div class="form-group">
                <label class="control-label"><?php _e("Company VAT Number:"); ?> </label>
                <input type="text" class="mw_option_field form-control" data-option-group="shop" name="invoice_company_vat_number" placeholder="" value="">
            </div>

            <div class="form-group">
                <label class="control-label"><?php _e("ID Company Number:"); ?> </label>
                <input type="text" class="mw_option_field form-control" data-option-group="shop" name="invoice_id_company_number" placeholder="" value="">
            </div>

            <div class="form-group">
                <label class="control-label"><?php _e("Additional information:"); ?> </label>
                <textarea class="mw_option_field form-control" data-option-group="shop" name="invoice_company_bank_details" placeholder="<?php _e("For example: reason for taxes"); ?>"></textarea>
            </div>

            <div class="form-group">
                <label class="control-label"><?php _e("Bank transfer details:"); ?> </label>
                <textarea class="mw_option_field form-control" data-option-group="shop" name="invoice_company_bank_details" placeholder="<?php _e("Enter your bank details here"); ?>"></textarea>
            </div>
        </div>
    </div>
</div>
