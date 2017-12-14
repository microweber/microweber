<?php

/*

type: layout

name: Products list 3

description: Products list 3 skin

*/

if(!isset($ratings)){
    return;
}
?>

<div id="stars<?php print $params['id'] ?>">
    <div class="rating rating-<?php print $ratings; ?> size-13"></div>
</div>