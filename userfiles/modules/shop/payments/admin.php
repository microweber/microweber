<?php if (!is_admin()) {
    return;
} ?>
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

    paymentModal = function (el) {
        var html = el.querySelector('.mw-ui-box-content').innerHTML
        var modal = mw.modal({
            content: '<form id="pm-' + mw.random() + '">' + el.querySelector('.mw-ui-box-content').innerHTML + '</form>',
            onremove: function (modal) {
                el.querySelector('.mw-ui-box-content').innerHTML = modal.container.innerHTML;
                $(mwd.body).removeClass('paymentSettingsModal')
            },
            onopen: function () {
                $(mwd.body).addClass('paymentSettingsModal');

            },
            overlay: true,
            id: 'paymentSettingsModal',
            title: $('.gateway-title', el).html()
        });

        $(modal.container).find('.mw-small').remove()
        mw.options.form($(modal.container), function () {
            mw.notification.success("<?php _ejs("Shop settings are saved"); ?>.");
            mw.reload_module_everywhere("shop/settings");
        });
    }

<?php
/*    mw.on.hashParam('optissson', function () {

       alert(this);
     /!*   $(".mw-set-payment-options .otab").hide();
        $("#db-" + this).show();
        $(".active-parent li.active").removeClass('active');
        var link = $('a[href*="?option=' + this + '"]');
        link
            .parent()
            .addClass('active');
        //$(".shop-options-title-icon").attr('class', 'shop-options-title-icon ' + link.find('span').attr('class'))*!/
    });*/

?>
    $(document).ready(function () {
        if (typeof thismodal !== 'undefined') {
            thismodal.width(1000);
        }
        $('.mw-admin-wrap').click(function () {
            // mw.options.form('.mw-set-payment-options');
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
                //  Alert("<?php _ejs("Saving"); ?> ... ");
            }
        })
    });


    mw.checkout_confirm_email_test = function () {
        var email_to = {}
        email_to.to = $('#test_email_to').val();
        //email_to.subject = $('#test_email_subject').val();
        $.post("<?php print site_url('api_html/checkout_confirm_email_test'); ?>", email_to, function (msg) {
            //Alert("<pre>"+msg+"</pre>")
            mw.tools.modal.init({
                html: "<pre>" + msg + "</pre>",
                title: "<?php _e('Email send results...'); ?>"
            });
            // $('#email_send_test_btn_output').html(msg);
        });
    }
</script>

<style>
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

    .mw-set-payment-options .mw-ui-box-header:hover .mw-ui-btn {
        background-color: #398abe;
        color: #fff;
    }

    .mw-set-payment-options .payment-state-status {
        padding: 12px 12px 5px;
        display: inline-block;
        margin-top: 12px;
        -webkit-transition: all 200ms;
        -moz-transition: all 200ms;
        -o-transition: all 200ms;
        transition: all 200ms;
        border: none;
    }

    .mw-set-payment-options .payment-state-status {
        background: #F27E54;
        color: white;
    }

    .mw-set-payment-options .payment-state-status.active {
        background: #48ad79;
    }

    .mw-set-payment-options .mw-ui-box-header .mw-icon-drag {
        visibility: hidden;
    }

    .mw-set-payment-options  .mw-ui-box-header:hover {
        background: #fefbea;
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
        width: 100%;
    }

    .gateway-icon-title > .mw-ui-row * {
        vertical-align: middle;
    }

    .gateway-icon-title > .mw-ui-row img,
    .gateway-icon-title img {
        max-width: 100px;
        max-height: 30px;
    }

    .gateway-icon-title > .mw-ui-row .mw-ui-col {
        padding-right: 15px;
    }

    .gateway-icon-title > .mw-ui-row .mw-ui-col:first-child {
        width: 40px;
    }

    .gateway-icon-title > .mw-ui-row .mw-ui-col:nth-child(2) {
        width: 200px;
    }

    .gateway-icon-title > .mw-ui-row .mw-ui-col:last-child {
        width: 200px;
        text-align: center;
    }

    ..gateway-icon-title > .mw-ui-row .mw-icon-drag {
        font-size: 19px;
        color: #808080;
        cursor: move;
        cursor: grab;
        cursor: -moz-grab;
        cursor: -webkit-moz-grab;
    }

    .gateway-icon-title > .mw-ui-row > .mw-ui-col:nth-child(2) {
        width: 170px;
        text-align: center;
    }

    .otab {
        padding-right: 10px;
    }

    #available_providers .mw-ui-box-header {
        cursor: pointer;
    }

    #available_providers > .mw-ui-box {
        margin-bottom: -1px;
        border-radius: 0;
    }

    #available_providers > .mw-ui-box .mw-ui-box-header {
        background-color: transparent;
    }

    #available_providers > .mw-ui-box:nth-child(odd) {
        background-color: #fff;
    }

    #available_providers > .mw-ui-box:nth-child(even) {
        background-color: #f5f5f5;
    }

    #available_providers > .mw-ui-box:hover {
        background-color: #fefbea;
    }

    #available_providers .mw-ui-box-header {
        padding: 25px;
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
                <div class="otab" style="display: block" id="db-payment-methods">
                    <?php if (is_array($payment_modules)): ?>
                    <div class="mw_simple_tabs mw_tabs_layout_stylish" id="available_providers">
                        <?php foreach ($payment_modules as $payment_module): ?>
                            <?php
                            $module_info = ($payment_module);
                            if (!isset($module_info['id']) or $module_info['id'] == false) {
                                $module_info['id'] = 0;
                            }
                            ?>
                            <div class="mw-ui-box mw-ui-box-accordion mw-accordion-active"  id="module-db-id-<?php print $module_info['id'] ?>">
                                <div class="mw-ui-box-header"        onclick="paymentModal(this.parentNode);">
                                    <div class="gateway-icon-title">
                                        <div class="mw-ui-row">
                                            <div class="mw-ui-col">
                                                <span class="mw-icon-drag"></span>
                                            </div>

                                            <div class="mw-ui-col" style="width: 40px; font-size: 16px; color: green;">
                                                <?php if (get_option('payment_gw_' . $payment_module['module'], 'payments') == 1): ?>
                                                    <i class="mw-icon-checkmark-circled"></i>
                                                <?php endif; ?>
                                            </div>

                                            <div class="mw-ui-col">
                                                <img src="<?php print $payment_module['icon']; ?>" alt=""/>
                                            </div>


                                            <div class="mw-ui-col">
                                                    <span class="gateway-title"><?php print $payment_module['name'] ?>
                                                        <?php if (get_option('payment_gw_' . $payment_module['module'], 'payments') != 1): ?>
                                                            <small class="mw-small">(disabled)</small>
                                                        <?php endif; ?>
                                                    </span>
                                            </div>
                                            <div class="mw-ui-col">
                                                <span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline">
                                                    <span class="mai-setting2"></span>
                                                    <?php _e('Settings'); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="mw-ui-box-content mw-accordion-content">
                                    <div class="mw-ui-row">
                                        <div class="">
                                            <p class="bold">Allow <?php print $payment_module['name'] ?> payment</p>
                                        </div>

                                        <div class="">
                                            <label class="mw-switch inline-switch m-0 m-t-10 m-b-10">
                                                <input onchange="setActiveProvider(this);" type="checkbox" name="payment_gw_<?php print $payment_module['module'] ?>" data-option-group="payments" data-id="shipping_gw_shop/shipping/gateways/country" data-value-checked="1"
                                                       data-value-unchecked="0"
                                                       class="mw_option_field" <?php if (get_option('payment_gw_' . $payment_module['module'], 'payments') == 1): ?> checked="checked" <?php endif; ?>>
                                                <span class="mw-switch-off">OFF</span>
                                                <span class="mw-switch-on">ON</span>
                                                <span class="mw-switcher"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <p class="bold m-b-10 m-t-10"><?php _e('Enter your API settings'); ?></p>
                                    <div class="mw-set-payment-gw-options">
                                        <module type="<?php print $payment_module['module'] ?>" view="admin"/>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
