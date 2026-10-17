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
