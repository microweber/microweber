<?php

/*

type: layout

name: Header

description: Header navigation

*/

?>

<nav class="nav-main">
    <?php
    $menu_filter['ul_class'] = 'menu';
    $menu_filter['ul_id'] = '';
    $menu_filter['ul_class_deep'] = '';

    $menu_filter['li_class'] = '';
    $menu_filter['li_submenu_class'] = 'dropdown';
    $menu_filter['a_class'] = '';
    $menu_filter['li_submenu_a_class'] = '';


    $mt = menu_tree($menu_filter);

    if ($mt != false) {
        print ($mt);
    } else {
        print lnotif("There are no items in the menu <b>" . $params['menu-name'] . '</b>');
    }
    ?>
</nav>