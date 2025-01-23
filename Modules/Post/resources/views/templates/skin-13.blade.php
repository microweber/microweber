@php

/*

type: layout

name: Posts 13

description: Posts 13

*/
@endphp

<div class="row py-4 blog-posts-13">
    @if (!empty($data))
        @foreach ($data as $item)
            @php
                $categories = content_categories($item['id']);
                $itemCats = '';
                if ($categories) {
                    foreach ($categories as $category) {
                        $itemCats .= '<small class="text-dark bg-body px-2 py-1 font-weight-bold d-inline-block mb-2 me-2" itemprop="category">' . $category['title'] . '</small> ';
                    }
                }
            @endphp

            <div class="mx-auto col-sm-10 mx-md-0 col-md-6 col-lg-4 mb-7" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                <div class="overflow-hidden h-100 d-flex flex-column bg-body hover-">
                    <div class="d-flex flex-column h-100 pt-3 pb-2">
                        @if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields))
                            <small class="mb-3 mt-3 d-block" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                            <a href="{{ $item['link'] }}" class="text-dark text-decoration-none"><h4 class="mb-2" itemprop="name">{{ $item['title'] }}</h4></a>
                        @endif

                        @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                            <p itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                        @endif
                    </div>
                    @if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields))
                        <a href="{{ $item['link'] }}" class="d-block position-relative" itemprop="url">
                            <div class="img-as-background square-75">
                                <img loading="lazy" src="{{ $item['image'] }}" itemprop="image" />
                            </div>
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif
