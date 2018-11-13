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

<?php if (is_array($data) and !empty($data)): ?>
    <div class="mw-ui-row m-t-20">
        <?php if ($active_or_disabled == 'active'): ?>
            <p class="disabled-and-enabled-label"><?php print _e('List of enabled countries'); ?></p>
        <?php else: ?>
            <p class="disabled-and-enabled-label"><?php print _e('List of disabled countries'); ?></p>
        <?php endif; ?>

        <div class="mw-shipping-items shipping_to_country_holder"
             id="shipping_to_country_holder<?php if ($active_or_disabled == 'active'): ?>_active<?php endif; ?>">
            <table style="width: 100%" class="mw-ui-table">
                <thead>
                <tr>
                    <th  style="width: 15px"></th>
                    <th>Country</th>
                    <th>Shipping Type</th>
                    <th>Shipping Cost</th>
                    <th>Edit</th>
                    <th></th>
                </tr>
                </thead>
                <?php foreach ($data as $item): ?>

                    <tr class="mw-ui-box mw-ui-box-content shipping-country-holder" data-field-id="<?php print $item['id']; ?>">
                        <td style="width: 15px">



                            <span title="<?php _e("Reorder shipping countries"); ?>"
                                  class="mw-icon-drag shipping-handle-field"></span>
                        </td>
                        <td>
                            <b><?php print $item['shipping_country'] ?></b>
                        </td>
                        <td>
                        <?php print mw()->format->titlelize($item['shipping_type']) ?>
                        </td>
                        <td>


                             <?php





                             if($item['shipping_type'] == 'dimensions'){
                                 print _e('from',true).' '. mw()->shop_manager->currency_format($item['shipping_cost'])  ;

                             } else {
                                 print mw()->shop_manager->currency_format($item['shipping_cost']) ;

                             }


                             ?>



                        </td>
                        <td>
                            <a class="mw-ui-btn"
                               href="javascript:mw_admin_edit_country_item_popup('<?php print $item['id'] ?>')">Edit</a>
                        </td>
                        <td  >
                            <span onclick="mw.shipping_country.delete_country('<?php print $item['id']; ?>');"
                                  class="mw-icon-close tip" data-tip="<?php _e("Delete"); ?>"></span>

                        </td>
                    </tr>


                <?php endforeach; ?>

            </table>

          <?php

          /*  <?php foreach ($data as $item): ?>
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
                <?php // include __DIR__ . "/item_edit.php"; ?>
            <?php endforeach; ?>*/

          ?>
        </div>
    </div>
<?php endif; ?>
