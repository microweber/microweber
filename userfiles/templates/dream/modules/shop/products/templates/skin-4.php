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
                </div>
            </a>

            <?php if (is_array($item['prices'])): ?>
                <?php foreach ($item['prices'] as $k => $v): ?>
                    <input type="hidden" name="price" value="<?php print $v ?>"/>
                    <input type="hidden" name="content_id" value="<?php print $item['id'] ?>"/>
                    <?php break; endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>