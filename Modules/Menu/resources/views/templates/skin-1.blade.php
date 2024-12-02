<?php

/*

type: layout

name: Menu skin 1

description: Navigation bar skin 1

*/

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

if ($mt != false) {
    print ($mt);
} else {
    print lnotif("There are no items in the menu <b>" . $menu_name . '</b>');
}
?>
