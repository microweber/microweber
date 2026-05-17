<?php

/*

type: layout

name: Default

description: List Navigation

*/

?>

<?php
    $params['ul_class'] = 'nav-list';
	$params['ul_class_deep'] = 'nav-list';


?>


{{-- audit-test 2026-05-07 Categories audit findings #1 + #4:
     The category list IS navigation — wrap in <nav> + aria-labelledby
     so screen-reader landmark navigation reaches it. visually-hidden
     <h2> is the announce-only label; pair the id via aria-labelledby
     so the heading travels with the landmark even when sighted users
     don't see it. --}}
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
<nav class="module-categories module-categories-template-default"
     aria-labelledby="cat-{{ $params['id'] ?? 'default' }}-h">
    <h2 id="cat-{{ $params['id'] ?? 'default' }}-h" class="visually-hidden">{{ $mwCatHeading }}</h2>
    {{-- AI-82 / TICKET-UU (cycle-93 2026-05-09): converted raw
         `<?php category_tree($params); ?>` to canonical Blade
         `{!! ... !!}` syntax. The helper now defaults `return_data=1`
         so it returns the tree string instead of printing — no
         double-render risk. The `{!!` echoes-without-escape because
         the tree is trusted module-rendered HTML. --}}
    {!! category_tree($params) !!}
</nav>

