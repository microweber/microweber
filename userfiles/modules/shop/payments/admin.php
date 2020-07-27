<?php if (!is_admin()) {
    return;
} ?>
<script type="text/javascript">
    mw.require('options.js');
    mw.lib.require('mwui_init');
</script>

<style>
    .cursor-move-holder {
        visibility: hidden;
        max-width: 60px;
        text-align: left;
        padding: 0;
    }

    .dragable-item:hover .cursor-move-holder {
        visibility: visible;
    }
</style>

<script type="text/javascript">
    setActiveProvider = function (el) {
        if (el.checked == true) {
            if (el.value == 1) {
                $(mw.tools.firstParentWithClass(el, 'payment-state-status')).addClass("active");
            } else {
                $(mw.tools.firstParentWithClass(el, 'payment-state-status')).removeClass("active");
            }
        }
    }

    paymentModal = function (el) {
        var html = el.querySelector('.js-modal-content').innerHTML
        var modal = mw.dialog({
            content: '<form id="pm-' + mw.random() + '">' + el.querySelector('.js-modal-content').innerHTML + '</form>',
            onremove: function (modal) {
                el.querySelector('.js-modal-content').innerHTML = modal.container.innerHTML;
                $(mwd.body).removeClass('paymentSettingsModal')
            },
            onopen: function () {
                $(mwd.body).addClass('paymentSettingsModal');

            },
            overlay: true,
            id: 'paymentSettingsModal',
            title: $('.gateway-title', el).html()
        });


        mw.options.form($(modal.container), function () {
            mw.notification.success("<?php _ejs("Shop settings are saved"); ?>.");
            mw.reload_module_everywhere("shop/settings");
        });
    }

    $(document).ready(function () {
        if (typeof thismodal !== 'undefined') {
            thismodal.width(1000);
        }
        $('.mw-admin-wrap').click(function () {
            // mw.options.form('.mw-set-payment-options');
        });

        mw.$("#available_providers").sortable({
            items: ".dragable-item",
            handle: ".mdi-cursor-move",
            axis: 1,
            placeholder: "available_providers_placeholder",
            start: function (a, b) {
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
        });
    }
</script>

<?php
$here = dirname(__FILE__) . DS . 'gateways' . DS;
$payment_modules = get_modules('type=payment_gateway');
?>

<div class="card bg-none style-1 mb-0">
    <div class="card-header">
        <h5>
            <i class="mdi mdi-cash-usd-outline text-primary mr-3"></i> <strong>Payment methods</strong>
        </h5>
        <div>

        </div>
    </div>

    <div class="card-body pt-3">
        <p class="text-muted">Enable and set up the payment method your customers will use to pay</p>

        <script type="text/javascript">
            $(document).ready(function () {
                mw.options.form('.<?php print $config['module_class'] ?>', function () {
                    mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
                });
            });
        </script>

        <div id="db-payment-methods" class="<?php print $config['module_class'] ?>">
            <?php if (is_array($payment_modules)): ?>
            <div id="available_providers">
                <?php foreach ($payment_modules as $payment_module): ?>
                    <?php
                    $module_info = ($payment_module);
                    if (!isset($module_info['id']) or $module_info['id'] == false) {
                        $module_info['id'] = 0;
                    }
                    ?>
                    <div class="dragable-item card style-1 mb-3 <?php if (get_option('payment_gw_' . $payment_module['module'], 'payments') == 1): ?>bg-primary-opacity-1<?php endif; ?>" id="module-db-id-<?php print $module_info['id'] ?>">
                        <div class="card-body py-3" onclick="paymentModal(this.parentNode);">
                            <div class="row d-flex align-items-center">
                                <div class="col cursor-move-holder">
                                    <a href="javascript:;" class="btn btn-link text-dark"><i class="mdi mdi-cursor-move"></i></a>
                                </div>

                                <div class="col pl-0" style="max-width: 60px;">
                                    <div class="form-group m-0">
                                        <div class="custom-control custom-switch m-0">
                                            <input type="checkbox"
                                                   class="mw_option_field custom-control-input"
                                                   id="ccheckbox-payment_gw_<?php print $payment_module['module'] ?>"
                                                   name="payment_gw_<?php print $payment_module['module'] ?>"
                                                   data-option-group="payments"
                                                   data-id="shipping_gw_shop/shipping/gateways/country"
                                                <?php if (get_option('payment_gw_' . $payment_module['module'], 'payments') == 1): ?> checked="checked" <?php endif; ?>
                                                   value="1">
                                            <label class="custom-control-label" for="ccheckbox-payment_gw_<?php print $payment_module['module'] ?>"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col pl-0">
                                    <img src="<?php print $payment_module['icon']; ?>" alt="" class="d-none"/>

                                    <h4 class="gateway-title font-weight-bold mb-0"><?php print $payment_module['name'] ?></h4>

                                    <small class="text-muted">
                                        <?php print $payment_module['name'] ?>
                                        <?php if (get_option('payment_gw_' . $payment_module['module'], 'payments') == 1): ?> <span class="text-primary">is ON</span><?php endif; ?>
                                    </small>
                                </div>

                                <div class="col text-right">
                                    <button type="button" class="btn btn-outline-primary btn-sm"><?php _e('Settings'); ?></button>
                                </div>
                            </div>

                            <div class="js-modal-content" style="display: none;">
                                <div class="mw-ui-row">
                                    <div class="">
                                        <p class="bold">Allow <?php print $payment_module['name'] ?> payment</p>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input onchange="setActiveProvider(this);"
                                                   type="checkbox"
                                                   class="custom-control-input"
                                                   id="checkbox-payment_gw_<?php print $payment_module['module'] ?>"
                                                   name="payment_gw_<?php print $payment_module['module'] ?>"
                                                   data-option-group="payments"
                                                   data-id="shipping_gw_shop/shipping/gateways/country"
                                                <?php if (get_option('payment_gw_' . $payment_module['module'], 'payments') == 1): ?> checked="checked" <?php endif; ?>
                                                   value="1">
                                            <label class="custom-control-label" for="checkbox-payment_gw_<?php print $payment_module['module'] ?>">Toggle this switch element</label>
                                        </div>
                                    </div>

                                </div>
                                <p class="bold m-b-10 m-t-10"><?php _e('Enter your API settings'); ?></p>
                                <div class="mw-set-payment-gw-options">
                                    <module type="<?php print $payment_module['module'] ?>" view="admin"/>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>


    </div>
</div>