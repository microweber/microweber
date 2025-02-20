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

<div class="module-categories module-categories-template-images">
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
                    <span class="items-count">({{ $itemsCount }})</span>
                @endif
            </a>
        @endforeach
    @else
        {{ lnotif(_e('No categories found', true)) }}
    @endif
</div>
