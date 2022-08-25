<script>mw.lib.require('mwui_init');</script>
<style>
    .js-product-variants {
        display: block;
    }
</style>

<script>
    function addProductVariantInTable(id, name, price, currency, qty, sku) {
        var variantHtml = '<tr class="js-product-variant-tr" data-id="'+id+'">\n' +
            '<th scope="row" style="vertical-align: middle;">\n' +
            '    <span>'+name+'</span>\n' +
            '</th>\n' +
            '<td>\n' +
            '    <div class="input-group prepend-transparent m-0">\n' +
            '        <div class="input-group-prepend">\n' +
            '            <span class="input-group-text text-muted">'+currency+'</span>\n' +
            '        </div>\n' +
            '        <input type="text" class="form-control js-product-variant-tr-price" data-id="'+id+'" value="'+price+'">\n' +
            '    </div>\n' +
            '</td>\n' +
            '<td>\n' +
            '    <div class="input-group append-transparent input-group-quantity m-0">\n' +
            '        <input type="text" class="form-control js-product-variant-tr-qty" data-id="'+id+'" value="'+qty+'">\n' +
            '        <div class="input-group-append">\n' +
            '            <div class="input-group-text plus-minus-holder">\n' +
            '                <button type="button" class="plus"><i class="mdi mdi-menu-up"></i></button>\n' +
            '                <button type="button" class="minus"><i class="mdi mdi-menu-down"></i></button>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '</td>\n' +
            '<td>\n' +
            '    <div class="form-group m-0">\n' +
            '        <input type="text" class="form-control js-product-variant-tr-sku" data-id="'+id+'" value="'+sku+'">\n' +
            '    </div>\n' +
            '</td>\n' +
            '<td style="vertical-align: middle;">\n' +
            '    <div class="btn-group">\n' +
            '        <button type="button" class="btn btn-outline-secondary btn-sm js-product-variant-tr-edit" data-id="'+id+'">Edit</button>\n' +
            '        <button type="button" class="btn btn-outline-secondary btn-sm js-product-variant-tr-delete" data-id="'+id+'"><i class="mdi mdi-trash-can-outline"></i></button>\n' +
            '    </div>\n' +
            '</td>\n' +
            '</tr>';

        $('.js-product-variants-fields').append(variantHtml);
    }

    function refreshProductVariantsOptions()
    {
        $('.js-product-variants-options').html('Loading...');

        $.get(mw.settings.api_url + "product_variant/parent/<?php echo (int) $data['id']; ?>/options", {}).done(function (data) {
            $('.js-product-variants-options').html('');
            $.each(data, function(index, option) {
                addProductVariantOption(option.option_id, option.option_name, option.option_values.join(", "));
            });
        });
    }

    function addProductVariantOption(option_id = 0, option_name = '', option_values = '')
    {
        var optionHtml = '<div class="row js-product-variant-option-box js-product-variant-option-'+option_id+'">\n' +
            '<div class="col-md-4">\n' +
            '<div class="form-group">\n' +
            '     <h6 class="pb-1"><strong>Option</strong></h6>\n' +
            '      <div>\n' +
            '           <input type="text" name="product_variant_option['+option_id+'][name]" value="'+option_name+'" class="form-control js-option-name">\n' +
            '     </div>\n' +
            '</div>\n' +
            '</div>\n' +
            '<div class="col-md-8">\n' +
            '    <div class="text-end text-right">\n' +
            '        <button type="button" class="btn btn-link py-1 pb-2 h-auto px-2">Edit</button>\n' +
            '        <button type="button" class="btn btn-link btn-link-danger py-1 pb-2 h-auto px-2" onclick="deleteProductVariantOption('+option_id+')">Remove</button>\n' +
            '    </div>\n' +
            '    <div class="form-group">\n' +
            '        <input type="text" data-role="tagsinput"  name="product_variant_option['+option_id+'][values]" value="'+option_values+'" class="js-tags-input" placeholder="Separate options with a comma" />\n' +
            '    </div>\n' +
            '</div>\n' +
            '</div>';

        $('.js-product-variants-options').append(optionHtml);

        $("input[name='product_variant_option["+option_id+"][values]']").tagsinput()
    }

    function deleteProductVariantOption(option_id) {
        refreshProductVariantValues();
        $('.js-product-variant-option-' + option_id).remove();
    }

    function refreshProductVariants(clearOld = false) {
        if (clearOld) {
            $('.js-product-variants-fields').html('Loading...');
        }
        $.get(mw.settings.api_url + "product_variant/parent/<?php echo (int) $data['id']; ?>", {}).done(function (data) {
            $('.js-product-variants-fields').html('');
            $.each(data, function(key,productVariant) {
                addProductVariantInTable(productVariant.id, productVariant.short_title, productVariant.price, productVariant.currency, productVariant.qty, productVariant.sku);
            });
        });
    }

    $(document).ready(function () {

        $('body').on('click', '.js-product-variant-tr-delete', function () {
            $.post(mw.settings.api_url + "product_variant/" + $(this).data('id'), {
                '_method': "DELETE",
                'id': $(this).data('id')
            }).done(function (data) {
                mw.notification.success('Variant is deleted!');
                refreshProductVariants(true);
            });
        });

        $('body').on('click', '.js-product-variant-tr-edit', function () {

        });

        $('body').on('change', '.js-product-variant-tr-sku', function () {

            $.post(mw.settings.api_url + "product_variant/" + $(this).data('id'), {
                '_method': "PATCH",
                'id': $(this).data('id'),
                'content_data[sku]': $(this).val()
            }).done(function (data) {
                mw.notification.success('SKU is updated!');
            });

        });

        $('body').on('change', '.js-product-variant-tr-qty', function () {

            $.post(mw.settings.api_url + "product_variant/" + $(this).data('id'), {
                '_method': "PATCH",
                'id': $(this).data('id'),
                'content_data[qty]': $(this).val()
            }).done(function (data) {
                mw.notification.success('Quantity is updated!');
            });

        });

        $('body').on('change', '.js-product-variant-tr-price', function () {

            $.post(mw.settings.api_url + "product_variant/" + $(this).data('id'), {
                '_method': "PATCH",
                'id': $(this).data('id'),
                'price': $(this).val()
            }).done(function (data) {
                mw.notification.success('Price is updated!');
            });

        });

       $('.js-product-has-variants').click(function () {
           if ($('.js-product-has-variants').is(':checked')) {
               $('.js-product-variants').fadeIn();
           } else {
               $('.js-product-variants').fadeOut();
           }
       });

       $('.js-add-variant-option').click(function () {

           if ($('.js-product-variant-option-box').length > 3) {
               alert('Maximum product variants are 3');
               return;
           }

           refreshProductVariantsOptions();

           var productVariantOptions = [];
           $(".js-product-variant-option-box").each(function() {

               var productVariantOptionName = $(this).find('.js-option-name').val();
               var productVariantOptionValues = $(this).find('.js-tags-input').val().split(",");

               productVariantOptions.push({
                    option_name:productVariantOptionName,
                    option_values:productVariantOptionValues,
               });
           });

           $.post(mw.settings.api_url + "product_variant_save", {product_id:<?php echo (int) $data['id']; ?>, options:productVariantOptions}).done(function (data) {
                console.log(data);
               refreshProductVariants(true);
           });

       });
       refreshProductVariants();
       refreshProductVariantsOptions();
    });
</script>

<div class="card style-1 mb-3">
    <div class="card-header no-border">
        <h6><strong>Variants</strong></h6>
    </div>

    <div class="card-body pt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-product-has-variants" id="the-product-has-variants">
                        <label class="custom-control-label" for="the-product-has-variants">This product has multiple options, like different sizes or colors</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="js-product-variants">
            <hr class="thin no-padding"/>

            <h6 class="text-uppercase mb-3"><strong>Create an option</strong></h6>

            <div class="options js-product-variants-options"></div>

            <hr class="thin" />

            <button type="button" class="btn btn-outline-primary text-dark js-add-variant-option">Add another option</button>
            <hr class="thin no-padding"/>
            <h6 class="text-uppercase mb-3"><strong>Preview</strong></h6>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col" class="border-0">Variant</th>
                        <th scope="col" class="border-0">Price</th>
                        <th scope="col" class="border-0">Quantity</th>
                        <th scope="col" class="border-0">SKU</th>
                        <th scope="col" class="border-0">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="js-product-variants-fields"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
