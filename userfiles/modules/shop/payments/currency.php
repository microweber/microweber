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


<div id="general-shop-settings-accordion" class="mw-ui-box mw-ui-box-silver-blue  m-t-20">
    <div class="mw-ui-box-header" onclick="mw.accordion('#general-shop-settings-accordion');">
        <div class="header-holder">
            <i class="mai-setting2"></i> <?php _e('General Shop Settings') ?>
        </div>
    </div>

    <div class="mw-accordion-content mw-ui-box-content" style="display: none">
        <div class="mw-ui-row">
            <div class="mw-ui-col">
                <div class="mw-ui-col-container">
                    <?php if (is_array($curencies)): ?>
                        <label class="mw-ui-label bold p-b-10"><?php _e("Currency settings"); ?></label>

                        <select name="currency" class="mw-ui-field mw_option_field w100 silver-field" data-option-group="payments" data-reload="mw_curr_rend">
                            <?php if (!$cur): ?>
                                <option value="" disabled="disabled" selected="selected"><?php _e('Select currency'); ?></option>
                            <?php endif; ?>
                            <?php foreach ($curencies as $item): ?>
                                <option value="<?php print $item[1] ?>" <?php if ($cur == $item[1]): ?> selected="selected" <?php endif; ?>><?php print $item[1] ?> <?php print $item[3] ?> (<?php print $item[2] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
            </div>

            <?php $currency_symbol_decimal = get_option('currency_symbol_decimal', 'payments'); ?>

            <?php $cur_pos = get_option('currency_symbol_position', 'payments'); ?>
            <div class="mw-ui-col">
                <div class="mw-ui-col-container">
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label bold p-b-10"><?php _e("Symbol position"); ?></label>

                        <select name="currency_symbol_position" class="mw-ui-field mw_option_field w100 silver-field" data-option-group="payments" data-reload="mw_curr_rend">
                            <option <?php if (!$cur_pos): ?> selected="selected" <?php endif; ?> value=""><?php _e('Default'); ?></option>
                            <option <?php if ($cur_pos == 'before'): ?> selected="selected" <?php endif; ?> value="before"><?php _e('Before number'); ?></option>
                            <option <?php if ($cur_pos == 'after'): ?> selected="selected" <?php endif; ?> value="after"><?php _e('After number'); ?></option>
                        </select>
                    </div>
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label bold p-b-10"><?php _e("Show Decimals"); ?></label>

                        <select name="currency_symbol_decimal" class="mw-ui-field mw_option_field w100 silver-field" data-option-group="payments" data-reload="mw_curr_rend">
                            <option <?php if (!$currency_symbol_decimal): ?> selected="selected" <?php endif; ?> value=""><?php _e('Always'); ?></option>
                            <option <?php if ($currency_symbol_decimal == 'when_needed'): ?> selected="selected" <?php endif; ?> value="when_needed"><?php _e('When needed'); ?></option>
                        </select>

                    </div>


                </div>
            </div>


            <div class="mw-ui-col">
                <div class="p-10">
                    <module type="shop/payments/currency_render" id="mw_curr_rend"/>
                </div>
            </div>
        </div>
    </div>
</div>