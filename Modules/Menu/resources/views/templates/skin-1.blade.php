<?php

/*

type: layout

name: Menu skin 1

description: Navigation bar skin 1

*/

?>


<?php
$menu_filter['ul_class'] = '';
$menu_filter['ul_class_deep'] = '';
$menu_filter['li_class'] = 'space';
$menu_filter['a_class'] = '';


//
$menu_filter['li_submenu_class'] = '';
$menu_filter['li_submenu_a_class'] = '';
//
$menu_filter['link'] = '<a itemprop="url" data-item-id="{id}" class="menu_element_link nav-link {active_class} {exteded_classes} {nest_level} {a_class}" {target_attribute} href="{url}"><span>{title}</span></a>';
$menu_filter['li_submenu_a_link'] = '<a itemprop="url" data-item-id="{id}" href="{url}"  class="menu_element_link nav_link {active_class} {exteded_classes} {nest_level} {li_submenu_a_class}" ><span class="name">{title}</span> <span class="caret"></span></a>';

$mt = menu_tree($menu_filter);

// task-2026-05-17-4f4f83 / AI-852 + task-2026-05-17-bdba1a / AI-853 bundled.
// Inline <script> + <style> moved INSIDE the truthy branch so empty-menu
// renders ship zero descendant CSS/JS bytes (textContent-leak family,
// ~600 wasted bytes per empty-menu render). Empty-state migrates to
// canonical .mw-canvas-empty-state with CTA replacing the broken
// mw-open-module-settings JS hook. is_admin() gate keeps empty-state
// admin-only. Supersedes task-2026-05-17-d00884 / AI-809 lnotif-with-
// e($menu_name) shape by removing the $menu_name interpolation entirely.
if ($mt != false) {
    print ($mt);
    ?>
    <script>
        $( document ).ready(function() {
            jQuery('#{{ $params['id'] }} > ul > li > a').on('click', function (e) {
                e.preventDefault();
                jQuery(this).next().stop().slideToggle();
            });
        });
    </script>

    <style>
        #{{ $params['id'] }} > ul > li > a:after {
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-345 240-585l56-56 184 184 184-184 56 56-240 240Z"/></svg>');
            position: absolute;
            right: 25px;
            content: '';
            z-index: 1;
            width: 20px;
            height: 20px;
        }

        #{{ $params['id'] }} > ul > li > a {
            position: relative;
        }

        #{{ $params['id'] }} > ul > li:not(:first-child) ul {
            display: none;
        }

    </style>
    <?php
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
