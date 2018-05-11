<?php

/*

type: layout

name: Shop 2 - Products

description: Skin 1

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

    <div class="masonry__container masonry--animate">
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

            <div class="col-md-12 masonry__item items" data-masonry-filter="<?php print $itemCats; ?>" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <div class="card card-8 item-<?php print $item['id'] ?>">
                    <?php if ($show_fields == false or in_array('thumbnail', $show_fields)): ?>
                        <div class="card__image col-sm-7 col-md-6">
                            <a href="<?php print $item['link'] ?>">
                                <img src="<?php print thumbnail($item['image'], 600, 600, true); ?>" alt="<?php print $item['title'] ?>" itemprop="image"/>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="card__body col-sm-5 col-md-6 boxed bg--white">
                        <div class="card__title">
                            <?php if ($show_fields == false or in_array('title', $show_fields)): ?>
                                <a href="<?php print $item['link'] ?>">
                                    <h3 itemprop="name"><?php print $item['title'] ?></h3>
                                </a>
                            <?php endif; ?>

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
                        </div>

                        <?php if ($show_fields == false or in_array('price', $show_fields)): ?>
                            <?php if (isset($item['prices']) and is_array($item['prices'])) { ?>
                                <?php
                                $vals2 = array_values($item['prices']);
                                $val1 = array_shift($vals2); ?>
                                <a href="<?php print $item['link'] ?>">
                                    <div class="card__price">
                                        <span><?php print currency_format($val1); ?></span>
                                    </div>
                                </a>
                            <?php } ?>
                        <?php endif; ?>

                        <hr>

                        <?php if ($show_fields == false or in_array('description', $show_fields)): ?>
                            <p class="col-md-10"><?php print $item['description'] ?></p>
                        <?php endif; ?>

                        <?php if ($show_fields == false or ($show_fields != false and in_array('add_to_cart', $show_fields))): ?>
                            <a class="btn btn--sm" href="javascript:;" onclick="mw.cart.add('.items .item-<?php print $item['id'] ?>');">
                                <span class="btn__text"><?php $add_cart_text ? print $add_cart_text : print _e('Add To Cart', true) ?></span>
                            </a>
                        <?php endif; ?>

                        <?php if ($show_fields == false or ($show_fields != false and in_array('read_more', $show_fields))): ?>
                            <a class="btn btn--sm btn--transparent" itemprop="url" href="<?php print $item['link'] ?>">
                                <span class="btn__text"><?php $read_more_text ? print $read_more_text : print 'More Details' ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php if (is_array($item['prices'])): ?>
                        <?php foreach ($item['prices'] as $k => $v): ?>
                            <input type="hidden" name="price" value="<?php print $v ?>"/>
                            <input type="hidden" name="content_id" value="<?php print $item['id'] ?>"/>
                            <?php break; endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <div class="pagination-container">
        <hr>
        <?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
    </div>
<?php endif; ?>
