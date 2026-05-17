<?php

/*

type: layout

name: Default

description: Default Menu skin

*/

?>

<script>mw.moduleCSS("<?php print asset('modules/menu/style.css'); ?>", true);</script>

<div class="module-navigation module-navigation-default">
    <?php
    $mt = menu_tree($menu_filter, false, true);
    if ($mt != false) {
        print($mt);
    } else {
        // task-2026-05-17-d00884 / AI-809 -- escape $menu_name via e()
        // before injecting into the lnotif HTML. Pre-fix raw interpolation
        // let an admin who renamed a menu to "><img src=x onerror=alert(1)>
        // fire script in another admin's live-edit session (lnotif is
        // editmode-gated so no frontend leak; admin-to-admin defense-in-
        // depth fix per the legacy-Blade audit class lesson).
        print lnotif(_e('There are no items in the menu', true) . " <b>" . e($menu_name) . '</b>');
    }
    ?>
</div>
