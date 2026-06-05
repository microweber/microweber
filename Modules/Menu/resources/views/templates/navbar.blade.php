<?php

/*

type: layout

name: Navbar

description: Navigation bar

*/

?>

@php
$menu_filter['ul_class'] = 'navbar-nav navbar-submenu-overflow navbar--big-default';
$menu_filter['ul_class_deep'] = 'dropdown-menu ul-deep';
$menu_filter['li_class'] = 'nav-item ';
$menu_filter['a_class'] = 'nav-link px-3';
//
$menu_filter['li_submenu_class'] = 'dropdown nav-item';
$menu_filter['li_submenu_a_class'] = 'nav-link px-3 dropdown-toggle';


$menu_filter['link'] = '<a itemprop="url" data-item-id="{id}" class="menu_element_link nav-link {active_class} {exteded_classes} {nest_level} {a_class}" {target_attribute} href="{url}"><span>{title}</span></a>';
$menu_filter['li_submenu_a_link'] = '<a itemprop="url" data-item-id="{id}" {target_attribute} href="{url}"  class="menu_element_link nav-link {active_class} {exteded_classes} {nest_level} {li_submenu_a_class}" ><span class="name">{title}</span> <span class="caret"></span></a>';

$mt = menu_tree($menu_filter);

// task-2026-05-22-f4a791 / AI-791 Slice D — deduplicate top-level menu items by href.
// Defect: when the installer runs multiple times (or demo seeds are applied on top of
// an existing install), the same page URL appears twice in the header_menu DB rows.
// Fix: use DOMDocument to remove duplicate top-level <li> entries; nested submenus
// are intentionally untouched (only direct children of the outermost <ul> are checked).
if ($mt && is_string($mt)) {
    $mwMenuTreeDoc = new \DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $mwMenuTreeDoc->loadHTML('<?xml encoding="UTF-8"><div id="mw_dedup">' . $mt . '</div>');
    libxml_clear_errors();
    $mwMenuTreeRoot = $mwMenuTreeDoc->getElementById('mw_dedup');
    if ($mwMenuTreeRoot) {
        foreach ($mwMenuTreeRoot->childNodes as $mwMenuTreeUl) {
            if ($mwMenuTreeUl->nodeType !== XML_ELEMENT_NODE || $mwMenuTreeUl->nodeName !== 'ul') continue;
            $mwMenuTreeSeen = [];
            $mwMenuTreeDel  = [];
            foreach (iterator_to_array($mwMenuTreeUl->childNodes) as $mwMenuTreeLi) {
                if ($mwMenuTreeLi->nodeType !== XML_ELEMENT_NODE || $mwMenuTreeLi->nodeName !== 'li') continue;
                foreach ($mwMenuTreeLi->childNodes as $mwMenuTreeAnchor) {
                    if ($mwMenuTreeAnchor->nodeType !== XML_ELEMENT_NODE || $mwMenuTreeAnchor->nodeName !== 'a') continue;
                    $mwMenuTreeHref = rtrim($mwMenuTreeAnchor->getAttribute('href'), '/');
                    if ($mwMenuTreeHref !== '' && isset($mwMenuTreeSeen[$mwMenuTreeHref])) {
                        $mwMenuTreeDel[] = $mwMenuTreeLi;
                    } else {
                        $mwMenuTreeSeen[$mwMenuTreeHref] = true;
                    }
                    break;
                }
            }
            foreach ($mwMenuTreeDel as $mwMenuTreeNode) {
                $mwMenuTreeUl->removeChild($mwMenuTreeNode);
            }
        }
        $mwMenuTreeOut = '';
        foreach ($mwMenuTreeRoot->childNodes as $mwMenuTreeNode) {
            $mwMenuTreeOut .= $mwMenuTreeDoc->saveHTML($mwMenuTreeNode);
        }
        $mt = $mwMenuTreeOut;
    }
    unset($mwMenuTreeDoc, $mwMenuTreeRoot, $mwMenuTreeSeen, $mwMenuTreeDel, $mwMenuTreeOut, $mwMenuTreeUl, $mwMenuTreeLi, $mwMenuTreeAnchor, $mwMenuTreeHref, $mwMenuTreeNode);
}
@endphp

{{-- task-2026-05-17-4f4f83 / AI-852 + task-2026-05-17-bdba1a / AI-853 bundled.
     Three defects closed in one shape:
     (1) skin support style+script moved INSIDE the truthy branch so empty-
         menu renders ship zero descendant CSS/JS bytes (textContent-leak
         family, Round 17 deeper-Menu audit -- ~600 wasted bytes per
         empty-menu render).
     (2) empty-state migrates from semantic-mismatched mw-notification
         mw-success ("success" on a "nothing yet" state -- AI-816 family)
         to the canonical .mw-canvas-empty-state chrome shared with
         Pictures/Page/Content/Posts per AI-780a/AI-808/AI-815 lineage.
     (3) raw slug $menu_name (e.g. "header_menu") no longer leaks --
         replaced with action-focused copy + a real <a href> CTA to
         settings/menus. The new .mw-canvas-empty-state__cta IS the
         click-affordance AI-853 needed; native href navigation replaces
         the broken mw-open-module-settings JS handler -- no JS binding
         needed. is_admin() gate keeps the empty-state admin-only so
         anonymous visitors never see editor copy.
     Supersedes task-2026-05-17-d00884 / AI-809 lnotif-with-
     e($menu_name) shape by removing the $menu_name interpolation
     entirely (stronger guarantee than the e()-wrap fix; nothing to
     escape if nothing is interpolated). --}}
@if ($mt != false)
    {!! $mt !!}

    <style>
        .navbar--big-default li.nav-item:hover > ul {
            display: block !important;
            position: absolute !important;
        }
        .navbar--big-default li:hover > a{
             transition: .3s;
        }
        .navbar--big-default li ul ul{
            top:0;
            left: 100%;
        }
    </style>

    <script>
        Array.from(document.querySelectorAll('.navbar-submenu-overflow')).forEach(node => {
            node.classList.remove('navbar-submenu-overflow');
            Array.from(node.querySelectorAll('li')).forEach(li => {
                li.addEventListener('mouseenter', function(e) {
                    var ul = this.querySelector('ul');
                    if(ul && !ul._ready && ul !== node) {
                        if(ul.offsetWidth + mw.element(ul).offset().offsetLeft > innerWidth) {
                            ul.style.left = 'auto';
                            ul.style.right = '100%';
                        }
                        setTimeout(() => {
                                ul._ready = true;
                                if(ul.offsetWidth + mw.element(ul).offset().offsetLeft > innerWidth) {
                                    ul.style.left = 'auto';
                                    ul.style.right = '100%';
                                }
                        }, 10)
                    }

                });
            })


        });
    </script>
@else
    @if(is_admin())
        <div class="mw-canvas-empty-state" data-mw-content-type="menu">
            <h3 class="mw-canvas-empty-state__title">{{ _e('This menu is empty', true) }}</h3>
            <p class="mw-canvas-empty-state__body">{{ _e('Add menu items via menu settings to fill this navigation.', true) }}</p>
            <a class="mw-canvas-empty-state__cta" href="{{ admin_url('settings/menus') }}" aria-label="{{ _e('+ Add menu item', true) }}">{{ _e('+ Add menu item', true) }}</a>
        </div>
    @endif
@endif
