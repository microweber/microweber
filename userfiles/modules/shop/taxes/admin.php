<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <script>
            mw_admin_edit_tax_item_popup_modal_opened = null

            function mw_admin_edit_tax_item_popup(tax_item_id) {
                if (!!tax_item_id) {
                    var modalTitle = '<?php _e('Edit tax item'); ?>';
                } else {
                    var modalTitle = '<?php _e('Add tax item'); ?>';
                }

                mw_admin_edit_tax_item_popup_modal_opened = mw.dialog({
                    content: '<div id="mw_admin_edit_tax_item_module"></div>',
                    title: modalTitle,
                    id: 'mw_admin_edit_tax_item_popup_modal'
                });

                var params = {}
                params.tax_item_id = tax_item_id;
                mw.load_module('shop/taxes/admin_edit_tax_item', '#mw_admin_edit_tax_item_module', null, params);
            }

            function mw_admin_delete_tax_item_confirm(tax_item_id) {
                var r = confirm("<?php _ejs('Are you sure you want to delete this tax?'); ?>");
                if (r == true) {
                    var url = mw.settings.api_url + 'shop/delete_tax_item';
                    $.post(url, {id: tax_item_id})
                        .done(function (data) {
                            mw_admin_after_changed_tax_item();
                        });
                }
            }

            function mw_admin_after_changed_tax_item() {
                mw.notification.success("<?php _ejs('Taxes are updated'); ?>");
                // mw.reload_module('#mw_admin_shop_taxes_items_list');
                mw.reload_module('shop/taxes');
                mw.reload_module_everywhere('shop/taxes/admin_list_taxes')
                mw.reload_module_everywhere('shop/cart')
            }

            $(document).ready(function () {
                $(window).on("mw.admin.shop.tax.edit.item.saved", function () {
                    if (typeof('mw_admin_edit_tax_item_popup_modal_opened') != 'null') {
                        mw_admin_edit_tax_item_popup_modal_opened.remove();
                    }
                    mw_admin_after_changed_tax_item();
                });
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function () {
                mw.options.form('.<?php print $config['module_class'] ?>', function () {
                    mw.notification.success("<?php _ejs("Saved"); ?>.");
                });
            });
        </script>

        <div class="d-flex justify-content-between">
            <div class="form-group">
                <div class="custom-control custom-switch ps-3">
                    <input type="checkbox" name="enable_taxes" id="enable_taxes" class="mw_option_field custom-control-input position-relative" data-option-group="shop" value="1" data-value-checked="1" data-value-unchecked="0" <?php if (get_option('enable_taxes', 'shop') == 1): ?>checked<?php endif; ?>>
                    <label class="custom-control-label" for="enable_taxes"><?php _e("Enable taxes support"); ?></label>
                </div>
                <small class="text-muted d-block"><?php _e('Setup different types of taxes and they will appear automatically in your cart'); ?></small>
            </div>

            <div>
                <a class="btn btn-primary btn-rounded" href="javascript:mw_admin_edit_tax_item_popup(0)"><?php _e('Add new tax'); ?></a>
            </div>
        </div>

        <div class="mt-3">
            <module type="shop/taxes/admin_list_taxes" id="mw_admin_shop_taxes_items_list"/>
        </div>
    </div>
</div>



