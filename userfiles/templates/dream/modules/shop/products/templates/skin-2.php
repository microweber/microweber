<?php

/*

type: layout

name: Shop 3 - Products

description: Skin 2

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

                <div class="col-sm-6 masonry__item" data-masonry-filter="<?php print $itemCats; ?>" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                    <div class="card card-7">
                        <?php if ($show_fields == false or in_array('thumbnail', $show_fields)): ?>
                            <a href="<?php print $item['link'] ?>">
                                <div class="card__image">
                                    <img src="<?php print thumbnail($item['image'], 400, 400, true); ?>" alt="<?php print $item['title'] ?>" itemprop="image"/>
                                </div>
                            </a>
                        <?php endif; ?>

                        <div class="card__body boxed bg--white">
                            <div class="card__title">
                                <?php if ($categories): ?>
                                    <h6>
                                        <?php foreach ($categories as $key => $category): ?>
                                            <?php if ($key == 0): ?>
                                                <a href="<?php print category_link($category['id']); ?>"><?php print $category['title']; ?></a>,
                                            <?php elseif ($key == 1): ?>
                                                <a href="<?php print category_link($category['id']); ?>"><?php print $category['title']; ?></a>
                                            <?php elseif ($key == 2): ?>
                                                ..
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </h6>
                                <?php endif; ?>

                                <?php if ($show_fields == false or in_array('title', $show_fields)): ?>
                                    <a href="<?php print $item['link'] ?>">
                                        <h5 itemprop="name"><?php print $item['title'] ?></h5>
                                    </a>
                                <?php endif; ?>
                                <?php if ($show_fields != false and in_array('created_at', $show_fields)): ?>

                                    <small class="date" itemprop="dateCreated"><?php print $item['created_at'] ?></small>

                                <?php endif; ?>
                                <?php if (isset($show_fields) and is_array($show_fields) and in_array('description', $show_fields)): ?>

                                    <p itemprop="description"><?php print character_limiter($item['description'], 150); ?></p>

                                <?php endif; ?>
                            </div>

                            <?php if ($show_fields == false or in_array('price', $show_fields)): ?>
                                <?php if (isset($item['prices']) and is_array($item['prices'])) { ?>
                                    <?php
                                    $vals2 = array_values($item['prices']);
                                    $val1 = array_shift($vals2); ?>
                                    <a href="<?php print $item['link'] ?>">
                                        <div class="card__price">
                                            <span><?php print currency_format($val1); ?></span>
                                            <?php if ($item['original_price']): ?>
                                                <del><?php print currency_format($item['original_price']); ?></del>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                <?php } ?>
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

                                            <button
                                                    class="btn btn-default pull-right"
                                                    type="button"
                                                    onclick="mw.cart.add('.mw-add-to-cart-<?php print $item['id'] . $i ?>');">
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
