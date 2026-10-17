<?php

/*

type: layout

name: LinkTree

description: LinkTree style menu

*/

?>

@php
$menu_filter['ul_class'] = 'linktree-nav';
$menu_filter['ul_class_deep'] = 'linktree-submenu';
$menu_filter['li_class'] = 'linktree-item';
$menu_filter['a_class'] = 'linktree-link';
//
$menu_filter['li_submenu_class'] = 'linktree-item linktree-has-submenu';
$menu_filter['li_submenu_a_class'] = 'linktree-link linktree-toggle';

$menu_filter['link'] = '<a itemprop="url" data-item-id="{id}" class="menu_element_link linktree-link {active_class} {exteded_classes} {nest_level} {a_class}" {target_attribute} href="{url}"><span>{title}</span></a>';
$menu_filter['li_submenu_a_link'] = '<a itemprop="url" data-item-id="{id}" {target_attribute} href="{url}" class="menu_element_link linktree-link {active_class} {exteded_classes} {nest_level} {li_submenu_a_class}"><span class="name">{title}</span></a>';

$mt = menu_tree($menu_filter);
@endphp

@if ($mt != false)
    {!! $mt !!}
@else

    {!! lnotif("There are no items in the menu <b>" . e($menu_name) . '</b>') !!}
@endif

