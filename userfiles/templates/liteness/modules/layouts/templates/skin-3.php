<?php

/*

type: layout

name: Shop products

position: 2

*/

?>

<div class="nodrop safe-mode edit" field="layout-skin-3-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="box-container latest-items">
            <h2 class="section-title">
                <small class="safe-element">What's new</small>
                <span class="safe-element">From the store</span></h2>
            <module type="shop/products" limit="3" hide-paging="true" data-show="thumbnail,title,add_to_cart,price" >
        </div>
    </div>
</div>