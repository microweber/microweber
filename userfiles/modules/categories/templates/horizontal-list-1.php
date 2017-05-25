<?php

/*

type: layout

name: Horizontal - List 1

description: List Navigation

*/

?>


<?php
$params['ul_class'] = 'nav nav-list';
$params['ul_class_deep'] = 'nav nav-list';
?>

<div class="module-categories module-categories-template-horizontal-list-1">
    <?php //category_tree($params); ?>
    <ul class="mw-cats-menu">
        <li><a href="<?php print page_link(); ?>">All Products</a></li>
        <li><a href="#">Tutorials</a>
            <ul>
                <li><a href="#">Tutorial #1@@</a></li>
                <li><a href="#">Tutorial #2</a></li>
                <li><a href="#">Tutorial #3</a></li>
            </ul>
        </li>
        <li><a class="active" href="#">About</a></li>
        <li><a href="#">Newsletter</a>
            <ul>
                <li><a href="#">News #1</a></li>
                <li><a href="#">News #2@@@</a></li>
                <li><a href="#">News #3</a></li>
            </ul>
        </li>
        <li class="contact"><a href="#">Contact</a></li>
    </ul>
</div>

