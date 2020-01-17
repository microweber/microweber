<script>mw.require("shop.js", true);</script>

<script>mw.moduleCSS("<?php print modules_url(); ?>shop/coupons/styles.css"); </script>

<?php


$applied_coupon_data=mw()->user_manager->session_get('applied_coupon_data');



$module_template = get_option('data-template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}
if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}

if (is_file($template_file) != false) {
    include($template_file);
} else {
    print lnotif("No template found. Please choose template.");
}
?>

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
                    } else {
                        if (typeof(data.success_apply) !== "undefined") {
                            $('.js-coupon-code-messages').html('<div class="js-green-text">' + data.success_message + '</div>');
                        }
                        mw.reload_module('shop/checkout');
                        mw.reload_module('shop/payments');

                    }



                    $('.js-apply-coupon-code').removeAttr('disabled');

                }
            });
        });
    });
</script>
