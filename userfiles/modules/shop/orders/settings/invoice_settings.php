<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
        });

        var uploader = mw.uploader({
            filetypes:"images,videos",
            multiple:false,
            element:"#mw_uploader"
        });

        $(uploader).bind("FileUploaded", function(event, data){
            mw.$("#mw_uploader_loading").hide();
            mw.$("#mw_uploader").show();
            mw.$("#upload_info").html("");
            $('.js-company-logo').val(data.src);
            $('.js-company-logo').trigger("change");
        });

        $(uploader).bind('progress', function(up, file) {
            mw.$("#mw_uploader").hide();
            mw.$("#mw_uploader_loading").show();
            mw.$("#upload_info").html(file.percent + "%");
        });

        $(uploader).bind('error', function(up, file) {
            mw.notification.error("The file is not uploaded.");
        });

    });
</script>

<div class="mw-ui-box mw-ui-settings-box mw-ui-box-content">
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

    <span id="mw_uploader" class="mw-ui-btn">
    <span class="ico iupload"></span>
    <span>Upload file<span id="upload_info"></span>
    </span>
    </span>

    <input type="hidden" class="js-company-logo mw_option_field" data-option-group="shop" name="invoice_company_logo" placeholder="" value="<?php echo get_option('invoice_company_logo', 'shop'); ?>">
</div>

<div class="mw-ui-row">
    <div class="m-b-10">
        <label class="mw-ui-label bold p-b-10">Company Name: </label>
        <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_name" placeholder="" value="<?php echo get_option('invoice_company_name', 'shop'); ?>">
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
        <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_city" placeholder="" value="<?php echo get_option('invoice_company_city', 'shop'); ?>">
    </div>

    <div class="m-b-10">
        <label class="mw-ui-label bold p-b-10">Company Address: </label>
        <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_address" placeholder="" value="<?php echo get_option('invoice_company_address', 'shop'); ?>">
    </div>

    <div class="m-b-10">
        <label class="mw-ui-label bold p-b-10">Company VAT Number: </label>
        <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_vat_number" placeholder="" value="<?php echo get_option('invoice_company_vat_number', 'shop'); ?>">
    </div>

    <div class="m-b-10">
        <label class="mw-ui-label bold p-b-10">ID Company Number: </label>
        <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_id_company_number" placeholder="" value="<?php echo get_option('invoice_id_company_number', 'shop'); ?>">
    </div>

    <div class="m-b-10">
        <label class="mw-ui-label bold p-b-10">Invoice Prefix: </label>
        <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_prefix" placeholder="" value="<?php echo get_option('invoice_prefix', 'shop'); ?>">
    </div>

    <div class="m-b-10">
        <label class="mw-ui-label bold p-b-10">Additional information: </label>
        <textarea class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_bank_details" placeholder="For example: reason for taxes"><?php echo get_option('invoice_company_bank_details', 'shop'); ?></textarea>
    </div>

    <div class="m-b-10">
        <label class="mw-ui-label bold p-b-10">Bank transfer details: </label>
        <textarea class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_bank_details" placeholder="Enter your bank details here"><?php echo get_option('invoice_company_bank_details', 'shop'); ?></textarea>
    </div>
</div>
</div>
