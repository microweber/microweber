<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>
<?php
$shipping_modules = get_modules("type=shipping_gateway");
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>



<script type="text/javascript">
    shippingSetActiveProvider = function (el, checkbox) {
        el = $(el);

        if ($(checkbox).is(':checked')) {
            el.addClass('bg-primary-opacity-1');
        } else {
            el.removeClass('bg-primary-opacity-1');
        }
    }

    shippingMethodModal = function (el,mod, title) {
        el = $(el);
        var html = el.find('.js-modal-content').html();
        var formId = mw.id('pm');


        var moduleId =formId;
        var moduleType = mod + '/admin';




        var dialog = mw.top().dialogIframe({
             url: route('live_edit.module_settings') + '?id=' + moduleId+ '&type=' + moduleType,
            title,
            id: 'mw_admin_existing_modal_shipping<?php print $params['id'] ?>',

            height: 'auto',
            width: '700px'
        })

        // var modal = mw.dialog({
        //     content: '<form id="'+formId+'">' + html + '</form>',
        //     onremove: function () {
        //         html = modal.container.innerHTML;
        //         $(document.body).removeClass('paymentSettingsModal')
        //     },
        //     onopen: function () {
        //         $(document.body).addClass('paymentSettingsModal');
        //
        //     },
        //     overlay: true,
        //     id: 'paymentSettingsModal',
        //     title: $('.gateway-title', el).html()
        // });

        mw.options.form('#' + formId, function () {
            mw.notification.success("<?php _ejs("Shop settings are saved"); ?>.");
            mw.reload_module_everywhere("shop/shipping/admin");
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
</script>

<div class="card">
    <div class="card-body mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
       <div class="row">
           <div class="card-header d-block px-0">
               <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
                <small class="text-muted d-block "><?php _e("Enable and set up the shipping methods your customers will use"); ?></small>
           </div>

           <div class="card bg-azure-lt ">
              <div class="card-body col-xl-8 col-12 mx-auto my-4">
                  <div id="db-shipping-methods">
                      <?php if (is_array($shipping_modules)): ?>
                          <div id="available_providers">
                              <?php foreach ($shipping_modules as $shipping_module): ?>
                                  <?php
                                  $module_info = ($shipping_module);
                                  if (!isset($module_info['id']) or $module_info['id'] == false) {
                                      $module_info['id'] = 0;
                                  }
                                  ?>

                                  <div x-data="{isModuleActivated: <?php if (get_option('shipping_gw_' . $shipping_module['module'], 'shipping')  === 'y'): ?>true<?php else: ?>false<?php endif; ?>}" class="dragable-item shipping-module-draggable-item  <?php if (get_option('shipping_gw_' . $shipping_module['module'], 'shipping')  === 'y'): ?>bg-primary-opacity-1<?php endif; ?>" id="module-db-id-<?php print $module_info['id'] ?>">
                                      <div class="row align-items-center shadow-sm bg-light mb-3">
                                              <div class="col-1 cursor-move-holder " style="max-width: 80px;">
                                                  <span href="javascript:;" class="btn btn-link text-blue-lt tblr-body-color">
                                                      <svg class="mdi-cursor-move" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M360 896q-33 0-56.5-23.5T280 816q0-33 23.5-56.5T360 736q33 0 56.5 23.5T440 816q0 33-23.5 56.5T360 896Zm240 0q-33 0-56.5-23.5T520 816q0-33 23.5-56.5T600 736q33 0 56.5 23.5T680 816q0 33-23.5 56.5T600 896ZM360 656q-33 0-56.5-23.5T280 576q0-33 23.5-56.5T360 496q33 0 56.5 23.5T440 576q0 33-23.5 56.5T360 656Zm240 0q-33 0-56.5-23.5T520 576q0-33 23.5-56.5T600 496q33 0 56.5 23.5T680 576q0 33-23.5 56.5T600 656ZM360 416q-33 0-56.5-23.5T280 336q0-33 23.5-56.5T360 256q33 0 56.5 23.5T440 336q0 33-23.5 56.5T360 416Zm240 0q-33 0-56.5-23.5T520 336q0-33 23.5-56.5T600 256q33 0 56.5 23.5T680 336q0 33-23.5 56.5T600 416Z"/></svg>
                                                  </span>
                                              </div>


                                              <div class="col js-change-method-status" style="max-width: 60px;">

                                                  <div class="form-check form-check-single form-switch p-0">
                                                      <input x-model="isModuleActivated" onchange="shippingSetActiveProvider('#module-db-id-<?php print $module_info['id'] ?>', this);" type="checkbox" data-value-unchecked="n" data-value-checked="y"  class="mw_option_field form-check-input" id="ccheckbox-shipping_gw_<?php print $shipping_module['module'] ?>" name="shipping_gw_<?php print $shipping_module['module'] ?>" data-option-group="shipping" <?php if (get_option('shipping_gw_' . $shipping_module['module'], 'shipping')  === 'y'): ?> checked="checked" <?php endif; ?> value="y">
                                                      <label class="custom-control-label gateway-title" for="ccheckbox-shipping_gw_<?php print($shipping_module['module']) ?>"></label>
                                                  </div>

                                              </div>

                                              <div class="col col-sm-6">
                                                  <img src="<?php print $shipping_module['icon']; ?>" alt="" class="d-none"/>
                                                  <label class="form-label fs-2 font-weight-bold mb-0"><?php _e($shipping_module['name']) ?></label>
                                                  <small class="text-muted">
                                                      <span class="text-primary">
                                                        <span x-show="isModuleActivated">Activated</span>
                                                        <span x-show="!isModuleActivated">Not activated</span>
                                                      </span>
                                                  </small>
                                              </div>

                                              <div class="col text-end text-right">
                                                  <button type="button" onclick="shippingMethodModal('#module-db-id-<?php print $module_info['id'] ?>', '<?php print($shipping_module['module']) ?>',  '<?php _ejs($shipping_module['name']) ?>');" class="btn btn-outline-primary btn-sm"><?php _e('Settings'); ?></button>
                                              </div>
                                          </div>
                                      </div>
                                  <script>
                                      $(document).ready(function () {
                                          mw.options.form('#module-db-id-<?php print $module_info['id'] ?> .js-change-method-status', function () {
                                              mw.notification.success("<?php _ejs("Shipping changes are saved"); ?>.");
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
</div>
