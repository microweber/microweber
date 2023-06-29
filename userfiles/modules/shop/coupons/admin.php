<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<script>
    function editCoupon(coupon_id = false) {
        var data = {};
        data.coupon_id = coupon_id;
        editModal = mw.top().tools.open_module_modal('shop/coupons/edit_coupon', data, {overlay: true, skin: 'simple', title: '<?php _ejs("Coupon Code"); ?>'})
    }

    function deleteCoupon(coupon_id) {
        var confirmUser = confirm('<?php _ejs('Are you sure to delete this coupon permanently?'); ?>');
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
        //mw.reload_module_parent('#<?php //print $params['id'] ?>//');
        //mw.reload_module_everywhere('shop/coupons/admin');
        //mw.reload_module_everywhere('shop/coupons/edit_coupons');
        //window.parent.$(window.parent.document).trigger('shop.coupons.update');
        //if (typeof(editModal) != 'undefined' && editModal.modal) {
        //    editModal.modal.remove();
        //}
        window.location.href = window.location.href;
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
</script>

<?php
$coupon_get_count = coupon_get_count();
?>

<div class="card shadow-none">
    <div class="card-body px-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
        <div class="row">

            <div class="card-header d-flex align-items-center justify-content-between px-0">

              <div>
                <span>
                <?php if (!isset($params['live_edit'])): ?>
                      <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
                <?php endif; ?>

                </span>
              </div>

                <?php
                if ($coupon_get_count > 0) {
                ?>
                <a href="javascript:;" class="btn btn-dark js-add-new-coupon"><?php _e('Add new'); ?></a>
                <?php
                }
                ?>

            </div>

            <?php
            if ($coupon_get_count > 0) {
                ?>
                <div class="d-flex align-items-center mb-4">
                    <label class="form-check form-check-single form-switch ps-0" style="width: unset;">
                        <input type="checkbox" name="enable_coupons" class="mw_option_field form-check-input" id="enable_coupons" data-option-group="shop" data-value-checked="1" data-value-unchecked="0" <?php if (get_option('enable_coupons', 'shop') == 1): ?>checked<?php endif; ?> />

                    </label>
                    <label class="ps-2"><?php _e("Enable") ?></label>
                </div>
            <?php
            }
            ?>

                <?php if ($from_live_edit): ?>
                    <?php include 'admin_live_edit.php'; ?>
                <?php else: ?>


                <?php
                    if ($coupon_get_count > 0) {
                ?>
                <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
                    <a class="btn justify-content-center active" data-bs-toggle="tab" href="#list-coupons"><?php _e('List coupons'); ?></a>
                    <a class="btn justify-content-center" data-bs-toggle="tab" href="#list-log-coupons"> <?php _e('Used coupons'); ?></a>
                </nav>
                <div class="tab-content py-3">
                    <div class="tab-pane fade show active" id="list-coupons">
                        <!-- Settings Content -->
                        <div class="module-coupons-settings">
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    mw.options.form('.<?php print $config['module_class'] ?>', function () {
                                        mw.notification.success("<?php _ejs("Saved"); ?>.");
                                    });
                                });
                            </script>
                            <module type="shop/coupons/edit_coupons"/>
                        </div>
                        <!-- Settings Content - End -->
                    </div>

                    <div class="tab-pane fade" id="list-log-coupons">
                        <module type="shop/coupons/log"/>
                    </div>

                </div>
                    <?php
                    } else {
                        include 'no-coupons.php';
                    }
                ?>

                <?php endif; ?>

        </div>
    </div>

</div>
