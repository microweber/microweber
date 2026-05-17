<?php

/*

type: layout

name: Skin - 1

description: List Navigation 1

*/

?>

<?php
    $params['ul_class'] = 'nav-list';
    $params['active_class'] = 'active';
	$params['ul_class_deep'] = 'nav-list';

?>


{{-- audit-test 2026-05-07 Categories audit findings #1 + #2 + #4:
     #1: wrap in <nav> + aria-labelledby for landmark navigation.
     #2: drop the duplicate `module-categories module-categories-template-default`
         classes that shipped via copy-paste (live-render confirmed
         class="module-categories module-categories-template-skin-1
         module-categories module-categories-template-default") — that
         caused skin-1 to inherit any .module-categories-template-default
         CSS rules unintentionally.
     #4: visually-hidden <h2> announce-only label. --}}
{{-- task-2026-05-17-1ffb35 / AI-815 — content_type-aware
     category heading. Pre-fix every site (blogs, portfolios,
     galleries) had screen-readers announce "Product categories
     navigation" because the module hardcoded the product slug.
     match() derives from $params['content_type'] (the parser-
     populated module type) with $params['heading'] as the
     manual override. Same content_type-aware derivation
     pattern as AI-780/AI-780a/AI-801. --}}
@php
    $mwCatHeading = $params['heading'] ?? match ($params['content_type'] ?? 'content') {
        'post'    => __('Post categories'),
        'page'    => __('Page categories'),
        'product' => __('Product categories'),
        'picture' => __('Picture categories'),
        default   => __('Categories'),
    };
@endphp
<nav class="module-categories module-categories-template-skin-1"
     aria-labelledby="cat-{{ $params['id'] ?? 'skin-1' }}-h">
    <h2 id="cat-{{ $params['id'] ?? 'skin-1' }}-h" class="visually-hidden">{{ $mwCatHeading }}</h2>
    {{-- AI-82 / TICKET-UU (cycle-93): raw PHP → Blade `{!! ... !!}`. --}}
    {!! category_tree($params) !!}
</nav>

