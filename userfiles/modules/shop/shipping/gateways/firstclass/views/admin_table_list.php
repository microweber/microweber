<script>
    function mw_admin_edit_country_item_popup(id) {
        if (id) {
            var modalTitle = '<?php _e('Edit country'); ?>';
        } else {
            id = 0;
            var modalTitle = '<?php _e('Add country'); ?>';
        }

        // mw_admin_edit_country_item_popup_modal_opened = mw.dialog({
        //     content: '<div id="mw_admin_edit_country_item_module"></div>',
        //     title: modalTitle,
        //
        //     id: 'mw_admin_edit_country_item_popup_modal'
        // });
        //
        // var params = {}
        // if (id) {
        //     params.edit_id = id
        // }
        //
        // mw.load_module('shop/shipping/gateways/firstclass/add_country', '#mw_admin_edit_country_item_module', null, params);


        var modal_id = 'mw_admin_edit_country_item_popup_modal_opened';
        var dialog = mw.top().dialogIframe({
            url: '<?php print site_url() ?>module/?type=shop/shipping/gateways/firstclass/add_country&live_edit=true&id=mw_admin_edit_country_item_popup_modal_opened__module&edit_id='+id+'&from_url=<?php print site_url() ?>',
            title:modalTitle,
            id: modal_id,

            height: 'auto',
            autoHeight: true
        })



    }
</script>

<script>
    mw.shipping_country = {
        delete_country : function(id){
            var q = "Are you sure you want to delete shipping to this country?";
            mw.tools.confirm(q, function(){
                var obj = {};
                obj.id = id;
                $.post("<?php print $config['module_api']; ?>/shipping_firstclass/delete",  obj, function(data){
                    mw.$(".country-id-"+id).fadeOut();
                    mw.reload_module_everywhere('[data-parent-module="shop/shipping"]');
                    mw.reload_module_everywhere('shop/shipping/gateways/firstclass/admin');
                    if(window.parent != undefined && window.parent.mw != undefined){
                        mw.reload_module_everywhere('shop/shipping/gateways/firstclass');
                    }
                });
            });
        },
        add_country:function(){

        }
    }
</script>

<script>mw.lib.require('mwui_init');</script>
<?php if ($active_or_disabled != 'active'): ?>
    <hr class="thin"/>
<?php endif; ?>

<div class="">
    <div class="row d-flex justify-content-between align-items-end">
        <div class="col">
            <div class="form-group mb-0">
                <?php if ($active_or_disabled == 'active'): ?>
                    <label class="control-label"><?php _e('Allowed countries for shipping'); ?></label>
                    <small class="text-muted d-block mb-0"> <?php _e('List of countries to which shipping is performed'); ?></small>
                <?php else: ?>
                    <label class="control-label"><?php _e('Denied countries for shipping'); ?></label>
                    <small class="text-muted d-block mb-0"><?php _e('List of countries where deliveries are not allowed'); ?></small>
                <?php endif; ?>
            </div>
        </div>
        <div class="col text-right">
            <a class="btn btn-primary btn-sm" href="javascript:mw_admin_edit_country_item_popup();"><?php _e("Add Country"); ?></a>
        </div>
    </div>

    <div class="mw-shipping-items shipping_firstclass_holder table-responsive mt-3" id="shipping_firstclass_holder<?php if ($active_or_disabled == 'active'): ?>_active<?php endif; ?>">
        <table class="table small">
            <thead class="<?php if ($active_or_disabled == 'active'): ?>table-success<?php else: ?>table-danger<?php endif; ?>">
            <tr>
                <th style="width: 10px; padding-right: 0;"></th>
                <th><?php if ($active_or_disabled == 'active'): ?><?php _e('Allowed'); ?><?php else: ?><?php _e('Denied'); ?><?php endif; ?> <?php _e('Country'); ?></th>
                <th><?php _e('Shipping Type'); ?></th>
                <th><?php _e('Shipping Cost'); ?></th>
                <th class="text-right" style="width: 200px;"><?php _e('Actions'); ?></th>
            </tr>
            </thead>
            <?php if (is_array($data) and !empty($data)): ?>
                <?php foreach ($data as $item): ?>
                    <tr class="shipping-country-holder vertical-align-middle show-on-hover-root" data-field-id="<?php print $item['id']; ?>" id="shipping-table-list-item-id-<?php print $item['id']; ?>">
                        <td style="width: 10px; padding-right: 0;">
                            <i data-title="<?php _e("Reorder shipping countries"); ?>" data-bs-toggle="tooltip" class="shipping-handle-field mdi mdi-cursor-move mdi-18px text-muted show-on-hover" style="cursor: pointer;"></i>
                        </td>
                        <td>
                            <?php if ($active_or_disabled == 'active'): ?>
                                <i class="mdi mdi-check text-success mdi-14px float-left mr-2"></i>
                            <?php else: ?>
                                <i class="mdi mdi-cancel text-danger mdi-14px float-left mr-2"></i>
                            <?php endif; ?>
                            <b><?php print $item['shipping_country'] ?></b>
                        </td>
                        <td>
                            <?php print mw()->format->titlelize($item['shipping_type']) ?>
                        </td>
                        <td>
                            <?php
                            if ($item['shipping_type'] == 'dimensions') {
                                print _e('from', true) . ' ' . mw()->shop_manager->currency_format($item['shipping_cost']);
                            } else {
                                print mw()->shop_manager->currency_format($item['shipping_cost']);
                            }
                            ?>
                        </td>

                        <td class="text-right">
                            <a class="btn btn-outline-primary btn-sm" href="javascript:mw_admin_edit_country_item_popup('<?php print $item['id'] ?>')"><?php _e("Edit"); ?></a>
                            <a href="javascript:;" onclick="mw.shipping_country.delete_country('<?php print $item['id']; ?>');" class="btn btn-link text-danger btn-sm px-0"><i class="mdi mdi-trash-can-outline"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr class="vertical-align-middle">
                    <td colspan="5" class="bg-grey font-weight-bold">
                        <?php _e("The list is empty"); ?>
                    </td>
                </tr>
            <?php endif; ?>
        </table>


        <?php
        /*            <hr>
                    deletem

                   <?php foreach ($data as $item): ?>
                        <div class="mw-ui-box mw-ui-settings-box box-enabled- mw-ui-box-content">
                            <span title="<?php _e("Reorder shipping countries"); ?>"
                                  class="mw-icon-drag shipping-handle-field"></span>
                            <span onclick="mw.shipping_country.delete_country('<?php print $item['id']; ?>');"
                                  class="mw-icon-close new-close tip" data-tip="<?php _e("Delete"); ?>"></span>

                            <b><?php print $item['shipping_country'] ?></b>
                            <a class="mw-ui-btn"
                               href="javascript:mw_admin_edit_country_item_popup('<?php print $item['id'] ?>')">Edit</a>


                        </div>
                    <?php endforeach; ?>


                    <?php foreach ($data as $item): ?>
                        <?php   include __DIR__ . "/item_edit.php"; ?>
                    <?php endforeach; ?>*/

        ?>
    </div>
</div>
