<script type="text/javascript">mw.require('options.js');</script>

<script type="text/javascript">
    __checkout_options_save_msg = function () {
        if (mw.notification != undefined) {
            mw.notification.success('Checkout updated!');
        }

        if (window.parent.mw != undefined && window.parent.mw.reload_module != undefined) {

            window.parent.mw.reload_module("#<?php print $params['id'] ?>");
        }
    }

    $(document).ready(function () {
        mw.options.form('.mw-set-checkout-options-swticher', __checkout_options_save_msg);
    });
</script>


<div class="mw-modules-tabs">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-shop-checkout-settings">
                <div class="mw-set-checkout-options-swticher">
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label"><?php _e("Show shopping cart in checkout?"); ?></label>

                        <?php $cart_show_enanbled = get_option('data-show-cart', $params['id']); ?>
                        <select name="data-show-cart" class="mw_option_field mw-ui-field mw-full-width">
                            <option value="y" <?php if (('n' != strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>><?php _e("Yes"); ?></option>
                            <option value="n" <?php if (('n' == strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>><?php _e("No"); ?></option>
                        </select>
                    </div>

                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label"><?php _e("Show shipping?"); ?></label>

                        <?php $cart_show_enanbled = get_option('data-show-shipping', $params['id']); ?>
                        <select name="data-show-shipping" class="mw_option_field mw-ui-field mw-full-width">
                            <option value="y" <?php if (('n' != strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>>
                                <?php _e("Yes"); ?>
                            </option>
                            <option value="n" <?php if (('n' == strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>>
                                <?php _e("No"); ?>
                            </option>
                        </select>
                    </div>

                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label"><?php _e("Show payments?"); ?></label>

                        <?php $cart_show_enanbled = get_option('data-show-payments', $params['id']); ?>
                        <select name="data-show-payments" class="mw_option_field mw-ui-field mw-full-width">
                            <option value="y" <?php if (('n' != strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>><?php _e("Yes"); ?></option>
                            <option value="n" <?php if (('n' == strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>><?php _e("No"); ?></option>
                        </select>
                    </div>
                </div>

            </div>
            <!-- Settings Content - End -->
        </div>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> <?php print _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>




