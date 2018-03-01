<?php
only_admin_access();


?>


<div class="dashboard-recent">
    <div class="dr-head">
        <span class="drh-activity-name"><?php _e("Recent Orders") ?></span>
        <span class="mw-ui-btn mw-ui-btn-medium"><span class="mai-plus"></span>Add New order</span>
        <span class="mw-ui-btn mw-ui-btn-medium"><span class="mai-order"></span><strong>2</strong> New orders</span>
    </div>
    <div class="dr-list">
        <?php

        $orders = get_orders();

        ?>
        <?php
        if(is_array($orders)){
            foreach($orders as $order){

                $ord = mw()->shop_manager->get_order_by_id($order['id']);



                ?>
                <div class="dr-item">
                    <div class="dr-item-table">
                        <table>
                            <tr>
                                <td class="dr-item-image">
                                    <span style="background-image: url(<?php print pixum(100,100); ?>)"></span>
                                </td>
                                <td class="dr-item-id">
                                    #<span class="tip" data-tip="<?php _e("Order"); ?> #"><?php print $order['id'] ?></span>
                                </td>
                                <td class="dr-item-title">


                                    <?php

                                    $cart_items = mw()->shop_manager->order_items($order['id']);

                                    if($cart_items){

                                        foreach($cart_items as $cart_item){
                                            ?>

                                            <a href="javascript:;"><?php print($cart_item['title']); ?></a>

                                        <?php }
                                    }
                                    ?>
                                </td>
                                <td class="dr-item-price">
                                    <?php print  currency_format($order['amount'], $order['payment_currency']); ?>
                                </td>
                                <td class="dr-item-date  text-center">
                                    <span class="tip" data-tip="<?php print $order['created_at']; ?>"><?php print mw()->format->ago($order['created_at']); ?></span>
                                </td>
                                <td class="dr-item-status dr-item-status-<?php print $order['order_status']; ?>">
                                    <?php print $order['order_status']; ?>
                                </td>
                            </tr>
                        </table>
                        <div class="dr-item-data">
                            <div class="dr-item-data-head">
                                <a href="<?php print admin_url() ?>view:shop/action:orders#vieworder=<?php print $order['id'] ?>" class="mw-ui-abtn"><span class="mai-eye"></span><?php _e("View order") ?></a>
                                <a href="javascript:;" class="mw-ui-abtn"><span class="mai-edit"></span><?php _e("Edit") ?></a>
                                <a href="<?php print admin_url() ?>view:shop/action:clients#?clientorder=<?php print $order['id'] ?>" class="mw-ui-abtn"><span class="mai-user"></span><?php _e("View client") ?></a>

                            </div>
                            <div class="dr-item-data-content">
                                <div class="dr-item-data-content-list">
                                    <h3><?php _e("Customer Information") ?></h3>
                                    <dl>
                                        <dt><?php _e("Client") ?></dt>
                                        <dd><?php print $order['first_name'] . ' ' . $order['last_name'] ?></dd>
                                        <dt><?php _e("Phone") ?></dt>
                                        <dd><?php print $order['phone']; ?></dd>
                                        <dt><?php _e("E-mail") ?></dt>
                                        <dd><?php print $order['email']; ?></dd>
                                    </dl>
                                </div>

                                <div class="dr-item-data-content-list">
                                    <h3><?php _e("Shipping Information") ?></h3>
                                    <dl>

                                        <dt><?php _e("Address") ?></dt>
                                        <dd><?php print $order['address']; ?><?php print isset($order['address2']) ? ('<br>' . $order['address2']) : ''; ?></dd>
                                        <?php if(isset($order['other_info'])){ ?>
                                            <dt><?php _e("Comment") ?></dt>
                                            <dd><?php print $order['other_info']; ?></dd>
                                        <?php } ?>
                                    </dl>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            <?php }} ?>

    </div>
</div>
