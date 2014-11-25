<?php
 
$payment_success = false;
if (isset($_SESSION['mw_payment_success'])) {
    mw()->user_manager->session_del('mw_payment_success');
    $payment_success = true;
}

$requires_registration = get_option('shop_require_registration', 'website') == 'y';
$requires_terms = get_option('shop_require_terms', 'website')  == 'y';

 

$template = get_option('data-template', $params['id']);
$template_file = false;
$module_template = false;
if ($template != false and strtolower($template) != 'none') {
    $template_css_prefix = no_ext($template);
    $template_file = module_templates($params['type'], $template);

} else {
    if($template == false and isset($params['template'])){
        $module_template =$params['template'];
        $template_file = module_templates($params['type'], $module_template);
    } else {
        $template_file = module_templates($params['type'], 'default');
    }
}
?>

<script type="text/javascript">
    mw.require("tools.js");
    mw.require("shop.js");

</script>


<script type="text/javascript">

    function checkout_callback(data, selector) {
		
		
	 
		
        var z = typeof(data);
        if (z != 'object') {
            var dataObj;
            try {
                dataObj = $.parseJSON(data);
                data = dataObj;
            }
            catch (e) {
                mw.$('.mw-checkout-responce').append(data);
            }
        } else {
            mw.$('.mw-checkout-responce').html(data);
        }
		
		
		
		
		
		
        mw.$('.mw-checkout-responce').removeClass('alert-error');
        mw.$('.mw-checkout-responce').removeClass('alert-success');
        if (data.success !== undefined) {
            mw.$('.mw-checkout-responce').html(data.success);
            mw.$('.mw-checkout-responce').addClass('alert alert-success');
            mw.$('.mw-checkout-form').fadeOut();
        } else if (data.error !== undefined) {
            mw.$('.mw-checkout-responce').empty().append(data.error);
            mw.$('.mw-checkout-responce').addClass('alert alert-error');
        } else {
            mw.$('.mw-checkout-responce').append(data);
        }
    }


    $(document).ready(function () {
        __max = 0;
        mw.$(".mw-checkout-form .well").each(function () {
            var h = $(this).height();
            if (h > __max) {
                __max = h;
            }
        });
        mw.$(".mw-checkout-form .well").css("minHeight", __max);

    });


</script>
<?php $cart_show_payments = get_option('data-show-payments', $params['id']); ?>
<?php $cart_show_shipping = get_option('data-show-shipping', $params['id']); ?>
<?php
if(is_file($template_file)){
    include($template_file);
}
?>