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
                    <div class="shop-item shop-item-1">
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
                    </div>
                    <?php if (is_array($item['prices'])): ?>
                        <?php foreach ($item['prices'] as $k => $v): ?>
                            <input type="hidden" name="price" value="<?php print $v ?>"/>
                            <input type="hidden" name="content_id" value="<?php print $item['id'] ?>"/>
                            <?php break; endforeach; ?>
                    <?php endif; ?>
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
