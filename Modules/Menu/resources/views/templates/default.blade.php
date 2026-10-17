<?php

/*

type: layout

name: Default

description: Default Menu skin

*/

?>

<div class="module-navigation module-navigation-default">
    <?php
    $mt = menu_tree($menu_filter, false, true);
    if ($mt != false) {
        ?>
        <script>mw.moduleCSS("<?php print asset('modules/menu/style.css'); ?>", true);</script>
        <?php
        print($mt);
    } else {
        if (is_admin()) {
            ?>
            <div class="mw-canvas-empty-state" data-mw-content-type="menu">
                <h3 class="mw-canvas-empty-state__title"><?php _e('This menu is empty'); ?></h3>
                <p class="mw-canvas-empty-state__body"><?php _e('Add menu items via menu settings to fill this navigation.'); ?></p>
                <a class="mw-canvas-empty-state__cta" href="<?php print admin_url('settings/menus'); ?>" aria-label="<?php _e('+ Add menu item'); ?>"><?php _e('+ Add menu item'); ?></a>
            </div>
            <?php
        }
    }
    ?>
</div>
