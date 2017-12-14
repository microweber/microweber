<?php

/*

type: layout

name: Default

description: Default

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
        <div class="masonry__container masonry--animate items">
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

                <div class="col-md-4 col-sm-6 masonry__item item-<?php print $item['id'] ?>" data-masonry-filter="<?php print $itemCats; ?>" itemscope
                     itemtype="<?php print $schema_org_item_type_tag ?>">
                    <div class="card card-7">
                        <?php if ($show_fields == false or in_array('thumbnail', $show_fields)): ?>
                            <a href="<?php print $item['link'] ?>">
                                <div class="card__image">
                                    <img src="<?php print thumbnail($item['image'], 600, 600, true); ?>" alt="<?php print $item['title'] ?>" itemprop="image"/>
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
