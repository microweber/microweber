<?php

/*

type: layout

name: Images

description: Images Menu skin

*/

?>

{{-- task-2026-05-17-4f4f83 / AI-852 + task-2026-05-17-bdba1a / AI-853 bundled.
     Two mw.moduleCSS asset-registration scripts moved INSIDE the truthy
     branch so empty-menu renders ship zero descendant CSS/JS bytes
     (textContent-leak family). Empty-state migrates to canonical
     .mw-canvas-empty-state with CTA replacing the broken
     mw-open-module-settings JS hook. is_admin() gate keeps empty-state
     admin-only. Supersedes task-2026-05-17-d00884 / AI-809 lnotif-with-
     e($menu_name) shape by removing the $menu_name interpolation entirely. --}}
<div class="module-navigation module-navigation-default">
    <?php
    $mt = menu_tree($menu_filter, false, true);
    if ($mt != false) {
        ?>
        <script>mw.moduleCSS("<?php print asset('modules/menu/style.css'); ?>", true);</script>
        <script>mw.moduleCSS("<?php print asset('modules/menu/rollover.css'); ?>", true);</script>
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
