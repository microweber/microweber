<?php

/*

type: layout

name: Products list

description: Products list skin

*/

if(!isset($ratings)){
    return;
}
?>

<div class="shop-item-rating-line" id="stars<?php print $params['id'] ?>">
    <div class="rating rating-<?php print $ratings; ?> size-11"></div>
</div>