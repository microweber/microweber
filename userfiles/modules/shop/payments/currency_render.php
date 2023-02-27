<?php
$curencies = mw()->shop_manager->currency_get_for_paypal();
$cur = get_option('currency', 'payments');
$num = 1.00;
?>

<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Currency settings are saved."); ?>");
            mw.reload_module('shop/payments/currency_render')
        });
    });
</script>

<?php if (!in_array(strtoupper($cur), $curencies)): ?>
    <?php $payment_currency = get_option('payment_currency', 'payments'); ?>
    <?php $payment_currency_rate = get_option('payment_currency_rate', 'payments');
    if ($payment_currency_rate != false) {
        $payment_currency_rate = str_replace(',', '.', $payment_currency_rate);
        $payment_currency_rate = trim($payment_currency_rate);

    }

    ?>
    <?php if (is_array($curencies)): ?>
        <hr class="thin"/>
        <h5 class="font-weight-bold"><?php _e("Accept payments in currency"); ?></h5>

        <div class="form-group">
            <label class="control-label"><?php _e("Select the currency accepted by payment provider"); ?></label>
            <small class="text-muted d-block mb-2"><?php _e("If your payment provider does not support the default currency then the customer will pay in selected currency"); ?></small>
            <select name="payment_currency" class="mw_option_field selectpicker" data-width="100%" data-option-group="payments" data-reload="mw_curr_rend" autocomplete="off" data-live-search="true" data-size="5">
                <option value="" <?php if ($payment_currency == false): ?> selected="selected" <?php endif; ?>><?php _e('Default'); ?></option>
                <?php foreach ($curencies as $item): ?>
                    <option value="<?php print $item ?>" <?php if ($payment_currency == $item): ?> selected="selected" <?php endif; ?>><?php print $item ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <script type="text/javascript">
            $(document).ready(function () {
                $('#payment_currency_rate_val_sugg').on('keyup input paste', function() {
                    this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                });


             });
        </script>



        <div class="form-group">
            <label class="control-label"><?php _e('Convert rate'); ?></label>
            <small class="text-muted d-block mb-2"><?php _e("Convert rate from default currency to payment currency"); ?></small>
            <input name="payment_currency_rate" value="<?php print $payment_currency_rate; ?>" id="payment_currency_rate_val_sugg" type="text" class="price-field mw_option_field form-control" data-option-group="payments" data-reload="mw_curr_rend"/>
            <?php $sugg = mw()->shop_manager->currency_convert_rate($cur, $payment_currency); ?>
            <?php $sugg = false; ?>
            <?php if ($sugg != false): ?>
                <br/>
                <small><?php _e("Suggested"); ?>: <?php print $sugg ?> <a class="mw-ui-link" href="javascript:$('#payment_currency_rate_val_sugg').val(<?php print $sugg ?>).change(); void(0);">[<?php _e("use"); ?>]</a></small>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<label class=""><?php _e("Example of how the price will be shown"); ?>:</label>
<input value="<?php print (currency_format($num, $cur)); ?>" disabled type="text" class="mw-ui-invisible-field text-outline-primary font-weight-bold" style="font-size: 18px;"/>
<?php if (isset($payment_currency) and !in_array(strtoupper($cur), $curencies)): ?>
    <label class="control-label"><?php _e("Equals to"); ?> (<?php _e('rate:'); ?> <?php print  $payment_currency_rate ?>
        <?php _e("or"); ?><?php print (currency_format(100, $cur)); ?>=<?php print (currency_format(100 * $payment_currency_rate, $payment_currency)); ?> )</label>
    <input value="<?php print (currency_format($num * $payment_currency_rate, $payment_currency)); ?>" disabled type="text" class="mw-ui-invisible-field"/>
<?php endif; ?>

