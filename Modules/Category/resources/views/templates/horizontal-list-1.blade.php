<?php

/*

type: layout

name: Horizontal - List 1

description: List Navigation

*/

?>

<?php
$params['ul_class'] = 'mw-cats-menu';
$params['ul_class_deep'] = 'nav-list';
?>

{{-- audit-test 2026-05-07 Categories audit findings #1 + #4:
     wrap in <nav> + aria-labelledby + visually-hidden <h2> for
     landmark navigation, matching default.blade and skin-1.blade. --}}

@php
    $mwCatHeading = $params['heading'] ?? match ($params['content_type'] ?? 'content') {
        'post'    => __('Post categories'),
        'page'    => __('Page categories'),
        'product' => __('Product categories'),
        'picture' => __('Picture categories'),
        default   => __('Categories'),
    };
@endphp
<nav class="module-categories module-categories-template-horizontal-list-1"
     aria-labelledby="cat-{{ $params['id'] ?? 'horizontal-list-1' }}-h">
    <h2 id="cat-{{ $params['id'] ?? 'horizontal-list-1' }}-h" class="visually-hidden">{{ $mwCatHeading }}</h2>

    {!! category_tree($params) !!}
</nav>

