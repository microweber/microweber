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
    // task-2026-05-17-d00884 / AI-809 -- escape $menu_name via e()
    // (admin-to-admin XSS defense-in-depth; lnotif is editmode-gated).
    print lnotif(_e('There are no items in the menu', true) . " <b>" . e($menu_name) . '</b>');
}
?>

<script>mw.moduleCSS("<?php print asset('modules/menu/style.css'); ?>", true);</script>
