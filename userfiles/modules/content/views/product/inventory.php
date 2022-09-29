<style>
    .js-track-quantity {
        display: none;
    }

</style>


<?php

if (!isset($contentData['sku'])) {
    $contentData['sku'] = '';
}
if (!isset($contentData['track_quantity'])) {
    $contentData['track_quantity'] = '';
}
if (!isset($contentData['barcode'])) {
    $contentData['barcode'] = '';
}
if (!isset($contentData['sell_oos'])) {
    $contentData['sell_oos'] = '';
}

?>

<script>
    $(document).ready(function () {
        $('.js-track-quantity-check').click(function () {
            mw.toggle_inventory_forms_fields();
        });

        <?php if (isset($contentData['track_quantity']) and intval($contentData['track_quantity']) != 0):?>
        mw.toggle_inventory_forms_fields();
        enableTrackQuantityFields();
        <?php else: ?>
        disableTrackQuantityFields();
        <?php endif; ?>

    });


    mw.toggle_inventory_forms_fields = function(){

        $('.js-track-quantity').toggle();

        if ($('.js-track-quantity-check').prop('checked')) {
            enableTrackQuantityFields();
        } else {
            disableTrackQuantityFields();
        }
    }

    function disableTrackQuantityFields() {
        $("input,select",'.js-track-quantity').prop("disabled", true);
        $("input,select",'.js-track-quantity').attr("readonly",'readonly');

    }

    function enableTrackQuantityFields() {
        $("input,select",'.js-track-quantity').prop("disabled", false);
        $("input,select",'.js-track-quantity').removeAttr("readonly");


    }

    function contentDataQtyChange(instance) {
        if ($(instance).val()== '') {
            $(instance).val('nolimit');
        }
    }
</script>

<div class="card style-1 mb-3">
    <div class="card-header no-border">
        <h6><strong><?php _e("Inventory"); ?></strong></h6>
    </div>

    <div class="card-body pt-3">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label><?php _e("SKU"); ?></label>
                    <small class="text-muted d-block mb-3"><?php _e("Stock Keeping Unit"); ?></small>
                    <input type="text" name="content_data[sku]" class="form-control js-invertory-sku" value="<?php echo $contentData['sku']; ?>">

                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label><?php _e("Barcode"); ?></label>
                    <small class="text-muted d-block mb-3"><?php _e("ISBN, UPC, GTIN, etc."); ?></small>
                    <input type="text" name="content_data[barcode]" class="form-control js-invertory-barcode" value="<?php echo $contentData['barcode']; ?>">
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="content_data[track_quantity]" class="custom-control-input js-track-quantity-check" value="1" <?php if ($contentData['track_quantity']==1):?>checked="checked"<?php endif; ?> id="customCheck2">
                        <label class="custom-control-label" for="customCheck2">
                            <?php _e("Track quantity"); ?>
                            <?php if (isset($product) && $product): ?>
                            <?php if($product->inStock): ?><span class="badge badge-success">In stock</span><?php endif; ?>
                            <?php if($product->inStock == false): ?><span class="badge badge-danger">Out Of Stock</span><?php endif; ?>
                            <?php endif; ?>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" data-value-checked="1" data-value-unchecked="0" class="custom-control-input js-invertory-sell-oos" id="customCheck3" name="content_data[sell_oos]" value="1" <?php if ($contentData['sell_oos']==1):?>checked="checked"<?php endif; ?>>
                        <label class="custom-control-label" for="customCheck3"><?php _e("Continue selling when out of stock"); ?></label>
                    </div>
                </div>
            </div>
        </div>

<?php
$dropdown_qty_max = 100;
$dropdown_qty_max_per_order = 100;

$qty_selected = 'nolimit';

$qty_selected_is_custom = false;
$max_qty_per_order_selected = 'nolimit';

if(isset($contentData['qty']) and $contentData['qty'] != 'nolimit'){
    $qty_selected = intval($contentData['qty']);
    if($qty_selected > $dropdown_qty_max){
        $qty_selected_is_custom = $qty_selected;
    }
}

if(isset($contentData['max_qty_per_order']) and $contentData['max_qty_per_order'] != 'nolimit'){
    $max_qty_per_order_selected = intval($contentData['max_qty_per_order']);
}


?>


        <script>

            set_custom_qty_number = function (el) {
                var val =  el.value;
                if(val == 'other'){

                    var next =  $('.js-track-quantity-select-qty-other-value').first();
                    next.removeClass('d-none');
                    next.attr('name','content_data[qty]');

                    $('.js-track-quantity-select-qty').remove();

                    $('.js-track-quantity-select-qty-other-value').on('change input', function (){
                        document.querySelector('.btn-save').disabled = false;
                        mw.askusertostay = true;

                    })


                }
            }

        </script>




        <div class="js-track-quantity">
            <hr class="thin no-padding"/>
            <label class="control-label my-3"><?php _e("Quantity"); ?></label>
            <div class="row">
                <div class="col-md-6 w-100">
                    <div class="form-group">
                        <label class="control-label"><?php _e("Available"); ?></label>
                        <small class="text-muted d-block mb-3"><?php _e("How many products you have available in stock"); ?></small>

                        <?php if(!$qty_selected_is_custom){ ?>
                        <select name="content_data[qty]" class="js-track-quantity-select-qty  selectpicker " data-size="7" onchange="set_custom_qty_number(this)">
                            <option selected="selected" value="nolimit" <?php if($qty_selected =='nolimit' ) : ?> selected <?php endif;  ?>>∞ <?php _e("No Limit"); ?></option>
                            <option value="0" <?php if($qty_selected ===0 ) : ?> selected <?php endif;  ?> title="This item is out of stock and cannot be ordered."><?php _e("Out of stock"); ?></option>
                            <?php for ($i = 1; $i <= $dropdown_qty_max; $i++){  ?>
                                <option value="<?php print $i ?>" <?php if($qty_selected ==$i ) : ?> selected <?php endif;  ?>><?php print $i ?></option>
                            <?php }   ?>
                            <option  value="other"><?php _e("Other"); ?></option>
                        </select>

                        <input type="number"  min="0" class="form-control d-none js-track-quantity-select-qty-other-value" placeholder="<?php _e("No Limit"); ?>"  value="<?php print $qty_selected ?>">
                        <?php } else { ?>
                        <input type="number"  name="content_data[qty]" placeholder="<?php _e("No Limit"); ?>"  min="0" class="form-control" value="<?php print $qty_selected ?>">
                        <?php }?>


                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group w-100">
                        <label class="control-label"><?php _e("Max quantity per order"); ?></label>
                        <small class="text-muted d-block mb-3"><?php _e("How many products can be ordered at once"); ?></small>
                        <select name="content_data[max_qty_per_order]" class="selectpicker js-invertory-max-quantity-per-order" data-size="7">
                            <option selected="selected" value="nolimit" <?php if($max_qty_per_order_selected =='nolimit' ) : ?> selected <?php endif;  ?>>∞ <?php _e("No Limit"); ?></option>

                            <?php for ($i = 1; $i <= $dropdown_qty_max_per_order; $i++){  ?>
                                <option value="<?php print $i ?>" <?php if($max_qty_per_order_selected ==$i ) : ?> selected <?php endif;  ?>><?php print $i ?></option>
                            <?php }   ?>

                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
