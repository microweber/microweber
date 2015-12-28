<script type="text/javascript">


        $(document).ready(function () {
            $(".mw-order-item-image").bind("mouseenter mouseleave", function (e) {
                var index = $(this).dataset('index');
                mw.tools.multihover(e, this, ".mw-order-item-index-" + index);
            });
            $("tr.mw-order-item").bind("mouseenter mouseleave", function (e) {
                var index = $(this).dataset('index');
                mw.tools.multihover(e, this, ".mw-order-item-image-" + index);
            });

            var obj = {
                id: "<?php print $ord['id']; ?>"
            }

            mw.$(".mw-order-is-paid-change").change(function () {
                var val = this.value;
                obj.is_paid = val;
                $.post(mw.settings.site_url + "api/shop/update_order", obj, function () {
                    var upd_msg = "<?php _e("Order is marked as un-paid"); ?>"
                    if (obj.is_paid == 'y') {
                        var upd_msg = "<?php _e("Order is marked as paid"); ?>";
                    }
                    mw.notification.success(upd_msg);
                    mw.reload_module('shop/orders');
                });
            });


            mw.$("input[name='order_status']").commuter(function () {
                var val = this.value;
                obj.order_status = val;
				 
                $.post(mw.settings.site_url + "api/shop/update_order", obj, function () {
                    mw.tools.el_switch(mwd.querySelectorAll('#mw_order_status .mw-notification'), 'semi');
                    var states = {
                        'y': '<?php _e("Completed"); ?>',
                        'n': '<?php _e("Pending"); ?>',
                    }
                    mw.which(val, states, function () {
                        mw.$(".mw-order-item-<?php print $ord['id']; ?> .mw-order-item-status").html(this.toString());
                    });
					mw.reload_module('shop/orders');
                });
            });
        });
    </script>