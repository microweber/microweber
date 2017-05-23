<?php

/*

type: layout

name: Product List 1

description: Product List 1 layout

*/
?>

<script>
    mw.require('https://fonts.googleapis.com/icon?family=Material+Icons&.css', 'material_icons');
</script>

<style>
    .module-shop-products .products-.layout-1 .item {

    }

    .module-shop-products .products-wrapper.layout-1 .item .image {
        height: 250px;
        display: block;
    }

    .module-shop-products .products-wrapper.layout-1 .item:hover .image {
        box-shadow: 0px 3px 19px -3px rgba(0, 0, 0, .17);
        -webkit-box-shadow: 0px 3px 19px -3px rgba(0, 0, 0, .17);
    }

    .module-shop-products .products-wrapper.layout-1 .item .image img {
        max-height: 220px;
        max-width: 100%;
        margin: 0 auto;
    }

    .module-shop-products .products-wrapper.layout-1 .item .title h3 a {
        font-size: 14px;
        font-weight: bold;
        color: #373737;
        text-decoration: none;
    }

    .module-shop-products .products-wrapper.layout-1 .item .add-to-cart {
        text-align: right;
    }

    .module-shop-products .products-wrapper.layout-1 .item .add-to-cart button {
        border: 0px;
        outline: none;
        background: none;
        padding: 0;
        font-size: 15px;
        font-weight: bold;
    }

    .module-shop-products .products-wrapper.layout-1 .item .add-to-cart button i {
        font-size: 18px;
    }

    .module-shop-products .products-wrapper.layout-1 .item .date {
        color: #a7a7a7;
        font-size: 12px;
    }

    .module-shop-products .products-wrapper.layout-1 .item .description {
        color: #373737;
        font-size: 14px;
        margin-top: 5px;
        height: 50px;
    }

    .module-shop-products .products-wrapper.layout-1 .item .price span {
        font-size: 14px;
        color: #373737;
        font-weight: bold;
    }

    .module-shop-products .products-wrapper.layout-1 .item .read-more {
        text-align: right;
        border: 0px;
        outline: none;
        background: none;
        padding-top: 21px;
        font-size: 15px;
        font-weight: bold;
    }

    .module-shop-products .products-wrapper.layout-1 .item .read-more a {
        font-size: 14px;
        color: #373737;
        font-weight: bold;
        text-decoration: none;
    }

    .module-shop-products .products-wrapper.layout-1 .item i.left {
        float: left;
        margin-right: 5px;
    }

    .module-shop-products .products-wrapper.layout-1 .item i.right {
        float: right;
        margin-left: 5px;
    }

    .module-shop-products .pagination > li > a,
    .module-shop-products .pagination > li > span,
    .module-shop-products .pagination > li > a:focus,
    .module-shop-products .pagination > li > a:hover,
    .module-shop-products .pagination > li > span:focus,
    .module-shop-productslayout-1 .pagination > li > span:hover {
        padding: 6px 12px;
        color: #373737;
        text-decoration: none;
        background: none;
        border: 0;
        font-size: 18px;
        font-weight: bold;
    }

    .module-shop-products .pagination > li > a.active {
        color: #ed6c59 !important;
        /*text-decoration: underline;*/
    }

    .module-shop-products .pagination-holder {
        text-align: center;
    }
</style>

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
                <?php if ($show_fields == false or in_array('thumbnail', $show_fields)): ?>
                    <a class="image" href="<?php print $item['link'] ?>">
                        <div class="valign">
                            <div class="valign-cell">
                                <img class="img-responsive <?php if ($item['image'] == false): ?>pixum<?php endif; ?>" src="<?php print thumbnail($item['image'], $tn[0], $tn[1]); ?>"
                                     alt="<?php print $item['title'] ?>" title="<?php print $item['title'] ?>" itemprop="image"/>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
                <div class="row">
                    <?php if ($show_fields == false or in_array('title', $show_fields)): ?>
                        <div class="col-xs-12 <?php if ($show_fields == false or in_array('read_more', $show_fields)): ?>col-sm-6<?php endif; ?> title">
                            <h3 itemprop="name"><a itemprop="url" class="lead" href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h3>
                        </div>
                    <?php endif; ?>

                    <?php if ($show_fields != false and ($show_fields != false and in_array('read_more', $show_fields))): ?>
                        <div class="col-xs-12 col-sm-6 read-more">
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
                            <p class="description" itemprop="description"> <?php print $item['description']; ?> </p>
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
                                <button class="add-to-cart" type="button" onclick="mw.cart.add_and_checkout('<?php print $item['id'] ?>');">
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

        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <?php print paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") ?>
<?php endif; ?>
