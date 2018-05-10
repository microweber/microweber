add order


<?php


$products = get_content('nolimit=1&content_type=product');

$data = $products;
//d($products );

?>
<script>
    mw_admin_custom_order_item_add = function($form){





mw.cart.add($form);




        mw.reload_module('shop/cart');
        mw.notification.success("Item added to cart");
    }
</script>


<script>







</script>
<div id="mw_admin_custom_order_item_add_form">
    <h2>Add custom item</h2>
    Product name
    <input type="text" name="title">
    <input type="hidden" name="for" value="custom_item">
    <input type="hidden" name="for_id" value="1">

    Price
    <input type="text" name="price">
    <button onclick="mw_admin_custom_order_item_add('#mw_admin_custom_order_item_add_form')">add</button>
</div>
<hr>
<?php if (is_array($data) and !empty($data)): ?>

    <h2>Add existing prodct</h2>
    
    Search product <input type="text" name="search" class="js-search-product-for-custom-order">
    <table class="mw-ui-table table-clients" width="100%" cellspacing="0" cellpadding="0">


        <tbody>


        <?php foreach ($data as $item): ?>

            <?php
            $pub_class = '';
            $append = '';
            if (isset($item['is_active']) and $item['is_active'] == '0') {
                $pub_class = ' content-unpublished';
                $append = '<div class="post-un-publish"><span class="mw-ui-btn mw-ui-btn-yellow disabled unpublished-status">' . _e("Unpublished", true) . '</span><span class="mw-ui-btn mw-ui-btn-green publish-btn" onclick="mw.post.set(' . $item['id'] . ', \'publish\');">' . _e("Publish", true) . '</span></div>';
            }

            ?>
            <?php $pic = get_picture($item['id']); ?>

            <tr>
                <td>

                    <a   class="text-center">
                                                                    <span class=" mw-user-thumb image" style="background-image: url('<?php print $pic ?>');">
                                </span>
                    </a>

                </td>
                <td> <h4><?php print content_title($item['id']) ?> <?php print $append; ?></h4></td>
                <td>

                    <module type="shop/cart_add" template="mw_default" content-id="<?php print ($item['id']) ?>" />

                </td>


            </tr>







        <?php endforeach; ?>





        </tbody>
    </table>




<?php endif; ?>
<hr>



<script>
    mw_admin_custom_checkout_callback = function(){
        $('#mw_admin_edit_order_item_popup_modal').remove();

        mw.reload_module('shop/orders/manage');
        mw.reload_module('shop/checkout');
        mw.reload_module('shop/cart');
        mw.notification.success("Order completed",5000);
    }
</script>



<module type="shop/cart" data-checkout-link-enabled="n" template="mw_default"  />
<module type="shop/checkout" data-checkout-link-enabled="n" template="admin_custom_order" id="mw-admin-custom-checkout-add-order"  />

<button class="btn btn-warning pull-right mw-checkout-btn"
        onclick="mw.cart.checkout('#mw-admin-custom-checkout-add-order', mw_admin_custom_checkout_callback);"
        type="button"
       >
    <?php _e("Complete order"); ?>
</button>



