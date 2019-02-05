<?php

/*

type: layout

name: Shop Inner - Related Products

description: Skin 4

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
    <?php $i = 1; ?>
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
        <div class="col-sm-4 m-b-30" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
            <a href="<?php print $item['link'] ?>">
                <div class="shop-item shop-item-1">
                    <?php if ($show_fields == false or in_array('price', $show_fields)): ?>
                        <?php if (isset($item['prices']) and is_array($item['prices'])) { ?>
                            <?php
                            $vals2 = array_values($item['prices']);
                            $val1 = array_shift($vals2); ?>
                            <div class="shop-item__price hover--reveal">
                                <span><?php print currency_format($val1); ?></span>
                                <?php if ($item['original_price']): ?>
                                    <del><?php print currency_format($item['original_price']); ?></del>
                                <?php endif; ?>
                            </div>
                        <?php } ?>
                    <?php endif; ?>

                    <?php if ($show_fields == false or in_array('thumbnail', $show_fields)): ?>
                        <div class="shop-item__image">
                            <img src="<?php print thumbnail($item['image'], 350, 350, true); ?>" alt="<?php print $item['title'] ?>" itemprop="image"/>
                        </div>
                    <?php endif; ?>

                    <?php if ($show_fields == false or in_array('title', $show_fields)): ?>
                        <div class="shop-item__title">
                            <h5 itemprop="name"><?php print $item['title'] ?></h5>
                        </div>
                    <?php endif; ?>
                    <?php if ($show_fields != false and in_array('created_at', $show_fields)): ?>

                        <small class="date" itemprop="dateCreated"><?php print $item['created_at'] ?></small>

                    <?php endif; ?>
                    <?php if ($show_fields == false or in_array('description', $show_fields)): ?>
                        <p><?php print $item['description'] ?></p>
                    <?php endif; ?>



                    <?php

                    if ($show_fields != false and ($show_fields != false and in_array('read_more', $show_fields))) {
                        print '<hr>';
                    } else if (is_array($show_fields) and in_array('add_to_cart', $show_fields)) {
                        print '<hr>';
                    }

                    ?>
                    <?php if ($show_fields != false and ($show_fields != false and in_array('read_more', $show_fields))): ?>

                        <a href="<?php print $item['link'] ?>" class="mw-more pull-left"><?php $read_more_text ? print $read_more_text : print _e('Read More', true); ?></a>

                    <?php endif; ?>

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
                                    <hr>
                                    <button
                                            class="btn btn-default pull-right"
                                            type="button"
                                            onclick="mw.cart.add('.mw-add-to-cart-<?php print $item['id'] . $i ?>');event.stopPropagation();return false;">
                                        <i class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>&nbsp;
                                        <?php print $add_cart_text ?>
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div class="mw-add-to-cart-<?php print $item['id'] . $i ?>">
                                <input type="hidden" name="price" value="<?php print $v ?>"/>
                                <input type="hidden" name="content_id" value="<?php print $item['id'] ?>"/>
                            </div>
                            <?php $i++;
                            break; endforeach; ?>
                    <?php endif; ?>
                </div>
            </a>


        </div>
    <?php endforeach; ?>
<?php endif; ?>