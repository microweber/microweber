<?php

/*

type: layout

name: Products list 2

description: Products list 2 skin

*/

if(!isset($ratings)){
    return;
}
?>

<div class="shop-item-rating-line" id="stars<?php print $params['id'] ?>">
    <div class="rating rating-<?php print $ratings; ?> size-13"></div>
</div>