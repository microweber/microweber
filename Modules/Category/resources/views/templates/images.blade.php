<?php
/*
type: layout
name: Images
description: Category Images Layout
*/
?>

@include('modules.category::partials.categories_data')

<style>
.module-categories-template-images:after {
    content: '.';
    font-size: 0;
    overflow: hidden;
    display: block;
    clear: both;
}

.module-categories-template-images {
    text-align: justify;
}

.module-categories-template-images > a {
    text-align: center;
    width: 120px;
    display: inline-block;
    margin: 12px 12px 24px;
    float: left;
    text-decoration: none;
}

.module-categories-template-images > a strong {
    font-weight: normal;
    display: block;
    text-align: center;
    padding-top: 5px;
}

.module-categories-template-images > a:hover strong,
.module-categories-template-images > a:focus strong {
    text-decoration: underline;
}

.module-categories-template-images .category-image {
    width: 120px;
    height: 120px;
    display: block;
    background-position: center;
    background-size: contain;
    background-repeat: no-repeat;
    margin-bottom: 10px;
}
</style>

{{-- audit-test 2026-05-07 Categories audit findings #1 + #4 + #8:
     #1: wrap in <nav> + aria-labelledby for landmark navigation.
     #4: visually-hidden <h2> announce-only label.
     #8: items-count span gets aria-label="N items" so screen readers
         hear "5 items" instead of "open paren five close paren".
     Findings #5 (a-with-background-image → <img>) and #6 (50-line
     inline <style>) are tracked separately as TICKET-SS / TICKET-V
     (refactor scope; this commit is the safe a11y pass only). --}}
<nav class="module-categories module-categories-template-images"
     aria-labelledby="cat-{{ $params['id'] ?? 'images' }}-h">
    <h2 id="cat-{{ $params['id'] ?? 'images' }}-h" class="visually-hidden">{{ __('Product categories') }}</h2>
    @if(!empty($data))
        @foreach($data as $item)
            @php
                $picture = isset($item['picture']) ? $item['picture'] : '';
                $title = isset($item['title']) ? $item['title'] : '';
                $url = isset($item['url']) ? $item['url'] : '';
                $itemsCount = isset($item['content_items_count']) ? $item['content_items_count'] : 0;
            @endphp

            <a href="{{ $url }}" class="category-item">
                @if($picture)
                    <span class="category-image" style="background-image: url('{{ $picture }}');"></span>
                @else
                    <span class="category-image" style="background-image: url('{{ asset('modules/category/img/category_images.svg') }}');"></span>
                @endif

                <strong>{{ $title }}</strong>
                @if($itemsCount)
                    <span class="items-count" aria-label="{{ $itemsCount . ' ' . __('items') }}">({{ $itemsCount }})</span>
                @endif
            </a>
        @endforeach
    @else
        {{ lnotif(_e('No categories found', true)) }}
    @endif
</nav>
