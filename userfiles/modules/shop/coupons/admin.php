<?php only_admin_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

    <script>
        function editCoupon(coupon_id = false) {
            var data = {};
            data.coupon_id = coupon_id;
            editModal = mw.tools.open_module_modal('shop/coupons/edit_coupon', data, {overlay: true, skin: 'simple', title: 'Coupon Code'})
        }

        function deleteCoupon(coupon_id) {
            var confirmUser = confirm('<?php _e('Are you sure to delete this coupon permanently?'); ?>');
            if (confirmUser == true) {
                $.ajax({
                    url: '<?php print api_url('coupon_delete');?>',
                    data: 'coupon_id=' + coupon_id,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (typeof(reload_coupon_after_save) != 'undefined') {
                            reload_coupon_after_save();
                        }
                    }
                });
            }
        }

        function reload_coupon_after_save() {
            mw.reload_module_parent('#<?php print $params['id'] ?>');
            mw.reload_module('shop/coupons/edit_coupons');
            window.parent.$(window.parent.document).trigger('shop.coupons.update');
            if (typeof(editModal) != 'undefined' && editModal.modal) {
                editModal.modal.remove();
            }
        }

        $(document).ready(function () {
            $(".js-add-new-coupon").click(function () {
                editCoupon(false);
            });
        });
    </script>

    <script>
        mw.lib.require('jqueryui');
        mw.require("<?php print $config['url_to_module'];?>css/main.css");
        mw.lib.require('font_awesome5');
    </script>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<?php if ($from_live_edit) : ?>
    <div class="mw-modules-tabs">
        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">


                <!-- Settings Content -->
                <div class="module-live-edit-settings module-coupons-settings">
                    <div class="mw-ui-field-holder add-new-button text-right m-b-10">
                        <a class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-rounded js-add-new-coupon" href="#"><i class="fas fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?></a>
                    </div>

                    <module type="shop/coupons/edit_coupons"/>
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
<?php else: ?>
    <!-- Settings Content -->
    <div class="module-live-edit-settings module-coupons-settings">



        <script type="text/javascript">
            $(document).ready(function () {

                mw.options.form('.<?php print $config['module_class'] ?>', function () {
                    mw.notification.success("<?php _ejs("Saved"); ?>.");
                });

            });

        </script>


        <div class="mw-ui-box mw-ui-settings-box mw-ui-box-content ">
            <div class="m-b-10">
                <h4 class=" pull-left"><?php _e("Enable coupons support"); ?></h4>
                <label class="mw-switch pull-left inline-switch">
                    <input
                            type="checkbox"
                            name="enable_coupons"
                            class="mw_option_field"
                            data-option-group="shop"
                            data-value-checked="1"
                            data-value-unchecked="0"
                        <?php if (get_option('enable_coupons', 'shop') == 1): ?> checked="1" <?php endif; ?>>
                    <span class="mw-switch-off"><?php _e('OFF'); ?></span>
                    <span class="mw-switch-on"><?php _e('ON'); ?></span>
                    <span class="mw-switcher"></span>
                </label>
                <div class="clearfix"></div>
            </div>


        </div>






        <div class="mw-ui-field-holder add-new-button text-right m-b-10">
            <a class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-rounded js-add-new-coupon" href="#"><i class="fas fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?></a>
        </div>

        <module type="shop/coupons/edit_coupons"/>
    </div>
    <!-- Settings Content - End -->
<?php endif; ?>