<?php

/*

type: layout

name: Product List 1

description: Product List 1 layout

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
    <?php
    $count = 0;
    $len = count($data);

    $helpclass = '';

    if ($len % 3 != 0) {
        if ((($len - 1) % 3) == 0 or $len == 1) {
            $helpclass = 'last-row-single';
        } elseif ((($len - 2) % 3) == 0 or $len == 2) {
            $helpclass = 'last-row-twoitems';
        }
    }
    ?>

    <div class="row <?php print $helpclass; ?> products-wrapper layout-1">
        <?php foreach ($data as $item): ?>
            <?php $count++; ?>

            <div class="col-xs-12 col-sm-6 col-md-4 item" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                <div class="item-wrapper">
                    <?php if ($show_fields == false or in_array('thumbnail', $show_fields)): ?>
                        <?php $second_picture = false; ?>
                        <a href="<?php print $item['link'] ?>" class="image bgimage-fader">
                            <?php $second_pictures = get_pictures($item['id']); ?>
                            <?php if (isset($second_pictures[1]) and isset($second_pictures[1]['filename'])): ?>
                                <?php $second_picture = $second_pictures[1]['filename']; ?>
                            <?php endif; ?>

                            <div class="valign">
                                <div class="valign-cell">
                                    <span class="<?php print ($second_picture ? 'multiple-thumbnails' : 'single-thumbnail'); ?>"
                                          style="background-image: url(<?php print thumbnail($item['image'], $tn[0], $tn[1]); ?>);"></span>

                                    <?php if ($second_picture): ?>
                                        <span style="background-image: url(<?php print thumbnail($second_picture, $tn[0], $tn[1]); ?>);"></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endif; ?>

                    <div class="row">
                        <?php if ($show_fields == false or in_array('title', $show_fields)): ?>
                            <div class="col-xs-12 <?php if ($show_fields == false or in_array('read_more', $show_fields)): ?>col-sm-8<?php endif; ?> title">
                                <h3 itemprop="name"><a itemprop="url" class="lead" href="<?php print $item['link'] ?>"><?php print character_limiter($item['title'], 35); ?></a></h3>
                            </div>
                        <?php endif; ?>

                        <?php if ($show_fields != false and ($show_fields != false and in_array('read_more', $show_fields))): ?>
                            <div class="col-xs-12 col-sm-4 read-more">
                                <a href="<?php print $item['link'] ?>" itemprop="url" class="mw-more">
                                    <?php $read_more_text ? print $read_more_text : '' ?> <i class="material-icons right">arrow_forward</i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <?php if ($show_fields != false and in_array('created_at', $show_fields)): ?>
                            <div class="col-xs-12">
                                <span class="date" itemprop="dateCreated"><?php print $item['created_at'] ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($show_fields == false or ($show_fields != false and is_array($show_fields) and in_array('description', $show_fields))): ?>
                            <div class="col-xs-12">
                                <p class="description" itemprop="description"> <?php print character_limiter($item['description'], 100); ?> </p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <?php if ($show_fields == false or in_array('price', $show_fields)): ?>
                            <div class="col-xs-12 col-sm-6 price">
                                <?php if (isset($item['prices']) and is_array($item['prices'])) : ?>
                                    <?php
                                    $vals2 = array_values($item['prices']);
                                    $val1 = array_shift($vals2); ?>
                                    <span><?php print currency_format($val1); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($show_fields == false or in_array('add_to_cart', $show_fields)): ?>
                            <div class="col-xs-12 col-sm-6 add-to-cart">
                                <?php
                                $add_cart_text = get_option('data-add-to-cart-text', $params['id']);
                                if ($add_cart_text == false or $add_cart_text == "") {
                                    $add_cart_text = '';
                                }

                                ?>
                                <?php if (is_array($item['prices'])): ?>
                                    <button class="add-to-cart" type="button" onclick="mw.cart.add_item('<?php print $item['id'] ?>');">
                                        <i class="material-icons left">shopping_cart</i> <?php print $add_cart_text ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (is_array($item['prices'])): ?>
                        <?php foreach ($item['prices'] as $k => $v): ?>
                            <div class="clear products-list-proceholder mw-add-to-cart-<?php print $item['id'] . $count ?>">
                                <input type="hidden" name="price" value="<?php print $v ?>"/>
                                <input type="hidden" name="content_id" value="<?php print $item['id'] ?>"/>
                            </div>
                            <?php break; endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
<?php endif; ?>
