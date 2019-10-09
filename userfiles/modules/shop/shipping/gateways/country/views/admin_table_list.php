<script>
    //    $(document).ready(function () {
    //        $('.toggle-item', '#<?php //print $params['id'] ?>//' ).on('click', function (e) {
    //             alert(1);
    //            if ($(e.target).hasClass('toggle-item') || (e.target).nodeName == 'TD') {
    //                $(this).find('.hide-item').toggleClass('hidden');
    //                $(this).closest('.toggle-item').toggleClass('closed-fields');
    //            }
    //        });
    //    });
</script>

<script>
    function mw_admin_edit_country_item_popup(id) {
        if (id) {
            var modalTitle = '<?php _e('Edit country'); ?>';
        } else {
            var modalTitle = '<?php _e('Add country'); ?>';

        }

        mw_admin_edit_country_item_popup_modal_opened = mw.modal({
            content: '<div id="mw_admin_edit_country_item_module"></div>',
            title: modalTitle,
            id: 'mw_admin_edit_country_item_popup_modal'
        });


        var params = {}
        if (id) {
            params.edit_id = id
        }

        mw.load_module('shop/shipping/gateways/country/add_country', '#mw_admin_edit_country_item_module', null, params);
    }
</script>
<?php if (is_array($data) and !empty($data)): ?>
    <div class="mw-ui-row m-t-20">
        <?php if ($active_or_disabled == 'active'): ?>
            <p class="disabled-and-enabled-label mw-color-notification"><?php print _e('List of enabled countries'); ?></p>
        <?php else: ?>
            <p class="disabled-and-enabled-label mw-color-important"><?php print _e('List of disabled countries'); ?></p>
        <?php endif; ?>

        <div class="mw-shipping-items shipping_to_country_holder table-responsive" id="shipping_to_country_holder<?php if ($active_or_disabled == 'active'): ?>_active<?php endif; ?>">
            <table class="table-style-3 mw-ui-table layout-auto">
                <thead>
                <tr>
                    <th style="width: 15px"></th>
                    <th>Country</th>
                    <th>Shipping Type</th>
                    <th>Shipping Cost</th>
                    <th class="center" style="width: 200px;"><?php print _e('Actions'); ?></th>
                </tr>
                </thead>
                <?php foreach ($data as $item): ?>
                    <tr class="shipping-country-holder" data-field-id="<?php print $item['id']; ?>" id="shipping-table-list-item-id-<?php print $item['id']; ?>">
                        <td style="width: 15px">
                            <span title="<?php _e("Reorder shipping countries"); ?>" class="mw-icon-drag shipping-handle-field"></span>
                        </td>
                        <td>
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

                        <td class="action-buttons center">
                            <a class="mw-ui-btn mw-ui-btn-info mw-ui-btn-medium" href="javascript:mw_admin_edit_country_item_popup('<?php print $item['id'] ?>')"><?php _e("Edit"); ?></a>
                            &nbsp;
                            <a href="javascript:;" onclick="mw.shipping_country.delete_country('<?php print $item['id']; ?>');" class="mw-ui-btn mw-ui-btn-important mw-ui-btn-outline mw-ui-btn-medium"><?php _e("Delete"); ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
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
<?php endif; ?>
