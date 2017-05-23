<?php

/*

type: layout

name: Horizontal - List 1

description: List Navigation

*/

?>

<style>
    .module-categories-template-horizontal-list-1 ul.mw-cats-menu {
        list-style-type: none;
        min-height: 30px;
    }

    .module-categories-template-horizontal-list-1 ul.mw-cats-menu li {
        display: inline-block;
        /*position: relative;*/
    }

    .module-categories-template-horizontal-list-1 ul.mw-cats-menu li:first-child > a {
        font-weight: bold;
    }

    .module-categories-template-horizontal-list-1 ul.mw-cats-menu li a {
        color: #373737;
        font-size: 14px;
        text-decoration: none;
        padding: 10px;
    }

    .module-categories-template-horizontal-list-1 ul.mw-cats-menu > li > a {
        padding: 15px 10px;
        display: block;
    }

    .module-categories-template-horizontal-list-1 ul.mw-cats-menu > li > ul {
        display: none;
        position: absolute;
        background: #fff;
        z-index: 1;
        border: 1px solid #000;
    }

    .module-categories-template-horizontal-list-1 ul.mw-cats-menu > li:hover > ul {
        display: block;
        padding: 10px 0;
    }

    .module-categories-template-horizontal-list-1 ul.mw-cats-menu > li > ul > li {
        display: block;
        padding: 5px 10px;
    }

    @media screen and (max-width: 600px) {
        .module-categories-template-horizontal-list-1 ul.mw-cats-menu li {
            display: block;
        }

        .module-categories-template-horizontal-list-1 ul.mw-cats-menu > li > ul,
        .module-categories-template-horizontal-list-1 ul.mw-cats-menu > li:hover > ul {
            display: block;
            padding: 0 0 0 20px;
            border: 0;
            position: relative;
        }

        .module-categories-template-horizontal-list-1 ul.mw-cats-menu > li > a {
            padding: 0px 10px;
        }

        .module-categories-template-horizontal-list-1 ul.mw-cats-menu {
            width: 200px;
            margin: 0 auto;
        }
    }

</style>

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

