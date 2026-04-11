<?php

/*

type: layout

name: E-commerce 1

position: 1

categories: Ecommerce

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = '';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? ''; $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>

<section class="section <?php print $layout_classes; ?>">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="mw-layout-container container">
        <div class="row justify-content-between">
            <div class="col-12 col-lg-9">
                <module type="shop/products" id="{{ $params['id'] }}-shop-products" template="default"/>
            </div>
            <div class="col-12 col-lg-3">
                <div class="sidebar">
                    <div class="sidebar__widget mb-4">
                        <module type="categories" id="{{ $params['id'] }}-categories" template="skin-1"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>
