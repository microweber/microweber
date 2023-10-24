@extends('admin::layouts.app')

@section('content')

    <script>
         mw_delete_shop_order  = function(pid, iscart, e){
            if(e)e.preventDefault();
            var iscart = iscart || false;

            var r = confirm("<?php _ejs("Are you sure you want to delete this order"); ?>?");
            if (r == true) {
                $('.cart-sid-'+pid).fadeOut();
                $.post("<?php print api_url('delete_order') ?>", { id: pid,is_cart:iscart}, function(data) {
                    mw.reload_module('shop/orders');
                    mw.reload_module('shop/orders/admin');
                });

                return true;

            }
        }
    </script>

<div class="col-xxl-10 col-lg-11 col-12 mx-auto px-lg-0 px-3">
    <div class="card">
        <div class="card-body">
            <div class="row p-0">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter abandoned-cart mb-5 cart-sid-<?php print ($order['session_id']) ?>" id="abandoned-cart-table<?php print $order['id'] ?>" cellpadding="0" cellspacing="0">
                        <script>
                            $(document).ready(function () {
                                $("#abandoned-cart-table<?php print $order['id'] ?> .mw-order-item-image").bind("mouseenter mouseleave", function (e) {
                                    var index = $(this).dataset('index');
                                    mw.tools.multihover(e, this, "#abandoned-cart-table<?php print $order['id'] ?> .mw-order-item-index-" + index);
                                });
                                $("#abandoned-cart-table<?php print $order['id'] ?> tr.mw-order-item").bind("mouseenter mouseleave", function (e) {
                                    var index = $(this).dataset('index');
                                    mw.tools.multihover(e, this, "#abandoned-cart-table<?php print $order['id'] ?> .mw-order-item-image-" + index);
                                });
                            });

                            mw.responsive.table('#shop-orders', {
                                breakPoints: {
                                    768: 4,
                                    600: 2,
                                    400: 1
                                }
                            })

                        </script>

                        <thead class="font-weight-bold" style="border-color: transparent;">
                        <tr>
                            <th> <span class="font-weight-bold form-label tblr-body-color"> <?php _e("Abandoned Cart ID"); ?>: <?php print $order['id']; ?> </span></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="mw-order-item-<?php print $order['id'] ?> no-hover"  >
                            <td class="px-0 py-3 border-0">
                                <?php
                                $recart_base = site_url();
                                ?>
                                <?php $cart_items = get_cart('order_completed=0&session_id=' . $order['session_id']); ?>
                                <?php if (is_array($cart_items) and !empty($cart_items)) : ?>
                                    <?php
                                    if (is_array($cart_items[0]) and isset($cart_items[0]['rel_id'])) {
                                        $recart_base = content_link($cart_items[0]['rel_id']);
                                    }
                                    ?>
                                <div class="mw-order-images">
                                        <?php for ($i = 0;
                                                   $i < sizeof($cart_items);
                                                   $i++) { ?>
                                        <?php $p = get_picture($cart_items[$i]['rel_id']); ?>
                                        <?php if ($p != false): ?>
                                    <span data-index="<?php print $i; ?>" class="bgimg mw-order-item-image mw-order-item-image-<?php print $i; ?>" style="width:70px;height:70px;background-image: url(<?php print thumbnail($p, 120, 120); ?>);"></span>
                                    <?php endif; ?>
                                    <?php } ?>
                                </div>
                                <table class="table m-0" cellspacing="0" cellpadding="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th class="w-50"><?php _e("Product Name"); ?></th>
                                        <th><?php _e("Custom fields"); ?></th>
                                        <th><?php _e("Price"); ?></th>
                                        <th><?php _e("Qty"); ?></th>
                                        <th><?php _e("Total"); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php $subtotal = 0; ?>
                                        <?php $index = -1;
                                    foreach ($cart_items as $item) : ?>
                                        <?php
                                        $index++;
                                        $item_total = floatval($item['qty']) * floatval($item['price']);
                                        $subtotal = $subtotal + $item_total;
                                        $grandtotal = $subtotal;
                                        ?>
                                    <tr data-index="<?php print $index; ?>" class="mw-order-item mw-order-item-<?php print $item['id'] ?> mw-order-item-index-<?php print $index; ?>">
                                        <td class="mw-order-item-id"><a class="tblr-body-color" href="<?php print content_link($item['rel_id']) ?>" target="_blank"><span><?php print $item['title'] ?></span></a></td>
                                        <td class="mw-order-item-fields text-muted"><small><?php if (isset($item['custom_fields'])): ?><?php print $item['custom_fields'] ?><?php endif ?></small></td>
                                        <td class="mw-order-item-amount"><?php print ($item['price']) ?></td>
                                        <td class="mw-order-item-count"><?php print $item['qty'] ?></td>
                                        <td class="nowrap"><?php print currency_format($item_total); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                <h5><?php _e("The cart is empty"); ?></h5>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <td class="d-flex align-items-center flex-wrap border-0">
                            <?php // event_trigger('mw_admin_quick_stats_by_session', $item['session_id']); ?>
                            <div class="col">
                                <label class="form-label d-block">
                                    <?php _e("Recover URL"); ?>
                                </label>
                                <small class="text-muted" onclick="mw.wysiwyg.select_all(this);"><?php print $recart_base . '?recart=' . $order['session_id']; ?></small>
                            </div>
                            <label class="form-label col align-self-center">
                                <span style="font-size: 16px;top:-1px;right:2px;"></span>
                                <span class="mw-ui-label-small tip" data-tipposition="top-center" data-tip="<?php _e("Last activity on"); ?>: <?php print $order['updated_at'] ?>"><?php print mw('format')->ago($order['updated_at']); ?></span>
                            </label>
                            <div class="text-lg-center col-lg col-12 mt-lg-0 mt-3 align-self-center">
                                <button class="btn btn-danger btn-sm" onclick="mw_delete_shop_order('<?php print ($order['session_id']) ?>',1);"><?php _e("Delete cart"); ?></button>
                                <a class="btn btn-outline-secondary btn-sm" href="<?php print $recart_base . '?recart=' . $order['session_id'] ?>" target="_blank"><?php _e("Recover"); ?></a>
                            </div>
                        </td>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
