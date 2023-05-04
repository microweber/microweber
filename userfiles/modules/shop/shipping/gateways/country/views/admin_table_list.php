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
        // mw.load_module('shop/shipping/gateways/country/add_country', '#mw_admin_edit_country_item_module', null, params);


        var modal_id = 'mw_admin_edit_country_item_popup_modal_opened';
        var dialog = mw.top().dialogIframe({
             url: route('live_edit.module_settings') + '?type=shop/shipping/gateways/country/add_country&live_edit=true&id=mw_admin_edit_country_item_popup_modal_opened__module&edit_id='+id+'&from_url=<?php print site_url() ?>',
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
                $.post("<?php print $config['module_api']; ?>/shipping_to_country/delete",  obj, function(data){
                    mw.$(".country-id-"+id).fadeOut();
                    mw.reload_module_everywhere('[data-parent-module="shop/shipping"]');
                    mw.reload_module_everywhere('shop/shipping/gateways/country/admin');
                    if(window.parent != undefined && window.parent.mw != undefined){
                        mw.reload_module_everywhere('shop/shipping/gateways/country');
                    }
                });
            });
        },
        add_country:function(){

        }
    }
</script>


<div class="">
    <div class="d-flex justify-content-between align-items-end flex-wrap">
        <div class="col-md-8">
            <div class="form-group mb-0 mt-3">
                <?php if ($active_or_disabled == 'active'): ?>
                    <label class="form-label"><?php _e('Allowed countries for shipping'); ?></label>
                    <small class="text-muted d-block mb-0"> <?php _e('List of countries to which shipping is performed'); ?></small>
                <?php else: ?>
                    <label class="form-label"><?php _e('Denied countries for shipping'); ?></label>
                    <small class="text-muted d-block mb-0"><?php _e('List of countries where deliveries are not allowed'); ?></small>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-4 text-end text-right">
            <a class="btn btn-primary btn-sm" href="javascript:mw_admin_edit_country_item_popup();"><?php _e("Add country"); ?></a>
        </div>
    </div>

    <div class="mw-shipping-items shipping_to_country_holder table-responsive mt-3" id="shipping_to_country_holder<?php if ($active_or_disabled == 'active'): ?>_active<?php endif; ?>">



                <?php if (is_array($data) and !empty($data)): ?>
                    <table class="table small">

                        <thead class="<?php if ($active_or_disabled == 'active'): ?>table-success<?php else: ?>table-danger<?php endif; ?>">
                        <tr>
                            <th style="width: 10px; padding-right: 0;"></th>
                            <th><?php if ($active_or_disabled == 'active'): ?><?php _e('Allowed'); ?><?php else: ?><?php _e('Denied'); ?><?php endif; ?> <?php _e('Country'); ?></th>
                            <th><?php _e('Shipping Type'); ?></th>
                            <th><?php _e('Shipping Cost'); ?></th>
                            <th class="text-end text-right" style="width: 200px;"><?php _e('Actions'); ?></th>
                        </tr>
                        </thead>
                    <?php foreach ($data as $item): ?>

                    <tr class="shipping-country-holder vertical-align-middle show-on-hover-root" data-field-id="<?php print $item['id']; ?>" id="shipping-table-list-item-id-<?php print $item['id']; ?>">
                        <td style="width: 10px; padding-right: 0;">
                            <svg class="shipping-handle-field mdi-cursor-move ui-sortable-handle" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M360 896q-33 0-56.5-23.5T280 816q0-33 23.5-56.5T360 736q33 0 56.5 23.5T440 816q0 33-23.5 56.5T360 896Zm240 0q-33 0-56.5-23.5T520 816q0-33 23.5-56.5T600 736q33 0 56.5 23.5T680 816q0 33-23.5 56.5T600 896ZM360 656q-33 0-56.5-23.5T280 576q0-33 23.5-56.5T360 496q33 0 56.5 23.5T440 576q0 33-23.5 56.5T360 656Zm240 0q-33 0-56.5-23.5T520 576q0-33 23.5-56.5T600 496q33 0 56.5 23.5T680 576q0 33-23.5 56.5T600 656ZM360 416q-33 0-56.5-23.5T280 336q0-33 23.5-56.5T360 256q33 0 56.5 23.5T440 336q0 33-23.5 56.5T360 416Zm240 0q-33 0-56.5-23.5T520 336q0-33 23.5-56.5T600 256q33 0 56.5 23.5T680 336q0 33-23.5 56.5T600 416Z"></path></svg>
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

                        <td class="text-end text-right">
                            <a class="btn btn-outline-primary btn-sm me-2" href="javascript:mw_admin_edit_country_item_popup('<?php print $item['id'] ?>')"><?php _e("Edit"); ?></a>
                            <a href="javascript:;" onclick="mw.shipping_country.delete_country('<?php print $item['id']; ?>');" class="btn btn-link text-danger btn-sm px-0">
                                <svg class="trash-svg-icon" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" width="20"><path d="M280 936q-33 0-56.5-23.5T200 856V336h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680 936H280Zm400-600H280v520h400V336ZM360 776h80V416h-80v360Zm160 0h80V416h-80v360ZM280 336v520-520Z"/></svg>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>

                <?php else: ?>

                            <?php _e("The list is empty"); ?>

                <?php endif; ?>


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
                            <a class="  btn btn-primary"
                               href="javascript:mw_admin_edit_country_item_popup('<?php print $item['id'] ?>')">Edit</a>


                        </div>
                    <?php endforeach; ?>


                    <?php foreach ($data as $item): ?>
                        <?php   include __DIR__ . "/item_edit.php"; ?>
                    <?php endforeach; ?>*/

        ?>
    </div>
</div>
