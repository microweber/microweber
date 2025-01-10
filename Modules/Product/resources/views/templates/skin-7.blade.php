<?php

/*

type: layout

name: Skin-7

description: Skin-7

*/
?>
<?php


$tn = $tn_size;
if (!isset($tn[0]) or ($tn[0]) == 150) {
    $tn[0] = 350;
}
if (!isset($tn[1])) {
    $tn[1] = $tn[0];
}


?>
<?php if (!empty($data)): ?>
    <div class="row shop-products">
        <?php foreach ($data as $key => $item): ?>
            <?php $categories = content_categories($item['id']); ?>

            <?php
            $itemData = content_data($item['id']);
            $itemTags = content_tags($item['id']);

            $in_stock = true;
            if (isset($itemData['qty']) and $itemData['qty'] != 'nolimit' and intval($itemData['qty']) == 0) {
                $in_stock = false;
            }

            ?>

            <?php
            $itemData = content_data($item['id']);
            $itemTags = content_tags($item['id']);

            if (!isset($itemData['label'])) {
                $itemData['label'] = '';
            }
            if (!isset($itemData['label-color'])) {
                $itemData['label-color'] = '';
            }
            $itemCats = '';
            if ($categories) {
                foreach ($categories as $category) {
                    $itemCats .= $category['title'] . ', ';
                }
            }
            ?>

            <div class="col-12 col-md-4 item-<?php print $item['id'] ?>" data-masonry-filter="<?php print $itemCats; ?>" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <div class="product mb-4 <?php echo $in_stock ? 'mw-layout-product-stock' : 'mw-layout-product-outOfStock'; ?> " itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <?php if (is_array($item['prices'])): ?>
                        <?php foreach ($item['prices'] as $k => $v): ?>
                            <input type="hidden" name="price" value="<?php print $v ?>"/>
                            <input type="hidden" name="content_id" value="<?php print $item['id'] ?>"/>
                            <?php break; endforeach; ?>
                    <?php endif; ?>

                    <?php if ($show_fields == false or in_array('thumbnail', $show_fields)): ?>

                        <div class="image" style="background-image: url('<?php print thumbnail($item['image'], 1250, 1250); ?>'); height: 500px;" itemprop="image">
                            <?php if (isset($itemData['label-type']) && $itemData['label-type'] === 'text'): ?>
                                <div class="position-absolute  top-0 left-0 m-2" style="z-index: 3;">
                                    <div class="badge text-white px-3 pb-1 pt-2 rounded-0" style="background-color: <?php echo $itemData['label-color']; ?>;"><?php echo $itemData['label']; ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($item['original_price']) and $item['original_price'] != ''): ?>


                                <?php
                                $vals2 = array_values($item['prices']);
                                $val1 = array_shift($vals2);
                                ?>

                                <?php
                                $percentChange = 0;
                                ?>

                                <?php if (isset($item['price_discount_percent']) and $item['price_discount_percent']): ?>
                                    <?php
                                    $percentChange = $item['price_discount_percent'];
                                    ?>
                                <?php endif; ?>
                                <?php if (isset($itemData['label-type']) && $itemData['label-type'] === 'percent' && $percentChange > 0): ?>

                                    <div class="discount-label">
                                                <span class="discount-percentage">
                                                        <?php echo $percentChange; ?>%
                                                </span>
                                        <span class="discount-label-text"><?php _lang("Discount"); ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <a href="<?php print $item['link'] ?>" class="d-flex h-100 w-100" itemprop="url"></a>
                            <?php /*<div class="hover">
                                <?php if ($show_fields == false or ($show_fields != false and in_array('add_to_cart', $show_fields))): ?>
                                    <a href="javascript:;" onclick="mw.cart.add('.shop-products .item-<?php print $item['id'] ?>');" class="btn btn-outline-primary"><i class="mw-micon-Shopping-Cart"></i></a>
                                <?php endif; ?>
                                <?php if ($show_fields == false or ($show_fields != false and in_array('read_more', $show_fields))): ?>
                                    <a href="<?php print $item['link'] ?>" class="btn btn-default"><i class="mw-micon-Eye-Visible"</i></a>
                                <?php endif; ?>
                            </div>*/ ?>
                        </div>
                    <?php endif; ?>


                    <div>
                        <?php if ($show_fields == false or in_array('title', $show_fields)): ?>
                            <a href="<?php print $item['link'] ?>" class="text-decoration-none" itemprop="name">
                                <div class="heading-holder text-md-start text-center mt-3">
                                    <h5><?php print $item['title'] ?></h5>
                                </div>
                            </a>
                        <?php endif; ?>

                        <?php
                        $itemPrices = $item['prices'];
                        $firstPrice = reset($itemPrices);
                        if ($firstPrice !== false && $firstPrice > 0): ?>
                            <div class="row justify-content-center">
                                <div class="col-6 price-holder justify-content-md-start align-items-center justify-content-center">
                                    <?php if ($show_fields == false or in_array('price', $show_fields)): ?>
                                        <?php if (isset($item['prices']) and is_array($item['prices'])) { ?>
                                            <?php
                                            $vals2 = array_values($item['prices']);
                                            $val1 = array_shift($vals2);
                                            ?>
                                            <p itemprop="price">
                                                <?php if (isset($item['original_price']) AND $item['original_price'] != ''): ?>
                                                    <span class="price-old"><?php print currency_format($item['original_price']); ?></span>
                                                <?php endif; ?>
                                                <span class="price"><?php print currency_format($val1); ?></span>
                                            </p>
                                        <?php } ?>
                                    <?php endif; ?>
                                </div>

                                <div class="col-sm-6 col-12 d-flex justify-content-md-end justify-content-center">
                                  <div>
                                      <?php if ($show_fields == false or ($show_fields != false and in_array('add_to_cart', $show_fields))): ?>
                                          <?php if ($in_stock == true): ?>
                                              <a href="javascript:;" onclick="mw.cart.add('.shop-products .item-<?php print $item['id'] ?>');" class="btn btn-outline-primary btn-sm"> <?php _lang("Buy", 'templates/big') ?></a>
                                          <?php endif; ?>
                                      <?php endif; ?>
                                  </div>
                                </div>
                            </div>
                      <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <module type="pagination" pages_count="<?php echo $pages_count; ?>" paging_param="<?php echo $paging_param; ?>"/>
<?php endif; ?>
