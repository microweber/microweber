<?php
$products = get_content('nolimit=1&content_type=product');

$data = $products;
//d($products );

?>
<script>
    mw_admin_custom_order_item_add = function ($form) {
        mw.cart.add($form);
        mw.reload_module('shop/cart');
        mw.notification.success("Item added to cart");
    }
</script>

<div class="demobox" id="mw-add-order">
    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
        <a href="javascript:;" class="mw-ui-btn active">Add to cart</a>
        <a href="javascript:;" class="mw-ui-btn">My cart's content</a>
        <a href="javascript:;" class="mw-ui-btn">Checkout</a>
    </div>
    <div class="mw-ui-box">
        <!-- Add to cart -->
        <div class="mw-ui-box-content">
            <div id="mw_admin_custom_order_item_add_form" class="m-b-10">
                <h2>Add custom item</h2>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">Product name</label>
                    <input type="text" name="title" class="mw-ui-field element-block" required="required">
                    <input type="hidden" name="for" value="custom_item">
                    <input type="hidden" name="for_id" value="1">
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label">Price</label>
                    <input type="text" name="price" class="mw-ui-field element-block" required="required" placeholder="Example: 10"/>
                </div>

                <div class="right">
                    <button class="mw-ui-btn mw-ui-btn-info" onclick="mw_admin_custom_order_item_add('#mw_admin_custom_order_item_add_form')"><i class="mai-plus"></i> add</button>
                </div>
            </div>

            <hr>
            <?php if (is_array($data) and !empty($data)): ?>
                <h2>Add existing prodct</h2>

                <div class="mw-ui-field-holder">
                    <input type="text" name="search" class="mw-ui-field element-block js-search-product-for-custom-order" placeholder="Search product in catalog" required="required">
                </div>

                <div class="table-responsive">
                    <table class="mw-ui-table table-style-2 layout-auto" width="100%" cellspacing="0" cellpadding="0">
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
                                <td><a class="text-center"><span class="mw-user-thumb image" style="background-image: url('<?php print $pic ?>');"></span></a></td>
                                <td><?php print content_title($item['id']) ?><?php print $append; ?></td>
                                <td>
                                    <module type="shop/cart_add" template="mw_default" content-id="<?php print ($item['id']) ?>"/>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- My cart's content -->
        <div class="mw-ui-box-content" style="display: none;">
            <module type="shop/cart" data-checkout-link-enabled="n" template="mw_default"/>
        </div>

        <!-- Checkout -->
        <div class="mw-ui-box-content" style="display: none;">
            <module type="shop/checkout" data-checkout-link-enabled="n" template="mw_default" id="mw-admin-custom-checkout-add-order"/>
        </div>
    </div>


    <button class="mw-ui-btn mw-ui-btn-notification m-t-20 pull-right" onclick="mw.cart.checkout('#mw-admin-custom-checkout-add-order', mw_admin_custom_checkout_callback);" type="button">
        <?php _e("Complete order"); ?>
    </button>
</div>

<script>
    mw_admin_custom_checkout_callback = function () {
        $('#mw_admin_edit_order_item_popup_modal').remove();

        mw.reload_module('shop/orders/manage');
        mw.reload_module('shop/checkout');
        mw.reload_module('shop/cart');
        mw.notification.success("Order completed", 5000);
    }
</script>
<script>
    $(document).ready(function () {
        mw.tabs({
            nav: '#mw-add-order .mw-ui-btn-nav-tabs a',
            tabs: '#mw-add-order .mw-ui-box-content'
        });
    });
</script>








