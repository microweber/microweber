<?php if (isset($orders) and is_array($orders)): ?>

<div class="row">

    <module type="users/sidebar" />

    <?php foreach ($orders as $order): ?>
        <?php $cart = get_cart('order_id=' . $order['id']); ?>
        <?php if (is_array($cart) and !empty($cart)): ?>
            <div class="col-md-12">
                <?php if ($order['order_status'] == 'completed') { ?>
                    <span class="text-green"><?php _e("Completed");?></span>
                <?php } else { ?>
                    <span class="text-red"><?php _e("Pending");?></span>
                <?php } ?>

                <h4><?php _e("Order");?> #<?php print $order['id']; ?> -
                    <small><?php _e("created on");?> <?php print $order['created_at']; ?></small>
                </h4>

                <a href="<?php echo site_url('users/orders/view_order'); ?>?id=<?php echo $order['id']; ?>"><?php _e("View order");?></a>

                <hr class="m-b-0"/>

                <table cellspacing="0" cellpadding="0" class="table table-responsive">
                    <thead>
                    <tr>
                        <th><?php _e("Image");?></th>
                        <th><?php _e("Title");?></th>
                        <th><?php _e("Quantity");?></th>
                        <th><?php _e("Price");?></th>
                        <th><?php _e("Total");?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cart as $product) { ?>
                        <?php $theproduct = get_content_by_id($product['rel_id']); ?>
                        <?php if ($theproduct): ?>
                            <tr>
                                <td>
                                    <img src="<?php print get_picture($theproduct['id']); ?>" width="70" alt=""/>
                                </td>
                                <td><?php print $theproduct['title']; ?></td>
                                <td><?php print $product['qty']; ?></td>
                                <td><?php print $product['price']; ?></td>
                                <td><?php print (intval($product['qty']) * intval($product['price'])); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <?php endforeach; ?>

</div>
<?php else: ?>
    <div>
        <h3><?php _e("You have no orders");?></h3>
    </div>
<?php endif; ?>
