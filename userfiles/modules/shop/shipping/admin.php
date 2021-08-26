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
            el.find('.js-method-on').removeClass('d-none');
        } else {
            el.removeClass('bg-primary-opacity-1');
            el.find('.js-method-on').addClass('d-none');
        }
    }

    shippingMethodModal = function (el,mod) {
        el = $(el);
        var html = el.find('.js-modal-content').html();
        var formId = mw.id('pm');



        var dialog = mw.top().dialogIframe({
            url: '<?php print site_url() ?>module/?type='+mod+'/admin&admin=true&id=mw_admin_existing_modal&from_url=<?php print site_url() ?>',
            title: $('.gateway-title', el).html(),
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

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <?php if($module_info){ ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php _e($module_info['name']); ?></strong>
        </h5>
        <?php } ?>
    </div>

    <div class="card-body pt-3">
        <p class="text-muted"><?php _e("Enable and set up the shipping methods your customers will use"); ?></p>

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

                        <div class="dragable-item card style-1 mb-3 <?php if (get_option('shipping_gw_' . $shipping_module['module'], 'shipping')  === 'y'): ?>bg-primary-opacity-1<?php endif; ?>" id="module-db-id-<?php print $module_info['id'] ?>">
                            <div class="card-body py-3">
                                <div class="row d-flex align-items-center">
                                    <div class="col cursor-move-holder" style="max-width: 80px;">
                                        <span href="javascript:;" class="btn btn-link text-dark"><i class="mdi mdi-cursor-move"></i></span>
                                    </div>

                                    <div class="col pl-0 js-change-method-status" style="max-width: 60px;">
                                        <div class="form-group m-0">
                                            <div class="custom-control custom-switch m-0">
                                                <input onchange="shippingSetActiveProvider('#module-db-id-<?php print $module_info['id'] ?>', this);" type="checkbox" data-value-unchecked="n" data-value-checked="y"  class="mw_option_field custom-control-input" id="ccheckbox-shipping_gw_<?php print $shipping_module['module'] ?>" name="shipping_gw_<?php print $shipping_module['module'] ?>" data-option-group="shipping" <?php if (get_option('shipping_gw_' . $shipping_module['module'], 'shipping')  === 'y'): ?> checked="checked" <?php endif; ?> value="y">
                                                <label class="custom-control-label" for="ccheckbox-shipping_gw_<?php print($shipping_module['module']) ?>"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col pl-0 col-sm-6">
                                        <img src="<?php print $shipping_module['icon']; ?>" alt="" class="d-none"/>
                                        <h4 class="gateway-title font-weight-bold mb-0"><?php _e($shipping_module['name']) ?></h4>
                                        <small class="text-muted">
                                            <?php _e($shipping_module['name']) ?> <span class="text-primary js-method-on <?php if (get_option('shipping_gw_' . $shipping_module['module'], 'shipping')   === 'n'): ?>d-none<?php endif; ?>"><?php _e("is ON"); ?></span>
                                        </small>
                                    </div>

                                    <div class="col text-end">
                                        <button type="button" onclick="shippingMethodModal('#module-db-id-<?php print $module_info['id'] ?>', '<?php print($shipping_module['module']) ?>');" class="btn btn-outline-primary btn-sm"><?php _e('Settings'); ?></button>
                                    </div>
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
