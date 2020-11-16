<?php
$allOffers = offers_get_all();
?>
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Label</th>
            <th>Price</th>
            <th>Offer</th>
            <th>Expire at</th>
            <th>Status</th>
            <th class="text-center" style="width:200px;">Action</th>
        </tr>
        </thead>
        <?php
        if ($allOffers):
            foreach ($allOffers as $offer):
                $class_product_deleted = ($offer['is_deleted'] == 1 ? 'class="mw-error"' : '');
                $class_status = ($offer['is_active'] == 1 ? 'status-active' : 'status-inactive');
                ?>
                <tr class="small td-valign <?php if ($offer['is_active'] == 1): ?>js-table-active<?php else: ?>js-table-inactive<?php endif; ?> <?php print $class_product_deleted; ?> ">
                    <td><?php print($offer['id']) ?></td>
                    <td><?php print($offer['product_title']) ?></td>
                    <td><?php print($offer['price_name']) ?></td>
                    <td><?php print currency_format($offer['price']) ?></td>
                    <td><?php print currency_format($offer['offer_price']) ?></td>
                    <td><?php if ($offer['expires_at']): ?><?php print date_system_format($offer['expires_at']) ?><?php else: ?>-<?php endif; ?></td>
                    <td class="<?php print $class_status; ?>"><?php print($offer['is_active'] == 1 ? 'Active' : 'Inactive') ?></td>
                    <td class="action-buttons">
                        <button onclick="editOffer(<?php print($offer['id']) ?>)" class="btn btn-outline-primary btn-sm" title="Edit">Edit</button>
                        <button onclick="deleteOffer(<?php print($offer['id']) ?>)" class="btn btn-outline-danger btn-sm" title="Delete">Delete</button>
                        <a href="<?php echo content_link($offer['product_id']) ?>" target="_new" class="btn btn-primary btn-sm" title="View">View</a>
                    </td>
                </tr>
                <?php
            endforeach;
        endif;
        ?>
    </table>
</div>