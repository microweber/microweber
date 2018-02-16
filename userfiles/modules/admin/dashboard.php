<?php event_trigger('mw.admin.dashboard.start'); ?>
<div class="mw-ui-col-container" style="padding-left: 35px;">
    <?php event_trigger('mw.admin.dashboard.content'); ?>



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
                                    foreach($cart_items as $cart_item){
                                ?>

                                <a href="javascript:;"><?php print($cart_item['title']); ?></a>

                                <?php } ?>
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
            <?php } ?>

        </div>
    </div>


    <?php
        $comments_data = array(
            'order_by'=>'created_at desc'
        );
        $comments = get_comments($comments_data);

         if(is_array($comments )){


            $ccount = count($comments);

        }
        else{
            $ccount = 0;
        }
    ?>
    <div class="dashboard-recent">
        <div class="dr-head">
            <span class="drh-activity-name"><?php _e("Latest activity") ?></span>
            <a class="mw-ui-btn mw-ui-btn-medium"><span class="mai-post"></span><?php _e("Go to posts"); ?></a>
            <span class="mw-ui-btn mw-ui-btn-medium"><span class="mai-comment"></span><?php print $ccount; ?> <?php _e("new comments") ?></span>

        </div>
        <div class="dr-list">


            <?php


            if(is_array($comments )){




            foreach($comments as $comment){

            $params = array(
                'id' => $comment['id']
            );

            $post = get_content($params);
            $post = $post[0];

            $comments_data = array(
                'order_by'=>'created_at desc',
                'rel_id'=>$post['id']
            );
            $postComments =  get_comments($comments_data);



            ?>

            <?php

            var_dump($postComments); ?>
            <div class="dr-item">
                <div class="dr-item-table">
                    <table>
                        <tr>
                            <td class="dr-item-image">
                                <span style="background-image: url(<?php print pixum(100,100); ?>)"></span>
                            </td>

                            <td class="dr-item-title">
                                <?php print $post['title']; ?>
                            </td>
                            <td class="dr-item-price">
                               33 <span class="mai-comment"></span> comments
                            </td>
                            <td class="dr-item-date">
                                10 days ago
                            </td>

                        </tr>
                    </table>
                </div>
            </div>
            <?php }    }  ?>

        </div>
    </div>



    <div class="mw-ui-box quick-lists pull-left">
        <div class="mw-ui-box-header">
            <?php _e("Quick Links"); ?>
        </div>

        <div class="mw-ui-box-content">
            <div class="mw-ui-row" id="quick-links-row">
                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <div class="mw-ui-navigation">
                            <?php event_trigger('mw.admin.dashboard.links'); ?>
                            <?php $dash_menu = mw()->ui->module('admin.dashboard.menu'); ?>
                            <?php if (!empty($dash_menu)): ?>
                                <?php foreach ($dash_menu as $item): ?>
                                    <?php $view = (isset($item['view']) ? $item['view'] : false); ?>
                                    <?php $link = (isset($item['link']) ? $item['link'] : false); ?>
                                    <?php if ($view==false and $link!=false){
                                        $btnurl = $link;
                                    } else {
                                        $btnurl = admin_url('view:') . $item['view'];
                                    } ?>
                                    <?php $icon = (isset($item['icon_class']) ? $item['icon_class'] : false); ?>
                                    <?php $text = $item['text']; ?>
                                    <a href="<?php print $btnurl; ?>"><span
                                            class="<?php print $icon; ?>"></span><span><?php print $text; ?></span></a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <div class="mw-ui-navigation">
                            <?php $dash_menu = mw()->ui->module('admin.dashboard.menu.second'); ?>
                            <?php if (!empty($dash_menu)): ?>
                                <?php foreach ($dash_menu as $item): ?>
                                    <?php $view = (isset($item['view']) ? $item['view'] : false); ?>
                                    <?php $link = (isset($item['link']) ? $item['link'] : false); ?>
                                    <?php if ($view==false and $link!=false){
                                        $btnurl = $link;
                                    } else {
                                        $btnurl = admin_url('view:') . $item['view'];
                                    } ?>
                                    <?php $icon = (isset($item['icon_class']) ? $item['icon_class'] : false); ?>
                                    <?php $text = $item['text']; ?>
                                    <a href="<?php print $btnurl; ?>"><span
                                            class="<?php print $icon; ?>"></span><span><?php print $text; ?></span></a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php event_trigger('mw.admin.dashboard.links2'); ?>
                            <?php event_trigger('mw.admin.dashboard.help'); ?>
                        </div>
                    </div>
                </div>



                    <div class="mw-ui-col">
                        <div class="mw-ui-col-container">
                            <?php if (mw()->ui->enable_service_links): ?>
                            <div class="mw-ui-navigation">
                                <?php $dash_menu = mw()->ui->module('admin.dashboard.menu.third'); ?>
                                <?php if (!empty($dash_menu)): ?>
                                    <?php foreach ($dash_menu as $item): ?>
                                        <?php $view = (isset($item['view']) ? $item['view'] : false); ?>
                                        <?php $link = (isset($item['link']) ? $item['link'] : false); ?>
                                        <?php if ($view==false and $link!=false){
                                            $btnurl = $link;
                                        } else {
                                            $btnurl = admin_url('view:') . $item['view'];
                                        } ?>
                                        <?php $icon = (isset($item['icon_class']) ? $item['icon_class'] : false); ?>
                                        <?php $text = $item['text']; ?>
                                        <a href="<?php print $btnurl; ?>"><span
                                                class="<?php print $icon; ?>"></span><span><?php print $text; ?></span></a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php event_trigger('mw.admin.dashboard.links3'); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>




            </div>
        </div>


    </div>
    <?php event_trigger('mw.admin.dashboard.main'); ?>
</div>