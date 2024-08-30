<?php if (!is_admin()) {
    return;
} ?>
<script type="text/javascript">
    //mw.require('options.js');

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

    .available_providers_placeholder {
        border: 2px dashed #ccc;
        background: transparent;
        height: 50px;
        margin: 10px 0;
        position: relative;
    }
</style>

<script type="text/javascript">
    setActiveProvider = function (el, checkbox) {
        el = $(el);

        if ($(checkbox).is(':checked')) {
            el.addClass('bg-primary-opacity-1');
            el.find('.js-method-on').removeClass('d-none');
        } else {
            el.removeClass('bg-primary-opacity-1');
            el.find('.js-method-on').addClass('d-none');
        }
    }

    paymentModal = function (el, id) {

        el = $(el);
        var html = el.find('.js-modal-content').html();
        var formId = mw.id('pm');
        var modal = mw.dialog({
            content: '<div id="'+formId+'">' + html + '</div>',
            onremove: function () {
                html = modal.container.innerHTML;
                $(document.body).removeClass('paymentSettingsModal')
                mw.reload_module_everywhere("shop/payments/admin");

            },
            onopen: function () {
                $(document.body).addClass('paymentSettingsModal');

            },
            overlay: true,
            id: 'paymentSettingsModal',
            title: $('.gateway-title', el).html()
        });

       // mw.reload_module_everywhere("shop/payments/admin");


        mw.reload_module_everywhere('#module-shop-payments-id-' + id, function () {
            mw.options.form('#' + formId, function () {
                mw.notification.success("<?php _ejs("Payment provider settings are saved"); ?>.");
            });
        });
    }

    $(document).ready(function () {
        if (typeof thismodal !== 'undefined') {
            thismodal.width(1000);
        }

        mw.$("#available_providers").sortable({
            items: ".dragable-item",
            handle: ".mdi-cursor-move",
            axis: 'y',
            placeholder: "available_providers_placeholder",
            start: function (a, b) {
                $(this).sortable("refreshPositions");

            },
            update: function () {
                var serial = $(this).sortable('serialize');
                $.ajax({
                    url: mw.settings.api_url + 'module/reorder_modules',
                    type: "post",
                    data: serial,
                    success: function(data) {
                        mw.notification.success("<?php _ejs("Shop settings are saved"); ?>.");

                    }
                });


            },
            stop: function () {
                //  mw.alert("<?php _ejs("Saving"); ?> ... ");
            }
        })
    });

    checkout_confirm_email_test = function () {
        var email_to = {}
        email_to.to = $('#test_email_to').val();
        //email_to.subject = $('#test_email_subject').val();
        $.post("<?php print site_url('api_html/checkout_confirm_email_test'); ?>", email_to, function (msg) {
            //mw.alert("<pre>"+msg+"</pre>")
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


<div class="card">
    <div class="card-body mb-3">
      <div class="row">
            <div class="card-header d-block px-0">
                <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>

                <p class="text-muted d-block"><?php _e("Enable and set up the payment method your customers will use to pay"); ?></p>
            </div>

          <div class="card bg-azure-lt " id="db-payment-methods">
              <div class="card-body col-xl-8 col-12 mx-auto my-4">
                 <?php if (is_array($payment_modules)): ?>
                  <div id="available_providers">
                      <?php foreach ($payment_modules as $payment_module): ?>
                          <?php
                          $module_info = ($payment_module);
                          if (!isset($module_info['id']) or $module_info['id'] == false) {
                              $module_info['id'] = 0;
                          }

                          $isEnabled = get_option('payment_gw_' . $payment_module['module'], 'payments') == '1';
                          ?>

                          <div class="dragable-item align-items-center shadow-sm bg-light mb-3 <?php if ($isEnabled): ?>bg-primary-opacity-1<?php endif; ?>" id="module-db-id-<?php print $module_info['id'] ?>">
                              <div class="row d-flex align-items-center">
                                  <div class="col-1 cursor-move-holder "
                                      <a href="javascript:;" class="btn btn-link text-blue-lt ">
                                          <svg class="mdi-cursor-move" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M360 896q-33 0-56.5-23.5T280 816q0-33 23.5-56.5T360 736q33 0 56.5 23.5T440 816q0 33-23.5 56.5T360 896Zm240 0q-33 0-56.5-23.5T520 816q0-33 23.5-56.5T600 736q33 0 56.5 23.5T680 816q0 33-23.5 56.5T600 896ZM360 656q-33 0-56.5-23.5T280 576q0-33 23.5-56.5T360 496q33 0 56.5 23.5T440 576q0 33-23.5 56.5T360 656Zm240 0q-33 0-56.5-23.5T520 576q0-33 23.5-56.5T600 496q33 0 56.5 23.5T680 576q0 33-23.5 56.5T600 656ZM360 416q-33 0-56.5-23.5T280 336q0-33 23.5-56.5T360 256q33 0 56.5 23.5T440 336q0 33-23.5 56.5T360 416Zm240 0q-33 0-56.5-23.5T520 336q0-33 23.5-56.5T600 256q33 0 56.5 23.5T680 336q0 33-23.5 56.5T600 416Z"/></svg>
                                      </a>
                                  </div>

                                  <div class="col pl-0 js-change-method-status" style="max-width: 60px;">

                                      <div class="form-check form-check-single form-switch p-0">
                                          <input onchange="setActiveProvider('#module-db-id-<?php print $module_info['id'] ?>', this);" type="checkbox" class="mw_option_field form-check-input" id="ccheckbox-payment_gw_<?php print $payment_module['module'] ?>" name="payment_gw_<?php print $payment_module['module'] ?>" data-option-group="payments" data-id="shipping_gw_shop/shipping/gateways/country"<?php if ($isEnabled): ?> checked="checked" <?php endif; ?> value="1">
                                          <label class="custom-control-label" for="ccheckbox-payment_gw_<?php print $payment_module['module'] ?>"></label>
                                      </div>

                                  </div>

                                  <div class="col pl-0">
                                      <img src="<?php print $payment_module['icon']; ?>" alt="" class="d-none"/>

                                      <h4 class=" form-label fs-2 font-weight-bold mb-0 gateway-title"><?php _e($payment_module['name']) ?></h4>

                                      <small class="text-muted d-block mt-1">
                                          <?php _e($payment_module['name']) ?> <span class="text-success js-method-on <?php if (!$isEnabled): ?>d-none<?php endif; ?>"><?php _e("is ON"); ?></span>
                                      </small>
                                  </div>

                                  <div class="col text-end text-right text-right">
                                      <button type="button" onclick="paymentModal('#module-db-id-<?php print $module_info['id'] ?>', '<?php print $module_info['id'] ?>');" class="btn btn-outline-primary btn-sm"><?php _e('Settings'); ?></button>
                                  </div>
                              </div>

                              <template class="js-modal-content" style="display: none;">
<!--                                  <h5 class="mb-0">--><?php //_e('Enter your API settings'); ?><!--</h5>-->
<!--                                  <small class="text-muted mb-3 d-block">--><?php //_e("Ask your payment provider for this information and put it below"); ?><!--</small>-->

                                  <div class="mw-set-payment-gw-options">
                                      <module id="module-shop-payments-id-<?php print $module_info['id'] ?>" type="<?php print $payment_module['module'] ?>" view="admin"/>
                                  </div>
                              </template>

                          </div>
                          <script>
                              $(document).ready(function () {
                                  mw.options.form('#module-db-id-<?php print $module_info['id'] ?> .js-change-method-status', function () {
                                      mw.notification.success("<?php _ejs("Payment changes are saved"); ?>.");
                                  });
                              });
                          </script>

                      <?php endforeach; ?>
                  </div>
              <?php endif; ?>
              </div>
          </div>
      </div>
    </div>
</div>
