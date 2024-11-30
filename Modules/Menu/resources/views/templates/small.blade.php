<?php

/*

type: layout

name: Small

description: Small Navigation

*/

$menu_filter['ul_class'] = 'nav-small';
$menu_filter['maxdepth'] = 1;
$menu_filter['li_class_empty'] = ' ';
$mt = menu_tree($menu_filter);
if ($mt != false) {
    print($mt);
} else {
    print lnotif(_e('There are no items in the menu', true) . " <b>" . $menu_name . '</b>');
}
?>

<script>mw.moduleCSS("<?php print asset('modules/menu/style.css'); ?>", true);</script>
