<?php use shop\shipping\gateways\country\shipping_to_country;

$rand = $module_wrapper_id = 'shipping_country_' . md5($params['module']). md5($params['id']);

$data_disabled = mw('shop\shipping\gateways\country\shipping_to_country')->get("is_active=0");
$shipping_cost = mw('shop\shipping\gateways\country\shipping_to_country')->get_cost();
$shipping_cost = floatval($shipping_cost);
$countries_used = array();
$countries_all = array();

$data = mw('shop\shipping\gateways\country\shipping_to_country')->get_available_countries();
?>
    <script type="text/javascript">
        mw.require('forms.js', true);
    </script>
    <script type="text/javascript">
        mw_shipping_country_last_val<?php print $rand; ?> = null;
        function mw_shipping_<?php print $rand; ?>(data) {

            if(mw_shipping_country_last_val<?php print $rand; ?>  === data){
                return;
            }

            mw_shipping_country_last_val<?php print $rand; ?> = data

            $.post("<?php print $config['module_api']; ?>/shipping_to_country/set", data)
            .done(function (msg) {
                mw.reload_module('shop/cart');
            });
        }


        $(document).ready(function () {

            mw.$("#<?php print $rand; ?> [name='country']").change(function () {
                setTimeout(function(){

                    var data = mw.serializeFields("#<?php print $rand; ?>");
                    mw_shipping_<?php print $rand; ?>(data);
                     },100);


            });
        });


        /*$(document).ready(function () {
            var ipinfo_cookie = mw.cookie.get('mw_checkout_page_auto_select_country_ip_info');
            if (typeof(ipinfo_cookie) === 'undefined') {
                $.getJSON('//ipinfo.io', function (data) {
                    if (typeof(data.country) !== 'undefined') {
                        console.log(data)
                        mw.$("#<?php print $rand; ?> [name='country']")
                    }
                })
            }
        });*/
    </script>
<?php

$module_template = get_option('data-template', $params['id']);

$disable_default_shipping_fields = get_option('disable_default_shipping_fields', 'shipping');
$enable_custom_shipping_fields = get_option('enable_custom_shipping_fields', 'shipping');

if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
} elseif ($module_template == false) {
    $module_template = 'default';
}


if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);

} else {
    $template_file = module_templates($config['module'], 'default');

}


if (isset($template_file) and ($template_file) != false and is_file($template_file) != false) {
    include($template_file);
} else {
    $template_file = module_templates($config['module'], 'default');
    if (($template_file) != false and is_file($template_file) != false) {
        include($template_file);
    } else {
        $complete_fallback = dirname(__FILE__) . DS . 'templates' . DS . 'default.php';
        if (is_file($complete_fallback) != false) {
            include($complete_fallback);
        }

    }
    //print 'No default template for '.  $config['module'] .' is found';
}

