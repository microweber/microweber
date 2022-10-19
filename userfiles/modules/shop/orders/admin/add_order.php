<?php
$kw = '';

$products_q = array();
$products_q['limit'] = 10;
$products_q['content_type'] = 'product';

if (isset($params['kw']) and $params['kw']) {
    $kw = $params['kw'];
    $products_q['keyword'] = $kw;
}

$products = get_content($products_q);

$data = $products;
?>

<script>mw.require('autocomplete.js')</script>

<script>
    mw_admin_custom_checkout_callback = function () {
        mw.dialog.remove();
        mw.reload_modules(['shop/orders/manage', 'shop/checkout', 'shop/cart']);
        mw.notification.success('<?php _e('Order completed') ?>', 5000);
        window.location.reload();
    }
</script>

<script>
    $(document).ready(function () {
        var created_by_field = new mw.autoComplete({
            element: "#mw-admin-search-for-products",
            placeholder: "Search products",
            ajaxConfig: {
                method: 'get',
                url: mw.settings.api_url + 'get_content_admin?get_extra_data=1&content_type=product&keyword=${val}'
            },
            map: {
                value: 'id',
                title: 'title',
                image: 'picture'
            }
        });
        $(created_by_field).on("change", function (e, val) {
            mw_admin_add_product_to_cart_open_modal(val[0].id);
        })
    });
</script>

<script>
    $(document).ready(function () {
        mw.on('mw.cart.add', function () {
            if (typeof addCartModal != 'undefined') {
                addCartModal.modal.remove()
            }
        });
    });

    function mw_admin_add_product_to_cart_open_modal(product_id = false) {
        var data = {};
        data.content_id = product_id;
        data.template = 'mw_default';
        addCartModal = mw.tools.open_module_modal('shop/cart_add', data, {overlay: true, skin: 'simple', title: 'Add to cart'})
    }

    mw_admin_custom_order_item_add = function ($form) {
        mw.cart.add($form);
    }

    $(function () {
        add_order_tabs = mw.tabs({
            nav: '#mw-add-order .mw-ui-btn-nav-tabs a',
            tabs: '#mw-add-order .mw-ui-box-content'
        });

        $(window).on("mw.cart.add", function () {
            mw.reload_module('shop/cart');
            mw.notification.success("Item added to cart");
            add_order_tabs.set(1)
        });

        $('.js-search-product-for-custom-order').on('submit', function (event) {
            event.preventDefault();
            event.stopPropagation();
            var $form = $(this);
            var $kw = '';
            $form.children('input[type="text"]').each(function () {
                if ($(this).val().length != 0) {
                    $kw = $(this).val();
                }
            });

            $('#<?php print $params['id'] ?>').attr('kw', $kw);
            mw.reload_module('#<?php print $params['id'] ?>')
        });
    });
</script>

<style>
    #mw-admin-search-for-products .mw-ui-field {
        width: 100%;
    }
</style>

<div id="mw-add-order">
        <!-- Add to cart -->
            <div class="row">
                <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs m-1 w-100">
                    <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
                        <a class="btn btn-outline-secondary justify-content-center active show" data-bs-toggle="tab" href="javascript:;"><i class="mdi mdi-cart-outline px-2 mdi-20px"></i><?php _e("Add to cart"); ?></a>
                        <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="javascript:;"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e("My cart's content"); ?></a>
                        <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="javascript:;"><i class="mdi mdi-arrow-right"></i> <?php _e("Checkout"); ?></a>
                    </nav>
                </div>
                <div class="mw-ui-box-content m-1 border-0 w-100">
                    <h5 class="font-weight-bold mb-4"><?php _e("Search Products"); ?></h5>
                    <div class="col-12 ">
                       <label class="control-label"><?php _e("Search for product"); ?></label>
                        <small class="text-muted d-block mb-2"> <?php _e("In the field below you can search for products from your shop."); ?></small>
                        <div class="mw-ui-field-holder p-0">
                            <div id="mw-admin-search-for-products"></div>
                        </div>
                    </div>

                    <br> <br> <br> <br>
                    <?php

                    /* adding custom item is disabled
                     *
                     * <h5 class="font-weight-bold my-4">Add New Product</h5>
                    <div class="col-12 mt-2">
                       <label class="control-label"><?php _e("Add custom item"); ?></label>
                        <small class="text-muted d-block mb-2"> <?php _e("In the field below you can add the name and price of products from your shop."); ?></small>
                        <div class="mw_admin_custom_order_item_add_form_toggle">
                            <div id="mw_admin_custom_order_item_add_form">
                                <div class="row">
                                    <div class="mw-ui-field-holder col-6">
                                        <label class="form-label" for="inputDefault"><?php _e("Product name"); ?></label>
                                        <input type="text" name="title" class="form-control" required="required">
                                        <input type="hidden" name="for" value="custom_item">
                                        <input type="hidden" name="for_id" value="1">
                                    </div>
                                    <div class="mw-ui-field-holder col-6">
                                        <label class="form-label" for="inputDefault"><?php _e("Price"); ?></label>
                                        <input type="text" name="price" class="form-control" placeholder="<?php _e("Example: 10"); ?>" required>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary icon-left pull-right m-2"  onclick="mw_admin_custom_order_item_add('#mw_admin_custom_order_item_add_form')"><i class="fa fa-plus-circle"></i> <?php _e('Add Item'); ?></button>
                            </div>
                        </div>
                    </div>*/

                    ?>

                    <?php
                    /*     <hr>

                        <h2>Add existing product</h2>

                        <div class="mw-ui-field-holder">
                            <form class="js-search-product-for-custom-order">
                                <input type="text" name="search" class="mw-ui-field element-block "
                                       placeholder="Search product in catalog" required="required" value="<?php print $kw; ?>">
                            </form>
                        </div>



                    <?php if (is_array($data) and !empty($data)): ?>
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
                                        <td><a class="text-center"><span class="mw-user-thumb image"
                                                                         style="background-image: url('<?php print $pic ?>');"></span></a>
                                        </td>
                                        <td><?php print content_title($item['id']) ?><?php print $append; ?></td>
                                        <td>
                                            <module type="shop/cart_add" template="mw_default"
                                                    content-id="<?php print ($item['id']) ?>"/>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                    No products found
                    <?php endif; ?>*/
                    ?>
                </div>
            </div>
    <!-- My cart's content -->
    <div class="mw-ui-box-content" style="display: block;">
        <module type="shop/cart" data-checkout-link-enabled="n" template="mw_default" class="no-settings">
    </div>
    <!-- Checkout -->
    <div class="mw-ui-box-content" style="display: none;">
        <module type="shop/checkout" class="no-settings" data-checkout-link-enabled="n" template="mw_default" id="mw-admin-custom-checkout-add-order"/>
            <button type="button" class="btn btn btn-primary pull-right m-auto" onclick="mw.cart.checkout('#mw-admin-custom-checkout-add-order', mw_admin_custom_checkout_callback);"><?php _e("Complete order"); ?></button>
    </div>
</div>










