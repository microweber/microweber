@php

/*

type: layout

name: Posts 14

description: Posts 14

*/
@endphp

<div class="row blog-posts-3">
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
            <div class="mx-auto mx-md-0 col-12 col-lg-6 mb-5" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                <div class="h-100 d-flex flex-column">
                    @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                        <a itemprop="url" href="{{ $item['link'] }}" class="d-block px-md-0 px-2">
                            <div class="img-as-background">
                                <img loading="lazy" itemprop="image" src="{{ $item['image'] }}" style="top: unset!important; position: relative !important;"/>
                            </div>
                        </a>
                    @endif

                    <div class="pt-4 pb-3">
                        @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                            <a href="{{ $item['link'] }}" class="px-md-0 px-2"><h4 class="text-start text-left" itemprop="name">{{ $item['title'] }}</h4></a>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                            <p itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
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
