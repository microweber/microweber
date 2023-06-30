<?php
only_admin_access();

$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card-body mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class=" ">
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs  active" data-bs-toggle="tab" href="#settings">  <?php _e('Settings'); ?></a>
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs " data-bs-toggle="tab" href="#templates">   <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="settings">
                <script type="text/javascript">mw.require('options.js');</script>

                <script type="text/javascript">
                    __checkout_options_save_msg = function () {
                        if (mw.notification != undefined) {
                            mw.notification.success('Checkout updated!');
                        }

                        if (window.parent.mw != undefined && window.mw.parent().reload_module != undefined) {

                            window.mw.parent().reload_module("#<?php print $params['id'] ?>");
                        }
                    }

                    $(document).ready(function () {
                        mw.options.form('.mw-set-checkout-options-swticher', __checkout_options_save_msg);
                    });
                </script>
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-shop-checkout-settings">
                    <div class="mw-set-checkout-options-swticher">
                        <div class="form-group">
                            <label class="form-label d-block"><?php _e("Show shopping cart in checkout?"); ?></label>

                            <?php $cart_show_enanbled = get_option('data-show-cart', $params['id']); ?>
                            <select name="data-show-cart" class="mw_option_field form-select" data-width="100%">
                                <option value="y" <?php if (('n' != strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>><?php _e("Yes"); ?></option>
                                <option value="n" <?php if (('n' == strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>><?php _e("No"); ?></option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label d-block"><?php _e("Show shipping?"); ?></label>

                            <?php $cart_show_enanbled = get_option('data-show-shipping', $params['id']); ?>
                            <select name="data-show-shipping" class="mw_option_field form-select" data-width="100%">
                                <option value="y" <?php if (('n' != strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>>
                                    <?php _e("Yes"); ?>
                                </option>
                                <option value="n" <?php if (('n' == strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>>
                                    <?php _e("No"); ?>
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label d-block"><?php _e("Show payments?"); ?></label>

                            <?php $cart_show_enanbled = get_option('data-show-payments', $params['id']); ?>
                            <select name="data-show-payments" class="mw_option_field form-select" data-width="100%">
                                <option value="y" <?php if (('n' != strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>><?php _e("Yes"); ?></option>
                                <option value="n" <?php if (('n' == strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>><?php _e("No"); ?></option>
                            </select>
                        </div>
                    </div>

                </div>
                <!-- Settings Content - End -->
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>
