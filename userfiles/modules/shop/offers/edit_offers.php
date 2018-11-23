<?php
$allOffers = offers_get_all();
?>
<div class="table-responsive">
    <table class="table-style-1 mw-ui-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Product Title</th>
            <th>Price Title</th>
            <th>Price</th>
            <th>Offer Price</th>
            <th>Expires</th>
            <th>Status</th>
            <th style="width:150px;">Action</th>
        </tr>
        </thead>
        <?php
        if ($allOffers):
            foreach ($allOffers as $offer):
                $class_product_deleted = ($offer['is_deleted'] == 1 ? 'class="mw-error"' : '');
                $class_status = ($offer['is_active'] == 1 ? 'status-active' : 'status-inactive');
                ?>
                <tr<?php if ($offer['is_active'] == 1): ?> class="js-table-active" <?php else: ?> class="js-table-inactive" <?php endif;
                print $class_product_deleted; ?>>
                    <td><?php print($offer['id']) ?></td>
                    <td><?php print($offer['product_title']) ?></td>
                    <td><?php print($offer['price_name']) ?></td>
                    <td><?php print currency_format($offer['price']) ?></td>
                    <td><?php print currency_format($offer['offer_price']) ?></td>
                    <td><?php if ($offer['expires_at']): ?><?php print date_system_format($offer['expires_at']) ?><?php else: ?>-<?php endif; ?></td>
                    <td class="<?php print $class_status; ?>"><?php print($offer['is_active'] == 1 ? 'on' : 'off') ?></td>
                    <td class="action-buttons">
                        <button onclick="editOffer(<?php print($offer['id']) ?>)"
                                class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline" title="Edit">
                            Edit
                        </button>
                        <button onclick="deleteOffer(<?php print($offer['id']) ?>)"
                                class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline" title="Delete">
                            Delete
                        </button>
                    </td>
                </tr>
                <?php
            endforeach;
        endif;
        ?>
    </table>
</div>