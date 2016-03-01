<?php

if (!is_admin()){
    return;
}

?>
<script type="text/javascript">
    mw.require('options.js');
</script>
<script type="text/javascript">


    setActiveProvider = function (el) {
        if (el.checked == true) {
            if (el.value == 1) {
                $(mw.tools.firstParentWithClass(el, 'payment-state-status')).addClass("active");
            }
            else {
                $(mw.tools.firstParentWithClass(el, 'payment-state-status')).removeClass("active");
            }
        }
    }


    $(document).ready(function () {


        if (typeof thismodal !== 'undefined') {
            thismodal.main.width(1000);
            $(thismodal.main[0].getElementsByTagName('iframe')).width(985);
        }


        mw.options.form('.mw-set-payment-options', function () {
            mw.notification.success("<?php _e("Shop settings are saved"); ?>.");

            mw.reload_module_parent("shop/payments");


        });


        $('.mw-admin-wrap').click(function () {
            // mw.options.form('.mw-set-payment-options');
        });


        mw.tools.tabGroup({
            nav: '.payment-tab',
            tabs: '.otab',
            master: mwd.querySelector('.mw-admin-side-nav'),
            onclick: function () {
                /* if(this.id == 'payment-tab-email' && !window.MailEditor){
                 runMailEditor();
                 }*/
            }
        });


        mw.$("#available_providers").sortable({
            items: ".mw-ui-box",
            handle: ".mw-icon-drag",
            axis: 1,
            placeholder: "available_providers_placeholder",
            start: function (a, b) {

                $(this).find(".mw-ui-box").each(function () {
                    $(this).height("auto");
                    $(this).removeClass("mw-accordion-active");
                    $(this).removeClass("active");
                    $(this).find(".mw-ui-box-content").hide();
                });
                $(this).sortable("refreshPositions");

            },
            update: function () {
                var serial = $(this).sortable('serialize');
                $.ajax({
                    url: mw.settings.api_url + 'module/reorder_modules',
                    type: "post",
                    data: serial
                });
            },
            stop: function () {
                //  Alert("<?php _e("Saving"); ?> ... ");
            }


        })


    });


    mw.checkout_confirm_email_test = function () {


        var email_to = {}
        email_to.to = $('#test_email_to').val();
        ;
        //email_to.subject = $('#test_email_subject').val();;

        $.post("<?php print site_url('api_html/checkout_confirm_email_test'); ?>", email_to, function (msg) {
//Alert("<pre>"+msg+"</pre>")

            mw.tools.modal.init({
                html: "<pre>" + msg + "</pre>",
                title: "Email send results..."
            });
            // $('#email_send_test_btn_output').html(msg);
        });
    }
</script>
<style>
    .mw-set-payment-options {
        padding-left: 30px;
    }

    .admin-side-box {
        padding-top: 19px;
    }

    .mw-set-payment-options #shipping-units-setup {
        padding: 20px 0 0;
    }

    .otab {
        display: none;
    }

    #order_email_subject, #test_email_to, #order_email_cc {
        width: 100%;
    }

    #mail-test-btn {
        float: right;
        margin-top: 15px;
    }

    .mw-set-payment-options .mw-ui-label {
        padding-bottom: 5px;
        padding-top: 10px;
        clear: both
    }

    .payment-state-status {
        padding: 12px 12px 5px;
        display: inline-block;
        margin-top: 12px;
        -webkit-transition: all 200ms;
        -moz-transition: all 200ms;
        -o-transition: all 200ms;
        transition: all 200ms;
        border: none;
    }

    .payment-state-status {
        background: #F27E54;
        color: white;
    }

    .payment-state-status.active {
        background: #48ad79;
    }

    .mw-ui-box-header .mw-icon-drag {
        visibility: hidden;
    }

    .mw-ui-box-header:hover .mw-icon-drag {
        visibility: visible;
    }

    .available_providers_placeholder {
        border: 2px dashed #ccc;
        background: transparent;
        height: 50px;
        margin: 10px 0;
        position: relative;
    }

    .gateway-icon-title > .mw-ui-row {
        width: auto;
    }

    .gateway-icon-title > .mw-ui-row * {
        vertical-align: middle;
    }

    .gateway-icon-title > .mw-ui-row img {
        max-width: 100px;
        max-height: 30px;
    }

    .gateway-icon-title > .mw-ui-row .mw-ui-col {
        padding-right: 15px;
    }

    .gateway-icon-title > .mw-ui-row .mw-icon-drag {
        font-size: 19px;
        color: #808080;
        cursor: move;
        cursor: grab;
        cursor: -moz-grab;
        cursor: -webkit-moz-grab;
    }

    .otab {
        padding-right: 10px;
    }

    #available_providers .mw-ui-box-header {
        cursor: pointer;
    }

    #available_providers > .mw-ui-box {
        margin-bottom: 15px;
    }

    #test_ord_eml_toggle {
        padding-bottom: 20px;
    }

    @media (max-width: 767px) {
        .otab {
            padding-left: 10px;
        }
    }

    .payment-tab.active {
        background-color: #111;
        border-color: #111;
        color: white;
    }
</style>
<?php
$here = dirname(__FILE__) . DS . 'gateways' . DS;


$payment_modules = get_modules('type=payment_gateway');


?>

<div class="mw-ui-row">
    <div class="mw-ui-col">
        <div class="mw-ui-col-container">
            <div class="mw-set-payment-options">
                <div
                    style="overflow: hidden;position: relative;padding: 30px 0 0;">
                    <div class="mw-ui-btn-nav"><a
                            class="mw-ui-btn payment-tab active"
                            href="javascript:;">
                            <?php _e("Payments"); ?>
                        </a>  <a class="mw-ui-btn payment-tab"
                                 href="javascript:;">
                            <?php _e("Taxes"); ?>
                        </a> <a class="mw-ui-btn payment-tab"
                                href="javascript:;">
                            <?php _e("Shipping"); ?>
                        </a> <a class="mw-ui-btn payment-tab"
                                href="javascript:;" id="payment-tab-email">
                            <?php _e("Email"); ?>
                        </a> <a class="mw-ui-btn payment-tab"
                                href="javascript:;">
                            <?php _e("Other"); ?>
                        </a> </div>
                </div>
                <div class="otab" style="display: block">
                    <div class="section-header">
                        <h2>
                            <?php _e("Payment providers"); ?>
                        </h2>
                    </div>
                    <?php if (is_array($payment_modules)): ?>
                    <div class="mw_simple_tabs mw_tabs_layout_stylish"
                         id="available_providers">
                        <?php foreach ($payment_modules as $payment_module): ?>
                            <?php
                            $module_info = ($payment_module);
                            if (!isset($module_info['id']) or $module_info['id']==false){
                                $module_info['id'] = 0;
                            }
                            ?>
                            <div
                                class="mw-ui-box mw-ui-box-accordion mw-accordion-active"
                                id="module-db-id-<?php print $module_info['id'] ?>">
                                <div class="mw-ui-box-header"
                                     onmousedown="mw.tools.accordion(this.parentNode);">
                                    <div class="gateway-icon-title">
                                        <div class="mw-ui-row">
                                            <div class="mw-ui-col"><span
                                                    class="mw-icon-drag"></span>
                                            </div>
                                            <div class="mw-ui-col"><img
                                                    src="<?php print $payment_module['icon']; ?>"
                                                    alt=""/></div>
                                            <div class="mw-ui-col"> <span
                                                    class="gateway-title"><?php print $payment_module['name'] ?>
                                                    <?php if (get_option('payment_gw_' . $payment_module['module'], 'payments')!=1): ?>
                                                        <small class="mw-small">
                                                            (disabled)
                                                        </small>
                                                    <?php endif; ?>
                      </span></div>
                                        </div>
                                    </div>
                                    <!--  <span class="ico ireport"></span><span><?php print $payment_module['name'] ?></span> -->

                                </div>
                                <div
                                    class="mw-ui-box-content mw-accordion-content">
                                    <div class="mw-ui-row">
                                        <div class="mw-ui-col">
                                            <h3><?php print $payment_module['name'] ?>
                                                :</h3>
                                        </div>
                                        <div class="mw-ui-col">
                                            <div
                                                class="mw-ui-box payment-state-status <?php if (get_option('payment_gw_' . $payment_module['module'], 'payments')==1): ?>active<?php endif; ?> pull-right">
                                                <div
                                                    class="mw-ui-check-selector">
                                                    <label class="mw-ui-check">
                                                        <input
                                                            onchange="setActiveProvider(this);"
                                                            name="payment_gw_<?php print $payment_module['module'] ?>"
                                                            class="mw_option_field"
                                                            data-option-group="payments"
                                                            value="1"
                                                            type="radio" <?php if (get_option('payment_gw_' . $payment_module['module'], 'payments')==1): ?> checked="checked" <?php endif; ?> >
                                                        <span></span> <span
                                                            class="first">
                          <?php _e("Enabled"); ?>
                          </span> </label>
                                                    <label class="mw-ui-check">
                                                        <input
                                                            onchange="setActiveProvider(this);"
                                                            name="payment_gw_<?php print $payment_module['module'] ?>"
                                                            class="mw_option_field"
                                                            data-option-group="payments"
                                                            value="0"
                                                            type="radio" <?php if (get_option('payment_gw_' . $payment_module['module'], 'payments')!=1): ?> checked="checked" <?php endif; ?> >
                                                        <span></span> <span
                                                            class="second">
                          <?php _e("Disabled"); ?>
                          </span> </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mw-set-payment-gw-options">
                                        <module
                                            type="<?php print $payment_module['module'] ?>"
                                            view="admin"/>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <br/>
                    <hr>
                    <module type="shop/payments/currency" id="mw_curr_select"/>
                </div>
                <div class="otab">
                    <module type="shop/taxes/admin"
                            id="mw_shop_set_tax_settings"/>
                </div>
                <div class="otab">
                    <module type="shop/shipping" view="admin"
                            id="mw_shop_set_shipping_settings"/>
                </div>
                <div class="otab">
                    <module type="shop/orders/settings/setup_emails_on_order"
                            id="setup_emails_on_order"/>
                </div>

                <div class="otab">
                    <module type="shop/orders/settings/other"
                            id="mw_shop_set_other_settings"/>
                </div>




            </div>
        </div>
    </div>
</div>
