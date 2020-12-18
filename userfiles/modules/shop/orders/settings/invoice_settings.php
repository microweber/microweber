<div class="mw-ui-row m-b-10">
    <div class="">
        <p class="bold">Enable invoicing</p>
    </div>

    <div class="">
        <label class="mw-switch inline-switch m-0 m-t-10 m-b-10">
            <input type="checkbox" name="enable_invoices" data-option-group="shop" data-value-checked="1" data-value-unchecked="0" class="mw_option_field" <?php if (true): ?> checked="checked" <?php endif; ?>>
            <span class="mw-switch-off">OFF</span>
            <span class="mw-switch-on">ON</span>
            <span class="mw-switcher"></span>
        </label>
    </div>
</div>

<div class="m-b-10">
    <label class="mw-ui-label bold p-b-10">Company Logo: </label>
    <input type="file" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_logo" placeholder="" value="">
</div>

<div class="mw-ui-row">
    <div class="m-b-10">
        <label class="mw-ui-label bold p-b-10">Company Name: </label>
        <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_name" placeholder="" value="">
    </div>
</div>

<div class="mw-ui-row m-b-10">
    <div class="mw-ui-col">
        <div class="mw-ui-col-container">
            <label class="mw-ui-label bold p-b-10">Company Country:</label>

            <select name="invoice_company_country" class="mw-ui-field mw_option_field w100 silver-field" data-option-group="shop">
                <?php if (countries_list()): ?>
                    <?php foreach (countries_list() as $country): ?>
                        <option value="<?php print $country; ?>" selected="selected"><?php print $country; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
</div>

<div class="mw-ui-row">
    <div class="m-b-10">
        <label class="mw-ui-label bold p-b-10">Company City: </label>
        <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_city" placeholder="" value="">
    </div>

    <div class="m-b-10">
        <label class="mw-ui-label bold p-b-10">Company Address: </label>
        <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_address" placeholder="" value="">
    </div>

    <div class="m-b-10">
        <label class="mw-ui-label bold p-b-10">Company VAT Number: </label>
        <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_vat_number" placeholder="" value="">
    </div>

    <div class="m-b-10">
        <label class="mw-ui-label bold p-b-10">ID Company Number: </label>
        <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_id_company_number" placeholder="" value="">
    </div>

    <div class="m-b-10">
        <label class="mw-ui-label bold p-b-10">Additional information: </label>
        <textarea class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_bank_details" placeholder="For example: reason for taxes"></textarea>
    </div>

    <div class="m-b-10">
        <label class="mw-ui-label bold p-b-10">Bank transfer details: </label>
        <textarea class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_bank_details" placeholder="Enter your bank details here"></textarea>
    </div>
</div>
