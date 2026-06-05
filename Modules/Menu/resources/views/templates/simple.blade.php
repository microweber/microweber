<?php

/*

type: layout

name: Simple

description: Simple menu

*/

?>

<?php
$class = '';
if(isset($params['data-class'])){
    $class = $params['data-class'];
}
$menu_filter['class'] = "footer-skin-default";
$menu_filter['ul_class'] =  "list-unstyled gap-2";
$menu_filter['ul_class_deep'] = '';
$menu_filter['li_class'] = 'nav-item';
$menu_filter['a_class'] = 'nav-link' . ' ';
$menu_filter['link'] = '<a itemprop="url" data-item-id="{id}" class="menu_element_link {active_class} {exteded_classes} {nest_level} {a_class}"  {target_attribute}  href="{url}"><span>{title}</span></a>';


//dd($menu_filter);
$mt = menu_tree($menu_filter);

// task-2026-05-17-4f4f83 / AI-852 + task-2026-05-17-bdba1a / AI-853 bundled.
// Empty-state migrates to canonical .mw-canvas-empty-state per AI-780a/AI-808
// /AI-815 lineage; CTA <a href> replaces the broken mw-open-module-settings
// JS hook with native browser navigation to admin/settings/menus (closes
// AI-853). is_admin() gate keeps empty-state admin-only. This skin has no
// inline style/script so Defect 1 of AI-852 (textContent-leak from
// unconditional skin support code) does not apply here; the rest of the
// bundle does. Supersedes task-2026-05-17-d00884 / AI-809 lnotif-with-
// e($menu_name) shape by removing the $menu_name interpolation entirely.
if ($mt != false) {
    print ($mt);
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
