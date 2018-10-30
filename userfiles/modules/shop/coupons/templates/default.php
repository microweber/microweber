<?php
/*
type: layout
name: Default
description: Default coupon template
*/
?>
<style>
    .js-red-text {
        color: #fb3f3f;
    }

    .js-green-text {
        color: #258d1a;
    }
</style>
<script>
    mw.lib.require('bootstrap3ns');
    mw.moduleCSS("<?php print modules_url(); ?>shop/coupons/styles.css");
</script>
<div class="nodrop container checkout-page element">
    <div class="box-container element">
        <div class="bootstrap3ns">
            <div class="mw-coupons-module">
                <div class="row">
                    <div class="col-xs-12">
                        <h4>Enter coupon code</h4>
                        <hr/>
                        <div class="js-coupon-code-messages"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <input type="text" name="coupon_code" class="form-control js-coupon-code" placeholder="Enter coupon code"/>
                    </div>
                    <div class="col-xs-6 right">
                        <button type="button" class="btn btn-default js-apply-coupon-code">Apply code</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".js-apply-coupon-code").click(function () {
            $('.js-coupon-code-messages').html('');
            $('.js-apply-coupon-code').attr('disabled', 'disabled');
            $.ajax({
                url: '<?php print api_url('coupon_apply');?>',
                data: 'coupon_code=' + $(".js-coupon-code").val(),
                type: 'POST',
                dataType: 'json',
                success: function (data) {

                    if (typeof(data.error_message) !== "undefined") {
                        $('.js-coupon-code-messages').html('<div class="js-red-text">' + data.error_message + '</div>');
                    }

                    if (typeof(data.success_apply) !== "undefined") {
                        $('.js-coupon-code-messages').html('<div class="js-green-text">' + data.success_message + '</div>');
                    }

                    $('.js-apply-coupon-code').removeAttr('disabled');

                    mw.reload_module('shop/checkout');
                }
            });
        });
    });
</script>