<?php

/*

type: layout

name: Shop 4 - Products

description: Skin 3

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
    <div>
        <div class="masonry__container masonry--animate">
            <?php $i = 0; ?>
            <?php foreach ($data as $item): ?>
                <?php $categories = content_categories($item['id']); ?>

                <?php
                $itemCats = '';
                if ($categories) {
                    foreach ($categories as $category) {
                        $itemCats .= $category['title'] . ', ';
                    }
                }
                ?>

                <div class="col-md-3 col-sm-4 col-xs-6 masonry__item" data-masonry-filter="<?php print $itemCats; ?>" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                    <div class="shop-item shop-item-1 shop-item-skin-4">
                        <?php if ($show_fields == false or in_array('price', $show_fields)): ?>
                            <?php if (isset($item['prices']) and is_array($item['prices'])) { ?>
                                <?php
                                $vals2 = array_values($item['prices']);
                                $val1 = array_shift($vals2); ?>
                                <a href="<?php print $item['link'] ?>">
                                    <div class="shop-item__price hover--reveal">
                                        <span><?php print currency_format($val1); ?></span>
                                    </div>
                                </a>
                            <?php } ?>
                        <?php endif; ?>

                        <?php if ($show_fields == false or in_array('thumbnail', $show_fields)): ?>
                            <div class="shop-item__image">
                                <a href="<?php print $item['link'] ?>">
                                    <img src="<?php print thumbnail($item['image'], 600, 600, true); ?>" alt="<?php print $item['title'] ?>" itemprop="image"/>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="shop-item__title">
                            <?php if ($show_fields == false or in_array('title', $show_fields)): ?>
                                <a href="<?php print $item['link'] ?>">
                                    <h5 itemprop="name"><?php print $item['title'] ?></h5>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php if (is_array($show_fields) and in_array('created_at', $show_fields)): ?>
                            <div class="post-date circle-point">
                                <span class="vertical-align">
                                    <?php print date('d', strtotime($item['created_at'])); ?>
                                    <i><?php print date('M', strtotime($item['created_at'])); ?></i>
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if ($show_fields == false or in_array('description', $show_fields)): ?>
                            <p class="col-md-10"><?php print $item['description'] ?></p>
                        <?php endif; ?>

                        <div class="mw-ui-row">
                            <div class="mw-ui-col">
                                <?php if (isset($show_fields) and $show_fields != false and in_array('read_more', $show_fields)): ?>
                                    <a href="<?php print $item['link'] ?>" class="read-more">
                                        <u><?php $read_more_text ? print $read_more_text : print _e('Read more', true); ?></u>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="mw-ui-col">
                                <?php if (is_array($item['prices'])): ?>
                                    <?php foreach ($item['prices'] as $k => $v): ?>
                                        <?php if (is_array($show_fields) and in_array('add_to_cart', $show_fields)): ?>
                                            <?php

                                            $add_cart_text = get_option('data-add-to-cart-text', $params['id']);
                                            if ($add_cart_text == false) {
                                                $add_cart_text = _e("Add to cart", true);
                                            }

                                            ?>
                                            <?php if (is_array($item['prices'])): ?>

                                                <button
                                                        class="mw-ui-btn mw-ui-btn-info mw-ui-btn-medium pull-right"
                                                        type="button"
                                                        onclick="mw.cart.add('.mw-add-to-cart-<?php print $item['id'] . $i ?>');">
                                                    <i class="mw-icon-cart"></i>&nbsp;
                                                    <?php print $add_cart_text ?>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <div class="mw-add-to-cart-<?php print $item['id'] . $i ?>">
                                            <input type="hidden" name="price" value="<?php print $v ?>"/>
                                            <input type="hidden" name="content_id" value="<?php print $item['id'] ?>"/>
                                        </div>
                                        <?php $i++;
                                        break;
                                    endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>


                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <div class="pagination-container">
        <hr>
        <?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
    </div>
<?php endif; ?>
