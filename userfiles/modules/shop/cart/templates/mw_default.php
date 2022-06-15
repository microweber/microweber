<div class="mw-cart mw-cart-big mw-cart-<?php print $params['id'] ?> <?php print  $template_css_prefix; ?>">
    <div class="checkout-modal-products-wrapper">
        <?php if (is_array($data) and $data) : ?>
            <?php foreach ($data as $item) : ?>
                    <div class="form-row checkout-modal-product-list-item ">
                        <div class="col-auto">
                            <?php if (isset($item['item_image']) and $item['item_image'] != false): ?>
                                <?php $p = $item['item_image']; ?>
                            <?php else: ?>
                                <?php $p = get_picture($item['rel_id']); ?>
                            <?php endif; ?>
                            <?php if ($p != false): ?>
                                <img style="max-width:70px; max-height:70px;" src="<?php print thumbnail($p, 70, 70, true); ?>" alt="pic"/>
                            <?php endif; ?>
                        </div>
                        <div class="col">
                            <div class="form-row h-100">
                                <div class="col-10">
                                    <div class="form-row align-items-md-center h-100">
                                        <div class="col-12 col-md-5">
                                            <div><?php _e($item['title']) ?></div>
                                        </div>
                                        <div class="col-6 col-md-4 align-self-center justify-content-md-center">
                                            <?php print currency_format($item['price']); ?>
                                        </div>
                                        <div class="col-6 col-md-3 align-self-center justify-content-md-center mw-qty-field">
                                            <input min=0 type="number" class="form-control input-sm " name="qty" value="<?php print $item['qty'] ?>"  onchange="mw.cart.qty('<?php print $item['id'] ?>', this.value)" style="width: 70px;"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2 justify-content-center align-self-center">
                                    <a data-bs-toggle="tooltip" title="<?php _e("Remove"); ?>" href="javascript:mw.cart.remove('<?php print $item['id'] ?>');"><i class="mdi mdi-delete text-danger d-flex justify-content-center justify-content-md-end" style="font-size: x-large"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
                <h5 class="m-5 text-center"><?php _e("Your cart is empty."); ?></h5>
        <?php endif; ?>

        <?php if (is_array($data) and $data) : ?>
            <div class="checkout-modal-amount-holder form-row mt-4">
                <div class="col-sm-6 checkout-modal-promocode-holder ml-auto">
                    <?php if (get_option('enable_coupons', 'shop') == 1): ?>
                        <?php
                        $discountData = app()->cart_manager->totals('discount');
                        ?>
                        <module type="shop/coupons" template="modal" />
                    <?php endif; ?>
                </div>
                <div class="col-sm-6 checkout-modal-total-holder ">
                    <div class="d-flex justify-content-md-end align-items-center">
                        <module type="shop/cart" template="totals" />
                    </div>
                </div >
            </div>
        <?php endif; ?>
    </div>
</div>
