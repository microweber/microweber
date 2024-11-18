<?php

/*

type: layout

name: Images

description: Images Menu skin

*/

?>

<script>mw.moduleCSS("<?php print asset('modules/menu/style.css'); ?>", true);</script>

<script>mw.moduleCSS("<?php print asset('modules/menu/rollover.css'); ?>", true);</script>

<div class="module-navigation module-navigation-default">
    <?php
    $mt = menu_tree($menu_filter, false, true);
    if ($mt != false) {
        print($mt);
    } else {
        print lnotif(_e('There are no items in the menu', true) . " <b>" . $menuName . '</b>');
    }
    ?>
</div>
