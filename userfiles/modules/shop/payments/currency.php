<?php
$cur = get_option('currency', 'payments');
$curencies = mw()->shop_manager->currency_get();
?>
<script type="text/javascript">
    $(document).ready(function () {

        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Currency settings are saved."); ?>");
            mw.reload_module('shop/payments/currency_render')
        });
    });
</script>

<div class="card bg-none style-1 mb-0 card-settings">
    <div class="card-header px-0">
        <h5>
            <i class="mdi mdi-cart-outline px-2 mdi-20px text-primary mr-3"></i> <strong><?php _e('Shop General'); ?></strong>
        </h5>
        <div></div>
    </div>

    <div class="card-body pt-3 px-0">
        <div class="row">
            <div class="col-md-3 pt-5">
                <h5 class="font-weight-bold"><?php _e("Currency settings"); ?></h5>
                <small class="text-muted"><?php _e('Set the currency in which your store will operate.'); ?></small>
            </div>

            <div class="col-md-9">
                <div class="card bg-light style-1 mb-3">
                    <div class="card-body pt-3">
                        <div class="row">
                            <div class="col-12">
                                <?php if (is_array($curencies)): ?>
                                    <div class="form-group mb-4">
                                        <label class="control-label"><?php _e('Set default currency'); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e('Default currency with which you will accept payments.'); ?></small>
                                        <select name="currency" class="mw_option_field selectpicker" data-width="100%" data-size="7" data-option-group="payments" data-reload="mw_curr_rend" data-live-search="true" data-size="5">
                                            <?php if (!$cur): ?>
                                                <option value="" disabled="disabled" selected="selected"><?php _e('Select currency'); ?></option>
                                            <?php endif; ?>
                                            <?php foreach ($curencies as $item): ?>
                                                <option value="<?php print $item[1] ?>" <?php if ($cur == $item[1]): ?> selected="selected" <?php endif; ?>><?php print $item[1] ?> <?php print $item[3] ?> (<?php print $item[2] ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endif; ?>

                                <?php $currency_symbol_decimal = get_option('currency_symbol_decimal', 'payments'); ?>
                                <?php $cur_pos = get_option('currency_symbol_position', 'payments'); ?>

                                <div class="form-group mb-4">
                                    <label class="control-label"><?php _e("Currency simbol position"); ?></label>
                                    <small class="text-muted d-block mb-2"><?php _e('Where to display the currency symbol before, after or by default relative to the amount.'); ?></small>

                                    <select name="currency_symbol_position" class="mw_option_field selectpicker" data-width="100%" data-option-group="payments" data-reload="mw_curr_rend">
                                        <option <?php if (!$cur_pos): ?> selected="selected" <?php endif; ?> value=""><?php _e('Default'); ?></option>
                                        <option <?php if ($cur_pos == 'before'): ?> selected="selected" <?php endif; ?> value="before"><?php _e('Before number'); ?></option>
                                        <option <?php if ($cur_pos == 'after'): ?> selected="selected" <?php endif; ?> value="after"><?php _e('After number'); ?></option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="control-label"><?php _e("Show Decimals"); ?></label>
                                    <select name="currency_symbol_decimal" class="mw_option_field selectpicker" data-width="100%" data-option-group="payments" data-reload="mw_curr_rend">
                                        <option <?php if (!$currency_symbol_decimal): ?> selected="selected" <?php endif; ?> value=""><?php _e('Always'); ?></option>
                                        <option <?php if ($currency_symbol_decimal == 'when_needed'): ?> selected="selected" <?php endif; ?> value="when_needed"><?php _e('When needed'); ?></option>
                                    </select>
                                </div>

                                <module type="shop/payments/currency_render" id="mw_curr_rend"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
