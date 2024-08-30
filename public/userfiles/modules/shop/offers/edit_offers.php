<?php
//WAS $allOffers = offers_get_all();
$allOffers = app()->offer_repository->getAll();
?>
<div class="table-responsive offers-table">
    <table class="table ">
        <thead>
        <tr>
            <th>#</th>
            <th><?php _e('Image'); ?></th>
            <th><?php _e('Product'); ?></th>
            <th><?php _e('Label'); ?></th>
            <th><?php _e('Price'); ?></th>
            <th><?php _e('Offer'); ?></th>
            <th><?php _e('Expire at'); ?></th>
            <th><?php _e('Status'); ?></th>
            <th class="text-center" style="width:200px;"><?php _e('Action'); ?></th>
        </tr>
        </thead>
        <?php
        if ($allOffers):
            foreach ($allOffers as $offer):
                $class_product_deleted = ($offer['is_deleted'] == 1 ? 'class="mw-error"' : '');
                ?>
                <tr class=" td-valign <?php if ($offer['is_active'] == 1): ?>js-table-active<?php else: ?>js-table-inactive<?php endif; ?> <?php print $class_product_deleted; ?> ">
                    <td><?php print($offer['id']) ?></td>
                    <td class="min-width-100">
                        <span class="avatar avatar-md me-2 shadow-none" style="background-image: url('<?php print  get_picture($offer['product_id']) ?>')"></span>
                    </td>
                    <td style="width: 250px;">
                        <a href="<?php print  content_link($offer['product_id']) ?>" target="_blank"><?php print($offer['product_title']) ?></a>
                    </td>
                    <td><?php print($offer['price_name']) ?></td>
                    <td class="min-width-100"><?php print currency_format($offer['price']) ?></td>
                    <td class="min-width-100"><?php print currency_format($offer['offer_price']) ?></td>
                    <td class="min-width-100"><?php if ($offer['expires_at'] and $offer['expires_at'] != '0000-00-00 00:00:00' ): ?><?php print date_system_format($offer['expires_at']) ?><?php else: ?>-<?php endif; ?></td>


                    <?php
                    $expired = false;
                    $class_status = ($offer['is_active'] == 1 ? 'status-active text-center' : 'status-inactive text-center');
                    $class_status_badge = ($offer['is_active'] == 1 ? 'badge bg-success' : 'badge bg-warning');

                    if ($offer['expires_at'] and $offer['expires_at'] != '0000-00-00 00:00:00' ) {
                        if (now() > $offer['expires_at']) {
                            $class_status = 'status-inactive text-center';
                            $class_status_badge = 'badge bg-warning';
                            $expired = true;
                        }
                    }
                    ?>

                    <td class="<?php print $class_status; ?>">

                        <span class="<?php print $class_status_badge; ?>"></span>

                        <?php if ($expired): ?>
                        <span class="d-xxl-inline-block d-none">
                            <?php _e('Expired'); ?>
                          </span>

                        <?php else: ?>

                          <span class="d-xxl-inline-block d-none">
                               <?php print($offer['is_active'] == 1 ? 'Active' : 'Inactive') ?>
                          </span>
                        <?php endif; ?>



                    </td>


                    <td class="action-buttons text-end min-width-100">
                        <div class="d-xxl-flex d-none d-flex justify-content-center align-items-center">
                            <button onclick="editOffer(<?php print($offer['id']) ?>)" class="btn btn-outline-primary btn-sm mx-1" title="Edit"><?php _e('Edit'); ?></button>
                            <button onclick="deleteOffer(<?php print($offer['id']) ?>)" class="btn btn-outline-danger btn-sm mx-1" title="Delete"><?php _e('Delete'); ?></button>
                        </div>
<!--                        <a href="--><?php //echo content_link($offer['product_id']) ?><!--" target="_new" class="btn btn-primary btn-sm" title="View">--><?php //_e('View'); ?><!--</a>-->
                        <div class="d-xxl-none d-flex justify-content-center align-items-center">
                            <button onclick="editOffer(<?php print($offer['id']) ?>)" class="btn btn-outline-primary btn-sm mx-1" title="Edit">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" width="16"><path d="M200 856h56l345-345-56-56-345 345v56Zm572-403L602 285l56-56q23-23 56.5-23t56.5 23l56 56q23 23 24 55.5T829 396l-57 57Zm-58 59L290 936H120V766l424-424 170 170Zm-141-29-28-28 56 56-28-28Z"/></svg>
                            </button>

                            <button onclick="deleteOffer(<?php print($offer['id']) ?>)" class="btn btn-outline-danger btn-sm mx-1" title="Delete">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 96 960 960" width="16"><path d="M280 936q-33 0-56.5-23.5T200 856V336h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680 936H280Zm400-600H280v520h400V336ZM360 776h80V416h-80v360Zm160 0h80V416h-80v360ZM280 336v520-520Z"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php
            endforeach;
        endif;
        ?>
    </table>
</div>
