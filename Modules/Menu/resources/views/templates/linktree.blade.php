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
    {{-- task-2026-05-17-9b3df3 / AI-842 — empty-state branch added.
         AI-808-family prong-2 hit (legacy-Blade audit class diagnostic).
         Pre-fix this template rendered zero output when $mt === false,
         leaving editors with no signal that the configured menu was
         empty. Fix mirrors the AI-809 lnotif pattern from the 6 sibling
         Menu templates (default + simple + small + skin-1 + navbar +
         images) rather than the AI-808 mw-canvas-empty-state shape —
         consistency across all 7 Menu templates wins over introducing a
         second empty-state shape in one module family for marginal
         benefit (designer Round 13.3 closeout recommendation).

         e($menu_name) auto-escape per AI-809 lineage — admin-controllable
         menu name MUST be escaped to prevent admin-to-admin XSS via lnotif
         (lnotif is editmode-gated so no frontend leak, but admin-to-admin
         defence-in-depth still applies). --}}
    {!! lnotif("There are no items in the menu <b>" . e($menu_name) . '</b>') !!}
@endif

