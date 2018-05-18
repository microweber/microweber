<?php only_admin_access(); ?>

<?php if (isset($params['order-id']) == true): ?>
    <?php
    $client = get_orders('one=1&id=' . intval($params['order-id']));
    $orders = get_orders('order_by=created_at desc&order_completed=1&email=' . $client['email']);
    ?>
    <script type="text/javascript">
        mw.require('forms.js');
    </script>
    <script type="text/javascript">


        mw.client_edit = {
            enable: function (e) {
                mw.$('.mw-client-information').removeClass('nonactive');
                mw.$('.mw-client-information input').eq(0).focus();
            },
            disable: function () {
                mw.$('.mw-client-information').addClass('nonactive');
            },
            save: function () {
                var URL = '<?php print api_link('shop/update_order') ?>';
                if (!mw.$('.mw-client-information').hasClass('nonactive')) {
                    var obj = mw.form.serialize('.mw-client-information');
                    $.post(URL, obj, function (data) {
                        mw.reload_module('<?php print $config['module'] ?>');
                    });
                }
            }
        }


        add_client_image = function (img) {
            mw.$("#client_image").val(img);
            mw.$(".mw-client-image-holder").html("<img src='" + img + "' />");
            mw.tools.modal.remove('mw_rte_image');
        }

        previewOrder = function (e) {
            if (mw.tools.hasClass(e.target, 'mw-ui-btn') || mw.tools.hasParentsWithClass(e.target, 'mw-ui-btn')) return false;
            mw.tools.accordion(mw.tools.firstParentWithClass(e.target, 'mw-ui-box'));

        }

    </script>

    <div class="mw-admin-wrap">

        <div class="section-header">
            <h2 class="inline-element m-r-10"><span class="mai-user3"></span><?php _e("Client Information"); ?></h2>

            <a class="mw-ui-btn mw-ui-btn-info mw-ui-btn-medium  btn-back" data-tip="Back to list" data-tipposition="bottom-center" href="<?php print admin_url(); ?>view:shop/action:clients"><span class="mw-icon-arrowleft"></span> Back to Clients</a>
        </div>


        <div class="admin-side-content">
            <div class="">
                <?php if (isset($client['created_by']) and $client['created_by'] > 0): ?>
                    <?php
                    $user_ord = get_user($client['created_by']);
                    ?>
                    <?php if (isset($user_ord['thumbnail']) and trim($user_ord['thumbnail']) != ''): ?>
                        <?php $userImg = thumbnail($user_ord['thumbnail'], 150, 150); ?>
                    <?php else: ?>
                        <?php $userImg = false; ?>
                    <?php endif; ?>
                <?php endif; ?>

                <input type="hidden" name="client_image" id="client_image"/>

                <script>
                    $(document).ready(function () {
                        $('.edit-client-info').on('click', function () {
                            $('.edit-client-save').show();
                        });
                    });
                </script>
                <div class='pull-right m-b-20'>
                    <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline edit-client-info" onclick="mw.client_edit.enable(this);"><span class="mw-icon-pen"></span><?php _e("Edit client information"); ?></button>

                    <button class="mw-ui-btn mw-ui-btn-info m-l-10 edit-client-save" style="display: none;" onclick="mw.client_edit.save();"><?php _e("Save"); ?></button>
                </div>
                <div>
                    <script>
                        responsivetableOrder = {
                            768: 4,
                            500: 2,
                            400: 1
                        }
                        mw.require('forms.js', true);


                        $(document).ready(function () {

                            mw.responsive.table('.mw-client-information', {
                                breakPoints: responsivetableOrder
                            })
                        });
                    </script>
                    <table border="0" cellpadding="0" cellspacing="0" class="mw-ui-table table-style-2 layout-auto table-clients mw-client-information nonactive " style="margin-bottom: 15px;">
                        <thead>
                        <tr>
                            <th style="width: 100px;"></th>
                            <th><?php _e("Names"); ?></th>
                            <th><?php _e("Email"); ?></th>
                            <th><?php _e("Phone"); ?></th>
                            <th><?php _e("Country"); ?></th>
                            <th><?php _e("City"); ?></th>
                            <th><?php _e("State"); ?></th>
                            <th><?php _e("Zip"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="last">
                            <td>
                                <?php if ($userImg): ?>
                                    <span class=" mw-user-thumb image" style="background-image: url('<?php print $userImg ?>');"></span>
                                <?php else: ?>
                                    <span class="mw-user-thumb  mai-user3"></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <input type="hidden" name="id" value="<?php print $client['id'] ?>"/>
                                <input class="mw-ui-field" type="text" name="first_name" value="<?php print $client['first_name'] ?>"/>
                                <input class="mw-ui-field" type="text" name="last_name" value="<?php print $client['last_name'] ?>" style="margin-top: 15px;"/>
                                <span class="val"><?php print $client['first_name'] ?></span> <span class="val"><?php print $client['last_name'] ?></span>
                            </td>
                            <td>
                                <input type="text" class="mw-ui-field" name="email" value="<?php print $client['email'] ?>"/>
                                <span class="val"><?php print $client['email'] ?></span></td>
                            <td><input type="text" class="mw-ui-field" name="phone" value="<?php print $client['phone'] ?>"/>
                                <span class="val"><?php print $client['phone'] ?></span></td>
                            <td><input type="text" class="mw-ui-field" name="country" value="<?php print $client['country'] ?>"/>
                                <span class="val"><?php print $client['country'] ?></span></td>
                            <td><input type="text" class="mw-ui-field" name="city" value="<?php print $client['city'] ?>"/>
                                <span class="val"><?php print $client['city'] ?></span></td>
                            <td><input type="text" class="mw-ui-field" name="state" value="<?php print $client['state'] ?>"/>
                                <span class="val"><?php print $client['state'] ?></span></td>
                            <td><input type="text" class="mw-ui-field" name="zip" value="<?php print $client['zip'] ?>"/>
                                <span class="val"><?php print $client['zip'] ?></span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table border="0" cellpadding="0" cellspacing="0" class="mw-ui-table table-style-2 layout-auto table-clients mw-client-information nonactive" style="margin-bottom: 15px;">
                        <thead>
                        <tr>
                            <th><?php _e("Address"); ?></th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><textarea class="mw-ui-field w100" name="address"><?php print $client['address'] ?></textarea><span class="val"><?php print $client['address'] ?></span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="m-t-40">
                <div>
                    <h4 class="pull-left m-0"><?php _e("Orders from"); ?><?php print $client['first_name'] ?><?php print $client['last_name'] ?></h4>
                    <div class="pull-right">
                        <button class="open-all-orders mw-ui-btn mw-ui-btn-info mw-ui-btn-small"><?php print _e('Open all'); ?></button>
                        <button class="close-all-orders mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-small"><?php print _e('Close all'); ?></button>
                    </div>

                    <script>
                        $(document).ready(function () {
                            $('.open-all-orders').on('click', function () {
                                $('.mw-ui-box.mw-ui-box-accordion.mw-accordion-active').each(function (index) {
                                    $(this).find('.mw-accordion-content').css({'display': 'block'});
                                });
                            });
                            $('.close-all-orders').on('click', function () {
                                $('.mw-ui-box.mw-ui-box-accordion.mw-accordion-active').each(function (index) {
                                    $(this).find('.mw-accordion-content').css({'display': 'none'});
                                });
                            });
                        });
                    </script>
                    <div class="clearfix"></div>
                </div>

                <br/>
                <?php if (is_array($orders)): ?>
                    <?php foreach ($orders as $item): ?>
                        <div class="mw-ui-box mw-ui-box-accordion mw-accordion-active" style="margin-bottom: 15px;">
                            <div class="mw-ui-box-header" onclick="previewOrder(event);"><span class="mw-icon-order"></span>

                                <h4 class="pull-left mw-blue"><?php _e("Order"); ?> #<?php print $item['id'] ?></h4>
                                <div class="pull-right show-on-hover">
                                    <span class="mw-ui-btn mw-ui-btn-info unselectable" onmousedown="mw.tools.accordion(mw.tools.firstParentWithClass(this, 'mw-ui-box'));"><?php _e("Preview Order"); ?></span>
                                    <a href="<?php print  admin_url() ?>view:shop/action:orders#vieworder=<?php print $item['id'] ?>" class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline unselectable"><span class="mw-icon-cart"></span><?php _e("Go to order"); ?></a>
                                </div>
                            </div>

                            <div class="mw-ui-box-content mw-accordion-content">
                                <?php $cart_items = get_cart('order_completed=any&order_id=' . $item['id'] . '&no_session_id=1'); ?>
                                <?php if (is_array($cart_items)): ?>
                                    <table cellspacing="0" cellpadding="0" class="mw-ui-table table-style-2 layout-auto table-clients" width="100%">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th><strong><?php _e("Product Name"); ?></strong></th>
                                            <th><strong><?php _e("Price"); ?></strong></th>
                                            <th><strong><?php _e("QTY"); ?></strong></th>
                                            <th><strong><?php _e("Total"); ?></strong></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($cart_items as $cart_item): ?>
                                            <tr class="mw-order-item mw-order-item-1">
                                                <td class="mw-order-item-image" width="35">
                                                    <?php $p = get_picture($cart_item['rel_id']); ?>
                                                    <?php if ($p != false): ?>
                                                        <span class="bgimg" style="width: 35px;height:35px;background-image:url(<?php print thumbnail($p, 120, 120); ?>);"></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="mw-order-item-id"><?php print $cart_item['title'] ?></td>
                                                <td class="mw-order-item-amount"><?php print $cart_item['price'] ?></td>
                                                <td class="mw-order-item-amount"><?php print $cart_item['qty'] ?></td>
                                                <td class="mw-order-item-count"><?php print currency_format($cart_item['price'] * $cart_item['qty'], $item['currency']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else : ?>
                                    <?php _e("Cannot get order's items"); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
<?php else : ?>
    <?php _e("Please set order-id parameter"); ?>
<?php endif; ?>