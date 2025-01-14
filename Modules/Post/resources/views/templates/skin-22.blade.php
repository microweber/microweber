<?php

/*

type: layout

name: Posts 22

description: Posts 22

*/
?>

<style>
    .blog-posts-22 {
        h3 {
            line-height: 1.2;
            margin-block-end: 1rem;
            margin-block-start: .5rem;
        }

        p {
            margin-block-end: .9rem;
            margin-block-start: 0;
        }

        .mw-post-22-element {
            align-self: auto;
            flex: 0 1 auto;
            order: 0;
        }

        .mw-post-22-widget {
            position: relative;
        }

        .mw-post-22-post-thumbnail-link {
            transition: none 0s;
            transition-behavior: normal;
        }

        .mw-post-22-posts-container .mw-post-22-post {
            margin: 0;
            padding: 0;
        }

        .mw-post-22-posts-container .mw-post-22-post-excerpt {
            flex-grow: 0;
        }

        .mw-post-22-posts-container .mw-post-22-post-thumbnail {
            overflow: hidden;
        }

        .mw-post-22-posts-container .mw-post-22-post-thumbnail-link {
            display: block;
            position: relative;
            width: 100%;
        }

        .mw-post-22-posts .mw-post-22-post {
            flex-direction: column;
            transition-duration: .25s;
            transition-property: background, border, box-shadow;
        }

        .mw-post-22-posts .mw-post-22-post-title {
            margin-left: 0;
            margin-right: 0;
            margin-top: 0;
        }

        .mw-post-22-posts .mw-post-22-post-text {
            display: block;
            flex-direction: column;
            flex-grow: 1;
        }

        .mw-post-22-posts .mw-post-22-post-read-more {
            align-self: flex-start;
            font-size: 12px;
        }

        .mw-post-22-posts .mw-post-22-post {
            display: flex;
        }

        .mw-post-22-posts .mw-post-22-post-card {
            border: 0 solid var(--mw-primary-color);
            border-radius: 3px;
            display: flex;
            flex-direction: column;
            min-height: 100%;
            overflow: hidden;
            position: relative;
            transition: all .25s;
            transition-behavior: normal;
            width: 100%;
        }

        .mw-post-22-posts .mw-post-22-post-badge {
            font-size: 12px;
            line-height: 1;
            padding: .6em 1.2em;
            position: absolute;
            top: 0;
        }

        .mw-post-22-card-shadow-yes .mw-post-22-post-card {
            box-shadow: rgba(0, 0, 0, .15) 0 0 10px 0;
        }

        .mw-post-22-widget:not(:last-child) {
            margin-block-end: 20px;
            margin-bottom: 20px;
        }

        .mw-post-22-element .mw-post-22-widget-container {
            transition: background .3s, border .3s, border-radius .3s, box-shadow .3s, transform .4s;
            transition-behavior: normal, normal, normal, normal, normal;
        }

        .mw-post-22-posts-container .mw-post-22-post-thumbnail img {
            display: block;
            max-height: none;
            max-width: none;
            transition: filter .3s;
            transition-behavior: normal;
        }

        .mw-post-22-posts .mw-post-22-post-excerpt p {
            font-size: 14px;
            line-height: 1.5em;
            margin: 0;
        }

        a:not([href]):not([tabindex]), a:not([href]):not([tabindex]):focus, a:not([href]):not([tabindex]):hover {
            color: inherit;
            text-decoration: none;
        }

        .mw-post-22-posts-container.mw-post-22-has-item-ratio .mw-post-22-post-thumbnail {
            bottom: 0;
            left: 0;
            right: 0;
            top: 0;
        }

        .mw-post-22-posts .mw-post-22-post-card .mw-post-22-post-thumbnail {
            position: relative;
            transform-style: preserve-3d;
        }

        .mw-post-22-posts .mw-post-22-post-card .mw-post-22-post-text {
            margin-bottom: 0;
        }

        .mw-post-22-posts .mw-post-22-post-card .mw-post-22-post-read-more {
            display: inline-block;
            margin-bottom: 20px;
        }

        .mw-post-22-posts .mw-post-22-post-card .mw-post-22-post-title {
            font-size: 21px;
        }

        .mw-post-22-posts .mw-post-22-post-card .mw-post-22-post-excerpt {
            line-height: 1.7;
        }

        .mw-post-22-posts .mw-post-22-post-card .mw-post-22-post-badge, .mw-post-22-posts .mw-post-22-post-card .mw-post-22-post-read-more {
            text-transform: uppercase;
        }

        .mw-post-22-card-shadow-yes .mw-post-22-post-card:hover {
            box-shadow: rgba(0, 0, 0, .15) 0 0 30px 0;
        }

        .mw-post-22-posts-container.mw-post-22-has-item-ratio .mw-post-22-post-thumbnail img {
            height: auto;
            left: calc(50% + 1px);
            position: absolute;
            top: calc(50% + 1px);
            transform: scale(1.01) translate(-50%, -50%);
        }

        .mw-post-22-posts .mw-post-22-post-card .mw-post-22-post-thumbnail img {
            width: 100%;

        }

        a:not([href]):not([tabindex]):focus {
            outline: 0;
        }

        .mw-post-22-element .mw-post-22-post-card {
            background-color: #f8fafc;
        }

        .mw-post-22-element .mw-post-22-post-text {
            margin-top: 0;
            padding: 0 15px;
        }

        .mw-post-22-element .mw-post-22-post-badge {
            right: 0;
        }

        .mw-post-22-element .mw-post-22-post-title {
            color: #242424;
            font-weight: 800;
            letter-spacing: .5px;
            margin-bottom: 5px;
        }

        .mw-post-22-element .mw-post-22-post-excerpt {
            margin-bottom: 5px;
        }

        .mw-post-22-element .mw-post-22-post-read-more {
            color: var(--mw-primary-color);
        }

        .mw-post-22-element .mw-post-22-post-title a {
            color: #242424;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .mw-post-22-element .mw-post-22-post-excerpt p {
            color: #242424;
        }

        .mw-post-22-element a.mw-post-22-post-read-more {
            font-weight: 700;
            letter-spacing: .5px;
        }

        .mw-post-22-element .mw-post-22-posts-container .mw-post-22-post-thumbnail {
            padding-bottom: calc(65%);
        }

        .mw-post-22-element .mw-post-22-post-card .mw-post-22-post-badge {
            background-color: var(--mw-primary-color);
            border-radius: 3px;
            font-weight: 700;
            letter-spacing: .5px;
            margin: 15px;
            color: #fff;
        }

        @media (max-width: 767px) {
            .mw-post-22-element .mw-post-22-posts-container .mw-post-22-post-thumbnail {
                padding-bottom: calc(50%);
            }
        }

        img.attachment-full.size-full {
            padding: 10px !important;
            height: 400px;
            width: 100%;
        }

    }
</style>

<div class="blog-posts-22">
    @if (!empty($data))
        <div class="mw-post-22-element mw-post-22-card-shadow-yes mw-post-22-widget">

            <div class="row mw-post-22-posts mw-post-22-widget-container">
                @foreach ($data as $item)
                    <div class="col-md-4 col-sm-10 col-12 mx-auto mb-4" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                        <div class="mw-post-22-post-card">
                            @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                                <a class="mw-post-22-post-thumbnail-link" itemprop="url" href="{{ $item['link'] }}">
                                    <img class="attachment-full size-full" loading="lazy" itemprop="image" src="{{ $item['image'] }}"/>
                                </a>
                            @endif

                            <div class="mw-post-22-post-badge">
                                @php
                                    $categories = content_categories($item['id']);
                                    $itemCats = '';
                                    if ($categories) {
                                        foreach ($categories as $category) {
                                            $itemCats .= $category['title'];
                                        }
                                    }
                                @endphp
                                {!! $itemCats !!}
                            </div>

                            <div class="mw-post-22-post-text">
                                @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                                    <h3 class="mw-post-22-post-title" itemprop="name">
                                        <a itemprop="url" href="{{ $item['link'] }}">{{ $item['title'] }}</a>
                                    </h3>
                                @endif

                                @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                                    <div class="mw-post-22-post-excerpt">
                                        <p itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                                    </div>
                                @endif

                                @if (!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields))
                                    <a itemprop="url" href="{{ $item['link'] }}" class="mw-post-22-post-read-more" aria-label="Read more about {{ $item['title'] }}">View Partner »</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif

