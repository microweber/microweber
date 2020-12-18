<?php if (isset($orders) and is_array($orders)): ?>
    <h3 class="m-b-20">My Orders</h3>

    <?php foreach ($orders as $order) { ?>
        <?php $cart = get_cart('order_id=' . $order['id']); ?>
        <?php if (is_array($cart) and !empty($cart)): ?>
            <div class="mw-ui-box mw-ui-box-content my-order">
                <div class="my-order-status pull-right">
                    <?php if ($order['order_status'] == 'completed') { ?>
                        <span class="my-order-status-completed text-green">Completed</span>
                    <?php } else { ?>
                        <span class="my-order-status-pending text-red">Pending</span>
                    <?php } ?>
                </div>

                <h4>Order #<?php print $order['id']; ?> - <small>created on <?php print $order['created_at']; ?></small></h4>

                <hr class="m-b-0"/>
                <table width="100%" cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cart as $product) { ?>
                        <?php $theproduct = get_content_by_id($product['rel_id']); ?>
                        <tr>
                            <td><img src="<?php print get_picture($theproduct['id']); ?>" width="70" alt=""/></td>
                            <td><?php print $theproduct['title']; ?></td>
                            <td><?php print $product['qty']; ?></td>
                            <td><?php print $product['price']; ?></td>
                            <td><?php print (intval($product['qty']) * intval($product['price'])); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <br/>
        <?php endif; ?>
    <?php } ?>
<?php else: ?>
    <div>
        <h3>You have no orders</h3>
    </div>
<?php endif; ?>