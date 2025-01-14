<?php

/*

type: layout

name: Posts 20

description: Posts 20

*/
?>

<style>
    .blog-posts-19 .post-19::after {
        content: "";
        width: 60px;
        position: absolute;
        bottom: -3px;
        left: 50%;
        transform: translateX(-50%);
        border-bottom: 4px solid rgb(32, 32, 32);
        margin: 0px auto;
        transition: width 0.5s ease 0s;
    }

    .blog-posts-19 .post-19:hover::after {
        width: 100%;
    }
</style>

<div class="row blog-posts-19">
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
            <div class="position-relative mx-auto mx-md-0 col-sm-10 col-md-6 mb-5" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                <div class="h-100 d-flex flex-column post-19">
                    @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                        <a href="{{ $item['link'] }}" class="d-block" itemprop="url">
                            <div class="img-as-background h-350">
                                <img loading="lazy" src="{{ $item['image'] }}" style="position: relative !important; object-fit: cover;" itemprop="image"/>
                            </div>
                        </a>
                    @endif

                    <div class="pt-4 pb-3">
                        @if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields))
                            <small class="mb-2 d-block" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                            <a href="{{ $item['link'] }}" class="" itemprop="name"><h6 class="text-start text-left font-weight-bold">{{ $item['title'] }}</h6></a>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                            <p class="" itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
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
