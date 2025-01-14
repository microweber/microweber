<?php

/*

type: layout

name: Posts 2

description: Posts 2

*/
?>

<div class="blog-posts-2">
    @if (!empty($data))
        @foreach ($data as $item)
            @php
                $categories = content_categories($item['id']);
                $itemCats = '';
                if ($categories) {
                    foreach ($categories as $category) {
                        $itemCats .= '<p class="text-dark font-weight-bold d-block mb-2" itemprop="category">' . $category['title'] . '</p> ';
                    }
                }
            @endphp
            <hr class="thin"/>
            <div class="row pt-3 mb-0" itemscope itemtype="{{ $schema_org_item_type_tag }}">

                @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                    <a href="{{ $item['link'] }}" class="text-dark"><h3 itemprop="name">{{ $item['title'] }}</h3></a>
                @endif

                <small class="my-2 d-block" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>

                @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                    <p itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                @endif

            </div>
        @endforeach
        <hr class="thin"/>
    @endif
</div>

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" template="default" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif
