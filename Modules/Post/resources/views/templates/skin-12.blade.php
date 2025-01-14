<?php

/*

type: layout

name: Posts 12

description: Posts 12

*/
?>
@include('modules.post::partials.slick_options')
<div class="slick-arrows-1">
    <div class="blog-posts-12 slickslider slick-dots-relative">
        @if (!empty($data))
            @foreach ($data as $item)
                @php
                    $categories = content_categories($item['id']);
                    $itemCats = '';
                    if ($categories) {
                        foreach ($categories as $category) {
                            $itemCats .= '<small class="text-dark font-weight-bold d-inline-block mb-2" itemprop="name">' . $category['title'] . '</small> ';
                        }
                    }
                @endphp
                <div class="px-1" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                    <div class="mb-5">
                        <div class="h-100 d-flex flex-column">
                            @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                                <a href="{{ $item['link'] }}" class="d-block position-relative overflow-hidden h-350">
                                    <img loading="lazy" alt="post-img" src="{{ $item['image'] }}" style="min-height: 100%;" itemprop="image">
                                </a>
                            @endif

                            <div class="pt-4 pb-3">
                                @if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields))
                                    <small class="mb-2 d-block" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                                @endif

                                @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                                    <a href="{{ $item['link'] }}" class="text-dark text-decoration-none mb-2"><h4 itemprop="name">{{ $item['title'] }}</h4></a>
                                @endif

                                @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                                    <p itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif
