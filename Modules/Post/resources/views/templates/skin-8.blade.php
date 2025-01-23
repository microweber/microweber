@php

/*

type: layout

name: Posts 8

description: Posts 8

*/
@endphp

<div class="row blog-posts-8">
    @if (!empty($data))
        @foreach ($data as $item)
            @php
                $categories = content_categories($item['id']);
                $itemCats = '';
                if ($categories) {
                    foreach ($categories as $category) {
                        $itemCats .= '<small class="text-dark font-weight-bold d-inline-block mb-2 text-start" itemprop="category">' . $category['title'] . '</small> ';
                    }
                }
            @endphp
            <div class="mx-auto col-sm-10 mx-md-0 col-md-6 col-lg-4 mb-6" itemscope itemtype="{{ $schema_org_item_type_tag }}">
                <div class="h-100 d-flex flex-column hover-bg-body text-dark pb-3 pt-5">
                    <div class="d-block d-sm-flex align-items-center h-100">
                        <div class="d-flex flex-column w-100 h-100 justify-content-start align-items-start">
                            {!! $itemCats !!}
                            @if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields))
                                <a href="{{ $item['link'] }}" class="text-start text-dark" itemprop="url"><h4 itemprop="name">{{ $item['title'] }}</h4></a>
                            @endif

                            @if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields))
                                <p class="text-start mb-2" itemprop="description">{{ \Illuminate\Support\Str::limit($item['description'], 250) }}</p>
                            @endif
                            <br/>

                            @if (!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields))
                                <div class="text-start m-t-auto">
                                    <a href="{{ $item['link'] }}" class="" itemprop="url"><span>{{ $read_more_text }}</span></a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif

@if (isset($pages_count) and $pages_count > 1 and isset($paging_param))
    <module type="pagination" pages_count="{{ $pages_count }}" paging_param="{{ $paging_param }}"/>
@endif
