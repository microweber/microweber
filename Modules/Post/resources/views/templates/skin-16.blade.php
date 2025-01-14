<?php

/*

type: layout

name: Posts 16

description: Posts 16

*/
?>

<style>
    .merry-blog-posts .merry-on-hover-button {
        display: none;
    }

    .merry-blog-posts .img-as-background:hover .merry-on-hover-button  {
        display: flex!important;
        position: relative;
        z-index: 2;
        color: #61efb3;
        font-size: 80px;
        text-decoration: none;
    }

    .merry-blog-posts .img-as-background:hover img {
        opacity: 0.5;
        z-index: 1;
        transition: 1s;
    }

    .merry-blog-posts .img-as-background:hover {
        display: flex!important;
        justify-content: center;
        align-items: center;
    }
</style>

<div class="row merry-blog-posts blog-posts-3">
    @if (!empty($data))
        @foreach ($data as $item)
            @php
                $categories = content_categories($item['id']);
                $itemCats = '';
                if ($categories) {
                    foreach ($categories as $category) {
                        $itemCats .= '<small class="text-dark font-weight-bold d-inline-block mb-2" itemprop="category">' . $category['title'] . '</small> ';
                    }
                }
            @endphp
            <div class="mx-auto mx-md-0 col-sm-10 col-md-6 col-xl-3 mb-5" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                <div class="h-100 d-flex flex-column">
                    <a href="{{ $item['link'] }}" class="d-block" itemprop="url">
                        @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                            <div class="img-as-background h-350" itemprop="image">
                                <a class="merry-on-hover-button" href=""><i class="mw-micon-Google-Play"></i></a>
                                <img loading="lazy" src="{{ $item['image'] }}" style="position: relative !important;" itemprop="image" alt="{{ $item['title'] }}" />
                            </div>
                        @endif
                    </a>

                    <div class="pt-4 pb-3">
                        @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                            <a href="{{ $item['link'] }}" class="" itemprop="url">
                                <h6 class="text-start text-left" itemprop="name">{{ $item['title'] }}</h6>
                            </a>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields))
                            <small class="d-block" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif
